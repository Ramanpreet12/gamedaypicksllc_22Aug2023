@extends('front.layout.user_layout.user_app')
@section('content')
   
    <section id="personalInfoBoard"
        style="background-image:url({{ asset('front/img/football-2-bg.jpg') }});color:{{ $colorSection['leaderboard']['text_color'] }};">
        <div class="container-fluid text-center">
            <div class="row">
                <div class="col-sm-12">
                    <div class="personalleaderBoard">
                        <br>
                        <div class="loader d-none">
                            <img height="100px" width="100px" src="{{ asset('front/img/orange_circles.gif') }}"
                                alt="loader">
                        </div>
                    </div>
                </div>
                @include('front.layout.user_layout.user_sidebar')
                <div class="col-sm-8 col-md-9">
                    <h2 style="color:{{ $colorSection['leaderboard']['header_color'] }};">
                        My Selections
                    </h2>
<br>
                    <h6 style="color:{{ $colorSection['leaderboard']['header_color'] }};">
                       Season : {{$season_name}}
                    </h6>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-dark table-striped  tableBoard">
                                    <thead>
                                        <tr class="table-primary">
                                            <th scope="col">S no.</th>

                                            <th scope="col" colspan="3">Match</th>

                                            {{-- <th scope="col">Win</th>
                                            <th scope="col">Loss</th> --}}

                                            <th scope="col">My Pick</th>

                                            <th scope="col"  class="matchFColDate">Date</th>
                                            <th scope="col" class="matchFColTime">Time</th>
                                            <th scope="col">Points</th>
                                        </tr>
                                    </thead>
                                    <tbody>
{{-- {{dd($my_selections)}} --}}
                                        @if (!empty($my_selections))
                                        @foreach ($my_selections as $week => $weakData)

                                        <tr>
                                            <td data-label="Sno."></td>
                                            <td data-label="Match" style="color: #db9a29;font-weight:bold;" colspan="3">Week : {{ $week }}</td>
                                            <td data-label="My Pick"></td>
                                            <td data-label="Date" class="matchFColDate"></td>
                                            <td data-label="Time" class="matchFColTime"></td>
                                            <td data-label="Points"></td>


                                        </tr>

                                        @php
                                            $count ='';
                                        @endphp
                                            {{-- @foreach ($my_selections as $his) --}}
                                            @foreach ($weakData as $weaks => $his)
{{-- {{dd($team)}} --}}
                                                @if ($week == $his->fweek)

                                                <tr>
                                                    <td>{{++$count;}}</td>
                                                    {{-- <td>{{$his->season_name}}</td> --}}
                                                    <td> 
                                                                <img src="{{ asset('storage/images/team_logo/' . $his->first_logo) }}"
                                                                    alt="" class="img-fluid">

                                                                <div style="min-width:100px">
                                                                    {{ $his->first_name }}
                                                                </div>
                                            </td>
                                            <td>
                                                            <div class="versis">
                                                                <h5>VS</h5>
                                                            </div>
                                                            <div class="d-md-none"> 
    <span class="matchFixtureDate" data-title="Date"> {{ \Carbon\Carbon::createFromFormat('Y-m-d', $his->fdate)->format('M d , Y') }}</span> 
                                                 <span class="matchFixtureTime"  data-title="Time">{{ \Carbon\Carbon::createFromFormat('H:i:s', $his->ftime)->format('H:i') }}{{ $his->ftime_zone }}</span>
                                                 </div>
                                                            </td>
                                                            <td>
                                                                <img src="{{ asset('storage/images/team_logo/' .$his->second_logo) }}"
                                                                    alt="" class="img-fluid">

                                                                <div style="min-width:100px">
                                                                    {{ $his->second_name }}
                                                                </div>
                                                            </td> 
                                                    </td>

                                                    {{-- <td>{{ get_team_name($his->team_win) }}
                                                    </td>
                                                    <td>{{ get_team_name($his->team_loss) }}
                                                    </td> --}}

                                                    <td>
                                                        <div class="teamOne teamCard">
                                                            <img src="{{ asset('storage/images/team_logo/' .$his->team_logo) }}"
                                                                alt="" class="img-fluid">

                                                            <div style="min-width:100px">{{ $his->user_team }}</div>
                                                        </div>
                                                    </td>

                                                    <td class="matchFColDate">  <span class="matchFixtureDate">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $his->fdate)->format('M d , Y') }}</span> </td>
                                                    {{-- <td class="matchFColTime"><span class="matchFixtureTime">{{ \Carbon\Carbon::createFromFormat('H:i:s', $his->ftime)->format('H:i') }}{{ $his->ftime_zone }} </span> </td> --}}

                                                    @if($his->ftime == '12:00:00' && $his->ftime_zone = 'am')
                                                    <td class="matchFColTime"><span class="matchFixtureTime">TBD</td>
                                                    @else
                                                    <td class="matchFColTime"><span class="matchFixtureTime">{{ \Carbon\Carbon::createFromFormat('H:i:s', $his->ftime)->format('H:i') }} {{ ucfirst($his->ftime_zone) }}  ET</span> </td>
                                                    @endif
                                                   </td>

                                                    <td>{{ $his->user_point }}
                                                    </td>


                                                </tr>
                                                @endif
                                            @endforeach
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>
@endsection
