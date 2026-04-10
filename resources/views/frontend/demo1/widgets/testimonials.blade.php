
<style>
    .stars-readonly { font-size: 1.5rem; color: #e9ecef; display:inline-flex; gap:.25rem; align-items:center; }
    .stars-readonly .star.on { color: gold; }
</style>
@if ($testimonials->count())
<section class="testimonial--section u-mb-110 u-mb-md-60">
    <div class="container">
        <div class="testimonial--wrap position-relative">
            <div class="row h-100">
                <div class="col-md-12 col-lg-6 d-flex align-items-center">
                    <div class="generic-header--section mb-0">
                        <a href="/testimonials">
                            <h2 class="generic-header--large f-45 f-lg-35 f-md-25 f-two c-white" data-aos="fade-left">TESTIMONIALS</h2>
                        </a>
                        <div class="generic-header--small -left-liner f-16 f-md-14 c-white" data-aos="fade-right">
                            <span class="liner">WHAT OUR CLIENTS SAY</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 d-flex align-items-center ">
                    <div class="testimonials--slick u-width-full">
                        @foreach ($testimonials as $testimonial)
                        @php
                            $rating = $testimonial->rating ?? 0.0; // numeric rating between 0–5
                            $fullStars = floor($rating); // full stars
                            $partial = ($rating - $fullStars); // half star if 0.1+
                            $emptyStars = 5 - ($fullStars + ($partial > 0 ? 1 : 0)); // remaining empty stars
                        @endphp
                        <div class="testimonials--item ">
                            <div class="c-white f-19 f-lg-19 f-md-13 f-sm-12 f-regular f-line-height-102 mb-4">
                                {!! substr(strip_tags($testimonial->quote),0,185) !!} <?php if (strlen($testimonial->quote)>185) echo '... <a href="/testimonials" class="f-13 c-white-link-underline"><b>Read client testimonials about our services</b></a>'?>”
                            </div>
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
                                </div>
                            </div>
                            <div class="c-white f-20 f-lg-18 f-md-15 f-semibold">
                            - {{ $testimonial->name }}, {{ $testimonial->location }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="testimonial--controls position-absolute text-center c-white">
                <a href="#" id="prev-testimonial" class="f-17 f-md-13 c-gray-3-link--white me-4">
                    <img src="{{ themeAsset('images/conrad-images/slide-arrow-left-gray.png') }}" class="me-2" alt="Arrow Prev" width="7px" height="13px"> <span>PREV</span>
                </a>

                

                <a href="#" id="next-testimonial" data-slick-trigger="autoplay" class="f-17 f-md-13 c-gray-3-link--white ms-4">
                   <span>NEXT</span> <img src="{{ themeAsset('images/conrad-images/slide-arrow-right-gray.png') }}" class="ms-2" alt="Arrow Next" width="7px" height="13px">
                </a>
            </div>
        </div>
    </div>
</section>
@endif