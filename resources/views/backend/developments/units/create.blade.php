@extends('backend.layouts.master')

@section('admin-content')

    <form method="post" action="{{ admin_url('properties/'.$property->id.'/create-unit') }}" data-toggle="validator">
        @csrf
        <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <h2>{{ $development->development_title }} - Create Unit</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <p class="text-muted font-13 m-b-30"><br/></p>
                    <div class="x_panel pw-inner-tabs">
                        <div class="x_title">
                            <h2>Unit Fields</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content pw-open">

                            <div class="xpw-fields">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Name: {!! required_label() !!}</label>
                                            <input name="development_unit_name" type="text" class="form-control" placeholder="Name" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
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
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Bedrooms: {!! required_label() !!}</label>
                                            <input name="development_unit_bedrooms" type="text" class="form-control" placeholder="Bedrooms" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Bathrooms: {!! required_label() !!}</label>
                                            <input name="development_unit_bathrooms" type="text" class="form-control" placeholder="Bathrooms" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Status: {!! required_label() !!}</label>
                                            <input name="development_unit_status" type="text" class="form-control" placeholder="Status" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Availability: </label>
                                            <input name="development_unit_availability" type="text" class="form-control" placeholder="Availability">
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Price ({!! settings('currency_symbol') !!}) {!! required_label() !!}:</label>
                                            <input name="development_unit_price" type="text" class="form-control" placeholder="Price" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Land / Plot Size :</label>
                                            <input name="development_unit_outside_area" type="text" class="form-control" placeholder="Land / Plot Size">
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Internal Area :</label>
                                            <input name="development_unit_inside_area" type="text" class="form-control" placeholder="Internal Area">
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
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

    <div class="form-group sticky-buttons">
        <button type="submit" class="btn btn-large btn-primary" name="action" >Create</button>
        <a href="{{admin_url('properties')}}" class="btn btn-default btn-spacing">Cancel <span>and Return<span></a>
    </div>

    <input type="hidden" name="development_id" value="{{ $development->development_id }}">

    </form>

@endsection

@push('headerscripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('footerscripts')
    <script src="{{url('assets/admin/build/js/pw-select2-ajax.js')}}"></script>
@endpush
