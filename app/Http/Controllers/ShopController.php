<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\PreSignup;
use App\Models\PaymentForProduct;
use App\Models\Coupon;
use App\Models\ReserveJersey;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Bootstrap\HandleExceptions;
use GuzzleHttp\Client;
use App\Mail\JerseyBuyMail;
use Illuminate\Support\Facades\Mail;
use Validator;
use Carbon\Carbon;
use Auth;
use Log;
use DB;


class ShopController extends Controller
{

    private $currency = "USD";

    public function __construct()
    {
        // Closure as callback
        $this->payment_mode = env('PAYMENT_MODE');

    }


    /**
     * returns the private key for clover payment
     */
    private function getThePrivateKey(){
        return $this->payment_mode == "TEST"? env('CLOVER_PRIVATE_KEY'):env('CLOVER_PRIVATE_KEY_PRODUCTION');
    }

    /**
     * returns the url for the clover payment charge
     */


     private function getTheChargeUrl(){
        return $this->payment_mode == "TEST" ? 'https://scl-sandbox.dev.clover.com/v1/charges': 'https://scl.clover.com/v1/charges';

     }



    // public function index(){
    //     $get_jersey = Product::with('product_images')->where(['status' => 'active'])->orderBy('id' , 'DESC')->get();
    //     return view('front.shop.adults_product_shop' , compact('get_jersey'));
    // }

    public function index(){

        return view('front.shop.adults_jersey_shop');
    }

    public function showCategoryProduct(Request $request){
        $parameter = $request->input('contentType');

        $get_product = Product::with('product_images' ,'product_variations')->where(['status' => 'active','product_type'=>$parameter])->orderBy('id' , 'DESC')->get();


        return response()->json($get_product);
    }


    public function getProductPrice(Request $request)
    {

        $product = ProductVariation::where(['product_id' => $request->input('product_id') , 'size_id' =>$request->input('size_id')])->first();
        return response()->json(['price' => $product->product_price]);
    }


    public function showProduct($product , $id){
        // Session::forget('buy_now_session');
        // Session::forget('shoppingCart');
        $product = Product::with('product_images' , 'product_variations')->where('id' ,$id )->first();

        if ($product != null ) {
          // get product sizes
            $get_sizes = ProductVariation::with('productSize')->where(['product_id'=>$id , 'status'=> '1'])->orderBy('id' ,'ASC')->get();
        }


        // get all the products to show in slider at the bottom
        $get_product = Product::with('product_images' , 'product_variations')->where(['status' => 'active'])->get();

        // dd($get_product);
        return view('front.shop.show_adult_jersey' , compact('get_product' , 'product' ,'get_sizes'));


    }



    // pony pages
    public function ponyFlagFootballShop(){
        // Session::forget('shoppingCart');
        // Session::forget('buy_now_session');
        $get_kids_product = Product::with('product_images' ,'product_variations')->where(['status' => 'active' , 'product_type' => 'youth' ])->get();
        // dd($get_kids_product);
        return view('front.shop.kids_jersey_shop' , compact('get_kids_product'));
    }

    public function ponyFlagFootballJersey($product , $id){
        $kids_product = Product::with('product_images')->where(['status' => 'active' , 'product_type' => 'youth' , 'id' => $id ])->first();
        $get_zipcodes = User::select('zipcode')->whereNotNull('zipcode')
        ->where('zipcode','<>','')->where('role_as' , 0)->orderBy('id' , 'DESC')->get();
        // get all the jerseys to show in slider at the bottom
        $get_kids_product = Product::with('product_images')->where(['status' => 'active' , 'product_type' => 'youth'])->get();
        if ($kids_product != null ) {
            // get product sizes
            $get_sizes = ProductVariation::with('productSize')->where(['product_id'=>$id , 'status'=> '1'])->orderBy('id' ,'ASC')->get();
          }
        return view('front.shop.show_kids_jersey' , compact('get_kids_product' , 'kids_product' , 'get_zipcodes', 'get_sizes' ));


    }


    public function shopProduct(Request $request)
    {
        switch ($request->input('action')) {
            case 'buy_now':
                // return $this->buyNow($request);
                return $this->buyNow($request);
            case 'add_to_cart':
                return $this->addToCart($request);
        }

        abort(400, 'Invalid action');
    }

    // add to cart using session
    public function addToCart(Request $request)
    {
        // dd($request);

        // Session::forget('shoppingCart');

        $product = Product::with('product_images')->find($request->product_id);


        if(!$product) {
            abort(404);
        }

        // set jersey number as 01 , 02 , 00 , 03 etc..
        $get_number_count =strlen($request->product_number) ;
           if ($get_number_count < 2 && $request->product_number == '0' ) {
             $product_number = '0';
           } elseif($get_number_count == 2 && $request->product_number == '00' ) {
            $product_number = '00';
           }
           elseif($get_number_count == 0 && $request->product_number == null ) {
            $product_number = '';
           }

           else{
            $product_number = sprintf("%02d", $request->product_number);
           }

        //    if the jersey number the kid is going to add into cart is already purchased by someone
               $already_buy_kid_product =  DB::table('order_items')->where(['age_group'=>  $request->age_group, 'zipcode' => $request->zipcode , 'product_jersy_number' => $request->product_number] )->get()->count();
            //    $already_buy_kid_product =  DB::table('order_items')->where(['product_size'=>  '10-13', 'zipcode' => $request->zipcode , 'product_jersy_number' => $request->product_number] )->get()->count();

               if( $already_buy_kid_product > 0){
                    return redirect()->back()->with('error' , 'This Jersey Number is out of stock for zipcode :'.$request->zipcode);
               }

           //    required checks for youth on add to cart
           $age_group_1 = DB::table('order_items')->where(['age_group'=>  '10-13', 'zipcode' => $request->zipcode] )->get()->count();
           $age_group_2 = DB::table('order_items')->where(['age_group'=>  '14-17', 'zipcode' => $request->zipcode] )->get()->count();



           if ($request->age_group == '10-13' &&  $age_group_1 == config('app.kid_product_limit_for_each_group')) {
               return redirect()->back()->with('error' , 'Out of stock for age group (10-13) !');
           }
           elseif($request->age_group == '14-17' && $age_group_2 == config('app.kid_product_limit_for_each_group')) {
               return redirect()->back()->with('error' , 'Out of stock for age group (14-17) !');
           }

           if($request->age_group == '10-13' OR $request->age_group == '14-17' ){

               $request->merge(["product_number"=>$product_number]);
               $request->validate([
                   'name' => 'required',
                   'product_number' => 'required|numeric|max:99|unique:order_items,product_jersy_number,NULL,id,product_size,'
                                       .$request->size. ',zipcode,' .$request->zipcode,
                   'age_group' => 'required',
                   'size' => 'required',
               ], [

                   'product_number.required' => 'Jersey Number is required.',
                   'product_number.numeric' => 'Jersey number should be numeric.',
                   'product_number.max' => 'Jersey number limit is upto 99.',
                   'product_number.unique' => 'Jersey Number is already taken. Please try another number.',
               ]);

           }else{
               $request->validate([
                   'name' => 'required',
                   'product_number' => 'required|numeric|max:99',
                   'gender' => 'required',
                   'size' => 'required',
               ], [
                   'product_number.required' => 'Jersey Number is required.',
                   'product_number.numeric' => 'Jersey number should be numeric.',
                   'product_number.max' => 'Jersey number limit is upto 99.',
                   'product_number.unique' => 'Jersey Number is already taken. Please try another number.',
               ]);

           }



        $shoppingCart = session()->get('shoppingCart');
        // dd( $shoppingCart);
        // if shoppingCart is empty then this the first jersey
        if(!$shoppingCart) {
            $shoppingCart = [
                    $request->product_id => [
                        'product_id' => $request->product_id,
                        'product_title' => $request->product_name,
                        'product_size' => $request->size,
                        'age_group' => $request->age_group,
                        'product_jersy_name' =>$request->name,
                        'product_jersy_number' => $product_number,
                        'product_qty' => $request->quantity,
                        'product_gender' => $request->gender,
                        'product_price' => $request->product_price,
                        'product_image' => $product->product_images['0']->image_name,

                    ]
            ];
            session()->put('shoppingCart', $shoppingCart);
            $getshoppingCart = session()->get('shoppingCart');

            return redirect()->route('cart');
        }
        // if cart not empty then check if this product exist then increment quantity
        if(isset($shoppingCart[$request->product_id])) {

            // $shoppingCart[$request->product_id]['quantity']++;
            return redirect()->back()->with('error', 'Product is already exists in cart!');
            session()->put('shoppingCart', $shoppingCart);
            return redirect()->route('cart');
        }
        // if item not exist in cart then add to cart with quantity = 1
        $shoppingCart[$request->product_id] = [
            'product_id' => $request->product_id,
            'product_title' => $request->product_name,
            'product_size' => $request->size,
            'age_group' => $request->age_group,
            'product_jersy_name' =>$request->name,
            'product_jersy_number' =>  $product_number,
            'product_qty' => $request->quantity,
            'product_gender' => $request->gender,
            'product_price' => $request->product_price,
            'product_image' => $product->product_images['0']->image_name,
        ];
        session()->put('shoppingCart', $shoppingCart);
        $getshoppingCart = session()->get('shoppingCart');
        
        return redirect()->route('cart');
    }

    // redirecting to cart page
    public function cartJersey(){
       return view('front.shop.cart');
    }


    // shop jersey from add to cart page bu click on proceed to checkout btn , save all cart items data into orders table
    public function proceed_to_checkout(Request $request){
        if (session()->has('shoppingCart')){
            $shoppingCart = session()->get('shoppingCart');

            return view('front.payment.cart_jeresy_payment');
        }

    }


    public function proceed_to_checkout_from_cart(Request $request){
        DB::beginTransaction();
        try{


             if (session()->has('shoppingCart')){
            // dd(Auth::user()->id , 'user data :'  , session()->get('shoppingCart'));
            $get_cart_total_amount = $request->cart_total_amount;
            if (!empty(session()->get('shoppingCart'))){

                // store shopping cart data into order table
                $create_cart_order = Order::create([
                    'user_id' => Auth::user()->id,
                    'zipcode' => Auth::user()->zipcode,
                    'total_amount' => $request->amount,
                    'order_created_date' => Carbon::now()->format('Y-m-d'),
                    'order_status' => 'pending',
                ]);

                // store shopping cart data into orderitem table
                foreach (session('shoppingCart') as $product_id => $product_details){
                    // now store all the cart items in order table
                    $create_cart_order_item = OrderItem::create([
                        'order_id' => $create_cart_order->id,
                        'product_id' => $product_details['product_id'],
                        'product_title' => $product_details['product_title'],
                        'product_size' => $product_details['product_size'],
                        'age_group' => $product_details['age_group'],
                        'zipcode' =>  Auth::user()->zipcode,
                        'product_jersy_name' => $product_details['product_jersy_name'],
                        'product_jersy_number' => $product_details['product_jersy_number'],
                        'product_qty' => $product_details['product_qty'],
                        'product_gender' => $product_details['product_gender'],
                        'product_price' => $product_details['product_price'],
                    ]);
                }
            }
        }

            $random_couponCode = 'GDP'.strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 12));
            // new payment creation
                $client = new Client();


                $response = $client->request('POST',$this->getTheChargeUrl(), [
                    'json' => [
                        'ecomind' => 'ecom',
                        'amount' => $request->input('amount'),
                        // 'amount' => config('app.product_amount'),
                        'user_id' =>   Auth::user()->id,
                        'name' =>  $request->input('name'),
                        'currency' => $this->currency,
                        'capture' => true,
                        'source' => $request->input('cloverToken'),
                    ],
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer '.$this->getThePrivateKey(),
                    ],
                ]);

                $res = json_decode($response->getBody(), true);

                if(isset($res["error"])){
                    $msg=$res["error"]["message"];
                    return redirect()->back()->with('message_error' , $msg);
                }

                elseif(isset($res["status"]) && $res["status"]=="succeeded"){

                    $message =  'This pre signup  user having id '.Auth::user()->id.' has successfully done the payment. The transaction number is '.$res["id"].'and the reference number is '.$res['ref_num'];
                    // Log::channel('product_payment_successfull')->info($message);
                    Log::channel('jersey_payment_successfull')->info($message);

                        $data = [
                            'user_id' =>Auth::user()->id,
                            'order_id' => $create_cart_order->id,
                            // 'product_id' => $product_id,
                            'coupon_code' => $random_couponCode,
                            // 'season_id' => $request->input('season'),
                            'amount'=> $res["amount"],
                            'transaction_id'=> $res["id"],
                            'payment_method' => $res["payment_method_details"],
                            'status' => $res["status"],
                            'currency' => $res["currency"],
                            'clover_payment_created_timestamp' => $res["created"],
                            'ref_num' => $res["ref_num"],
                            'exp_month_card' => $res['source']["exp_month"],
                            'exp_year_card' => $res['source']["exp_year"],
                            'first6_digit_of_card' =>  $res['source']["first6"],
                            'last4_digit_of_card' =>$res['source']["last4"],
                            'clover_payment_intiation_id'=>$res['source']["id"],
                            'name'=> $request->input('fname'),
                            'email'=> Auth::user()->email,
                            // 'email'=> $request->input('email'),
                            'address'=>$request->input('address'),
                            'city'=>$request->input('city'),
                            'zipcode'=>$res['source']["address_zip"],
                            'country'=> $request->input('country')

                        ];
                        $product_Payment = PaymentForProduct::create($data);
                                if ($product_Payment ) {

                                    DB::commit();

                                    // fetch orderDetails to sending order confirmation mail to user
                                    $orderDetails = DB::table('order_items')
                                    ->join('orders', 'orders.id', '=', 'order_items.order_id')
                                    ->join('payment_for_products', 'payment_for_products.order_id', '=', 'orders.id')
                                    ->join('products', 'products.id', '=', 'order_items.product_id')
                                    ->join('product_images', 'product_images.product_id', '=', 'products.id')
                                     ->where('orders.id','=',$create_cart_order->id)
                                     ->where('product_images.image_sort','=','1')
                                     ->orderBy('order_items.order_id' , 'DESC')
                                     ->get();

                                    $update_order_status = Order::whereId( $product_Payment->order_id)->update([
                                        'order_status' => 'success'
                                    ]);


                                    session()->forget('shoppingCart');
                                    Mail::to(Auth::user()->email)->send(new JerseyBuyMail($orderDetails));
                                    return view('front.payment.product_payment_success' , compact('product_Payment'));


                                } else {
                                    $update_order_status = Order::whereId( $product_Payment->order_id)->update([
                                        'order_status' => 'failed'
                                    ]);

                                    return redirect()->back()->with('error', "Something  went wrong");
                                }


                }

            }   catch (\GuzzleHttp\Exception\ClientException $e) {
                DB::rollback();
                $response = $e->getResponse();
                $result =  $response->getBody()->getContents();
                $formatted_response =   json_decode( $result, true);
                if($formatted_response['error']['code'] == 'card_declined'){
                    return redirect()->back()->with('message_error' , 'Your Card is Declined. Please try again or use another card.');
                }
            else{
                        return redirect()->back()->with('message_error' , 'We are facing issue from the payment gateway. Please try again or use another card.');
                        }

            }


        catch (GuzzleHttp\Exception\BadResponseException $exception) {
            DB::rollback();
                $response = $exception->getResponse();
                $responseAsString = $response->getBody()->getContents();
                $formatted_response =   json_decode( $responseAsString, true);
                // dd($formatted_response);
                if($formatted_response['error']['code'] == 'issuer_declined'){
                    return redirect()->back()->with('message_error' , 'Your Card is Declined.Please try again or use another card.');
                }else{
                return redirect()->back()->with('message_error' , 'We are facing issue from the payment gateway. Please try again or use another card.');
                }

        }catch (\Exception $e) {
            DB::rollback();
            $message =  'This pre signup user having id '.Auth::user()->id.' is facing the following isssue '.$e->getMessage();

            Log::channel('jersey_payment_failed')->info($message);
            // Log::channel('product_payment_failed')->info($message);

            return redirect()->back()->with('message_error', "We are facing issue while processing your payment. Please try after some time.If amount is debited from your side then please contact to our support team");

        }


    }



    // public function update(Request $request)
    // {
    //     if($request->id and $request->quantity)
    //     {
    //         $cart = session()->get('cart');
    //         $cart[$request->id]["quantity"] = $request->quantity;
    //         session()->put('cart', $cart);
    //         session()->flash('success', 'Cart updated successfully');
    //     }
    // }


    public function removeCartItem(Request $request)
    {

        if($request->id) {
            $shoppingCart = session()->get('shoppingCart');
            if(isset($shoppingCart[$request->id])) {
                unset($shoppingCart[$request->id]);
                session()->put('shoppingCart', $shoppingCart);
            }
            session()->flash('success', 'Product removed successfully');
        }
    }

    public function remove_all_items_from_cart(){

        if (session()->has('shoppingCart')){
            if (!empty(session()->get('shoppingCart'))){
                session()->forget('shoppingCart');
            }
        }
        return redirect()->route('cart')->with('success' ,'All items has been removed from the cart');


    }


    // shop jersey from  buy now button
    public function buyNow(Request $request){

        if ($request->isMethod('post')) {



            $get_number_count =strlen($request->product_number) ;
           if ($get_number_count < 2 && $request->product_number == '0' ) {
             $product_number = '0';
           } elseif($get_number_count == 2 && $request->product_number == '00' ) {
            $product_number = '00';
           }
           elseif($get_number_count == 0 && $request->product_number == null ) {
            $product_number = '';
           }

           else{
            $product_number = sprintf("%02d", $request->product_number);
           }

            //    required checks for youth on buy now
            $age_group_1 = DB::table('order_items')->where(['age_group'=>  '10-13', 'zipcode' => $request->zipcode] )->get()->count();
            $age_group_2 = DB::table('order_items')->where(['age_group'=>  '14-17', 'zipcode' => $request->zipcode] )->get()->count();


            if ($request->age_group == '10-13' &&  $age_group_1 == config('app.kid_product_limit_for_each_group')) {
                return redirect()->back()->with('error' , 'Out of stock for age group (10-13) !');
            }
            elseif($request->age_group == '14-17' && $age_group_2 == config('app.kid_product_limit_for_each_group')) {
                return redirect()->back()->with('error' , 'Out of stock for age group (14-17) !');
            }

            if($request->age_group == '10-13' OR $request->age_group == '14-17' ){
                $request->merge(["product_number"=>$product_number]);
                $request->validate([
                    'name' => 'required',
                    'product_number' => 'required|numeric|max:99|unique:order_items,product_jersy_number,NULL,id,product_size,'
                                        .$request->size. ',zipcode,' .$request->zipcode,
                    'size' => 'required',
                    'age_group' => 'required',
                ], [

                    'product_number.required' => 'Jersey Number is required.',
                    'product_number.numeric' => 'Jersey number should be numeric.',
                    'product_number.max' => 'Jersey number limit is upto 99.',
                    'product_number.unique' => 'Jersey Number is already taken. Please try another number.',
                ]);

            }else{
                $request->validate([
                    'name' => 'required',
                    'product_number' => 'required|numeric|max:99',
                    'gender' => 'required',
                    'size' => 'required',
                ], [
                    'product_number.required' => 'Jersey Number is required.',
                    'product_number.numeric' => 'Jersey number should be numeric.',
                    'product_number.max' => 'Jersey number limit is upto 99.',
                    'product_number.unique' => 'Jersey Number is already taken. Please try another number.',
                ]);

            }

            // create session to store all product data by click on Buy now btn

            Session::forget('buy_now_session');

        $create_buy_now_session = session()->get('buy_now_session');
        // if shoppingCart is empty then this the first jersey
        if(!$create_buy_now_session) {
            $create_buy_now_session = [
                    // $request->product_id => [

                        'product_id' => $request->product_id,
                        'product_title' => $request->product_name,
                        'product_size' => $request->size,
                        'age_group' => $request->age_group,
                        'product_jersy_name' =>$request->name,
                        'product_jersy_number' => $product_number,
                        'product_qty' => $request->quantity,
                        'product_gender' => $request->gender,
                        'product_price' => $request->product_price,
                    // ]
            ];
            session()->put('buy_now_session', $create_buy_now_session);
            $get_buy_now_session = session()->get('buy_now_session');

            // dd($get_buy_now_session);


        }
        return redirect('proceed-to-checkout');
        }
    }


    public function proceedToCheckout(){

        return view('front.payment.product_payment');
    }

    public function placeOrder(Request $request){

        DB::beginTransaction();
        try{
            $random_couponCode = 'GDP'.strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 12));

            // store session data in order table
            $create_order = Order::create([
                'user_id' => Auth::user()->id,
                'zipcode' => Auth::user()->zipcode,
                'total_amount' => $request->amount,
                'order_created_date' => Carbon::now()->format('Y-m-d'),
                'order_status' => 'pending',
            ]);
            // store session data into order item table
             // get buy now session
            if(session()->has('buy_now_session')){
                if(!empty(session()->get('buy_now_session'))){
                    $get_buy_now_session = session()->get('buy_now_session');
                    $create_order_item = OrderItem::create([
                        'order_id' => $create_order->id,
                        'product_id' => $get_buy_now_session['product_id'],
                        'product_title' => $get_buy_now_session['product_title'],
                        'product_size' => $get_buy_now_session['product_size'],
                        'age_group' => $get_buy_now_session['age_group'],
                        'zipcode' =>  Auth::user()->zipcode,
                        'product_jersy_name' => $get_buy_now_session['product_jersy_name'],
                        'product_jersy_number' => $get_buy_now_session['product_jersy_number'],
                        'product_qty' => $get_buy_now_session['product_qty'],
                        'product_gender' => $get_buy_now_session['product_gender'],
                        'product_price' => $get_buy_now_session['product_price'],
                    ]);

                }
            }

            // new payment creation
                $client = new Client();
                $response = $client->request('POST',$this->getTheChargeUrl(), [
                    'json' => [
                        'ecomind' => 'ecom',
                        'amount' => $request->input('amount'),
                        // 'amount' => config('app.product_amount'),
                        'user_id' =>   Auth::user()->id,
                        'name' =>  $request->input('fname'),
                        'currency' => $this->currency,
                        'capture' => true,
                        'source' => $request->input('cloverToken'),
                    ],
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer '.$this->getThePrivateKey(),
                    ],
                ]);


                $res = json_decode($response->getBody(), true);
                if(isset($res["error"])){
                    $msg=$res["error"]["message"];
                    return redirect()->back()->with('message_error' , $msg);
                }

                elseif(isset($res["status"]) && $res["status"]=="succeeded"){

                    $message =  'This product customer having id '.Auth::user()->id.' has successfully done the payment for product. The transaction number is '.$res["id"].'and the reference number is '.$res['ref_num'];
                    Log::channel('jersey_payment_successfull')->info($message);
                    // Log::channel('product_payment_successfull')->info($message);

                        $data = [
                            'user_id' =>Auth::user()->id,
                            'order_id' => $create_order->id,
                            'coupon_code' => $random_couponCode,
                            // 'season_id' => $request->input('season'),
                            'amount'=> $res["amount"],
                            'transaction_id'=> $res["id"],
                            'payment_method' => $res["payment_method_details"],
                            'status' => $res["status"],
                            'currency' => $res["currency"],
                            'clover_payment_created_timestamp' => $res["created"],
                            'ref_num' => $res["ref_num"],
                            'exp_month_card' => $res['source']["exp_month"],
                            'exp_year_card' => $res['source']["exp_year"],
                            'first6_digit_of_card' =>  $res['source']["first6"],
                            'last4_digit_of_card' =>$res['source']["last4"],
                            'clover_payment_intiation_id'=>$res['source']["id"],
                            'name'=> $request->input('fname'),
                            'email'=> Auth::user()->email,
                            // 'email'=> $request->input('email'),
                            'address'=>$request->input('address'),
                            'city'=>$request->input('city'),
                            'zipcode'=>$res['source']["address_zip"],
                            'country'=> $request->input('country')

                        ];


                        $product_Payment = PaymentForProduct::create($data);
                                if ($product_Payment ) {
                                    DB::commit();
                                    // fetch orderDetails to sending order confirmation mail to user
                                    $orderDetails = DB::table('order_items')
                                    ->join('orders', 'orders.id', '=', 'order_items.order_id')
                                    ->join('payment_for_products', 'payment_for_products.order_id', '=', 'orders.id')
                                    ->join('products', 'products.id', '=', 'order_items.product_id')
                                    ->join('product_images', 'product_images.product_id', '=', 'products.id')

                                     ->where('orders.id','=',$create_order->id)
                                     ->where('product_images.image_sort','=','1')
                                     ->orderBy('order_items.order_id' , 'DESC')
                                     ->get();

                                    //  echo "<pre>";
                                    //  print_r($orderDetails);
                                    //  die();




                                    $update_order_status = Order::whereId( $product_Payment->order_id)->update([
                                        'order_status' => 'success'
                                    ]);
                                    session()->forget('buy_now_session');
                                    Mail::to(Auth::user()->email)->send(new JerseyBuyMail($orderDetails));
                                    return view('front.payment.product_payment_success' , compact('product_Payment'));


                                } else {
                                    $update_order_status = Order::whereId( $product_Payment->order_id)->update([
                                        'order_status' => 'failed'
                                    ]);
                                    session()->forget('buy_now_session');
                                    return redirect()->back()->with('error', "Something  went wrong");
                                }



                }

            }  catch (\GuzzleHttp\Exception\ClientException $e) {
                DB::rollback();
                $response = $e->getResponse();
                $result =  $response->getBody()->getContents();
                $formatted_response =   json_decode( $result, true);
                if($formatted_response['error']['code'] == 'card_declined'){
                    return redirect()->back()->with('message_error' , 'Your Card is Declined. Please try again or use another card.');
                }
            else{
                        return redirect()->back()->with('message_error' , 'We are facing issue from the payment gateway. Please try again or use another card.');
                        }

            }

        catch (GuzzleHttp\Exception\BadResponseException $exception) {
            DB::rollback();
                $response = $exception->getResponse();
                $responseAsString = $response->getBody()->getContents();
                $formatted_response =   json_decode( $responseAsString, true);

                // dd($formatted_response);

                if($formatted_response['error']['code'] == 'issuer_declined'){
                    return redirect()->back()->with('message_error' , 'Your Card is Declined.Please try again or use another card.');
                }else{
                return redirect()->back()->with('message_error' , 'We are facing issue from the payment gateway. Please try again or use another card.');
                }

        }catch (\Exception $e) {
            DB::rollback();
            $message =  'This pre signup user having id '.Auth::user()->id.' is facing the following isssue '.$e->getMessage();

            Log::channel('jersey_payment_failed')->info($message);
            // Log::channel('product_payment_failed')->info($message);

            return redirect()->back()->with('message_error', "We are facing issue while processing your payment.Please try after some time.If amount is debited from your side then please contact to our support team");

        }

    }


}
