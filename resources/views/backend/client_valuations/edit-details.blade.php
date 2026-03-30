<form method="post" action="{{ admin_url('market-valuation'.'/'. $data->client_valuation_id)}}" data-toggle="validator">

<div class="x_panel pw-inner-tabs">
    <div class="x_title">
        @if($data->client_valuation_status == 'instructed')
            <h2>Instructed on {{ date("jS F Y", strtotime($data->client_valuation_instructed_date )) }}</h2>
        @else
            <h2>Manually Set Instructed Date</h2>
        @endif
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content pw_open">
        <div class="xpw-fields">
            @if($data->client_valuation_status == 'instructed')
                @if(isset($data->property))
                    <div class="row u-mb2">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-primary" href="{{ admin_url('properties/'.$data->property->id.'/edit') }}">View Property Record</a>
                        </div>
                    </div>
                @else
                    <div class="row u-mb2">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-primary" href="{{ admin_url('market-valuation/create-property/'.$data->client_valuation_id) }}">Convert to Property Record</a>
                        </div>
                    </div>
                @endif
            @else
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Instructed Date</label>
                            <input type="text" class="form-control datepicker" name="client_valuation_instruction_date" placeholder="Instruction Date">
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="x_panel pw-inner-tabs">
    <div class="x_title">
        <h2>Client Details</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content pw-open">
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="fullname">Name: {!! required_label() !!}</label>
                    <input type="text" name="client_name" class="form-control" value="{{ $data->client->client_name }}" placeholder="Client Name" required>
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>

            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="slug">Email Address: {!! required_label() !!}</label>
                    <input name="client_email" type="text" class="form-control" value="{{ $data->client->client_email }}" required="required" placeholder="Client Email Address">
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            <input type="hidden" name="client_id" value="{{ $data->client->client_id }}">
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
    <div class="x_content pw-open">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Address Line 1:</label>
                    <input
                        type="text"
                        name="client_valuation_street"
                        id="id-street"
                        class="form-control"
                        placeholder="Please enter..."
                        value="{{ old('client_valuation_street', $data->client_valuation_street) }}"
                        required
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
                        value="{{ old('client_valuation_town', $data->client_valuation_town) }}"
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
                        value="{{ old('client_valuation_city', $data->client_valuation_city) }}"
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
                        value="{{ old('client_valuation_region', $data->client_valuation_region) }}"
                    >
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
                        value="{{ old('client_valuation_postcode', $data->client_valuation_postcode) }}"
                        required
                    >
                </div>
            </div>
        </div>
    </div>
</div>

<div class="x_panel pw pw-inner-tabs">
    <div class="x_title">
        <h2>Map</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
            </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content pw-open">
        <div id="map-view" class="map-display"></div>
        <input type="hidden" name="latitude" class="os-latitude" value="{{!empty($data->client_valuation_latitude) ? $data->client_valuation_latitude : settings('default_latitude')}}">
        <input type="hidden" name="longitude" class="os-longitude" value="{{!empty($data->client_valuation_longitude) ? $data->client_valuation_longitude : settings('default_longitude')}}">
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
                    <label>Valuation Date</label>
                    <input name="client_valuation_date" value="{{ $data->client_valuation_date }}" type="text" class="form-control datepicker" placeholder="Valuation Date">
                </div>
            </div>
            <div class="col-xs-12 col-md-3">
                <div class="form-group">
                    <label>Price: {!! required_label() !!}</label>
                    <input type="number" value="{{ $data->client_valuation_price }}" name="client_valuation_price" class="form-control" placeholder="Please enter.." required="required">
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-sm-12 col-md-3">
                <div class="form-group">
                    <label>Price Advice: {!! required_label() !!}</label>
                    <input type="text" name="client_valuation_price_advice" value="{{ $data->client_valuation_price_advice }}" class="form-control" placeholder="Please enter.." required="required">
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-xs-12 col-md-3">
                <div class="form-group">
                    <label>Show Map: {!! required_label() !!}</label>
                    <select name="client_valuation_map" class="form-control select-pw" data-placeholder="Please select....">
                        <option selected="selected"></option>
                        <option @if($data->client_valuation_map == 'y') selected="selected" @endif value="y">Yes</option>
                        <option @if($data->client_valuation_map == 'n') selected="selected" @endif value="n">No</option>
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
                                {{ old('property_type_id', $data->property_type_id) == $key ? 'selected' : '' }}>
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
                        <option selected="selected"></option>
                        <?php for ($i = 1; $i <= 20; $i++) { ?>
                        <option @if($data->client_valuation_beds == $i) selected="selected" @endif value="{{ $i }}">{{ $i }}</option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="control-form">
                    <label>Number of bathrooms: {!! required_label() !!}</label>
                    <select name="client_valuation_baths" class="form-control select-pw" data-placeholder="Please select....">
                        <option selected="selected"></option>
                        <?php for ($i = 1; $i <= 20; $i++) { ?>
                        <option @if($data->client_valuation_baths == $i) selected="selected" @endif value="{{ $i }}">{{ $i }}</option>
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
                    >{{ old('client_valuation_property_description', $data->client_valuation_property_description) }}</textarea>
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
                    >{{ old('client_valuation_location_info', $data->client_valuation_location_info) }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

        @csrf
        {{ method_field('PATCH') }}

    <div class="form-group sticky-buttons">
        <button type="submit" class="btn btn-large btn-primary" >Save</button>
        <a href="{{admin_url('pages')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
    </div>

</form>

@push('headerscripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.css" />
@endpush

@push('footerscripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.js"></script>
    <script src="{{url('assets/admin/build/js/map-geocoding.js')}}"></script>
@endpush