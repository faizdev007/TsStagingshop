@extends('frontend.demo1.layouts.frontend')

@push('body_class') search-page @endpush

<!-- inner-hero -->
<section class="inner-hero property-hero">
    <div class="container position-relative">      
        @include('frontend.demo1.partials.front.pw.breadcrumb')
        <h1 class="f-two f-30 text-center text-capitalize">
            Property Search
        </h1>
        @include('frontend.demo1.forms.home-search')
    </div>
</section>

<section class="map">
    <div id="mapToggle">
        <div id="map-search" class="search--map"></div>
    </div>
</section>

<!-- Blog Listing -->
<section class="blog-wrapper product-wrp u-circle-style-1">
    <div class="u-circle-style-2">
        <div class="container">
            <!-- Include Grid Filters -->
            @include('frontend.demo1.partials.grid-filters')
            @include('frontend.demo1.partials.styles.grid-filters')

            <div class="products-filter-wrp">
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-12 col-sm-12">
                        <div class="prod-sort-bx">
                            <ul>
                                <li class="-sort">
                                    @if (!empty($properties->total()))
                                    <div class="search-form-home--item -sort"> 
                                        @include('frontend.demo1.forms.sort-by')
                                    </div>
                                    @endif
                                </li>
                                <li class="-currency">
                                    @php $all_currencies = all_currencies();  @endphp
                                    <div class="search-form-home--item -country"> 
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCurrency" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-current="{{ !empty($all_currencies[get_current_currency()])? get_current_currency() :'Currency' }}">
                                            {{ !empty($all_currencies[get_current_currency()])? get_current_currency() :'Currency' }}
                                            <span class="pw-nav-hover"><span class="pw-nav-hover-inner"></span></span>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdownCurrency">
                                            @foreach($all_currencies as $currency => $symbol)
                                                @if( get_current_currency() !=  $currency)
                                                    <a href="{{lang_url('properties/set/currency/'.$currency)}}" rel="nofollow" class="dropdown-item hover-tick">
                                                        {{$currency}}
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="custome-switch">
                                        <h6>Show Map</h6>
                                        <div class="checkbox">
                                            <div class="search-map--toggler inner">
                                                <div class="toggle"></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="property-grid--style-1">
                    <div class="u-mw-md-400-c">
                        <div class="row">
                            @if(count($properties)>0)
                                @foreach ($properties as $property)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="mb-4">
                                            @include('frontend.demo1.partials.front.properties.property-grid-style-1')
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-center">No Results Found</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="pager-bx">
                    {{ $properties->onEachSide(1)->links('vendor.pagination.style-1') }}
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.shared.components.map-loader')

@push('frontend_scripts')
<script>
    // Define your locations: HTML content for the info window, latitude, longitude
    @if (count($properties) > 0)
        var locations = [
            @foreach($properties as $res)
                ['<h4><a href="{{$res->url}}" target="" >{{$res->search_headline}}</a></h4>', {{$res->latitude}}, {{$res->longitude}}],
            @endforeach
        ];
    @endif
</script>
{{ $properties->links('vendor.pagination.header-link') }}
@endpush

@push('frontend_css')
<style>
    .search-page .search-form-home--item.-sort .select2-selection__rendered, 
    .search-page .search-form-home--item.-country .select2-selection__rendered, 
    .search-page .-currency a {
        font-size: calc(var(--bs-body-font-size) - 2px);
    }

    .search-page .-currency {
        height: 34px;
    }

    .search-page .-currency .search-form-home--item, 
    .search-page .-currency .search-form-home--item a {
        height: 100%;
    }

    .search-page .product-wrp .products-filter-wrp .prod-sort-bx ul li .custome-switch {
        padding: 1px 0;
    }
    
    .search-page .product-wrp {
        padding-top: 40px;
    }
</style>
@endpush
