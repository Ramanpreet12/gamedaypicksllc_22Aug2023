@extends('front.layout.app')

@section('content')

{{-- <section id="aboutBanner" style="background-image:url(front/img/new_banner_2.jpg)"> --}}





@if($general->selected_option ==  'youtube_link')

<section id="aboutBanner">

        <div class="ratio youtube-container" style="--bs-aspect-ratio: 35%;">

        <iframe src="{{$general->youtubelink}}?autoplay=1&mute=1&loop=1&controls=0"  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen> </iframe>



        <!-- <iframe width="560" height="315" src="http://www.youtube.com/embed/GRonxog5mbw?autoplay=1&loop=1&playlist=GRonxog5mbw" frameborder="0" allowfullscreen></iframe>​ -->





        </div>

        </div>

@else

    @if (($get_prize_banner != null ) && ($get_prize_banner->prize_banner))

        <section id="aboutBanner" style="background-image:url({{asset('storage/images/general/'.$get_prize_banner->prize_banner)}})">

    @else

        <section id="aboutBanner" style="background-image:url(front/img/new_banner_2.jpg)">

    @endif

@endif







    <div class="container">

        <div class="row">

            <div class="col">

            </div>

        </div>

    </div>

</section>



<section id="prizePart">

    <div class="container">

        <div class="row align-items-center justify-content-center">

            <div class="col-12">

                @if (!empty($get_prize_heading->value))

                <h2 class="prize_head">{{ strtoupper($get_prize_heading->value) }}</h2>

                    @else

                    <h2 class="prize_head">Prizes</h2>

                    @endif



            </div>

        </div>

        @foreach ($prizes as $prize)

        @if($loop->iteration % 2 == 0)

        {{-- <div class="even"></div> --}}

        <div class="prizeShow">

            <div class="card mb-3">

                <div class="row g-0">

                <div class="col-md-6 order-md-2">

                        {{-- <img src="front/img/winn2.jpg" class="img-fluid" alt="..."> --}}

                        <img src="{{asset('storage/images/prize/'.$prize->image)}}" class="img-fluid" alt="...">

                    </div>



                    <div class="col-md-6">

                        <div class="card-body">

                            {{-- <h3 class="card-title prize_head">Singapore Holiday Package</h3> --}}

                            <h3 class="card-title prize_head">{{$prize->name}}</h3>

                           

                            <div class="prize_content">

                                <p class="card-text prize_text">{!! $prize->content !!}</p>

                            </div>

                            <div class="prize d-flex">

                                <span class="prize_text"><i class="fa-solid fa-trophy "></i> Season :  {{$prize->season->season_name ?? ''}}</span>

                                {{-- <span><i class="fa-solid fa-calendar-days"></i> DECEMBER 19, 2016</span> --}}

                                <span class="prize_text"><i class="fa-solid fa-calendar-days "></i> {{ \Carbon\Carbon::parse($prize->prize_date ?? '')->format('j F, Y') }}</span>


                                {{-- <span class="prize_text"><i class="fa-solid fa-calendar-days "></i> {{ \Carbon\Carbon::parse($prize->season->starting ?? '')->format('j F, Y') }}</span> --}}

                            </div>

                        </div>

                    </div>

                    

                </div>

            </div>

        </div>





    @else

        {{-- <div class="odd"></div> --}}





        <div class="prizeShow">

            <div class="card mb-3">

                <div class="row g-0">

                    <div class="col-md-6">

                        {{-- <img src="front/img/winn.webp" class="img-fluid" alt="..."> --}}

                        <img src="{{asset('storage/images/prize/'.$prize->image)}}" class="img-fluid" alt="...">

                    </div>

                    <div class="col-md-6">

                        <div class="card-body">

                            <h3 class="card-title prize_head">{{$prize->name}}</h3>

                            <div class="prize_content">

                                <p class="card-text prize_text">{!! $prize->content !!}</p>

                            </div>

                           

                            <div class="prize d-flex">

                                <span class="prize_text"><i class="fa-solid fa-trophy"></i> Season : {{$prize->season->season_name ?? ''}}</span>

                                {{-- <span><i class="fa-solid fa-calendar-days"></i> DECEMBER 19, 2016</span> --}}

                                {{-- <span class="prize_text"><i class="fa-solid fa-calendar-days"></i> {{ \Carbon\Carbon::parse($prize->season->starting ?? '')->format('j F, Y') }}</span> --}}

                                <!-- <p class="card-text"><small class="text-muted">DECEMBER 19, 2016</small></p>              -->
                                <span class="prize_text"><i class="fa-solid fa-calendar-days "></i> {{ \Carbon\Carbon::parse($prize->prize_date ?? '')->format('j F, Y') }}</span>


                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif







        @endforeach





        {{-- <div class="prizeShow">

            <div class="card mb-3">

                <div class="row g-0">

                    <div class="col-md-6">

                        <img src="front/img/winn3.webp" class="img-fluid" alt="...">

                    </div>

                    <div class="col-md-6">

                        <div class="card-body">

                            <h3 class="card-title ">World Tour</h3>

                            <p class="card-text prize_text">Prize money at the FIFA World Cup is distributed according to

                                performance, with Argentina receiving a handsome payout of 42 million U.S. dollars

                                for winning the 2022 tournament.</p>

                            <div class="prize d-flex">

                                <span><i class="fa-solid fa-trophy "></i> Indian Super League</span>

                                <span><i class="fa-solid fa-calendar-days"></i> DECEMBER 19, 2016</span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div> --}}

    </div>

</section>

<style>

    .prize_head{

        color: <?php echo $colorSection['prize']['header_color']; ?>;

    }

    .prize_text , .prize_content{

        color: <?php echo $colorSection['prize']['text_color']; ?>;

    }



    .youtube-container {

	overflow: hidden;

	width: 100%;

	/* Keep it the right aspect-ratio */ 

	/* No clicking/hover effects */

	pointer-events: none;

    }

  .youtube-container  	iframe {

		/* Extend it beyond the viewport... */

		width: 300%;

		height: 100%;

		/* ...and bring it back again */

		margin-left: -100%;

	} 

</style>



@endsection

