<div class="search-collapse">
    <a href="#" data-toggle="collapse" data-target=".title-search" class="cta-collapse"><i class="fa fa-search"></i> Search </a>
</div>

<style>
    .normal-case {
        text-transform: none !important;
    }
    .normal-case option {
        text-transform: none !important;
    }
</style>

<form
    action="{{ route('properties.index', '') }}"
    method="GET"
    class="title-search">

    <ul class="sf-field">

        @if(settings('sale_rent') == 'sale_rent')
            <li>
                <select
                    name="for"
                    id="id-mode"
                    class="form-control select-pw price-range-search">
                    @foreach(p_fieldTypesSearch('Type') as $key => $value)
                        <option value="{{ $key }}" @if(request('for') == $key) selected @endif>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </li>
        @elseif(settings('sale_rent') == 'sale')
            <input type="hidden" name="for" value="sale" class="price-range-search">
        @else
            <input type="hidden" name="for" value="rent" class="price-range-search">
        @endif

        <!-- Location -->
        <li>
            <select
                name="in"
                id="id-location"
                class="form-control select-pw normal-case">
                <option value="">Location</option>
                @foreach(get_locations() as $key => $value)
                    <option value="{{ $key }}" @if(request('in') == $key) selected @endif>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </li>

        <!-- Area -->
        <li>
            <select
                name="area"
                id="area"
                class="form-control select-pw normal-case">
                @foreach(get_areas('Area', request('in')) as $key => $value)
                    <option value="{{ $key }}" @if(request('area') == $key) selected @endif>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </li>

        <!-- Complex -->
        <li>
            <select
                name="complex"
                id="id-complex"
                class="form-control select2_single">
                <option value="">Complex</option>
                @foreach(get_complexx('Complex','') as $key => $value)
                    <option value="{{ $key }}" @if(request('complex') == $key) selected @endif>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </li>

        <!-- Property Type -->
        <li>
            <select
                name="property_type_id"
                id="id-property"
                class="form-control select-pw">
                <option value="">Property Type</option>
                <option value="1" @if(request('property_type_id') == 1) selected @endif>Apartment</option>
                <option value="2" @if(request('property_type_id') == 2) selected @endif>Penthouse</option>
                <option value="3" @if(request('property_type_id') == 3) selected @endif>Villa</option>
                <option value="4" @if(request('property_type_id') == 4) selected @endif>Townhouse</option>
                <option value="5" @if(request('property_type_id') == 5) selected @endif>Plot-Land</option>
                <option value="6" @if(request('property_type_id') == 6) selected @endif>Hotel Apartment</option>
                <option value="7" @if(request('property_type_id') == 7) selected @endif>Building or Bulk Deal</option>
            </select>
        </li>

        <!-- Min Beds -->
        <li>
            <select
                name="minbeds"
                id="id-minbeds"
                class="form-control select-pw">
                @foreach(p_beds('Min Beds') as $key => $value)
                    <option value="{{ $key }}" @if(request('minbeds') == $key) selected @endif>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </li>

        <!-- Reference -->
        <li>
            <input
                type="text"
                name="ref"
                id="id-ref"
                class="form-control"
                value="{{ request('ref') }}"
                placeholder="Reference">
        </li>

        <!-- Notes -->
        <li>
            <input
                type="text"
                name="agent_notes"
                id="id-agent_notes"
                class="form-control"
                value="{{ request('agent_notes') }}"
                placeholder="Notes">
        </li>

        <!-- Featured -->
        <li>
            <select
                name="is_featured"
                id="id-featured"
                class="form-control select-pw">
                <option value="">Featured</option>
                <option value="1" @if(request('is_featured') == 1) selected @endif>Yes</option>
                <option value="2" @if(request('is_featured') == 2) selected @endif>No</option>
            </select>
        </li>

        <!-- Status -->
        <li>
            <select
                name="status"
                id="id-status"
                class="form-control select-pw">
                @foreach(p_states_search('Status') as $key => $value)
                    <option value="{{ $key }}" @if(request('status') == $key) selected @endif>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </li>

        <!-- New Development -->
        <li>
            <select
                name="is_development"
                id="id-isdevelopment"
                class="form-control select-pw">
                <option value="">New Development</option>
                <option value="y" @if(request('is_development') == 'y') selected @endif>Yes</option>
                <option value="n" @if(request('is_development') == 'n') selected @endif>No</option>
            </select>
        </li>

        <!-- Admin Approval -->
        <li>
            <select
                name="is_admin_approval"
                id="id-isadminapproval"
                class="form-control select-pw">
                <option value="">Pending Properties Listing</option>
                <option value="false" @if(request('is_admin_approval') === 'false') selected @endif>Only</option>
                <option value="true" @if(request('is_admin_approval') === 'true') selected @endif>Exclude</option>
            </select>
        </li>

        <!-- Price Range -->
        <li>
            <?php
                $price_range = request('price_range');
                if (!empty($price_range)) {
                    [$minsel, $maxsel] = explode('-', $price_range);
                } else {
                    $minsel = min_price();
                    $maxsel = max_price();
                }
            ?>
            <div id="price-range-search" class="price-slider-attr">
                <div class="price-range-container">
                    <div class="pr-wrap">
                        <input
                            type="hidden"
                            class="price-range-input"
                            name="price_range"
                            value="{{ $price_range }}">
                        <div
                            class="price-slider"
                            data-minsel="{{ $minsel }}"
                            data-maxsel="{{ $maxsel }}">
                        </div>
                        <div class="price-indicator">
                            <span class="price-label">Price Range:</span>
                            <span class="min-price-slider slider-min formatPrice"></span>
                            -
                            <span class="max-price-slider slider-max formatPrice"></span>
                        </div>
                    </div>
                </div>
            </div>
        </li>

    </ul>

    <div class="clearfix"></div>

    <!-- Actions -->
    <ul class="sf-field sf-action">
        <li>
            <div class="pw-search-btn">
                <div class="psb-col">
                    <button
                        type="submit"
                        name="search"
                        value="yes"
                        class="btn btn-small btn-primary pw-search-btn">
                        Search
                    </button>
                </div>
                <div class="psb-col">
                    <a
                        href="{{ admin_url('properties') }}"
                        class="btn btn-small btn-default pw-search-btn">
                        Clear <span>Search</span>
                    </a>
                </div>
            </div>
        </li>
    </ul>

    <div class="clearfix"></div>
</form>

@push('footerscripts')
<script>
    $(function() {
        $("#id-complex").select2({
            placeholder: "Projects",
            allowClear: true
        });
        
    });
</script>
<style>
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #73879C;
    }
</style>
@endpush

