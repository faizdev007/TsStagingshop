<div class="search-form">

    <form
    action="{{ action([\App\Http\Controllers\Frontend\PropertiesController::class, 'search']) }}"
    method="POST"
    class="form form-1 property-search-form"
    id="property-search-form"
>
    @csrf

    <ul class="field-layout">

        <input type="hidden" name="for" value="BUY">

        {{-- Location --}}
        <li class="li-location">
            <select
                name="in"
                id="location_list"
                class="select-pw-ajax-locations"
            >
                <option value="{{ request('in') }}">
                    {{ request('in') ? urldecode(request('in')) : trans_fb('app.app_Type_Search_Location', 'LOCATION') }}
                </option>
            </select>
        </li>

        {{-- Property Type (multiple) --}}
        <li
            id="home-type-selection"
            class="li-property-type mutliple-selection--attr multiple-container-o multiple-container-o-dark"
        >
            <div class="multiple-selected text-uppercase" data-label="PROPERTY TYPE">
                <label for="property_feature" class="c-white f-15 f-500">0</label>
            </div>

            <select
                id="li-feature"
                class="select-pw-mutiple w-100 type-select z-10"
                name="property_type[]"
                data-placeholder="PROPERTY TYPE"
                multiple
            >
                @php
                    $propertyTypesArray = prepare_dropdown_ptype_slug($propertyTypes, 'PROPERTY TYPE');
                @endphp

                @foreach($propertyTypesArray as $key => $ptype)
                    <option value="{{ $key }}">{{ $ptype }}</option>
                @endforeach
            </select>
        </li>

        {{-- Prices --}}
        @php
            $min_price_dropdown = sale_price(false);
            $max_price_dropdown = sale_price();
        @endphp

        <li class="li-min-price">
            <select name="min_price" class="select-pw min_price">
                <option value="">{{ trans_fb('app.app_Min_Price', 'MIN PRICE') }}</option>
                @foreach($min_price_dropdown as $k => $v)
                    <option value="{{ $k }}">{{ $v }}</option>
                @endforeach
            </select>
        </li>

        <li class="li-max-price">
            <select name="max_price" class="select-pw max_price">
                <option value="">{{ trans_fb('app.app_Max_Price', 'MAX PRICE') }}</option>
                @foreach($max_price_dropdown as $k => $v)
                    @if($k != 0)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endif
                @endforeach
            </select>
        </li>

        {{-- Beds --}}
        <li class="li-beds">
            <select name="beds" class="beds select-pw">
                @foreach(p_beds_frontend(trans_fb('app.app_Min_Beds','BEDS')) as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </li>

        {{-- Filter icon --}}
        <li class="li-filter">
            <a href="#">
                <img src="{{ themeAsset('images/svg/filtering.svg') }}" alt="Filter">
            </a>
        </li>

        {{-- Submit --}}
        <li class="li-button">
            <button type="submit" class="f-600 f-14">
                <i class="fas fa-search"></i> SEARCH
            </button>
        </li>

    </ul>
</form>
</div>
