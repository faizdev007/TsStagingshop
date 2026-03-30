@if(!empty($slides))

<section class="home-slider--section position-relative">

    <div class="home-slider--slick">
        @foreach($slides as $key=>$slide)
        @php
            $innerBanner = $slide;
            $linksstr = OptimizeImgLinks($slide['photo']);
        @endphp
        <div class="slide">
            <div class="position-relative">
                <div class="home-slider--image-wrap">
                    <div class="home-slider--image fill go-center">
                        <a href="">
                            <img class="lozad" src="{{asset('storage/slides/1728737541.webp')}}" data-lazy="{{ !empty($slide['photo']) ? storage_url($slide['photo']) : 'storage/slides/1728737541.webp' }}" alt="{{$slide['text_line1']}}" style="max-width: 100%; height: 500px;"
                                loading="{{$key == 0 ? 'eager' : 'lazy'}}" fetchpriority="{{$key == 0 ? 'high' : 'low'}}"
                                srcset="{{$linksstr}}"
                                sizes="(max-width: 768px) 100vw, 570px"
                                decoding="async"
                            />
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="home-slider--form-wrap">
        <div class="container position-relative">
            <div class="home-slider--title f-two f-lg-50 f-md-40 f-sm-30 c-white f-medium f-line-height-1 mb-4 f-50  " data-aos="fade-down" onclick="window.location.href=''">
                <div class="u-mw-711 text-center text-sm-start f-four">
                    An unrivaled collection of<br class="d-none d-lg-block">
                    Luxury Properties in Dubai
                </div>
            </div>
            <div class="home-page--form">
                <div class="row">
                    <div class="col-md-12">
                         @include('frontend.demo1.forms.home-search')
                    </div>
                    <div class="col-md-12">
                        <div class="home-slider--content">
                            <div class="home-slider--controllers d-flex flex-column mb-4">
                                <div class="home-slider--subtitle fix-line c-white f-semibold f-14 f-sm-12 text-uppercase order-2">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
@endif
