@extends('front.layout.app')
@section('content')
  <!-- mainheader -->
  <!-- <section id="aboutBanner" style="background-image:url(front/img/new_banner_2.jpg)">
    <div class="container">
        <div class="row">
            <div class="col">
            </div>
        </div>
    </div>
</section> -->

<!-- <section id="arenaCrousel">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2>Arena</h4>
                    <div class="owl-carousel owl-heroSlider">
                        <div class="owlItem">
                            <img src="front/img/arenaOne.jpg" alt="" class="img-fluid">
                        </div>
                        <div class="owlItem">
                            <img src="front/img/arenaTwo.jpg" alt="" class="img-fluid">
                        </div>
                        <div class="owlItem">
                            <img src="front/img/arenaThree.webp" alt="" class="img-fluid">
                        </div>
                    </div>
            </div>
        </div>
    </div>
</section> -->
<!-- <section id="locationBox">
    <div class="container">
        <div class="loacationChart">
          <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="locationInfo">
                    <div class="loactionAddress d-flex">
                        <i class="fa-solid fa-location-dot"></i>
                        <h5>Loaction</h5>
                        <p>20 First Avenue, San Jose, California 95101, United States</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="locationInfo">
                    <div class="loactionAddress d-flex">
                        <i class="fa-solid fa-laptop"></i>
                        <h5>Capacity</h5>
                        <p> 93.607</p>
                    </div>
                </div>
                <hr>
                <div class="locationInfo">
                    <div class="loactionAddress d-flex">
                        <i class="fa-solid fa-inbox"></i>
                        <h5>Surface</h5>
                        <p> Natural grass</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="locationInfo">
                    <div class="loactionAddress d-flex">
                        <i class="fa-solid fa-calendar"></i>
                        <h5>Opened</h5>
                        <p>May 1, 1983</p>
                    </div>
                </div>
                <hr>
                <div class="locationInfo">
                    <div class="loactionAddress d-flex">
                        <i class="fa-solid fa-republican"></i>
                        <h5>Renovated</h5>
                        <p>1993, 1995, 2011</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section> -->
<section id="abouttexting">
<div class="container">
<div class="row">
            <div class="col-12">
                <h2 class="about_head">{{$get_about_details->heading}}</h4>
            </div>
</div>
<div class="aboutText">
    <div class="row">
        
        <div class="col-md-6 order-md-last mb-4">
        <div class="aboutusImg">
           
                            {{-- <img src="front/img/arenaOne.jpg" alt="" class="img-fluid"> --}}
                            @if ($get_about_details->images)
                            <img src="{{asset('storage/images/static_page/'.$get_about_details->images)}}" alt="" class="img-fluid">
                            @else
                            <img src="{{asset('front/img/arenaOne.jpg')}}" alt="" class="img-fluid">
                            @endif
                            
                        </div>
        </div>
        <div class="col-md-6">
            <p class="about_text">{!! $get_about_details->content !!}</p>
        </div>
    </div>
</div>
</div>
</section>
<style>
    .about_head{
        color: <?php echo $colorSection['about']['header_color']; ?>;
    }
    .about_text{
        color: <?php echo $colorSection['about']['text_color']; ?>;
    }
</style>
@endsection
