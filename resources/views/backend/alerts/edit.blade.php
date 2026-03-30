@extends('backend.alerts.template')

@section('alerts-content')

<form
    action="{{ action([\App\Http\Controllers\Backend\PropertyAlertController::class, 'update'], $PropertyAlert->id) }}"
    method="POST"
>
    @csrf
    @method('PUT')

    {{-- ================= INFO PANEL ================= --}}
    <div class="x_panel pw-inner-tabs">
        <div class="x_title">
            <h2>Info</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content pw-open">
            <div class="xpw-fields">
                <div class="row">

                    <div class="col-md-3">
                        <div class="control-form">
                            <label>Fullname: {!! required_label() !!}</label>
                            <input
                                type="text"
                                name="fullname"
                                id="id-fullname"
                                class="form-control"
                                value="{{ $PropertyAlert->fullname }}"
                                placeholder="Please enter..."
                            >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="control-form">
                            <label>Email Address: {!! required_label() !!}</label>
                            <input
                                type="text"
                                name="email"
                                id="id-email"
                                class="form-control"
                                value="{{ $PropertyAlert->email }}"
                                placeholder="Please enter..."
                            >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="control-form">
                            <label>Telephone:</label>
                            <input
                                type="text"
                                name="contact"
                                id="id-contact"
                                class="form-control"
                                value="{{ $PropertyAlert->contact }}"
                                placeholder="Please enter..."
                            >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="control-form">
                            <label>Active: {!! required_label() !!}</label>
                            <select
                                name="is_active"
                                id="id-is_active"
                                class="form-control select-pw"
                            >
                                <option value="0" {{ $PropertyAlert->is_active == 0 ? 'selected' : '' }}>No</option>
                                <option value="1" {{ $PropertyAlert->is_active == 1 ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ================= ALERTS PANEL ================= --}}
    <div class="x_panel pw-inner-tabs">
        <div class="x_title">
            <h2>Alerts</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content pw-open">
            <div class="xpw-fields">
                <div class="row">

                    {{-- SALE / RENT --}}
                    @if(settings('sale_rent') === 'sale_rent')
                        <div class="col-md-3">
                            <div class="control-form">
                                <label>For:</label>
                                <select
                                    name="is_rental"
                                    id="id-mode"
                                    class="form-control select-pw price-range-search"
                                >
                                    @foreach(p_fieldTypes_no_default() as $key => $label)
                                        <option
                                            value="{{ $key }}"
                                            {{ $PropertyAlert->is_rental == $key ? 'selected' : '' }}
                                        >
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @elseif(settings('sale_rent') === 'sale')
                        <input type="hidden" name="is_rental" value="0" class="price-range-search">
                    @else
                        <input type="hidden" name="is_rental" value="1" class="price-range-search">
                    @endif

                    {{-- LOCATION --}}
                    <div class="col-md-3">
                        <div class="control-form">
                            <label>Location:</label>
                            <select
                                name="in"
                                id="id-location"
                                class="form-control select-pw-ajax-locations"
                            >
                                <option value="{{ $PropertyAlert->in }}">
                                    {{ $PropertyAlert->in ? urldecode($PropertyAlert->in) : 'Location' }}
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- PROPERTY TYPE --}}
                    <div class="col-md-3">
                        <div class="control-form">
                            <label>Property Type:</label>
                            <select
                                name="property_type_ids[]"
                                class="select-pw w-100 type-select"
                                multiple
                            >
                                @php
                                    $selected = !empty($PropertyAlert->property_type_ids)
                                        ? explode(',', $PropertyAlert->property_type_ids)
                                        : [];
                                @endphp

                                @foreach(prepare_dropdown_ptype($propertyTypes) as $id => $label)
                                    <option
                                        value="{{ $id }}"
                                        {{ in_array($id, $selected) ? 'selected' : '' }}
                                    >
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- BEDS --}}
                    <div class="col-md-3">
                        <div class="control-form">
                            <label>Beds:</label>
                            <select name="beds" class="form-control select-pw">
                                @foreach(p_beds() as $key => $label)
                                    <option
                                        value="{{ $key }}"
                                        {{ $PropertyAlert->beds == $key ? 'selected' : '' }}
                                    >
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- PRICE RANGE --}}
                    <div class="col-md-3">
                        <div class="control-form">
                            <label>Price Range:</label>

                            @php
                                if ($PropertyAlert->price_from && $PropertyAlert->price_to) {
                                    $minsel = $PropertyAlert->price_from;
                                    $maxsel = $PropertyAlert->price_to;
                                    $price_range = "{$minsel}-{$maxsel}";
                                } else {
                                    $minsel = min_price();
                                    $maxsel = max_price();
                                    $price_range = '';
                                }
                            @endphp

                            <div id="price-range-search" class="price-slider-attr">
                                <div class="price-range-container">
                                    <div class="pr-wrap">
                                        <input
                                            type="hidden"
                                            name="price_range"
                                            class="price-range-input"
                                            value="{{ $price_range }}"
                                        >
                                        <div
                                            class="price-slider"
                                            data-minsel="{{ $minsel }}"
                                            data-maxsel="{{ $maxsel }}"
                                        ></div>
                                        <div class="price-indicator">
                                            <span class="price-label">Price Range:</span>
                                            <span class="min-price-slider slider-min formatPrice"></span>
                                            -
                                            <span class="max-price-slider slider-max formatPrice"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ================= ACTION BUTTONS ================= --}}
    <div class="form-group sticky-buttons">
        <button type="submit" class="btn btn-large btn-primary">Save</button>

        <a href="{{ admin_url('property-alerts') }}" class="btn btn-default btn-spacing">
            Cancel <span>and Return</span>
        </a>

        <a
            href="#"
            class="btn btn-danger btn-spacing modal-toggle"
            data-item-id="{{ $PropertyAlert->id }}"
            data-toggle="modal"
            data-modal-type="delete"
            data-modal-title="Delete"
            data-modal-size="small"
            data-delete-type="property-alerts"
            data-target="#global-modal"
        >
            <i class="fas fa-trash"></i> Delete
        </a>
    </div>

</form>
@endsection

@push('headerscripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('footerscripts')
<script src="{{asset('assets/admin/build/vendors/jquery/jquery.ui.touch-punch.min.js')}}"></script>
<script src="{{asset('assets/admin/build/vendors/jquery/jquery.formatCurrency-1.4.0.min.js')}}"></script>
<script src="{{asset('assets/admin/build/js/price-range.js')}}"></script>
<script src="{{asset('assets/admin/build/js/pw-select2-ajax.js')}}"></script>
@endpush
