<?php  use App\PropertyType;$banner = \App\Carousel::getHomeBanner($exchange_rate);

?>

@if(isset($page_text))

    <br>

    <p class="page-text">
        {{ $page_text }}
        @if(isset($page_detail))<span id="read-more">..<span class=" read-more"  onclick="showDetail()">Read More</span></span> @endif
    </p>

    @if(isset($page_detail))

        <div id="page-detail" style="display: none">

            {!! $page_detail !!}

            <p class="page-text"> <span class="pull-left read-more read-less" onclick="hideDetail()">Read Less</span> </p>

        </div>

        <br>

    @endif

@endif

<style>

    select {



        /* styling */

        background-color: white;

        border: thin solid blue;

        /*border-radius: 4px;*/

        display: inline-block;

        font: inherit;

        line-height: 1.5em;

        padding: 0.5em 3.5em 0.5em 1em;



        /* reset */



        margin: 0;

        -webkit-box-sizing: border-box;

        -moz-box-sizing: border-box;

        box-sizing: border-box;

        -webkit-appearance: none;

        -moz-appearance: none;



        text-decoration: none;

        display: block;

        font-size: 16px;

        color: #000;

        text-transform: uppercase

    }

    select.minimal {

        background-image:

            linear-gradient(45deg, transparent 50%, #58cbca 50%),

            linear-gradient(135deg, #58cbca 50%, transparent 50%),

            linear-gradient(to right, #ccc, #ccc);

        background-position:

            calc(100% - 20px) calc(1em + 2px),

            calc(100% - 15px) calc(1em + 2px),

            calc(100% - 2.5em) 0.5em;

        background-size:

            5px 5px,

            5px 5px,

            1px 1.5em;

        background-repeat: no-repeat;

    }

    .slider-block .heapBox:first-child {

        width: 14%;

    }

    .slider-block .heapBox {

        height: 58px;

        float: left;

        border-right: 1px solid #d6d6d6;

        width: 15%;

        position: relative;

    }

    #heapbox_search-type{

        width: 11%;

    }

    #heapbox_property-price{

        width: 11%;

    }

    #heapbox_search-buy-rent{

        width: 17%;

    }

    .slider-block .slick-dots {

        z-index: 1;

    }

    .slider-block #heapbox_country_select.heapBox .heapOption a {

        padding: 10px 15px 10px 25px;

    }

    .slider-block #heapbox_country_select.heapBox .heapOption a.city, .slider-block #heapbox_search-place.heapBox .heapOption:first-child a {

        padding-left: 15px;

    }

</style>

<div id="filter-form" class="search schfrm" >

    {{--*/$data = Session::all();/*--}}



    <form
    action="{{ url('search') }}"
    method="GET"
    id="property-search-form"
>
    <fieldset>
        <div class="inner">

            {{-- COUNTRY --}}
            <select id="country_select" name="country" onchange="countryChange(this)">
                @php
                    $countries = \App\Country::whereHas('props_list')
                        ->orderBy('id', 'ASC')
                        ->where('status', '1')
                        ->get();
                @endphp

                <option value="">COUNTRY</option>

                @foreach($countries as $country)
                    <option
                        value="city_{{ $country->id }}"
                        class="city"
                        {{ Session::get('country') === "city_{$country->id}" ? 'selected' : '' }}
                    >
                        {{ $country->name }}
                    </option>

                    @foreach($country->province as $province)
                        <option
                            value="{{ $province->id }}"
                            {{ Session::get('country') == $province->id ? 'selected' : '' }}
                        >
                            {{ $province->name }}
                        </option>
                    @endforeach
                @endforeach
            </select>

            {{-- PLACE --}}
            @php
                $data['place'] = Session::get('place');
            @endphp

            <select name="place" id="search-place" class="property-place">
                @foreach(\App\City::getSearchSelect() as $key => $label)
                    <option
                        value="{{ $key }}"
                        {{ isset($data['place']) && $data['place'] == $key ? 'selected' : '' }}
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            {{-- BUY / RENT --}}
            <select
                name="action"
                id="search-buy-rent"
                class="search_buy_rent"
            >
                @foreach(config('conrad.buy_sell_select') as $key => $label)
                    <option
                        value="{{ $key }}"
                        {{ isset($data['action']) ? ($data['action'] == $key ? 'selected' : '') : ($key === 'buy' ? 'selected' : '') }}
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            {{-- PROPERTY TYPE --}}
            @php
                $first_types = \App\PropertyType::orderBy('order', 'ASC')
                    ->where('status', '1')
                    ->limit(2)
                    ->get();

                $second_types = \App\PropertyType::orderBy('order', 'ASC')
                    ->where('status', '1')
                    ->offset(2)
                    ->limit(20)
                    ->get();
            @endphp

            <select class="select" name="type" id="search-type">
                <option value="">TYPE</option>
                <option value="">ANY</option>

                @foreach($first_types as $type)
                    <option
                        value="{{ $type->id }}"
                        {{ isset($data['type']) && $data['type'] == $type->id ? 'selected' : '' }}
                    >
                        {{ $type->label }}
                    </option>
                @endforeach

                <option
                    value="laxury"
                    {{ ($data['type'] ?? '') === 'laxury' ? 'selected' : '' }}
                >
                    LUXURY VILLA
                </option>

                @foreach($second_types as $type)
                    <option
                        value="{{ $type->id }}"
                        {{ isset($data['type']) && $data['type'] == $type->id ? 'selected' : '' }}
                    >
                        {{ $type->label }}
                    </option>
                @endforeach

                <option
                    value="covid_deals"
                    {{ ($data['type'] ?? '') === 'covid_deals' ? 'selected' : '' }}
                >
                    Covid Deals
                </option>
            </select>

            {{-- COVID FLAG --}}
            @if(strpos($_SERVER['REQUEST_URI'], 'covid-property-discounts-thailand') !== false)
                <input type="hidden" name="is_covid_discount" value="1">
            @endif

            {{-- BEDROOMS --}}
            <select name="bedroom" class="select">
                @foreach(config('conrad.bedrooms_select') as $key => $label)
                    <option
                        value="{{ $key }}"
                        {{ isset($data['bedroom']) && $data['bedroom'] == $key ? 'selected' : '' }}
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            {{-- PRICE --}}
            @php
                $priceSel =
                    isset($data['action']) && $data['action'] === 'rent'
                        ? config('conrad.price_rent_select')
                        : config('conrad.price_buy_select');
            @endphp

            <select name="price" id="property-price" class="select">
                @foreach($priceSel as $key => $label)
                    <option
                        value="{{ $key }}"
                        {{ isset($data['price']) && $data['price'] == $key ? 'selected' : '' }}
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>

        </div>

        <button type="submit">SEARCH</button>
    </fieldset>
</form>

</div>

<div id="laxury-message" class="laxury-message">

    @if( (isset($data['type'])) && $data['type'] == 'laxury')

        <p>

            @if($data['action'] && ($data['action'] == 'rent') )

                We offer some of the most stunning luxury villas for rent in Koh Samui with infinity pools, BBQ, gyms and jacuzzi. Whether you are planning for a quiet holiday or long term rental , our specialized real estate team can help you pick your luxury villa for rent.

            @else

                {{--We offer some of the most amazing luxury villas for sale in Koh Samui. whether you want panoramic sea-views, an infinity swimming pool, or beachfront, all our properties have been hand-picked and our specialized real estate team will find exactly what you’re looking for.--}}

            @endif



        </p>



    @endif

    @if(isset($search_text))

        <p> {{$search_text}} </p>

    @endif

</div>

