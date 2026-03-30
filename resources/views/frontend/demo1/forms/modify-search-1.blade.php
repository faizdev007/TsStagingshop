@php
    $uniqueID = !empty($uniqueID) ? '-'.$uniqueID : false;
@endphp
<form
    action="{{ action([\App\Http\Controllers\Frontend\PropertiesController::class, 'search']) }}"
    method="POST"
    class="search-form"
    id="search-form"
>
    @csrf

    {{-- Preserve hidden filters --}}
    @if(post_criteria($criteria, 'category'))
        <input type="hidden" name="category" value="{{ $criteria['category'] }}">
    @endif

    @if(post_criteria($criteria, 'is-brown-harris-stevens'))
        <input type="hidden"
               name="is-brown-harris-stevens"
               value="{{ $criteria['is-brown-harris-stevens'] }}">
    @endif

    <div class="search-form">
        <input type="hidden" name="for" value="BUY">

        <ul class="field-layout">

            {{-- Country --}}
            <div class="search-form-home--item -country">
                <select
                    name="in"
                    id="country_list"
                    class="select-pw-ajax-country select-pw-country country_select"
                >
                    <option value="{{ request('in') }}">
                        {{ request('in') ? urldecode(request('in')) : trans_fb('app.app_Type_Search_Location', 'LOCATION') }}
                    </option>
                </select>
            </div>

            {{-- Area --}}
            <div class="search-form-home--item -country">
                <select
                    name="area"
                    id="location_lists"
                    class="select-pw-ajax-locationss country_selects"
                >
                    <option value="{{ request('area') }}">
                        {{ request('area') ? urldecode(request('area')) : trans_fb('app.app_Type_Search_Location', 'AREA') }}
                    </option>
                </select>
            </div>

            {{-- Complex --}}
            <div class="search-form-home--item -country -area">
                <select
                    name="complex"
                    id="location_list-"
                    class="select-pw country_select select-pw-ajax-complex"
                >
                    @foreach(get_complexx('COMPLEX') as $key => $value)
                        <option
                            value="{{ $key }}"
                            {{ post_criteria($criteria, 'complex') == $key ? 'selected' : '' }}
                        >
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Property Type (multiple) --}}
            <li
                id="search-type-selection"
                class="li-property-type mutliple-selection--attr multiple-container-o multiple-container-o-light"
            >
                <div class="multiple-selected text-uppercase" data-label="PROPERTY TYPE">
                    <label for="property_feature" class="f-14 f-500">0</label>
                </div>

                <select
                    id="li-feature"
                    class="select-pw-mutiple w-100 type-select"
                    name="property_type[]"
                    data-placeholder="TYPE"
                    multiple
                >
                    @php
                        $ptypeArray = prepare_dropdown_ptype_slug($propertyTypes);
                        $ptypeArraySelected = is_array($criteria['property-type-slugs'] ?? null)
                            ? $criteria['property-type-slugs']
                            : [];
                    @endphp

                    @foreach($ptypeArray as $slug => $propertyType)
                        <option
                            value="{{ $slug }}"
                            {{ in_array($slug, $ptypeArraySelected) ? 'selected' : '' }}
                        >
                            {{ $propertyType }}
                        </option>
                    @endforeach
                </select>
            </li>

            {{-- Beds --}}
            <li class="li-beds">
                <select name="beds" class="beds select-pw">
                    @foreach(p_beds_frontend(trans_fb('app.app_Min_Beds','BEDS')) as $key => $value)
                        <option
                            value="{{ $key }}"
                            {{ post_criteria($criteria, 'beds') == $key ? 'selected' : '' }}
                        >
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </li>

            {{-- Baths --}}
            <li class="li-bath">
                <select name="baths" class="baths select-pw">
                    @foreach(p_baths(trans_fb('app.app_Min_Baths','BATHS')) as $key => $value)
                        <option
                            value="{{ $key }}"
                            {{ post_criteria($criteria, 'baths') == $key ? 'selected' : '' }}
                        >
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </li>

            {{-- Price logic --}}
            @php
                $for = $criteria['for'];
                if ($for === 'sale') {
                    $min_price_dropdown = sale_price(false);
                    $max_price_dropdown = sale_price();
                } else {
                    $min_price_dropdown = rent_price();
                    $max_price_dropdown = rent_price();
                }

                $set_range_min = $criteria['min-price'] ?? null;
                $set_range_max = $criteria['max-price'] ?? null;
            @endphp

            {{-- Min price --}}
            <li class="li-min-price">
                <select name="min_price" class="select-pw min_price">
                    <option value="">{{ trans_fb('app.app_Min_Price', 'MIN PRICE') }}</option>
                    @foreach($min_price_dropdown as $k => $v)
                        @if($k != 0)
                            <option
                                value="{{ $k }}"
                                {{ $set_range_min == $k ? 'selected' : '' }}
                            >
                                {{ $v }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </li>

            {{-- Max price --}}
            <li class="li-max-price">
                <select name="max_price" class="select-pw max_price">
                    <option value="">{{ trans_fb('app.app_Max_Price', 'MAX PRICE') }}</option>
                    @foreach($max_price_dropdown as $k => $v)
                        @if($k != 0)
                            <option
                                value="{{ $k }}"
                                {{ $set_range_max == $k ? 'selected' : '' }}
                            >
                                {{ $v }}
                            </option>
                        @endif
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
                    <i class="fas fa-search"></i> AMEND
                </button>
            </li>

        </ul>
    </div>

    {{-- Preserve sort --}}
    <input
        type="hidden"
        name="sort"
        value="{{ post_criteria($criteria, 'sort') }}"
    >
</form>
