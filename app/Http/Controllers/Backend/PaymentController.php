<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index(){
        $get_payments = Payment::with(['season' ,'user'])->orderBy('id', 'desc')->get();
      // dd($get_payments);
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

}
