@extends('backend.layouts.master')

@section('admin-content')

    <form method="post" action="{{ url('admin/market-valuation') }}" data-toggle="validator">

        <div class="x_panel pw-inner-tabs">
            <div class="x_title">
                <h2>Client Details</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content pw_open">
                <div class="xpw-fields">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label for="fullname">Name: {!! required_label() !!}</label>
                                <input type="text" name="client_name" class="form-control" value="{{ old('client_name') }}" placeholder="Client Name" required>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label for="slug">Email Address: {!! required_label() !!}</label>
                                <input name="client_email" type="text" class="form-control" value="{{ old('client_email') }}" required="required" placeholder="Client Email Address">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="x_panel pw-inner-tabs">
            <div class="x_title">
                <h2>Location</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content pw_open">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Address Line 1: {!! required_label() !!}</label>
                            <input
                                type="text"
                                name="client_valuation_street"
                                id="id-street"
                                class="form-control"
                                placeholder="Please enter..."
                                required
                                value="{{ old('client_valuation_street') }}"
                            >
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Town / Village:</label>
                            <input
                                type="text"
                                name="client_valuation_town"
                                id="id-town"
                                class="form-control"
                                placeholder="Please enter..."
                                value="{{ old('client_valuation_town') }}"
                            >
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>City: {!! required_label() !!}</label>
                            <input
                                type="text"
                                name="client_valuation_city"
                                id="id-city"
                                class="form-control"
                                placeholder="Please enter..."
                                value="{{ old('client_valuation_city') }}"
                                required
                            >
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            @if( settings('overseas') == 1)
                                <label>Complex Name:</label>
                            @else
                                <label>Region:</label>
                            @endif
                            <input
                                type="text"
                                name="client_valuation_region"
                                id="id-region"
                                class="form-control"
                                placeholder="Please enter..."
                                value="{{ old('client_valuation_region') }}"
                            >
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Postal Code: {!! required_label() !!}</label>
                            <input
                                type="text"
                                name="client_valuation_postcode"
                                id="id-postcode"
                                class="form-control"
                                placeholder="Please enter..."
                                value="{{ old('client_valuation_postcode') }}"
                                required
                            >
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="control-form">
                            <label><br/></label><br/>
                            <button id="search_geocoding" class="btn btn-large btn-primary" type="button">Search location</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="x_panel pw pw-inner-tabs">
            <div class="x_title">
                <h2>Map</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content pw-open">
                <div id="map-view" class="map-display"></div>

                <input type="hidden" name="latitude" class="os-latitude" value="{{ settings('default_latitude')}}">
                <input type="hidden" name="longitude" class="os-longitude" value="{{ settings('default_longitude')}}">
            </div>
        </div>
        <div class="x_panel pw-inner-tabs">
            <div class="x_title">
                <h2>Valuation Details</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content pw-open">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Valuation Date {!! required_label() !!}</label>
                            <input name="client_valuation_date" value="{{ old('client_valuation_date') }}" type="text" class="form-control datepicker" placeholder="Valuation Date" required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <div class="form-group">
                            <label>Price: {!! required_label() !!}</label>
                            <input type="number" name="client_valuation_price" value="{{ old('client_valuation_price') }}" class="form-control" placeholder="Please enter.." required="required">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <div class="form-group">
                            <label>Price Advice: {!! required_label() !!}</label>
                            <input type="text" name="client_valuation_price_advice" value="{{ old('client_valuation_price_advice') }}" class="form-control" placeholder="Please enter.." required="required">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <div class="form-group">
                            <label>Show Map: {!! required_label() !!}</label>
                            <select name="client_valuation_map" class="form-control select-pw" data-placeholder="Please select....">
                                <option selected="selected">{{ old('client_valuation_map') }}</option>
                                <option value="y">Yes</option>
                                <option value="n">No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="x_panel pw-inner-tabs">
            <div class="x_title">
                <h2>Property Details</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content pw-open">
                <div class="row">
                    <div class="col-xs-12 col-md-4">
                        <div class="control-form">
                            <label>Property Type: {!! required_label() !!}</label>
                            <select
                                name="property_type_id"
                                class="form-control select-pw"
                            >
                                <option value="">Select</option>

                                @foreach (prepare_dropdown_ptype($property_types) as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ old('property_type_id') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="control-form">
                            <label>Number of bedrooms: {!! required_label() !!}</label>
                            <select name="client_valuation_beds" class="form-control select-pw" data-placeholder="Please select....">
                                <option selected="selected">{{ old('client_valuation_beds') }}</option>
                                <?php for ($i = 1; $i <= 20; $i++) { ?>
                                <option value="{{ $i }}">{{ $i }}</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="control-form">
                            <label>Number of bathrooms: {!! required_label() !!}</label>
                            <select name="client_valuation_baths" class="form-control select-pw" data-placeholder="Please select....">
                                <option selected="selected">{{ old('client_valuation_baths') }}</option>
                                <?php for ($i = 1; $i <= 20; $i++) { ?>
                                <option value="{{ $i }}">{{ $i }}</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Property Description {!! required_label() !!}</label>
                            <textarea
                                name="client_valuation_property_description"
                                id="content"
                                class="mceEditor description"
                                style="width:100%"
                                placeholder="Please enter..."
                                maxlength="60000"
                            >{{ old('client_valuation_property_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="x_panel pw-inner-tabs">
            <div class="x_title">
                <h2>Location Information</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content pw-open">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Location Information {!! required_label() !!}</label>
                            <textarea
                                name="client_valuation_location_info"
                                id="content"
                                class="mceEditor description"
                                style="width:100%"
                                placeholder="Please enter..."
                                maxlength="60000"
                            >{{ old('client_valuation_location_info') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @csrf
        <div class="form-group sticky-buttons">
            <button type="submit" class="btn btn-large btn-primary" >Save</button>
            <a href="{{admin_url('pages')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
        </div>

    </form>
@endsection

@push('headerscripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.css" />
@endpush

@push('footerscripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.js"></script>
    <script src="{{url('assets/admin/build/js/map-geocoding.js')}}"></script>
@endpush