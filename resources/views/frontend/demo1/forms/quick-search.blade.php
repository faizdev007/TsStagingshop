<div class="-title f-28 f-two c-white text-center">Quick Search</div>
<form
    action="{{ action([\App\Http\Controllers\Frontend\PropertiesController::class, 'search']) }}"
    method="POST"
    class="quick-search-form select-style-1 form-1"
    id="search-form"
>
    @csrf

    <div class="-fields select-style-1">

        {{-- Buy / Rent --}}
        <div class="form-group form-group-1 -transparent-bg u-mb1">
            <select name="for" id="id-status" class="sale select-pw">
                <option value="sale">BUY</option>
                <option value="rent">RENT</option>
            </select>
        </div>

        {{-- Location --}}
        <div class="form-group form-group-1 -transparent-bg u-mb1">
            <select
                name="in"
                id="location_list"
                class="select-pw-ajax-locations"
            >
                <option value="{{ request('in') }}">
                    {{ request('in') ? urldecode(request('in')) : trans_fb('app.app_Type_Search_Location', 'LOCATION') }}
                </option>
            </select>
        </div>

        {{-- Property Type --}}
        <div class="form-group form-group-1 -transparent-bg u-mb1">
            <select name="property_type" class="property select-pw">
                @foreach(prepare_dropdown_ptype_slug($propertyTypes, 'PROPERTY TYPE') as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        {{-- Min Price --}}
        <div class="form-group form-group-1 -transparent-bg u-mb2">
            @php
                $min_price_dropdown = sale_price(false);
            @endphp

            <select name="min_price" class="select-pw min_price">
                <option value="">{{ trans_fb('app.app_Min_Price', 'MIN PRICE') }}</option>

                @foreach($min_price_dropdown as $k => $v)
                    @if($k != 0)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        {{-- Submit --}}
        <div class="form-group form-group-1 text-center">
            <button type="submit" class="cta -secondary -wider-3 f-14">
                <i class="fas fa-search"></i> SEARCH
            </button>
        </div>

    </div>
</form>