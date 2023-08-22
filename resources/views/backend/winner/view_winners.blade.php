@extends('../layout/' . $layout)

@section('subhead')
    <title>NFL | Winners</title>
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

    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">All Winners</h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <a class="btn btn-primary shadow-md mr-2" href="{{route('winner.index')}}" id="">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5 p-5 bg-white mb-5">
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2 table-responsive" id="view_winners">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center whitespace-nowrap">Season </th>
                        <th class="text-center whitespace-nowrap">User Name </th>
                        <th class="text-center whitespace-nowrap">User Email </th>
                        <th class="text-center whitespace-nowrap">User Photo </th>
                        <th class="text-center whitespace-nowrap">Points </th>
                        <th class="text-center whitespace-nowrap">Prize</th>
                        <th class="text-center whitespace-nowrap">Prize Photo</th>
                        <th class="text-center whitespace-nowrap">Created At</th>
                        <th class="text-center whitespace-nowrap">Updated At</th>
                        <th class="text-center whitespace-nowrap">Action</th>

                    </tr>
                </thead>

                <tbody>

                    @if ($get_winners->isNotEmpty())
                    @forelse ($get_winners as $winner)

                        <tr class="intro-x">
                            <td>
                                <div class="text-slate-500 font-medium mx-4">  {{$winner->season->season_name}} </div>
                            </td>
                            <td>
                                <div class="text-slate-500 font-medium mx-4"> {{ $winner->user->name ?? ''}} </div>
                            </td>
                            <td>
                                <div class="text-slate-500 font-medium mx-4"> {{ $winner->user->email ?? ''}} </div>
                            </td>
                            <td class="">
                                <div class="flex">
                                    <div class="w-10 h-10 image-fit zoom-in">
                                        @if (!empty($winner->photo))
                                        <img src="{{asset('storage/images/user_images/'.$winner->photo)}}" alt="" height="50px" width="100px" class="rounded-full">
                                        @else
                                                <img src="{{asset('dist/images/dummy_image.webp')}}" alt="" class="img-fluid rounded-full">
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-slate-500 font-medium mx-4">{{ $winner->total_points }} </div>
                            </td>
                            <td>
                                <div class="text-slate-500 font-medium mx-4">  {{$winner->prize->name}} </div>
                            </td>
                            <td class="">
                                <div class="flex">
                                    <div class="zoom-in">
                                        @if (!empty($winner->prize->image))
                                        <img src="{{asset('storage/images/prize/'.$winner->prize->image)}}" alt="" height="50px" width="100px" class="img-fluid">
                                        @else
                                                <img src="{{asset('dist/images/dummy_image.webp')}}" alt="" class="img-fluid rounded-full">
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-slate-500 font-medium  mx-4">{{\Carbon\Carbon::parse($winner->created_at)->format('j F , Y , H:i')}}</div>
                            </td>
                            <td>
                                <div class="text-slate-500 font-medium  mx-4">{{\Carbon\Carbon::parse($winner->updated_at)->format('j F , Y , H:i')}}</div>
                            </td>

                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a class="flex items-center mr-3" href="{{ route('winner.edit',$winner->id) }}">
                                        <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('winner.destroy', $winner->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        {{-- <a class="flex items-center text-danger" href="" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal">
                                            <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                        </a> --}}
                                            <button class="btn btn-danger show_sweetalert" type="submit" data-toggle="tooltip">  <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete</button>

                                      </form>


                                </div>
                            </td>


                            {{-- <td class="text-center">{{ $user->user->name ?? ''}}</td>
                            <td class="text-center">{{ $user->points }}</td> --}}
                            {{-- <td class="text-center">{{ \Carbon\Carbon::parse($user->created_at)->format('j F, Y') }}</td> --}}


                            {{-- <td class="table-report__action">
                                <div class="flex justify-center items-center">
                                    <a class="flex items-center mr-3" href="{{url('admin/winner/assign_prize/'.$user->user->id ?? '')}}">
                                        <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Assign Prize
                                    </a>
                                </div>
                            </td> --}}
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No Records found</td>

                        </tr>
                    @endforelse
                    @endif
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->

        <!-- END: Pagination -->
    </div>
    <!-- BEGIN: Delete Confirmation Modal -->
    <div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-feather="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">Are you sure?</div>
                        <div class="text-slate-500 mt-2">Do you really want to delete these records? <br>This process
                            cannot be undone.</div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <button type="button" data-tw-dismiss="modal"
                            class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                        <button type="button" class="btn btn-danger w-24">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Delete Confirmation Modal -->

@endsection



   @section('script')
   <script>
    $(function() {
      $('#view_winners').DataTable({
        scrollX: true,
      });
    });
   </script>
   @endsection
