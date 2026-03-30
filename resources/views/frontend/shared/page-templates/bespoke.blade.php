<?php
    $banner = !empty($page->photo) ? asset('storage/'.$page->photo) : false;
    $banner_style = !empty($banner) ? 'style="background-image:url('.$banner.')"' : false;
?>

@push('body_class')bespoke-page @endpush

@extends('frontend.demo1.layouts.frontend')

@section('main_content')
    <section class="hero-2-container u-pt0 u-pb0">
        <div class="hero-slider-style-1">
            <div class="slide">
                <div class="slide-image {{ !empty($page->photo) ? 'fill' : '' }}"><span></span>
                    <img data-lazy="{{ !empty($page->photo) ? storage_url($page->photo) : default_thumbnail() }}" alt="{{$page->title}}">
                </div>
                <div class="slide-content">
                    <div class="s-content-inner">
                        <h1 class="f-60 f-bold text-uppercase c-white f-two">{{$page->title}}</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="search-form">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <span class="c-white f-20 u-block u-mb05 f-two">Start Your Search</span>
                        </div>
                    </div>
                </div>
                <div class="search-fields-generic">
                    <div class="search-heading"></div>
                        @include('frontend.demo1.forms.search-2', ['uniqueID'=>'1'])
                    <div class="responsive-search" data-toggle="modal" data-target="#search-modal-style-1">
                        <i class="fas fa-search"></i> advanced search
                    </div>
                    <!-- The Modal -->
                        <div class="modal fade search-modal-style-1" id="search-modal-style-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <div class="modal-body">
                                        @include('frontend.demo1.forms.search-2', ['uniqueID'=>'2'])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>


    @if($page->sections->count() > 0)
        <section class="c-bg-secondary u-pt1 u-pb1">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <ul class="quick-link__list">
                            <div class="text-center">
                                @foreach($page->sections as $page_section)
                                    <li class="quick-link__item">
                                        <a class="c-white f-semibold f-14 jump-link" href="#{{ $page_section->url }}">{{ $page_section->title }}</a>
                                    </li>
                                @endforeach
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="c-bg-primary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center u-mt2 u-mb2">
                        <h2 class="c-white f-24 f-bold u-mb1">{{ $page->header_title ?? $page->title }}</h2>
                        <div class="u-block u-mt1">
                            <p class="c-white f-14">{!! $page->content !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($page->sections->count() > 0)
        @foreach($page->sections as $page_section)
            @if($page_section->type == 'news')
                <section id="news">
                    @widget('latestNews')
                </section>
            @else
                @include('frontend.demo1.parts.bespoke.'.$page_section->type)
            @endif
        @endforeach
    @endif

@endsection