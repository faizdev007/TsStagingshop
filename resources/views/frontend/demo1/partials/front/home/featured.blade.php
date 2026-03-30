<?php $featured = \App\Property::featured(); ?>
@if(!empty($featured))
<section class="featured-properties--section property-grid--style-1">
    <div class="container">
        <div class="position-relative">
            <div class="generic-header--section -cta text-center">
                <h3 class="generic-header--large f-53 f-xlg-45 f-lg-35 f-md-30 f-two" data-aos="fade-right">FEATURED</h3>
                <div class="generic-header--small -right-liner  f-23 f-md-14 c-light-brown" data-aos="fade-left">
                    <span class="liner">PROPERTIES</span>
                </div>
            </div>
            <div class="generic-header--section-cta">
                <a href="{{url('search')}}" class="button -default -left-liner f-14 f-sm-12">VIEW ALL</a>
            </div>
        </div>

        <div class="u-mw-md-400-c">
            <div class="row">
                @foreach($featured->with('propertyRibbon')->with('area')->with('province')->with('country')->with('city')->with('gallery')->get() as $f)
                <div class="col-md-6 col-lg-4">
                    <div class="mb-4">
                        @include('partials.front.properties.property-grid-style-1')
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</section>
@endif
