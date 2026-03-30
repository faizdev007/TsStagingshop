@push('body_class')generic-page page-404 @endpush
@extends('frontend.demo1.layouts.frontend')

@section('main_content')
<!-- inner-hero -->
<section class="inner-hero">
    <div class="container">
       @include('frontend.demo1.partials.front.pw.breadcrumb')
        <h1 class="f-two text-uppercase">Page not found! Error 404.</h1>
    </div>
</section>


<section class="page-content u-mt405 u-mb405">
    <div class="container">
        <div class="property-content-wrapper page-inner-container">
            <div class="row">
                <div class="col-12">
                    <div class="generic-text-content">
						<p>Sorry, but the page did not exist.</p>
						<p>Try checking the URL for errors and hit the refresh button on your browser or go back to <a href="{{url('')}}">HOMEPAGE</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
