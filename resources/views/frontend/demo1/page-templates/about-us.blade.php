<?php
    $banner = !empty($page->photo) ? asset('storage/'.$page->photo) : false;
    $banner_style = !empty($banner) ? 'style="background-image:url('.$banner.')"' : false;
?>

@push('body_class')bespoke-page @endpush

@extends('frontend.demo1.layouts.frontend')

@section('main_content')
<!-- inner-hero -->
<section class="inner-hero">
    <div class="container">
       @include('frontend.demo1.partials.front.pw.breadcrumb')
        <h1 class="f-four" data-aos="fade-right text-uppercase">{!! $page->title !!}</h1>
    </div>
</section>
<!-- End inner-hero -->

<style>
    .transition-box {
        max-height: 155px;       /* collapsed height */
        overflow: hidden;
        transition: max-height 0.5s ease;
    }

    .transition-box.open {
        max-height: 2000px;      /* large enough to show full text */
    }
</style>
<!-- About -->
<section class="abt-wrp-bx u-circle-style-1">
    <div class="u-circle-style-2">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-sm-12">
                    <div class="abt-dt-blc f-align-justify">
                        <h3>{{ $page->header_title }} </h3>
                        <div class="about-content">
                            {!! $page->content !!}
                            
                        </div>

                        <!-- <div class="quotebx">
                                <h4> {!! $page->content1 !!}</h4>
                            </div> -->

          

                    </div>
                 </div>

                
                <div class="col-md-5 col-sm-12">
                    <div class="abt-img-pg">
                        <img src="{{ $banner }}" alt="">
                    </div>
                </div>




                
                <div class="col-sm-13">
                    <div class="abt-dt-blc f-align-justify">
                        <div class="quotebx">
                            <h4> {!! $page->content1 !!}</h4>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
</section>


<section class="our-office-wrp" id="our-office-services">
    <div class="container">
        <div class="position-relative mt-5">
            <div class="generic-header--section -cta text-center">
                <h3 class="generic-header--large f-45 f-xlg-45 f-lg-35 f-md-25 f-two" data-aos="fade-right">{!! $page->heading1 !!}</h3>
                <div class="generic-header--small -right-liner  f-19 f-md-14 c-light-brown" data-aos="fade-left">
                    <span class="liner">{!! $page->heading2 !!}</span>
                </div>
            </div>
        </div>

        <div>{!! $page->content2 !!}</div> </div>

    </div>
</section>


    <section class="our-office-wrp mb-5">
        <div class="container ps-0 pe-0">
            <div class="grid-container">
                <div class="grid" >

                    <div class="grid-item">
                        <img src="{{asset('assets/demo1/images/conrad-images/about/about-12.webp') }}" class="img-fluid" alt="" data-aos="fade-right">
                        <img src="{{asset('assets/demo1/images/conrad-images/about/about-11-A.jpg') }}" class="img-fluid" alt="" data-aos="fade-left">

                    </div>
                    <div class="grid-item">
                        <img src="{{asset('assets/demo1/images/conrad-images/about/about-10.webp') }}" class="img-fluid" alt="" data-aos="fade-right">
                        <img src="{{asset('assets/demo1/images/conrad-images/about/about-15.webp') }}" class="img-fluid" alt="" data-aos="fade-left">

                    </div>
                    <div class="grid-item">
                        <img src="{{asset('assets/demo1/images/conrad-images/about/72_dpi.webp') }}" class="img-fluid " alt="" data-aos="fade-right">
                        <img src="{{asset('assets/demo1/images/conrad-images/about/about-3.webp') }}" class="img-fluid" alt="" data-aos="fade-left">

                    </div>
                   
                </div>
            </div>
        </div>
    </section>




<!-- Our Team -->
 @if( !empty(setting('team_page')) )
<section class="our-team-wrp" id="our-team">
    <div class="container">
        <div class="generic-header--section" data-aos="fade-right">
            <h3 class="text-left generic-header--large f-40 f-xlg-45 f-lg-35 f-md-25 f-two">OUR TEAM</h3>
        </div>
        @if($team->count())
        @php $i=0; @endphp
        @foreach($team as $team)
        <div class="team-box">
            <div class="d-flex flex-column flex-sm-row ">
                <div class="me-sm-5 mb-3 mb-sm-0">
                    <div class="team-img text-center">
                   
                    @if($team->team_member_photo)
                        <img src="{{ storage_url($team->team_member_photo) }}" alt="{{ strip_tags($team->team_member_name) }}" width="184px" >
                    @else
                        <img class="img-fluid" alt="{{ $team->team_member_name }}" src="{{ themeAsset('images/placeholder/large.png') }}" width="184px" height="225px"/>
                    @endif
                       
                    </div>
                </div>
                <div class="flex-fill">
                    <div class="team-dts d-flex flex-column">
                        <div class="align-items-baseline d-md-flex gap-4 text-center">
                            <a class="text-decoration-none" href="{{isset($team->user->profile_id) ? Route('profile',$team->user->profile_id) : '#'}}"><h4 class="text-center text-sm-start order-sm-0">{{ $team->team_member_name }} / <span class="order-sm-1">{!! $team->team_member_role !!}</span></h4></a>
                            <a class="-default -left-liner button f-14 f-sm-12 f-xs-9 p-4 py-2" href="{{isset($team->user->profile_id) ? Route('profile',$team->user->profile_id) : '#'}}">View Profile</a>
                        </div>
                        
                        <div class="position-relative mt-2">
                            <div class="text-justify order-sm-2 mb-sm-3 f-13 item transition-box" id="memberdiscrip">
                                {!! $team->team_member_description !!}
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="f-13 btn px-0 text-decoration-underline fw-bold c-blue-green-link" id="readmorebtn"> ...Read More</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @php $i++; @endphp
        @endforeach
    @endif     

    </div>
</section>
@endif
<!-- End About -->
<section class="discover-property-wrp">
    <div class="container">
        <div class="dis-dt">
            <h3 data-aos="fade-down">DISCOVER OUR PROPERTIES </h3>
            <div>{!! $page->content3 !!}</div>

            <div class="btn-group">
                <a href="{{ url('property-for-sale') }}" class="button -default -left-liner f-14 f-sm-12 f-xs-9">search properties</a>
                <a href="{{ url('contact-us') }}" class="button -default -left-liner -right-liner f-14 f-sm-12 f-xs-9">contact us</a>
            </div>
        </div>
    </div>
</section>
<!-- End Our Team -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btn = document.getElementById("readmorebtn");
        const box = document.getElementById("memberdiscrip");

        btn.addEventListener("click", function () {
            box.classList.toggle("open");

            if (box.classList.contains("open")) {
                btn.innerText = "Read Less";
            } else {
                btn.innerText = "...Read More";
            }
        });
    });

</script>
@endsection

