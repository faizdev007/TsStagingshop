<?php
$curr = get_current_currency();
$curr = (!empty($curr)) ? $curr : 'AED';

$prices = [];
$prices["0-5000000"] = "UPTO 5,000,000";
$prices["5000000-20000000"] = "5,000,000 - 20,000,000";
$prices["20000000-50000000"] = "20,000,000 - 50,000,000";
$prices["50000000-100000000"] = "50,000,000 - 100,000,000";

$prices["100000000-9999999999"] = "100,000,000 +";

?>
 <form
    action="{{ action([\App\Http\Controllers\Frontend\PropertiesController::class, 'search']) }}"
    method="POST"
    class="form form-1 property-search-form"
    id="property-search-form"
>
    @csrf

    <div class="search-form-home--container" style="position:static !important;">
        <div class="search-form-home--wrap d-flex" data-aos="fade-righ">

            {{-- Location --}}
            <div class="search-form-home--item -country">
                <select
                    name="in"
                    id="location_list-"
                    class="form-control select-pw country_select locaion_in"
                >
                    @foreach(get_locations($criteria['for'] ?? 'sale') as $key => $value)
                        <option
                            value="{{ $key }}"
                            {{ (post_criteria($criteria, 'in') ?: 'Dubai, United Arab Emirates') == $key ? 'selected' : '' }}
                        >
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Area --}}
            <div class="search-form-home--item -country -area">
                <select
                    name="area"
                    id="area_list-"
                    class="select-pw area_select select-pw-ajax-area"
                    data-parea="{{ $criteria['for'] ?? 'sale' }}"
                >
                    @foreach(
                        get_areas(
                            'AREA',
                            post_criteria($criteria, 'in') ?: 'Dubai, United Arab Emirates',
                            $criteria['for'] ?? 'sale'
                        ) as $key => $value
                    )
                        <option
                            value="{{ $key }}"
                            {{ post_criteria($criteria, 'area') == $key ? 'selected' : '' }}
                        >
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Complex --}}
            <div class="search-form-home--item -country -area -complex">
                <select
                    name="complex"
                    id="complex_list-"
                    class="select-pw country_select select-pw-ajax-complex"
                >
                    @if(count(get_complexx(
                            'PROJECT',
                            post_criteria($criteria, 'area') ?: NULL,
                            $criteria['for'] ?? 'sale'
                        )) <= 1)
                        <option value="">Please select the area first</option>
                    @endif
                    @foreach(
                        get_complexx(
                            'PROJECT',
                            post_criteria($criteria, 'area') ?: NULL,
                            $criteria['for'] ?? 'sale'
                        ) as $key => $value
                    )
                        <option
                            value="{{ $key }}"
                            {{ post_criteria($criteria, 'complex') == $key ? 'selected' : '' }}
                        >
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Property Type (Filtered) --}}
            <div class="search-form-home--item -type">
                <select name="property_type" class="property select-pw">
                    @foreach(
                        array_filter(
                            prepare_dropdown_ptype_slug(
                                $propertyTypes,
                                trans_fb('app.app_PropertyType','TYPE')
                            ),
                            fn ($type) => ! in_array($type, [
                                'Plot-Land',
                                'Hotel apartment',
                                'Building or Bulk Deal'
                            ])
                        ) as $key => $value
                    )
                        <option
                            value="{{ $key }}"
                            {{ post_criteria($criteria, 'property_type') == $key ? 'selected' : '' }}
                        >
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Beds --}}
            <div class="search-form-home--item -bed">
                <select name="minbeds" class="beds select-pw">
                    @foreach(p_beds_frontend(trans_fb('app.app_Min_Beds','BEDS')) as $key => $value)
                        <option
                            value="{{ $key }}"
                            {{ post_criteria($criteria, 'minbeds') == $key ? 'selected' : '' }}
                        >
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Price --}}
            <div class="search-form-home--item -price single-input arrow-r">
                <select
                    name="price_range"
                    id="price_range"
                    class="select filters-single filters-price search_form_mobile select-pw"
                >
                    <option value="">{{ trans_fb('app.app_Price', 'PRICE') }}</option>

                    @foreach ($prices as $price_val => $price_txt)
                        <option
                            value="{{ $price_val }}"
                            {{ post_criteria($criteria, 'price_range') == $price_val ? 'selected' : '' }}
                        >
                            {{ $price_txt }}
                        </option>
                    @endforeach

                    <option value="">ANY</option>
                </select>
            </div>

            {{-- Submit --}}
            <div class="search-form-home--item -search-btn">
                <input type="hidden" name="for" value="{{ $criteria['for'] ?? 'sale' }}">

                <button
                    type="submit"
                    class="button -secondary u-height-100 u-width-full f-15 text-uppercase"
                >
                    SEARCH
                </button>
            </div>

            <div class="u-clear"></div>
        </div>
    </div>
</form>