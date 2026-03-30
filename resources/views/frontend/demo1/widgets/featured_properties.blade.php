@if (count($properties))
<section class="property-grid--style-1">
    <div class="p-3">
        <div class="container">
            <div class="position-relative d-md-flex d-lg-block justify-content-between">
                <div class="generic-header--section -cta text-center">
                    <h3 class="generic-header--large f-40 f-xlg-45 f-lg-35 f-md-30 f-two" data-aos="fade-right">FEATURED</h3>
                    <div class="generic-header--small -right-liner  f-16 f-md-14 c-light-brown" data-aos="fade-left">
                        <span class="liner">PROPERTIES</span>
                    </div>
                </div>
                <div class="generic-header--section-cta d-none d-md-block">
                    <a href="{{url('property-for-sale')}}" class="button -default -left-liner f-14 f-sm-12">VIEW ALL</a>
                </div>
            </div>
        </div>
        <style>
            .home_price {
                display: none;
            }
        </style>
        <div class="home_price">
            <li class="-currency">
                @php $all_currencies = all_currencies(); @endphp

                <div class="search-form-home--item -country">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCurrency" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-current="{{ !empty($all_currencies[get_current_currency()])? get_current_currency() :'Currency' }}">
                        <!-- GBP (£)  -->
                        {{ !empty($all_currencies[get_current_currency()])? get_current_currency() :'Currency' }}
                        <span class="pw-nav-hover"><span class="pw-nav-hover-inner"></span></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownCurrency">
                        @foreach($all_currencies as $currency => $symbol)
                        @if( get_current_currency() != $currency)
                        <a href="{{lang_url('properties/set/currency/'.$currency)}}" rel="nofollow" class="dropdown-item hover-tick">
                            {{$currency}}

                        </a>
                        @endif
                        @endforeach
                    </div>
                </div>
            </li>
        </div>


        <!-- Home PagecPrice code will come with CSS  -->

        <div class="container">
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
        
        <div class="generic-header--section-cta d-md-none mt-4">
            <a href="{{url('property-for-sale')}}" class="button -default -left-liner f-14 f-sm-12">VIEW ALL</a>
        </div>
    </div>
</section>
@else
<div class="u-mb1"></div>
@endif