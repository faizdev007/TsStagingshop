@extends('backend.properties.template')

@section('property-content')

<form action="{{ route('properties.location.update', $property->id) }}" method="POST">
    @csrf

    @if(settings('overseas') == 1)

        @if(settings('overseas_country') == '')
            <div class="alert alert-danger">
                Please provide a country on the settings.
            </div>
        @endif

        <input
            id="id-country"
            type="hidden"
            name="country"
            value="{{ !empty(settings('overseas_country')) ? settings('overseas_country') : 'United Kingdom' }}">

        <div class="x_panel pw-inner-tabs">
            <div class="x_title">
                <h2>Location (Overseas)</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="xpw-fields">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="control-form">
                                <label>Address Line 1:</label>
                                <input type="text"
                                       name="street"
                                       id="id-street"
                                       class="form-control"
                                       value="{{ $property->street }}"
                                       placeholder="Please enter...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="control-form">
                                <label>Complex Name:</label>
                                <input type="text"
                                       name="complex_name"
                                       id="id-region"
                                       class="form-control"
                                       value="{{ $property->complex_name }}"
                                       placeholder="Please enter...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="control-form">
                                <label>Area</label>
                                <input type="text"
                                       name="town"
                                       id="id-town"
                                       class="form-control"
                                       value="{{ $property->town }}"
                                       placeholder="Please enter...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="control-form">
                                <label>City: {!! required_label() !!}</label>
                                <input type="text"
                                       name="city"
                                       id="id-city"
                                       class="form-control"
                                       value="{{ $property->city }}"
                                       placeholder="Please enter..."
                                       required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="control-form">
                                <label>Country: {!! required_label() !!}</label>
                                <input type="text"
                                       name="country"
                                       id="id-country"
                                       class="form-control"
                                       value="{{ $property->country }}"
                                       placeholder="Please enter..."
                                       required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="control-form">
                                <label>Postal Code:</label>
                                <input type="text"
                                       name="postcode"
                                       id="id-postcode"
                                       class="form-control"
                                       value="{{ $property->postcode }}"
                                       placeholder="Please enter...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="control-form">
                                <label><br></label><br>
                                <button id="search_geocoding"
                                        type="button"
                                        class="btn btn-large btn-primary">
                                    Search location
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    @else

        <input id="id-country" type="hidden" name="country" value="United Kingdom">

        <div class="x_panel pw-inner-tabs">
            <div class="x_title">
                <h2>Location (UK)</h2>
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
                                <label>Address Line 1:</label>
                                <input type="text"
                                       name="street"
                                       id="id-street"
                                       class="form-control"
                                       value="{{ $property->street }}"
                                       placeholder="Please enter...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="control-form">
                                <label>Town/Village:</label>
                                <input type="text"
                                       name="town"
                                       id="id-town"
                                       class="form-control"
                                       value="{{ $property->town }}"
                                       placeholder="Please enter...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="control-form">
                                <label>City: {!! required_label() !!}</label>
                                <input type="text"
                                       name="city"
                                       id="id-city"
                                       class="form-control"
                                       value="{{ $property->city }}"
                                       placeholder="Please enter..."
                                       required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="control-form">
                                <label>Region:</label>
                                <input type="text"
                                       name="region"
                                       id="id-region"
                                       class="form-control"
                                       value="{{ $property->region }}"
                                       placeholder="Please enter...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="control-form">
                                <label>Postal Code:</label>
                                <input type="text"
                                       name="postcode"
                                       id="id-postcode"
                                       class="form-control"
                                       value="{{ $property->postcode }}"
                                       placeholder="Please enter...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="control-form">
                                <label><br></label><br>
                                <button id="search_geocoding"
                                        type="button"
                                        class="btn btn-large btn-primary">
                                    Search location
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    @endif

    <div class="x_panel pw-inner-tabs">
        <div class="x_title">
            <h2>Map</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div id="map-view" class="map-display"></div>

            <input type="hidden"
                   name="latitude"
                   class="os-latitude"
                   value="{{ !empty($property->latitude) ? $property->latitude : settings('default_latitude') }}">

            <input type="hidden"
                   name="longitude"
                   class="os-longitude"
                   value="{{ !empty($property->longitude) ? $property->longitude : settings('default_longitude') }}">
        </div>
    </div>

    <div class="form-group sticky-buttons">
        <button type="submit" class="btn btn-large btn-primary" name="action">
            Save
        </button>
        @include('backend.properties.sticky-buttons')
    </div>

    <input type="hidden" name="_method" value="PUT">
</form>

@endsection

@push('headerscripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.css" />
@endpush

@push('footerscripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.js"></script>
<script src="{{url('assets/admin/build/js/map-geocoding.js')}}"></script>
@endpush
