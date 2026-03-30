@push('body_class') @endpush
@push('body_class')testimonials @endpush
@extends('frontend.demo1.layouts.frontend')

@section('main_content')

<style>
    .stars-readonly { font-size: 1.5rem; color: #e9ecef; display:inline-flex; gap:.25rem; align-items:center; }
    .stars-readonly .star.on { color: gold; }
</style>
@php
    use Carbon\Carbon;
@endphp
 <!-- inner-hero -->
<section class="inner-hero">
    <div class="container">
       @include('frontend.demo1.partials.front.pw.breadcrumb')
        <h2 class="f-four text-uppercase pb-5">What Our Customers Think</h2>
    </div>
</section>
 <!-- =================== Testimonial Area =================== -->
 <section class="blog-wrapper u-circle-style-1 u-circle-style-2">
     

<div class="about-block text-center p-1">   
    <div class="centering blog-details-wrp">
    <h1>Testimonials</h1>
        @foreach ($testimonials AS $tm_c)
            @php
                $rating = $tm_c->rating ?? 0.0; // numeric rating between 0–5
                $fullStars = floor($rating); // full stars
                $partial = ($rating - $fullStars); // half star if 0.1+
                $emptyStars = 5 - ($fullStars + ($partial > 0 ? 1 : 0)); // remaining empty stars
            @endphp

            <div class="border container mx-auto p-4 rounded shadow-sm text-start w-100">
                <p class="fs-6 fw-normal lh-base">{!! strip_tags($tm_c->quote,'<br><strong><b><a>') !!}</p>
                <div class="mb-2">
                    <div class="stars-readonly" aria-hidden="true">
                        {{-- Full stars --}}
                        @for($i = 0; $i < $fullStars; $i++)
                            <span class="star on">★</span>
                        @endfor

                        {{-- Half star --}}
                        @if($partial)
                            <span class="star half" 
                                style="background: linear-gradient(90deg, gold {{ $partial * 100 }}%, #ddd {{ $partial * 100 }}%);
                                        -webkit-background-clip: text;
                                        -webkit-text-fill-color: transparent;">
                                ★
                            </span>
                        @endif

                        {{-- Empty stars --}}
                        @for($i = 0; $i < $emptyStars; $i++)
                            <span class="star off">★</span>
                        @endfor

                        <small class="fs-6 ms-2 text-black">
                            {{ \Carbon\Carbon::parse($tm_c->date)->diffForHumans() }}
                        </small>
                    </div>
                </div>
                <span class="text-start mt-4 fw-semibold">- {{ $tm_c->name }} </span>
            </div>
          <div class="tm_shadow"></div>
        @endforeach
         <div class="pagination text-center">
             {{$testimonials->links('vendor.pagination.default')}}
        </div><!-- /.pagination -->
    </div>  
</div>  
 </section>
    @endsection