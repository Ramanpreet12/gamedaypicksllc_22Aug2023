<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session,Auth;
use Stripe;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Address;
use App\Models\Season;
use Illuminate\Support\Carbon;
use App\Models\UserDetail;
use App\Models\UserTeam;
use App\Models\Coupon;
use Illuminate\Support\Facades\Mail;
use App\Mail\Payment as payment_class;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Log;

class StripeController extends Controller
{

    private $payment_mode;

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


    public function stripe()
    {
        // $date = Carbon::now();
        // $season =Season::where('starting', '<=',  $date)
        // ->where('ending', '>=',  $date)
        // ->where('league','1')
        // ->first();

        // $date = Season::where('status' , 'active')->value('starting');
        $get_current_year = Carbon::now()->format('Y');
        $date = Season::where(['status'=>'active' , 'season_name' => $get_current_year])->value('starting');

        $season = DB::table('seasons')
            ->whereRaw('"' . $date . '" between `starting` and `ending`')
            ->where('status' , 'active')
            ->where('league','1')
            ->first();

        return view('front.payment.index',compact('season'));
    }




    public function selectTeam(Request $req)
    {
        DB::beginTransaction();
        try {
            // $c_date = Carbon::now();
            // $c_season= DB::table('seasons')
            //     ->whereRaw('"'.$c_date.'" between `starting` and `ending`')
            //     ->first();
            $select = UserTeam::where(['user_id'=>$req->user_id,'season_id'=>$req->season_id,'week'=>$req->week])->first();
            if($select){
                return response()->json(['status'=>200,'message'=>'Team is already selected for week','select'=>'already']);
            }else{
               $created =  UserTeam::create([
                    'user_id'=>$req->user_id,
                    'leauge_id'=>1,
                    'season_id'=>$req->season_id,
                    'week'=>$req->week,
                    'team_id'=>$req->team_id,
                    'fixture_id'=>$req->fixture
                ]);
            }
            DB::commit();
            if ($created) {
                return response()->json(['status'=>200,'message'=>'Team selected successfully','select'=>'']);
            } else {
                return response()->json(['status'=>401,'message'=>'Team is not selected','select'=>'']);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>401,'message'=>$e->getMessage()]);
        }
    }


    public function clover_charge(Request $request){

        try {
        $get_current_year = Carbon::now()->format('Y');
        $c_date = Season::where(['status'=>'active' , 'season_name' => $get_current_year])->value('starting');
        $c_season = DB::table('seasons')
            ->whereRaw('"' . $c_date . '" between `starting` and `ending`')
            ->where('status' , 'active')
            ->first();

        $payment_data = Payment::where(['user_id' => auth()->user()->id , 'season_id' => $request->input('season') ])->first();

        if($payment_data){
             $season_name_for_msg = $c_season->season_name;
             $season_for_msg = 'You are already subscribed for season :'.$season_name_for_msg;

             return redirect()->back()->with('message_error' ,$season_for_msg);
         }


         // new payment creation
        $client = new Client();

        $response = $client->request('POST',$this->getTheChargeUrl(), [
                'json' => [
                    'ecomind' => 'ecom',
                    'amount' => ($c_season->season_amount)*100,
                    'user_id' =>   auth()->user()->id,
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

                $message =  'This user having id '.auth()->user()->id.' has successfully done the payment. The transaction number is '.$res["id"].'and the reference number is '.$res['ref_num'];
                Log::channel('successfullpayment')->info($message);

                    $data = [
                        'user_id' => auth()->user()->id,
                        'season_id' => $request->input('season'),
                        'amount'=> $res["amount"]/100,
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
                        'clover_payment_intiation_id'=>$res['source']["id"]
                    ];
                    $Payment = Payment::create($data);
                    $addressData = [
                        'user_id'=>auth()->user()->id,
                        'payment_id'=> $Payment->id,
                        'name'=> $request->input('fname'),
                        'address'=>$request->input('address'),
                        'city'=>$request->input('city'),
                        'zip'=>$res['source']["address_zip"],
                        'country'=> $request->input('country')
                    ];
                    $address = Address::create($addressData);

                    $orderDetails = DB::table('payments')->join('addresses', 'addresses.payment_id','=', 'payments.id')->join('seasons', 'seasons.id','=', 'payments.season_id')->where(['payments.id' => $Payment->id])->select('seasons.season_name','payments.*','addresses.name','addresses.address','addresses.city','addresses.country','addresses.zip')->first();

                            if ($address) {
                                Mail::to(Auth::user()->email)->send(new payment_class($orderDetails));
                                // Mail::to('yamanwalia000@gmail.com')->send(new payment_class($orderDetails));
                                return view('front.payment.success' , compact('Payment'));

                            } else {

                                return redirect()->back()->with('error', "Something  went wrong");
                            }

            }
       } catch (GuzzleHttp\Exception\BadResponseException $exception) {

            $response = $exception->getResponse();
            $responseAsString = $response->getBody()->getContents();
            $formatted_response =   json_decode( $responseAsString, true);

            dd($formatted_response);

            if($formatted_response['error']['code'] == 'issuer_declined'){
                return redirect()->back()->with('message_error' , 'Your Card is Declined.Please try again or use another card.');
            }else{
               return redirect()->back()->with('message_error' , 'We are facing issue from the payment gateway. Please try again or use another card.');
            }

       }catch (\Exception $e) {

        $message =  'This user having id '.auth()->user()->id.' is facing the following isssue '.$e->getMessage();

        Log::channel('payment')->info($message);

        return redirect()->back()->with('message_error', "We are facing issue while processing your payment.Please try after some time.If amount is debited from your side then please contact to our support team");

      }

    }

    public function couponPage(Request $request){
        if ($request->isMethod('post')) {
           $coupon_check =  Coupon::where('coupon_code' , $request->coupon_code)->first();

           //if the requested coupon match with coupon_code in table
           if ($coupon_check != null) {
                //if user already had used the coupon (check if he reusing the coupon )
                $reusing_the_coupon =  Coupon::where(['coupon_code' => $request->coupon_code , 'user_id' => auth()->user()->id])->first();
                if ($reusing_the_coupon != null) {
                    return redirect()->back()->with('message_error' , 'You have already used this coupon !');

                } //reusing condition ends here
                else {
                //store the id of the user who use the coupon
                    $store_user_id =  Coupon::where('coupon_code' , $request->coupon_code)->update(['user_id' => auth()->user()->id]);
                    //if coupon has matched and the id of user has been saved then redirect with success msg
                    if ($store_user_id) {
                        return redirect()->back()->with('success' , 'coupon applied successfully');

                    }
                } //(reusing condition's else part ends here)




            }// requested coupon match condition ends here


            //if the requested coupon doesn't match with coupon_code in table
            else {
                return redirect()->back()->with('message_error' , 'Invalid coupon code !');
            }



        }
        else {
            $get_current_year = Carbon::now()->format('Y');
            $date = Season::where(['status'=>'active' , 'season_name' => $get_current_year])->value('starting');

            $season = DB::table('seasons')
                ->whereRaw('"' . $date . '" between `starting` and `ending`')
                ->where('status' , 'active')
                ->where('league','1')
                ->first();

            return view('front.payment.coupon' , compact('season'));
        }
    }
}
