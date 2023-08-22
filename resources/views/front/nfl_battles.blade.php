@extends('front.layout.app')
@section('content')
    <section id="matchFixture">
        <div class="container mt-4">
            <div class="row">


                <div class="col-12">
                    <h2 class="fixture_head">{{ $fixture_headings['match_fixture_section_heading'] }}</h2>

                    <div class="headerMenu row">
                        <div class="col">
                            <h5 class="seasonFixed fixture_head" id="">
                               
                                {{ $fixture_headings['match_fixture_selected_season_heading'] }}:
                                {{ $c_season->season_name ?? '' }}
                            </h5>
                        </div>
                        <div class="col">
                            {{-- @if (Request::url() == config('app.url') . '/fixtures/weeks') --}}
                            @foreach ($fixtures as $week => $data)
                                <h5 id="set_week" class="seasonFixed selectWeekPart fixture_head">
                                    {{ $fixture_headings['match_fixture_selected_week_heading'] }} :
                                    {{ $week }}</h5>
                            @endforeach
                            {{-- @endif --}}
                        </div>
                        <div class="fixtureForms col">
                            <form action="{{ url('nfl-battles') }}" method="get" class="seasonFixed formSpacing">
                                <div class="inner_form">
                                    {{-- @csrf --}}
                                    <label class="fixture_head" for=""
                                        style=" margin-right:10px; font-weight:800; font-size: 20px; font-family: 'Oxanium', 'cursive';">{{ $fixture_headings['match_fixture_select_season_heading'] }}:
                                    </label>
                                    @if ($get_all_seasons->isNotEmpty())
                                        <select class="form-control" name="seasons" id="seasons">
                                            {{-- <option value="">{{$c_season->season_name ?? ''}}</option> --}}
                                            {{-- <input type="text" name="" value=""> --}}
                                            {{-- <option value="">select </option> --}}

                                            @foreach ($get_all_seasons as $season)
                                                <option value="{{ $season->id ?? '' }}"
                                                    {{ $c_season->id == $season->id ? 'selected' : '' }}>
                                                    {{ $season->season_name }}</option>
                                            @endforeach

                                            <i class="fa-solid fa-angle-down"></i>
                                        </select>
                                    @endif
                                </div>
                            </form>
                        </div>
                        {{-- for weeks --}}
                        <div class=" col">
                            <form action="{{ url('nfl-battles') }}" method="get" class="seasonFixed ">
                                <div class="inner_form">
                                    {{-- @csrf --}}
                                    <input type="hidden" value="{{ $c_season->id ?? '' }}" name="season_id">
                                    <label class="fixture_head" for=""
                                        style=" margin-right:10px; font-weight:800; font-size: 20px; font-family: 'Oxanium', 'cursive';">{{ $fixture_headings['match_fixture_select_week_heading'] }}:
                                    </label>
                                    @if ($get_all_seasons->isNotEmpty())
                                        <select class="form-control" name="weeks" id="weeks">
                                            @for ($i = 1; $i <= 18; $i++)
                                                <option value="{{ $i }}"
                                                    @php if( request()->query('weeks') == $i){ echo "selected"; } @endphp>
                                                    Week
                                                    {{ $i }}</option>
                                            @endfor
                                        </select>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-4">
                <form action="">
                    <div>
                        <select name="" id="" class="form-control">
                            <option value="">select Season</option>
                            @foreach ($fixtures as $seasons => $item)
                                <option value="">{{ $seasons }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="" id="" class="form-control">
                            @foreach ($fixtures as $seasons => $item)
                                <option value="">{{ $seasons }}</option>
                            @endforeach
                            <option value="">select Week</option>
                        </select>
                    </div>
                </form>
            </div> --}}

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <div class="alert alert-danger show flex items-center mb-2 alert_messages text-center"
                            role="alert" style="display:none;" id="login_msg_div">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-alert-octagon w-6 h-6 mr-2">
                                <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                </polygon>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <span>Login first to select the team . </span>
                            <a href="{{ route('login') }}">Click here to login</a>
                        </div>
                        @if ($fixtures->isNotEmpty())
                            <table class="table">
                                <thead>
                                    <tr class="table-dark">

                                        {{-- <th scope="col" class="rollPlay">Week</th> --}}
                                        <th scope="col" class="fixture_head text-center">Match</th>
                                        <th scope="col" class="fixture_head text-center">Date</th>
                                        <th scope="col" class="fixture_head text-center">Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fixtures as $week => $weakData)
                                        <tr>
                                            <td style="color: #db9a29;font-weight:bold;" class="text-center ">Week :
                                                {{ $week }}</td>
                                            <td></td>
                                            <td></td>
                                            {{-- <td></td> --}}
                                        </tr>
                                        @foreach ($weakData as $weeks => $team)
                                            @if (!empty($team->first_team_id) && !empty($team->second_team_id))
                                                @php
                                                    $formatted_first_team_name = str_replace(' ', '_', $team->first_team_id->name);
                                                @endphp
                                                @php
                                                    $formatted_second_team_name = str_replace(' ', '_', $team->second_team_id->name);
                                                @endphp
                                            @endif

                                            @if ($week == $team->week)
                                                {{-- {{dd($get_selected_teams_by_user)}} --}}
                                                <tr>
                                                    <td>
                                                        <div
                                                            class="fixureMatch d-flex align-items-center justify-content-center">
                                                            <div class="teamOne">
                                                                @if (\Carbon\Carbon::now() > $team->season->ending)
                                                                    <button data-bs-toggle="modal"
                                                                        data-bs-target="#SeasonExpireModal"
                                                                        style="background:none;  border:none; color:#212529"
                                                                        class="expire_season_msg">

                                                                        <img src="{{ asset('storage/images/team_logo/' . $team->first_team_id->logo) }}"
                                                                            alt="" class="img-fluid">

                                                                        <div class="fixture_text" style="min-width:200px">
                                                                            @if (!empty($team->first_team_id))
                                                                                {{ $team->first_team_id->name }}
                                                                            @else
                                                                                {{ 'TBD' }}
                                                                            @endif

                                                                        </div>
                                                                    </button>
                                                                @else
                                                                    @if (!empty($team->first_team_id))
                                                                        @if (!empty($get_selected_teams))
                                                                            @if (get_selected_teams($team->first_team_id->id, $team->season_id, $team->id, $team->week))
                                                                                <button disabled
                                                                                    style="background:rgb(243, 101, 101);  border:none; color:#212529"
                                                                                    class="team_name"
                                                                                    @if ($upcoming_week > $team->date)
                                                                                    upcoming_selectable_week = "true"
                                                                                    @else
                                                                                    upcoming_selectable_week = "false"
                                                                                    @endif

                                                                                    
                                                                                    @if ($upcoming_season_date < $team->date)

                                                                                    fixture_id={{ $team->id }}
                                                                                    team_id={{ $team->first_team_id->id }}
                                                                                    season_id={{ $team->season_id }}
                                                                                    week={{ $team->week }}
                                                                                    teamName={{ $formatted_first_team_name }}
                                                                                    first_teamName={{ $formatted_first_team_name }}
                                                                                    second_teamName={{ $formatted_second_team_name }}
                                                                                    fixture_date={{ $team->date }}
                                                                                    fixture_time={{ \Carbon\Carbon::createFromFormat('H:i:s', $team->time)->format('H:i') }}{{ $team->time_zone }}
                                                                                    @endif>
                                                                                    <img src="{{ asset('storage/images/team_logo/' . $team->first_team_id->logo) }}"
                                                                                        alt="" class="img-fluid">

                                                                                    <div class="fixture_text"
                                                                                        style="min-width:200px">
                                                                                        {{ $team->first_team_id->name }}
                                                                                    </div>
                                                                                </button>
                                                                            @else
                                                                                <button data-bs-toggle="modal"
                                                                                    data-bs-target="#selectTeam"
                                                                                    style="background:none;  border:none; color:#212529"
                                                                                    class="team_name"
                                                                                    @if ($upcoming_week > $team->date)
                                                                                    upcoming_selectable_week = "true"
                                                                                    @else
                                                                                    upcoming_selectable_week = "false"
                                                                                    @endif
                                                                                    @if ($upcoming_season_date < $team->date)

                                                                                    fixture_id={{ $team->id }}
                                                                                    team_id={{ $team->first_team_id->id }}
                                                                                    season_id={{ $team->season_id }}
                                                                                    week={{ $team->week }}
                                                                                    teamName={{ $team->first_team_id->name }}
                                                                                    teamName={{ $formatted_first_team_name }}
                                                                                    first_teamName={{ $formatted_first_team_name }}
                                                                                    second_teamName={{ $formatted_second_team_name }}
                                                                                    fixture_date={{ $team->date }}
                                                                                    fixture_time={{ \Carbon\Carbon::createFromFormat('H:i:s', $team->time)->format('H:i') }}{{ $team->time_zone }}
                                                                                    @endif>
                                                                                    <img src="{{ asset('storage/images/team_logo/' . $team->first_team_id->logo) }}"
                                                                                        alt="" class="img-fluid">

                                                                                    <div class="fixture_text"
                                                                                        style="min-width:200px">
                                                                                        {{ $team->first_team_id->name }}
                                                                                    </div>
                                                                                </button>
                                                                            @endif
                                                                        @else
                                                                            <button data-bs-toggle="modal"
                                                                                data-bs-target="#selectTeam"
                                                                                style="background:none;  border:none; color:#212529"
                                                                                class="team_name"
                                                                                @if ($upcoming_week > $team->date)
                                                                                    upcoming_selectable_week = "true"
                                                                                    @else
                                                                                    upcoming_selectable_week = "false"
                                                                                    @endif
                                                                                    @if ($upcoming_season_date < $team->date)

                                                                                fixture_id={{ $team->id }}
                                                                                team_id={{ $team->first_team_id->id }}
                                                                                season_id={{ $team->season_id }}
                                                                                week={{ $team->week }}
                                                                                teamName={{ $team->first_team_id->name }}
                                                                                teamName={{ $formatted_first_team_name }}
                                                                                first_teamName={{ $formatted_first_team_name }}
                                                                                second_teamName={{ $formatted_second_team_name }}
                                                                                fixture_date={{ $team->date }}
                                                                                fixture_time={{ \Carbon\Carbon::createFromFormat('H:i:s', $team->time)->format('H:i') }}{{ $team->time_zone }}
                                                                                @endif>
                                                                                <img src="{{ asset('storage/images/team_logo/' . $team->first_team_id->logo) }}"
                                                                                    alt="" class="img-fluid">

                                                                                <div class="fixture_text"
                                                                                    style="min-width:200px">
                                                                                    {{ $team->first_team_id->name }}
                                                                                </div>
                                                                            </button>
                                                                        @endif
                                                                    @else
                                                                        {{ 'TBD' }}
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="versis">
                                                                <h5 class="fixture_head">VS</h5>

                                                            </div>
                                                            <div class="teamOne">
                                                                @if (\Carbon\Carbon::now() > $team->season->ending)
                                                                    <button data-bs-toggle="modal"
                                                                        data-bs-target="#SeasonExpireModal"
                                                                        style="background:none;  border:none; color:#212529"
                                                                        class="expire_season_msg">

                                                                        <img src="{{ asset('storage/images/team_logo/' . $team->second_team_id->logo) }}"
                                                                            alt="" class="img-fluid">

                                                                        <div class="fixture_text" style="min-width:200px">
                                                                            {{ $team->second_team_id->name }}
                                                                        </div>
                                                                    </button>
                                                                @else
                                                                    @if (!empty($team->second_team_id))
                                                                        @if (!empty($get_selected_teams))
                                                                            @if (get_selected_teams($team->second_team_id->id, $team->season_id, $team->id, $team->week))
                                                                                <button disabled class="team_name"
                                                                                    style="background:rgb(184, 107, 107);  border:none; color:#212529"
                                                                                    @if ($upcoming_week > $team->date)
                                                                                    upcoming_selectable_week = "true"
                                                                                    @else
                                                                                    upcoming_selectable_week = "false"
                                                                                    @endif
                                                                                    @if ($upcoming_season_date < $team->date)

                                                                                    fixture_id={{ $team->id }}
                                                                                    team_id={{ $team->second_team_id->id }}
                                                                                    season_id={{ $team->season_id }}
                                                                                    week={{ $team->week }}
                                                                                    teamName={{ $formatted_second_team_name }}
                                                                                    first_teamName={{ $formatted_first_team_name }}
                                                                                    second_teamName={{ $formatted_second_team_name }}
                                                                                    fixture_date={{ $team->date }}
                                                                                    fixture_time={{ \Carbon\Carbon::createFromFormat('H:i:s', $team->time)->format('H:i') }}{{ $team->time_zone }}
                                                                                    @endif>
                                                                                    <img src="{{ asset('storage/images/team_logo/' . $team->second_team_id->logo) }}"
                                                                                        alt="" class="img-fluid">

                                                                                    <div class="fixture_text"
                                                                                        style="min-width:200px">
                                                                                        {{ $team->second_team_id->name }}
                                                                                    </div>
                                                                                </button>
                                                                            @else
                                                                                <button data-bs-toggle="modal"
                                                                                    data-bs-target="#selectTeam"
                                                                                    class="team_name"
                                                                                    style="background:none;  border:none; color:#212529"
                                                                                    @if ($upcoming_week > $team->date)
                                                                                    upcoming_selectable_week = "true"
                                                                                    @else
                                                                                    upcoming_selectable_week = "false"
                                                                                    @endif
                                                                                    @if ($upcoming_season_date < $team->date)

                                                                                    fixture_id={{ $team->id }}
                                                                                    team_id={{ $team->second_team_id->id }}
                                                                                    season_id={{ $team->season_id }}
                                                                                    week={{ $team->week }}
                                                                                    teamName={{ $formatted_second_team_name }}
                                                                                    first_teamName={{ $formatted_first_team_name }}
                                                                                    second_teamName={{ $formatted_second_team_name }}
                                                                                    fixture_date={{ $team->date }}
                                                                                    fixture_time={{ \Carbon\Carbon::createFromFormat('H:i:s', $team->time)->format('H:i') }}{{ $team->time_zone }}
                                                                                    @endif>
                                                                                    <img src="{{ asset('storage/images/team_logo/' . $team->second_team_id->logo) }}"
                                                                                        alt="" class="img-fluid">

                                                                                    <div class="fixture_text"
                                                                                        style="min-width:200px">
                                                                                        {{ $team->second_team_id->name }}
                                                                                    </div>
                                                                                </button>
                                                                            @endif
                                                                        @else
                                                                            <button data-bs-toggle="modal"
                                                                                data-bs-target="#selectTeam"
                                                                                class="team_name"
                                                                                style="background:none;  border:none; color:#212529"
                                                                                @if ($upcoming_week > $team->date)
                                                                                    upcoming_selectable_week = "true"
                                                                                    @else
                                                                                    upcoming_selectable_week = "false"
                                                                                    @endif
                                                                                    @if ($upcoming_season_date < $team->date)

                                                                                fixture_id={{ $team->id }}
                                                                                team_id={{ $team->second_team_id->id }}
                                                                                season_id={{ $team->season_id }}
                                                                                week={{ $team->week }}
                                                                                teamName={{ $formatted_second_team_name }}
                                                                                first_teamName={{ $formatted_first_team_name }}
                                                                                second_teamName={{ $formatted_second_team_name }}
                                                                                fixture_date={{ $team->date }}
                                                                                fixture_time={{ \Carbon\Carbon::createFromFormat('H:i:s', $team->time)->format('H:i') }}{{ $team->time_zone }}
                                                                                @endif>
                                                                                <img src="{{ asset('storage/images/team_logo/' . $team->second_team_id->logo) }}"
                                                                                    alt="" class="img-fluid">

                                                                                <div class="fixture_text"
                                                                                    style="min-width:200px">
                                                                                    {{ $team->second_team_id->name }}
                                                                                </div>
                                                                            </button>
                                                                        @endif
                                                                    @else
                                                                        {{ 'TBD' }}
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>


                                                    {{-- <td class="fixture_text text-center" >{{$team->date}}</td> --}}
                                                    <td class="fixture_text text-center">
                                                        {{ \Carbon\Carbon::parse($team->date)->format('j F, Y') }}
                                                    </td>
                                                    @if ($team->time == '12:00:00' && ($team->time_zone = 'am'))
                                                        <td class="fixture_text text-center">TBD</td>
                                                    @else
                                                        <td class="fixture_text text-center">
                                                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $team->time)->format('H:i') }}
                                                            {{ ucfirst($team->time_zone) }} ET
                                                    @endif
                                                    </td>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <hr style="color:#888;">
                            <section id="pageNotFound" class="no_data_found">

                                <div class="container">
                                    <div class="row justify-content-center text-center">
                                        <div class="col-auto">
                                            <div class="notFoundImg">
                                                {{-- <img src="https://nfl.kloudexpert.com/front/img/soccerFootball.png" alt=""> --}}
                                                <img src="{{ asset('front/img/soccerFootball.png') }}" alt="">
                                            </div>
                                            <h3>No Data Found</h3>

                                            <a href="{{ route('home') }}" class="btn btn-primary">Go to Home</a>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    {{-- <div class="modal fade" id="selectTeam" tabindex="-1" aria-labelledby="selectTeamLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="teamSelectedMsg">
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary pr-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Modal -->
    <div class="modal fade" id="SeasonExpireModal" tabindex="-1" aria-labelledby="selectTeamLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="expire_season_msg">
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary pr-4" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                </div>
            </div>
        </div>
    </div>
    <style>
        #matchFixture {
            background-color: <?php echo $colorSection['match_fixture']['bg_color']; ?>;
        }

        .fixture_text {
            color: <?php echo $colorSection['match_fixture']['text_color']; ?>;
        }

        .fixture_head {
            color: <?php echo $colorSection['match_fixture']['header_color']; ?>;
        }
    </style>
@endsection



@section('script')
    <script type="text/javascript">
        jQuery(function() {
            jQuery('#seasons').change(function() {
                this.form.submit();
            });

            jQuery('#weeks').change(function() {
                this.form.submit();
            });


            //Pick the team from fixture page

            $('.team_name').click(function() {
                let attr = $(this).attr('fixture_id');
                if (typeof attr == 'undefined') {
                    Swal.fire({
                        title: 'Time over ',
                        html: "Your Time to pick the team for week is over . You can pick the team <span style='color:#f27474'> before Thursday 12:00 AM </span> . You can still pick the team for next week.Good Luck",
                        icon: 'error',
                    });

                    return false;

                }

                let upcoming_selectable_week = $(this).attr('upcoming_selectable_week');
                // console.log(upcoming_selectable_week);
                if (upcoming_selectable_week != 'true') {
                    Swal.fire({
                        title: "Can't pick the team in advance ! ",
                        html: "You can't pick the team before it's starts. Please wait for the week to come.",
                        icon: 'error',
                    });

                    return false;

                }


                let season_id = $(this).attr('season_id');
                let fixture_id = $(this).attr('fixture_id');
                let team_id = $(this).attr('team_id');
                let teamName = $(this).attr('teamName');
                formatted_team_name = teamName.replace(/_/g, ' ');

                let first_teamName = $(this).attr('first_teamName');
                formatted_first_team_name = first_teamName.replace(/_/g, ' ');

                let second_teamName = $(this).attr('second_teamName');
                formatted_second_team_name = second_teamName.replace(/_/g, ' ');

                let fixture_date = $(this).attr('fixture_date');
                let fixture_time = $(this).attr('fixture_time');
                let week = $(this).attr('week');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    // url: '/check_user',
                    url: '/check_user_subscribe_for_nfl_battles',
                    data: {
                        season_id: season_id,
                        fixture_id: fixture_id,
                        team_id: team_id,
                        week: week
                    },
                    success: function(resp) {

                         console.log(resp);
                        let login_url = "payment";
                        if (resp.message == 'not_login') {
                            console.log('pleaser login');
                            Swal.fire({
                                title: 'Please login first ?',
                                icon: 'warning',
                                showCancelButton: true,
                                html: `Please <a href="${login_url}">login</a> To pick the team`

                            });
                        } else {
                        let url = "payment";
                        if (resp.message == 'not subscribed') {
                            Swal.fire({
                                // title: 'Please subscribe first ?',
                                icon: 'warning',
                                showCancelButton: true,
                                html: `Please <a href="${url}">Subscribe </a> To pick the team`

                            });
                        } else {
                            Swal.fire({
                                title: 'Are you sure?',
                                html: "Do you really want to pick the <span style='color:#3085d6'>" +
                                    formatted_team_name +
                                    " </span> team for the nfl battle between <span style='color:#3085d6'>" +
                                    formatted_first_team_name +
                                    "</span> and <span style='color:#3085d6'>" +
                                    formatted_second_team_name + " </span>?",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, Pick it!'
                            }).then((result) => {
                                if (result.isConfirmed) {

                                    $.ajax({
                                        type: 'POST',
                                        // url: '/check_user',
                                        url: '/nfl_battles_team_pick',
                                        data: {
                                            season_id: season_id,
                                            fixture_id: fixture_id,
                                            team_id: team_id,
                                            week: week
                                        },
                                        success: function(resp) {
                                            console.log(resp);

                                            if (resp.message == 'update') {
                                                Swal.fire({
                                                    title: 'Your Pick team has been updated',
                                                    html: "You have pick <span style='color:#3085d6'>" +
                                                        formatted_team_name +
                                                        " </span> team for  week <span style='color:#3085d6'>" +
                                                        week +
                                                        " </span> on <span style='color:#3085d6'>" +
                                                        fixture_date +
                                                        " </span> at <span style='color:#3085d6'>" +
                                                        fixture_time +
                                                        " </span>",
                                                    icon: 'success',
                                                    // showCancelButton: true,


                                                });
                                                setTimeout(() => {
                                                    location
                                                        .reload();
                                                }, 5000);
                                            }
                                            if (resp.message == 'added') {
                                                Swal.fire({
                                                    title: 'You have pick the team',
                                                    html: "You have pick <span style='color:#3085d6'>" +
                                                        formatted_team_name +
                                                        " </span> team for  week <span style='color:#3085d6'>" +
                                                        week +
                                                        " </span> on <span style='color:#3085d6'>" +
                                                        fixture_date +
                                                        " </span> at <span style='color:#3085d6'>" +
                                                        fixture_time +
                                                        " </span>",
                                                    icon: 'success',
                                                    // showCancelButton: true,

                                                });
                                                setTimeout(() => {
                                                    location
                                                        .reload();
                                                }, 5000);
                                            }

                                            // else{
                                            //     location.reload();
                                            // }
                                        }
                                    });

                                }
                                // else {
                                //     console.log('not');
                                // }
                            });
                        }
                    }
                    }
                });

            });


            // $('.team_name').click(function() {

            //     let season_id = $(this).attr('season_id');
            //     let fixture_id = $(this).attr('fixture_id');
            //     let team_id = $(this).attr('team_id');
            //     let teamName = $(this).attr('teamName');
            //     let fixture_date = $(this).attr('fixture_date');
            //     let fixture_time = $(this).attr('fixture_time');
            //     let week = $(this).attr('week');

            //     formatted_team_name = teamName.replace(/_/g, ' ');

            //     let first_teamName = $(this).attr('first_teamName');
            //     formatted_first_team_name = first_teamName.replace(/_/g, ' ');

            //     let second_teamName = $(this).attr('second_teamName');
            //     formatted_second_team_name = second_teamName.replace(/_/g, ' ');

            //     $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         }
            //     });

            //     $.ajax({
            //         type: 'POST',
            //         // url: '/check_user',
            //         url: '/fixture_team_pick',
            //         data: {
            //             season_id: season_id,
            //             fixture_id: fixture_id,
            //             team_id: team_id,
            //             week: week
            //         },
            //         success: function(resp) {
            //             if (resp.message == 'login') {
            //                 // let login_url = "{{ route('login') }}";
            //                 // location.href = login_url;
            //                 $('#selectTeam #teamSelectedMsg').html(
            //                     '<p style="color:red"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon w-6 h-6 mr-2"> <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"> </polygon> <line x1="12" y1="8" x2="12" y2="12"></line> <line x1="12" y1="16" x2="12.01" y2="16"></line>  </svg> <span> Please <a href="{{ route('login') }}" style="color:red">login</a> first to continue . </span></p>'
            //                 );
            //             }
            //             if (resp.message == 'subscribe') {
            //                 $('#login_msg_div').hide();
            //                 // let payment_url = "{{ route('payment') }}";
            //                 // location.href = payment_url;
            //                 $('#selectTeam #teamSelectedMsg').html(
            //                     '<p style="color:red"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon w-6 h-6 mr-2"> <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"> </polygon> <line x1="12" y1="8" x2="12" y2="12"></line> <line x1="12" y1="16" x2="12.01" y2="16"></line>  </svg> <span> Please <a href="{{ route('payment') }}" style="color:red">subscribe</a> to pick the teams . It will cost you $100 . </span></p>'
            //                 );
            //             }
            //             if (resp.message == 'update') {
            //                 $('#selectTeam #teamSelectedMsg').html(
            //                     'You have selected <span style="color:#06083B">' +
            //                     teamName +
            //                     '</span> for the week  <span style="color:#06083B"> ' +
            //                     week + ' </span> on <span style="color:#06083B">' +
            //                     fixture_date + '</span> at <span style="color:#06083B">' +
            //                     fixture_time + '</span>');
            //             }
            //             if (resp.message == 'added') {
            //                 $('#selectTeam #teamSelectedMsg').html(
            //                     'You have selected <span style="color:#06083B;">' +
            //                     teamName +
            //                     '</span> for the week <span style="color:#06083B"> ' +
            //                     week + ' </span> on <span style="color:#06083B">' +
            //                     fixture_date + '</span> at <span style="color:#06083B">' +
            //                     fixture_time + '</span>');
            //             }
            //             if (resp.message == 'Time_id_over') {
            //                 $('#selectTeam #teamSelectedMsg').html(
            //                     '<p style="color:red"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon w-6 h-6 mr-2"> <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"> </polygon> <line x1="12" y1="8" x2="12" y2="12"></line> <line x1="12" y1="16" x2="12.01" y2="16"></line>  </svg> <span> Your time is over to pick the team  as you can pick the team till Thursaday 12:00 am . You will receive loss for this week  </span></p>'
            //                 );
            //             }

            //         },
            //     })
            // });




            $('.expire_season_msg').click(function() {
                $('#SeasonExpireModal #expire_season_msg').html(
                    '<p style="color:red"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon w-6 h-6 mr-2"> <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"> </polygon> <line x1="12" y1="8" x2="12" y2="12"></line> <line x1="12" y1="16" x2="12.01" y2="16"></line>  </svg> <span style="color:red" >Season has been expired </span></p>'
                );
            });

        });
    </script>
@endsection
