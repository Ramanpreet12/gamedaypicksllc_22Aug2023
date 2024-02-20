<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\PaymentForProduct;
use DB;

class PaymentController extends Controller
{
    public function index(){
        $get_payments = Payment::with(['season' ,'user'])->orderBy('id', 'desc')->get();
    //   dd($get_payments);
        return view('backend.payment.index' , compact('get_payments'));
    }

    public function getAll(){
        $payment = Payment::paginate(6);
        return response()->json($payment, 200);

    }

    //user payment
    public function paymentPage()
    {
        return view('front.payment.index');
    }

    // jersey order payment

    public function orderPayment()
    {
        // $orderDetails = DB::table('order_items')
        //                             ->join('orders', 'orders.id', '=', 'order_items.order_id')
        //                             ->join('payment_for_products', 'payment_for_products.order_id', '=', 'orders.id')
        //                             ->join('products', 'products.id', '=', 'order_items.product_id')
        //                             ->join('product_images', 'product_images.product_id', '=', 'products.id')
        //                             //  ->where('orders.id','=',$create_cart_order->id)
        //                              ->where('product_images.image_sort','=','1')
        //                              ->orderBy('order_items.order_id' , 'DESC')
        //                              ->get();


        $get_orders = DB::table('payment_for_products')
                        ->join('users' , 'users.id' , '=' , 'payment_for_products.user_id' )
                        ->join('orders' , 'orders.id' , '=' , 'payment_for_products.order_id' )
                        ->join('order_items' , 'order_items.order_id' , '=' , 'orders.id' )
                        ->join('products' , 'order_items.product_id' , '=' , 'products.id' )
                        ->join('product_images' , 'product_images.product_id' , '=' , 'products.id' )
                        ->where('product_images.image_sort','=','1')
                        ->select('payment_for_products.id as payemnt_id' , 'payment_for_products.coupon_code' , 'payment_for_products.transaction_id'  ,
                                'payment_for_products.payment_method' ,  'payment_for_products.status' ,
                                'payment_for_products.currency' ,  'payment_for_products.ref_num'  , 'payment_for_products.clover_payment_intiation_id' ,
                                'payment_for_products.created_at' ,  'payment_for_products.address', 'payment_for_products.city' , 'payment_for_products.zipcode' , 'payment_for_products.country',
                                'users.id as user_id' , 'users.name'  , 'users.email' , 'users.photo' , 'users.gender' , 'users.age'  , 'users.dob' ,
                               'orders.id as order_id' ,'orders.total_amount' , 'orders.order_created_date' ,
                                'orders.order_status'  , 'order_items.product_title' , 'order_items.product_size' , 'order_items.product_jersy_name' ,
                                'order_items.product_jersy_number' , 'order_items.product_qty' , 'order_items.product_gender' , 'order_items.product_price' ,
                                'products.id as product_id'  , 'product_images.image_name' )
                        ->get();

        // dd($get_orders);

        return view('backend.payment.order_payment' , compact('get_orders'));
    }

    public function orderUserDetails($id) {
        $get_users_order_details = DB::table('payment_for_products')
                        ->join('users' , 'users.id' , '=' , 'payment_for_products.user_id' )
                        // ->join('users' , 'users.id' , '=' , $id)
                        ->join('orders' , 'orders.id' , '=' , 'payment_for_products.order_id' )
                        ->join('order_items' , 'order_items.order_id' , '=' , 'orders.id' )
                        ->join('products' , 'order_items.product_id' , '=' , 'products.id' )
                        ->join('product_images' , 'product_images.product_id' , '=' , 'products.id' )
                        ->where('payment_for_products.user_id','=',$id)
                        ->where('product_images.image_sort','=','1')
                        ->select('payment_for_products.id as payemnt_id' , 'payment_for_products.coupon_code' , 'payment_for_products.transaction_id'  ,
                                'payment_for_products.payment_method' ,  'payment_for_products.status' ,
                                'payment_for_products.currency' ,  'payment_for_products.ref_num'  , 'payment_for_products.clover_payment_intiation_id' ,
                                'payment_for_products.created_at' ,  'payment_for_products.address', 'payment_for_products.city' , 'payment_for_products.zipcode' , 'payment_for_products.country',
                                'users.id as user_id' , 'users.name'  , 'users.email' , 'users.photo' , 'users.gender' , 'users.age'  ,
                                'users.dob' , 'users.country_code' , 'users.phone_number',
                               'orders.id as order_id' ,'orders.total_amount' , 'orders.order_created_date' ,
                                'orders.order_status'  , 'order_items.product_title' , 'order_items.product_size' , 'order_items.product_jersy_name' ,
                                'order_items.product_jersy_number' , 'order_items.product_qty' , 'order_items.product_gender' , 'order_items.product_price' ,
                                'products.id as product_id'  , 'product_images.image_name' )
                        ->first();

                        // dd($get_users_order_details);

        return view('backend.payment.order_user_details' , compact('get_users_order_details') );

    }
}
