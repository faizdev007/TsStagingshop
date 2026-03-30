@if (count($properties))
<section class="featured-properties--section property-grid--style-1">
    <div class="container">
        <div class="position-relative">
            <div class="generic-header--section -cta ">
                <h3 class="generic-header--large f-25 f-xlg-45 f-md-20 f-one" data-aos="fade-right">Similar Properties</h3>                
            </div>
            
        </div>
        <div class="u-mw-md-400-c">
            <div class="row">
               @foreach ($properties as $property)
                <div class="col-md-6 col-lg-4">
                    <div class="mb-4">
                        @include('frontend.demo1.partials.front.properties.property-grid-style-2')
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