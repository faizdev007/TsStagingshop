@if (count($properties))
<section class="featured-properties--section property-grid--style-1">
    <div class="container">
        <div class="position-relative">
            <div class="generic-header--section -cta text-center">
                <h3 class="generic-header--large f-40 f-xlg-45 f-lg-35 f-md-30 f-two" data-aos="fade-right">FEATURED</h3>
                <div class="generic-header--small -right-liner  f-16 f-md-14 c-light-brown" data-aos="fade-left">
                    <span class="liner">NEW DEVELOPMENTS</span>
                </div>
            </div>
            <div class="generic-header--section-cta">
                <a href="{{url('property-for-sale/property-type/new-development')}}" class="button -default -left-liner f-14 f-sm-12">VIEW ALL</a>
            </div>
        </div>

        <div class="u-mw-md-400-c">
            <div class="row featureProperties--slider">
               @foreach ($properties as $property)
                <div class="col-md-6 col-lg-4">
                    <div class="mb-4">
                        @include('frontend.demo1.partials.front.properties.property-grid-style-1')
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</section>
@else
<div class="u-mb1"></div>
@endif