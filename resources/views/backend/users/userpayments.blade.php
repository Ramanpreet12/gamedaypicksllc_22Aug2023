@extends('../layout/' . $layout)

@section('subhead')
    <title>NFL | Users</title>
@endsection

@section('subcontent')
    {{-- <h2 class="intro-y text-lg font-medium mt-10">Banners Management</h2> --}}
    @if (session()->has('success_msg'))
    <div class="alert alert-success show flex items-center mb-2 alert_messages" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
            class="bi bi-check2-circle" viewBox="0 0 16 16">
            <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z" />
            <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z" />
        </svg>
        &nbsp; {{ session()->get('success_msg') }}
    </div>

@endif

@if (session('error_msg'))
<div class="alert alert-danger-soft show flex items-center mb-2 alert_messages" role="alert">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
        class="feather feather-alert-octagon w-6 h-6 mr-2">
        <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
        <line x1="12" y1="8" x2="12" y2="12"></line>
        <line x1="12" y1="16" x2="12.01" y2="16"></line>
    </svg>
    {{ session('error_msg') }}
</div>
@endif


    <div class="grid grid-cols-12 gap-6 mt-5 p-5 bg-white mb-5">
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2" id="user_table">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center whitespace-nowrap">S.no.</th>
                        <th class="text-center whitespace-nowrap">Season</th>
                        <th class="text-center whitespace-nowrap">Transaction Id</th>
                        <th class="text-center whitespace-nowrap"> Payment Status</th>
                        <th class="text-center whitespace-nowrap"> Payment Method</th>
                        <th class="text-center whitespace-nowrap"> Amount</th>
                        <th class="text-center whitespace-nowrap">Payment Expire Date</th>
                        <th class="text-center whitespace-nowrap">Payment Date</th>
                        <th class="text-center whitespace-nowrap">Biller Name</th>
                        <th class="text-center whitespace-nowrap">Biller Address </th>
                        <th class="text-center whitespace-nowrap">Biller City </th>
                        <th class="text-center whitespace-nowrap">Biller Country </th>
                        <th class="text-center whitespace-nowrap">Action</th>
                    </tr>
                </thead>

                <tbody>
                @if ($userPaymentData->isNotEmpty())
                    @php
                    $count = '';
                    @endphp
                    @foreach ($userPaymentData as $PayData)
                            <tr class="intro-x">
                                <td>
                                    <div class="text-slate-500 font-medium whitespace-nowrap mx-4">  {{++$count}} </div>
                                </td>

                                <td> 
                                    <div class="text-slate-500 font-medium whitespace-nowrap mx-4">  {{$PayData->season_name}} </div>
                                </td>
                                <td>
                                    <div class="text-slate-500 font-medium whitespace-nowrap mx-4">  {{$PayData->transaction_id}} </div>
                                </td>

                                <td>
                                    <div class="text-slate-500 font-medium whitespace-nowrap mx-4">  {{$PayData->status}} </div>
                                </td>
                                <td>
                                    <div class="text-slate-500 font-medium whitespace-nowrap mx-4">  {{$PayData->payment_method}} </div>
                                </td>
                                <td>
                                    <div class="text-slate-500 font-medium whitespace-nowrap mx-4">  {{$PayData->amount}}{{$PayData->currency}} </div>
                                </td>

                                <td>
                                    <div class="text-slate-500 font-medium whitespace-nowrap mx-4">  {{ \Carbon\Carbon::parse($PayData->expire_on)->format('j F, Y') }} </div>
                                </td>

                                <td>
                                    <div class="text-slate-500 font-medium whitespace-nowrap mx-4">  {{ \Carbon\Carbon::parse($PayData->created_at)}} </div>
                                </td>

                                <td>
                                    <div class="text-slate-500 font-medium whitespace-nowrap mx-4">  {{$PayData->name}} </div>
                                </td>

                                <td>
                                    <div class="text-slate-500 font-medium whitespace-nowrap mx-4">  {{$PayData->address}} </div>
                                </td>

                                <td>
                                    <div class="text-slate-500 font-medium whitespace-nowrap mx-4">  {{$PayData->city}} </div>
                                </td>

                                <td>
                                    <div class="text-slate-500 font-medium whitespace-nowrap mx-4">  {{$PayData->country}} </div>
                                </td>
                                <td><a href="{{ route('admin/PaymentInvoice',['id'=>$PayData->id])}}" class="btn btn-primary">Download</a></td>
                            </tr>

                        @endforeach
                        @else
                        <tr>
                            <td colspan="12" class="text-center">No Records found</td>

                        </tr>
                        @endif

                </tbody>
            </table>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->

        <!-- END: Pagination -->
    </div>
@endsection



   @section('script')
   <script>
    $(function() {
      $('#user_table').DataTable({
        scrollX: true,
      });
    });
   </script>
   @endsection
