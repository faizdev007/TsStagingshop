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

<!-- About -->
<section class="abt-wrp-bx u-circle-style-1">
    <div class="u-circle-style-2">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-sm-12">
                    <div class="abt-dt-blc f-align-justify">
                        <h3>{{ $page->header_title }} </h3>
                        {!! $page->content !!}

                        <!-- <a href="javascript:void(0)" id="home-read-more" class="fw-bold text-dark text-decoration-none text-capitalize">Read More</a></p>
                        <div class="quotebx">
                            <h4>At Conrad Villas we care not only about making sale, but sincerely and genuinely care about our client’s comfort, security and peace-of-mind…</h4>
                        </div> -->
                    </div>
                </div>
                <div class="col-md-5 col-sm-12">
                    <div class="abt-img-pg">
                        <img src="{{ $banner }}" alt="">
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</section>
<!-- End About -->
   <!-- End Our Team -->
@endsection

