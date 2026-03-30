<?php  use App\PropertyType; ?>
<?php $currencyGet = !empty(request('currency'))? '?currency='.request('currency') : '' ?>

<form
    action="{{ url('search/' . $currencyGet) }}"
    method="GET"
    id="property-search-form"
    class="property-search-form"
>
    <div class="search-form-home--container">
        <div class="search-form-home--wrap d-flex" data-aos="fade-righ">

            {{-- Country --}}
            <div class="search-form-home--item -country">
                @php
                    $countries = \App\Country::orderBy('id', 'ASC')
                        ->where('status', 1)
                        ->get();
                @endphp

                <select
                    id="country_select"
                    name="country"
                    class="select-pw-country country_select"
                >
                    <option value="">COUNTRY</option>

                    @foreach($countries as $country)
                        <option
                            value="city_{{ $country->id }}"
                            class="select2-option--heading"
                        >
                            {{ $country->name }}
                        </option>

                        @foreach($country->province as $province)
                            <option
                                value="{{ $province->id }}"
                                class="select2-option--province"
                            >
                                {{ $province->name }}
                            </option>
                        @endforeach
                    @endforeach
                </select>

                @php
                    Session::put('country', 0);
                @endphp
            </div>

            {{-- Location --}}
            <div class="search-form-home--item -location">
                @php
                    $cityAreaArray = \App\City::getSearchSelect();
                @endphp

                <select
                    id="search-place"
                    name="place"
                    class="select-pw property-place search-place"
                >
                    @foreach($cityAreaArray as $cityId => $city)
                        <option
                            value="{{ $cityId }}"
                            class="{{ strpos($cityId, 'city') === false
                                ? 'select2-option--area'
                                : 'select2-option--heading' }}"
                        >
                            {{ $city }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Property Type --}}
            <div class="search-form-home--item -type">
                @php
                    $types = PropertyType::orderBy('order', 'ASC')
                        ->where('status', 1)
                        ->get();
                @endphp

                <select
                    id="search-type"
                    name="type"
                    class="select-pw"
                >
                    <option value="">TYPE</option>
                    <option value="">ANY</option>

                    @foreach($types as $type)
                        <option value="{{ $type->id }}">
                            {{ $type->label }}
                        </option>
                    @endforeach
                </select>

                @if(strpos($_SERVER['REQUEST_URI'], 'covid-property-discounts-thailand') !== false)
                    <input type="hidden" name="is_covid_discount" value="1">
                @endif
            </div>

            {{-- Price --}}
            <div class="search-form-home--item -price">
                @php
                    $priceConversion = Config::get('conrad.price_buy_select_pw');
                    $priceSymbol = CustomHelper::getCurrencyInitial();
                @endphp

                <select
                    id="property-price"
                    name="price"
                    class="select select-pw"
                >
                    @foreach($priceConversion as $val => $price)
                        <option value="{{ $val }}">
                            {{ $price }} {{ ($val === '' || $val === ' ') ? '' : $priceSymbol }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Submit --}}
            <div class="search-form-home--item -search-btn">
                <button
                    type="submit"
                    name="button"
                    class="button -secondary u-height-100 u-width-full f-15 text-uppercase"
                >
                    SEARCH
                </button>
            </div>

            <div class="u-clear"></div>
        </div>
    </div>
</form>
