@php
// Ensure $template is not empty
$template = !empty($template) ? $template : 1;
$in = post_criteria($criteria, 'in');

$curr = get_current_currency();
$curr = (!empty($curr)) ? $curr : 'AED';

$prices = [];
$prices["0-5000000"] = "UPTO 5,000,000";
$prices["5000000-20000000"] = "5,000,000 - 20,000,000";
$prices["20000000-50000000"] = "20,000,000 - 50,000,000";
$prices["50000000-100000000"] = "50,000,000 - 100,000,000";

$prices["100000000-9999999999"] = "100,000,000 +";

$filtered = $sort_list_data;

if(request('country')){
    $filtered = $filtered->where('country', request('country'));
}

if(request('street')){
    $filtered = $filtered->where('street', request('street'));
}

if(request('pname')){
    $filtered = $filtered->where('pname', request('pname'));
}

if(request('beds')){
    $filtered = $filtered->where('beds', request('beds'));
}

@endphp

<style>
    /* Chrome, Edge, Safari */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Firefox */
    .hide-scrollbar {
        scrollbar-width: none;
    }

    /* Internet Explorer & old Edge */
    .hide-scrollbar {
        -ms-overflow-style: none;
    }

    .tagstyle{
        border: 1px solid #d9b483;
        color: #252525;
        background: #fff;
    }

    .tagstyle:hover{
        background: #d9b483;
        color:#fff;
    }

    .tagstyleActive{
        background: #d9b483;
        color: #fff;
    }

    .dropdown-item.active, .dropdown-item:active{
        background-color: #d9b483;
        color: #fff!important;
    }
</style>

@push('body_class') search-page @endpush

@if(0)
@if(settings('members_area') == 1 && Auth::user() )
<div class="save-search-cta">
    <a @if($saved_search) id="save-search-{{ $saved_search->saved_search_id }}" @endif class="button f-13 -shortlist search-save @if($saved_search) search-remove @endif u-block-mobile" @if($saved_search) data-item-id="{{ $saved_search->saved_search_id }}" @endif href="#">
        @if($saved_search)
        <span class="c-white"><i class="fas fa-times"></i></span> Remove Saved Search
        @else
        <span class="c-white"><i class="fas fa-heart"></i></span> &nbsp; Save this search
        @endif
    </a>
</div>
@endif
@endif

<!-- inner-hero -->
<section class="inner-hero">
    <div class="container position-relative">
        @include('frontend.demo1.partials.front.pw.breadcrumb')
        <h1 class="f-two f-30 text-center text-capitalize">
            @if(@$search_content->content_title)
            {{ @$search_content->content_title }}
            @else
            @if(request()->segment(1)=='property-for-development')
            New Developments For Sale
            @else
            {{ str_replace('-', ' ', request()->segment(1) )}}
            @endif
            @endif
            @if($in)
            in {{$in}}
            @endif
        </h1>

        @include('frontend.demo1.forms.home-search')
    </div>
</section>

<script>
    // function submitshortdata(value) {
    //     // Get the button's ID correctly
    //     const buttonId = value.getAttribute('short_input_id');

    //     // Find the target input using the correct ID
    //     const targetInput = document.getElementById('short-' + buttonId);

    //     // Set the value correctly (JS uses .value NOT .val)
    //     targetInput.value = value.getAttribute('search-data');

    //     document.getElementById('property-short-form').submit();
    // }

    function submitshortdata(button) {
        const input = button.dataset.shortInput;
        const value = button.dataset.search;

        document.getElementById(`short-${input}`).value = value;
        document.getElementById('property-short-form').submit();
    }
</script>


<section class="map">
    <div id="mapToggle">
        <div id="map-search" class="search--map"></div>
    </div>
</section>

<!-- Blog Listing -->
<section class="blog-wrapper product-wrp u-circle-style-1">
    <div class="u-circle-style-2">
        <div class="container">
            @if($in=='Greece' || $in=='Pakistan' || $in=='India')
            <div class="u-generic-text-content f-14 mb-5">
                We are currently working on selecting the best properties for you to choose from in collaboration with our international partners, please contact us for more information on <strong><a href="mailto:hello@terezaestates.com">hello@terezaestates.com</a></strong> or call us at <strong><a href="callto">+971 585 365 111</a></strong>
            </div>
            @endif
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

                                    @php $all_currencies = all_currencies(); @endphp

                                    <div class="search-form-home--item -country">
                                        <a class="nav-link dropdown-toggle border px-2" href="#" id="navbarDropdownCurrency" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-current="{{ !empty($all_currencies[get_current_currency()])? get_current_currency() :'Currency' }}">
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
                
                <section for="dynamic_shorting" class="position-relative">
                    <div class="mb-3 position-relative hover-arrow">
                        @if(count($sort_list_data) > 0)
                            <button id="arrow-left" class="arrowleft rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                                </svg>
                            </button>
                        @endif
                        <form
                            action="{{ action([\App\Http\Controllers\Frontend\PropertiesController::class, 'search']) }}"
                            method="POST"
                            class="form form-1 property-short-form text-nowrap overflow-auto hide-scrollbar"
                            id="property-short-form"
                        >
                            @csrf

                            <!-- Hidden search state -->
                            <input type="hidden" id="short-in" name="in" value="{{ post_criteria($criteria, 'in') }}">
                            <input type="hidden" id="short-area" name="area" value="{{ post_criteria($criteria, 'area') }}">
                            <input type="hidden" id="short-complex" name="complex" value="{{ post_criteria($criteria, 'complex') }}">
                            <input type="hidden" id="short-property_type" name="property_type" value="{{ post_criteria($criteria, 'property_type') }}">
                            <input type="hidden" id="short-minbeds" name="minbeds" value="{{ post_criteria($criteria, 'minbeds') }}">
                            <input type="hidden" id="short-price_range" name="price_range" value="{{ post_criteria($criteria, 'price_range') }}">
                            <input type="hidden" name="for" value="{{ $criteria['for'] ?? 'sale' }}">

                            @if(count($sort_list_data) > 0)
                            <div id="swiptoright" class="infinite-text d-md-none text-capitalize text-end text-secondary">
                                Swipe to filter
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                                </svg>
                            </div>
                            @endif
                            <div class="py-2 px-0 d-flex flex-nowrap overflow-x-auto gap-2 hide-scrollbar" id="profileTabs">

                                {{-- AREA --}}
                                @if(!post_criteria($criteria, 'area'))
                                    @php
                                        $streetCounts = $filtered->groupBy('town')->map->count();
                                    @endphp

                                    @foreach(
                                        get_areas(
                                            'AREA',
                                            post_criteria($criteria, 'in') ?: 'Dubai, United Arab Emirates',
                                            $criteria['for']
                                        ) as $key => $single
                                    )
                                        @if($key !== '' && $streetCounts->get($single, 0) > 0)
                                            <button
                                                type="button"
                                                class="button btn py-1 px-2 btn-sm tagstyle text-nowrap"
                                                onclick="submitshortdata(this)"
                                                data-short-input="area"
                                                data-search="{{ $key }}"
                                            >
                                                {{ $single }}
                                            </button>
                                        @endif
                                    @endforeach
                                @endif

                                {{-- COMPLEX --}}
                                @if(post_criteria($criteria, 'area') && !post_criteria($criteria, 'complex'))
                                    @php
                                        $complexName = $filtered->groupBy('complex_name')->map->count();
                                    @endphp

                                    @foreach(
                                        get_complexx(
                                            'PROJECT',
                                            post_criteria($criteria, 'area') ?: 'Downtown & Burj Khalifa District',
                                            $criteria['for']
                                        ) as $key => $single
                                    )
                                        @if($key !== '' && $complexName->get($single, 0) > 0)
                                            <button
                                                type="button"
                                                class="button btn py-1 px-2 btn-sm tagstyle text-nowrap"
                                                onclick="submitshortdata(this)"
                                                data-short-input="complex"
                                                data-search="{{ $key }}"
                                            >
                                                {{ $single }}
                                            </button>
                                        @endif
                                    @endforeach
                                @endif

                                {{-- PROPERTY TYPE --}}
                                @if(post_criteria($criteria, 'complex') && !post_criteria($criteria, 'property_type'))
                                    @php
                                        $property_type_list = array_filter(
                                            prepare_dropdown_ptype_slug($propertyTypes, trans_fb('app.app_PropertyType','TYPE')),
                                            fn ($type) => !in_array($type, [
                                                'Plot-Land',
                                                'Hotel apartment',
                                                'Building or Bulk Deal'
                                            ])
                                        );
                                        $pname = $filtered->groupBy('pname')->map->count();
                                    @endphp

                                    @foreach($property_type_list as $key => $single)
                                        @if($key !== '' && $pname->get($single, 0) > 0)
                                            <button
                                                type="button"
                                                class="button btn py-1 px-2 btn-sm tagstyle text-nowrap"
                                                onclick="submitshortdata(this)"
                                                data-short-input="property_type"
                                                data-search="{{ $key }}"
                                            >
                                                {{ $single }}
                                            </button>
                                        @endif
                                    @endforeach
                                @endif

                                {{-- BEDS --}}
                                @if(post_criteria($criteria, 'property_type') && !post_criteria($criteria, 'minbeds'))
                                    @php
                                        $beds = $filtered->groupBy('beds')->map->count();
                                    @endphp

                                    @foreach(p_beds_frontend(trans_fb('app.app_Min_Beds','BEDS')) as $key => $single)
                                        @if($key !== '' && $beds->get($key, 0) > 0)
                                            <button
                                                type="button"
                                                class="button btn py-1 px-2 btn-sm tagstyle text-nowrap"
                                                onclick="submitshortdata(this)"
                                                data-short-input="minbeds"
                                                data-search="{{ $key }}"
                                            >
                                                {{ $single }}
                                            </button>
                                        @endif
                                    @endforeach
                                @endif

                                {{-- PRICE --}}
                                @if(post_criteria($criteria, 'minbeds') && !post_criteria($criteria, 'price_range'))
                                    @foreach($prices as $key => $single)
                                        @if($key !== '')
                                            @php
                                                [$minp, $maxp] = explode('-', $key);
                                                $pricecount = $filtered
                                                    ->where('price', '>=', $minp)
                                                    ->where('price', '<=', $maxp)
                                                    ->count();
                                            @endphp

                                            @if($pricecount > 0)
                                                <button
                                                    type="button"
                                                    class="button btn py-1 px-2 btn-sm tagstyle text-nowrap"
                                                    onclick="submitshortdata(this)"
                                                    data-short-input="price_range"
                                                    data-search="{{ $key }}"
                                                >
                                                    {{ $single }}
                                                </button>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif

                            </div>
                        </form>
                        @if(count($sort_list_data) > 0)
                            <button id="arrow-right" class="arrowright rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                </section>

                <div class="property-grid--style-1">
                    <div style="justify-content: center;">
                        @include('frontend.demo1.forms.aggregation-search')

                    </div>

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

                            {{-- Use total() method correctly for pagination --}}
                            @if($properties instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="pager-bx">
                                {{ $properties->onEachSide(1)->links('vendor.pagination.style-1') }}
                            </div>
                            @endif
                            @else
                            <p class="text-center">No Results Found</p>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- <div class="pager-bx">
                   {{ $properties->onEachSide(1)->links('vendor.pagination.style-1') }}
                </div> -->


            </div>
        </div>
    </div>
</section>

@include('frontend.shared.components.map-loader')
<script>
@if(count($properties) > 0)
    var locations = [
        @foreach($properties as $res)
            @if(!empty($res->latitude) && !empty($res->longitude))
                [
                    `<h4><a href="{{ $res->url }}" target="_blank">{{ $res->search_headline }}</a></h4>`,
                    {{ (float) $res->latitude }},
                    {{ (float) $res->longitude }}
                ],
            @endif
        @endforeach
    ];
@endif
</script>

@push('frontend_scripts')
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