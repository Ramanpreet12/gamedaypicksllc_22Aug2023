@extends('front.layout.app')
@section('content')
<section id="successForm">
    <div class="container ">
        <div class="row">
            <div class="col-sm-6 mx-auto border">

                 <div class="successPage">
                    <h2>Payment Successfull</h2>
                    <i class="fa-solid fa-check"></i>
                 </div>
                 <div class="d-flex justify-content-around p-3">
                    <div>
                        {{-- {{dd($jersey_Payment)}} --}}
                        <p>Transaction ID</p>
                        <p>Reference ID</p>
                        <p>Name</p>
                        <p>Email</p>
                        <p>Amount Paid </p>
                        <p>Payment Method </p>
                        <p>Date</p>
                    </div>
                    <div>
                       <p>{{$product_Payment->transaction_id}}</p>
                       <p>{{$product_Payment->ref_num}}</p>
                       <p>{{$product_Payment->name}}</p>
                       <p>{{$product_Payment->email}}</p>
                       <p>{{$product_Payment->amount}} {{env('AMOUNT_CURRENCY')}}</p>
                       <p>{{$product_Payment->payment_method}}</p>
                       <p>{{ \Carbon\Carbon::parse($product_Payment->created_at)->format('j F, Y ,H:i') }}

                        </p>


                </div>

                {{-- <p>We've received your request ,  we'll be in touch shortly!</p> --}}
            </div>
            <div class="text-center">
                <p class="text-center">We've received your request ,  we'll be in touch shortly!</p>
               <a @if (Auth::user()->age <= config('app.jersey_kid_age_limit'))
                href="{{ route('pony-express-flag-football-shop') }}"
                @else
                href="{{ route('shop') }}"
               @endif > <button class="btn btn-primary my-3">Continue</button></a>

            </div>

        </div>
    </div>
</section>

@endsection
{{-- @section('script')
<script>
    let URL = "{{ route('teams') }}";
    setTimeout(function(){window.location=URL }, 10000);
    </script>
@endsection --}}

