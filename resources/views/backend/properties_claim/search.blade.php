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

<form action="{{ route('properties.claim', '') }}"
      method="GET"
      class="title-search">

    <ul class="sf-field">

        {{-- Sale / Rent --}}
        @if(settings('sale_rent') === 'sale_rent')
            <li>
                <select name="for"
                        id="id-mode"
                        class="form-control select-pw price-range-search">
                    @foreach(p_fieldTypesSearch('Type') as $key => $value)
                        <option value="{{ $key }}" {{ request('for') == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </li>
        @elseif(settings('sale_rent') === 'sale')
            <input type="hidden" name="for" value="sale" class="price-range-search">
        @else
            <input type="hidden" name="for" value="rent" class="price-range-search">
        @endif

        {{-- Location --}}
        <li>
            <select name="in"
                    id="id-location"
                    class="form-control select-pw normal-case">
                <option value="">Location</option>
                @foreach(get_locations() as $key => $value)
                    <option value="{{ $key }}" {{ request('in') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </li>

        {{-- Area --}}
        <li>
            <select name="area"
                    id="area"
                    class="form-control select-pw normal-case">
                @foreach(get_areas('Area', request('in')) as $key => $value)
                    <option value="{{ $key }}" {{ request('area') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </li>

        {{-- Complex --}}
        <li>
            <select name="complex"
                    id="id-complex"
                    class="form-control select2_single">
                <option value="">Complex</option>
                @foreach(get_complexx('Complex') as $key => $value)
                    <option value="{{ $key }}" {{ request('complex') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </li>

        {{-- Property Type --}}
        <li>
            <select name="property_type_id"
                    id="id-property"
                    class="form-control select-pw">
                <option value="">Property Type</option>
                <option value="1" {{ request('property_type_id') == 1 ? 'selected' : '' }}>Apartment</option>
                <option value="2" {{ request('property_type_id') == 2 ? 'selected' : '' }}>Penthouse</option>
                <option value="3" {{ request('property_type_id') == 3 ? 'selected' : '' }}>Villa</option>
                <option value="4" {{ request('property_type_id') == 4 ? 'selected' : '' }}>Townhouse</option>
                <option value="5" {{ request('property_type_id') == 5 ? 'selected' : '' }}>Plot-Land</option>
                <option value="6" {{ request('property_type_id') == 6 ? 'selected' : '' }}>Hotel Apartment</option>
                <option value="7" {{ request('property_type_id') == 7 ? 'selected' : '' }}>Building or Bulk Deal</option>
            </select>
        </li>

        {{-- Reference --}}
        <li>
            <input type="text"
                   name="ref"
                   id="id-ref"
                   class="form-control"
                   placeholder="Reference"
                   value="{{ request('ref') }}">
        </li>

        {{-- Featured --}}
        <li>
            <select name="is_featured"
                    id="id-featured"
                    class="form-control select-pw">
                <option value="">Featured</option>
                <option value="1" {{ request('is_featured') == '1' ? 'selected' : '' }}>Yes</option>
                <option value="2" {{ request('is_featured') == '2' ? 'selected' : '' }}>No</option>
            </select>
        </li>

        {{-- Status --}}
        <li>
            <select name="status"
                    id="id-status"
                    class="form-control select-pw">
                @foreach(p_states_search('Status') as $key => $value)
                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </li>

        {{-- Claimed --}}
        <li>
            <select name="claim_request"
                    id="id-claim"
                    class="form-control select-pw">
                <option value="">Claimed Properties</option>
                <option value="1" {{ request('claim_request') === '1' ? 'selected' : '' }}>Only</option>
                <option value="0" {{ request('claim_request') === '0' ? 'selected' : '' }}>Exclude</option>
            </select>
        </li>

    </ul>

    <div class="clearfix"></div>

    {{-- Actions --}}
    <ul class="sf-field sf-action">
        <li>
            <div class="pw-search-btn">
                <div class="psb-col">
                    <button type="submit"
                            name="search"
                            value="yes"
                            class="btn btn-small btn-primary pw-search-btn">
                        Search
                    </button>
                </div>

                <div class="psb-col">
                    <a href="{{ admin_url('properties-claim') }}"
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

