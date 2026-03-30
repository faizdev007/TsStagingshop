@extends('backend.layouts.master')

@section('admin-content')

    <form method="post" action="{{ admin_url('development-unit/'.$unit->development_unit_id.'/edit') }}" data-toggle="validator">
        @csrf
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel pw">
                    <div class="x_title">
                        <h2>Edit Unit - {{ $unit->development_unit_name }}</h2>
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
                                                <input name="development_unit_name" type="text" class="form-control" placeholder="Name" value="{{ $unit->development_unit_name }}" required>
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
                                                            {{ old('property_type_id', $unit->property_type_id) == $key ? 'selected' : '' }}>
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
                                                <input name="development_unit_bedrooms" type="text" class="form-control" placeholder="Bedrooms" value="{{ $unit->development_unit_bedrooms }}" required>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>Bathrooms: {!! required_label() !!}</label>
                                                <input name="development_unit_bathrooms" type="text" class="form-control" placeholder="Bathrooms" value="{{ $unit->development_unit_bathrooms }}" required>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>Status: {!! required_label() !!}</label>
                                                <input name="development_unit_status" type="text" class="form-control" placeholder="Status" value="{{ $unit->development_unit_status }}" required>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>Availability: </label>
                                                <input name="development_unit_availability" type="text" class="form-control" value="{{ $unit->development_unit_availability }}" placeholder="Availability">
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>Price ({!! settings('currency_symbol') !!}) {!! required_label() !!}:</label>
                                                <input name="development_unit_price" type="text" class="form-control" value="{{ $unit->development_unit_price }}" placeholder="Price" required>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>Land / Plot Size :</label>
                                                <input name="development_unit_outside_area" type="text" class="form-control" value="{{ $unit->development_unit_outside_area }}" placeholder="Land / Plot Size">
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>Internal Area :</label>
                                                <input name="development_unit_inside_area" type="text" class="form-control" value="{{ $unit->development_unit_inside_area }}" placeholder="Internal Area">
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <input type="hidden" name="development_unit_id" value="{{ $unit->development_unit_id }}">
                        <input type="hidden" name="development_id" value="{{ $unit->development_id }}">

                        <button type="submit" class="btn btn-large btn-primary" name="action" >Update</button>
                        <a href="{{admin_url('properties')}}" class="btn btn-default btn-spacing">Cancel <span>and Return<span></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="x_panel pw">
                    <div class="x_title">
                        <h2>Unit Media</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="upload-info">
                            <div class="row">
                                <div class="col-md-3 col-sm-3">
                                    <div class="xpw-notes">
                                        <p>Image types accepted: JPG, GIF, PNG, TIF</p>
                                        <p>Maximum file size: 6MB</p>
                                        <p>Maximum of 35 Photos</p>
                                    </div>
                                    <div class="dz-error-message-display"></div>
                                </div>
                                <div class="col-md-9 col-sm-9">

                                </div>
                                <div class="col-md-12 col-sm-12 dz-hide-error-message">
                                    <div id="upload-form" class="upload-form">
                                        <input type="hidden" class="unit-id" name="unit_id" value="{{ $unit->development_unit_id }}">
                                        <input type="hidden" name="development_id" value="{{ $unit->development_id }}">
                                        <form action="{{ route('developments.photo-upload.save', $unit->development_unit_id) }}"
                                            method="POST"
                                            enctype="multipart/form-data">

                                            @csrf

                                            <div class="dropzone v1" id="unit-photo-upload"></div>

                                            <div class="upload-btn">
                                                <button type="submit"
                                                        class="btn btn-success"
                                                        id="submit-all">
                                                    Upload
                                                </button>

                                                <a href="{{ url()->current() }}"
                                                class="btn btn-info"
                                                id="refresh-page">
                                                    Refresh
                                                </a>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form method="post" action="{{ admin_url('development-unit/'.$unit->development_unit_id.'/delete-photo') }}">
            @method('DELETE')
            <div class="media-container">
                <input id="media-sort-url" type="hidden" name="sort_url" value="{{ admin_url('development-unit/'.$unit->development_unit_id.'/photo-sort') }}">
                <div id="development-media-sort" class="row">
                    @foreach( $unit->media as $media )
                        <div id="item-{{ $media->id }}" class="col-md-55">
                            <div class="thumbnail pw">
                                <div class="image view view-first fill">
                                    <img src="{{ storage_url($media->path) }}" alt="image" />
                                </div>
                                <div class="pw-checkbox">
                                    <label>
                                        <input type="checkbox" name="delete_ids[]" value="{{ $media->id }}" class="flat">
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="form-group sticky-buttons">
                <a href="{{admin_url('developments')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
                <button type="submit" class="confirm-action btn btn-danger btn-spacing v2" title="delete">
                    <i class="fa fa-trash-o"></i> Delete Selected
                </button>
            </div>
            {{ csrf_field() }}
        </form>
@endsection

@push('headerscripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{url('assets/admin/build/vendors/jquery-ui-1.12.1/jquery-ui.min.css')}}">
    <!-- Dropzone.js -->
    <link href="{{url('assets/admin/vendors/dropzone/dist/min/dropzone.min.css')}}" rel="stylesheet">
@endpush

@push('footerscripts')
    <script src="{{url('assets/admin/build/vendors/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
    <!-- Dropzone.js -->
    <script src="{{url('assets/admin/vendors/dropzone/dist/dropzone.js')}}"></script>
    <script src="{{url('assets/admin/build/js/pw-dropzone.js')}}"></script>
    <script src="{{url('assets/admin/build/js/pw-select2-ajax.js')}}"></script>
@endpush
