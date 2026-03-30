@php $form_id = !empty($uniqueID) ? $uniqueID : "property-alert-form"; @endphp
<form
    action="{{ url('alert/ajax/add') }}"
    method="POST"
    data-toggle="validator"
    id="ajax-form-{{ $form_id }}"
>
    @csrf

    <div id="response-{{ $form_id }}"></div>

    <input type="hidden" name="recaptcha_token" id="recaptcha_token">
    <input type="hidden" name="url_from" value="{{ url()->current() }}">
    <input type="hidden" name="members_area" value="y">

    <div class="property-alert-form">
        <div class="alert-content">
            <div class="property-alert-inner">
                <div class="alert-fields select-style-1">
                    <div class="row u-row-spacing">

                        {{-- Full name --}}
                        <div class="col-md-6 col-sm-6 @if(Auth::user()) d-none @endif">
                            <div class="form-group u-mb05">
                                @if(Auth::user())
                                    <input type="hidden" name="fullname" value="{{ Auth::user()->name }}">
                                @else
                                    <input type="text" name="fullname" placeholder="Full Name *">
                                @endif
                                <span class="glyphicon form-control-feedback"></span>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6 col-sm-6 @if(Auth::user()) d-none @endif">
                            <div class="form-group u-mb05">
                                @if(Auth::user())
                                    <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                                @else
                                    <input type="email" name="email" placeholder="Email Address *">
                                @endif
                                <span class="glyphicon form-control-feedback"></span>
                            </div>
                        </div>

                        {{-- Telephone --}}
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group u-mb05">
                                @if(Auth::user())
                                    <input type="hidden" name="contact" value="{{ Auth::user()->telephone }}">
                                @else
                                    <input type="tel" name="contact" placeholder="Telephone">
                                @endif
                                <span class="glyphicon form-control-feedback"></span>
                            </div>
                        </div>

                        {{-- Location --}}
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group u-mb05">
                                <select
                                    name="in"
                                    id="location_list-alert-create"
                                    class="form-control select-pw-ajax-locations"
                                >
                                    @foreach(get_locations() as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Sale / Rent --}}
                        @if(settings('sale_rent') == 'sale_rent')
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group u-mb05">
                                    <select
                                        name="is_rental"
                                        id="id-status-alert-create"
                                        class="sale select-pw sample-dynamic-id-1"
                                    >
                                        @foreach(p_fieldTypes_no_default() as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @elseif(settings('sale_rent') == 'sale')
                            <input type="hidden" name="is_rental" value="0" class="sample-dynamic-id-1">
                        @else
                            <input type="hidden" name="is_rental" value="1" class="sample-dynamic-id-1">
                        @endif

                        {{-- Property types (multiple) --}}
                        <div class="col-md-4 col-sm-6">
                            <div
                                id="search-type-selection-create"
                                class="form-group u-mb05 mutliple-selection--attr multiple-container-o multiple-container-o-dark"
                            >
                                <select
                                    id="li-feature"
                                    class="select-pw-mutiple w-100 type-select"
                                    name="property_type_id[]"
                                    data-placeholder="Property Type"
                                    multiple
                                >
                                    @foreach(prepare_dropdown_ptype($propertyTypes) as $slug => $propertyType)
                                        <option value="{{ $slug }}">{{ $propertyType }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Beds --}}
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group u-mb05">
                                <select name="beds" class="beds select-pw">
                                    @foreach(p_beds_frontend(trans_fb('app.app_Min_Beds','BEDS')) as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Price --}}
                        @php
                            $prices = [
                                '0-5000000' => 'UPTO 5,000,000',
                                '5000000-20000000' => '5,000,000 - 20,000,000',
                                '50000000-100000000' => '50,000,000 - 100,000,000',
                                '100000000-9999999999' => '100,000,000 +',
                            ];
                        @endphp

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group u-mb05">
                                <select name="price_range" class="select-pw min_price">
                                    <option value="">{{ trans_fb('app.app_Price', 'Price') }}</option>
                                    @foreach($prices as $price_val => $price_txt)
                                        <option value="{{ $price_val }}">{{ $price_txt }}</option>
                                    @endforeach
                                    <option value="">ANY</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    {{-- Submit --}}
                    <div class="property-alert-submit u-mt2">

                        <div
                            id="gr-{{ $form_id }}"
                            class="g-recaptcha-pw"
                            data-sitekeypw="{{ settings('recaptcha_public_key') }}"
                            data-sizepw="invisible"
                            data-callbackpw="propertyAlertSubmit"
                            data-counterpw=""
                        ></div>

                        <div class="text-center">
                            @if(Auth::user() && settings('members_area') == 1)
                                <button
                                    type="submit"
                                    class="button -primary f-16 f-bold text-uppercase u-rounded-10 u-pr2 u-pl2"
                                    id="btn-{{ $form_id }}"
                                >
                                    Create Alert
                                </button>
                            @else
                                <button
                                    type="submit"
                                    class="button -primary f-bold f-16 text-uppercase u-rounded-10 u-pr2 u-pl2"
                                    id="btn-{{ $form_id }}"
                                >
                                    Sign Up Now
                                </button>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</form>

@push('frontend_css')
    <style>
        #ajax-form-property-alert-form-v2 .select2-container {
            background: #fff;
            width: 100% !important;
            border-radius: 4px;
            border: 1px solid #aaa;
            height: 38px;
        }

        #ajax-form-property-alert-form-v2 .select2-container .select2-selection--single .select2-selection__rendered {
            margin-top: 5px;
        }

        #ajax-form-property-alert-form-v2 .select2-container--default .select2-search--inline .select2-search__field::placeholder {
            font-weight: var(--bs-body-font-weight);
            text-transform: uppercase;
            color: #252525;
        }

        #property-alert-form {
            background: #e7e7e7 !important;
        }

        #ajax-form-property-alert-form-v2 .select2-container--default.select2-container--focus .select2-selection--multiple, 
        #ajax-form-property-alert-form-v2 .select2-container--default .select2-selection--multiple {
            border: unset !important;
        }
    </style>
@endpush

