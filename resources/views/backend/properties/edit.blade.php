@extends('backend.properties.template')

@section('property-content')

<form action="{{route('properties.update', $property->id)}}" method="POST">
@csrf
<div class="x_panel pw-inner-tabs">
    <div class="x_title">
        <h2>Property Settings</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>

    <div class="x_content pw-open">
        <div class="xpw-fields">
            <div class="row">
                @if((Auth::check() && Auth::user()->role_id != 3) || ($property->admin_approval == 1 && Auth::user()->role_id == 3))
                <div class="@if(settings('sale_rent') !== 'sale_rent') col-md-4 @else col-md-4 @endif">
                    <div class="control-form">
                        <label>Status: {!! required_label() !!}</label>
                        <select name="status" id="id-status" class="form-control select-pw">
                            @foreach(p_states() as $key => $value)
                                <option value="{{ $key }}" @if($property->status == $key) selected @endif>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
                @if(settings('sale_rent') == 'sale_rent')
                <div class="col-md-4">
                    <div class="control-form">
                        <label>Field Type: {!! required_label() !!}</label>
                        <select
                            name="is_rental"
                            id="id-mode"
                            class="form-control select-pw mode-attr"
                            data-category=".p-category">

                            @foreach(p_fieldTypes() as $key => $value)
                                <option value="{{ $key }}" @if($property->is_rental == $key) selected @endif>
                                    {{ $value }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>
                @elseif(settings('sale_rent') == 'sale')
                <input type="hidden" name="is_rental" value="0">
                @else
                <input type="hidden" name="is_rental" value="1">
                @endif

                @if(0)
                <div class="col-md-4">
                    <div class="control-form p-category-field">
                        <label>Subtype: {!! required_label() !!}</label>
                        <select
                            name="category_type"
                            class="form-control select-pw p-category"
                            data-val="{{ $property->category_type }}">

                            @foreach(p_category() as $key => $value)
                                <option value="{{ $key }}" @if($property->category_type == $key) selected @endif>
                                    {{ $value }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>@endif
                <!-- @if(!settings('propertybase'))
                <div class="col-md-4">
                    <div class="control-form p-category-field">
                        <label>Community:</label>
                        <select name="community_id" class="form-control select-pw">
                            @foreach(p_communities() as $key => $value)
                                <option value="{{ $key }}" @if($property->community_id == $key) selected @endif>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
@endif -->
                @if( Auth::user()->role_id == '3' )
                <input type="hidden" name="user_id" value="{{$property->user_id}}" readonly/>
                <input type="hidden" name="is_featured" value="{{$property->is_featured}}" readonly/>
                @else
                <div class="@if(settings('sale_rent') !== 'sale_rent') col-md-4 @else col-md-4 @endif">
                    <div class="control-form">
                        <label>Agent: {!! required_label() !!}</label>
                        <select
                            name="user_id"
                            class="form-control select-pw-ajax-agent"
                            >
                            @foreach($agentSelected[1] as $key => $value)
                                <option value="{{ $key }}" @if($property->user_id == $key) selected @endif>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="@if(settings('sale_rent') !== 'sale_rent') col-md-4 @else col-md-4 @endif">
                    <div class="control-form">
                        <label>Featured:</label>
                        <select name="is_featured" class="form-control select-pw">
                            <option value="0" @if($property->is_featured == 0) selected @endif>No</option>
                            <option value="1" @if($property->is_featured == 1) selected @endif>Yes</option>
                        </select>
                    </div>
                </div>
                <div class="@if(settings('new_developments') !== 'new_developments') col-md-4 @else col-md-4 @endif">
                    <div class="control-form">
                        <label>New Development:</label>
                        <select name="is_development" class="form-control select-pw">
                            <option value="n" @if(($property->is_development ?? 'y') === 'n') selected @endif>No</option>
                            <option value="y" @if(($property->is_development ?? 'y') === 'y') selected @endif>Yes</option>
                        </select>
                    </div>
                </div>
                @endif

                @if(settings('branches_option') == 1)
                <div class="col-md-6">
                    <div class="control-form">
                        <label>Branch</label>
                        <select id="id-mode" class="form-control select-pw" name="branch_id" data-placeholder="Optional....">
                            <option></option>
                            @foreach($branches as $branch)
                            @if($property->branch)
                            <option @if($branch->branch_id == $property->branch->branch_id) selected="selected" @endif value="{{ $branch->branch_id }}">{{ $branch->branch_name }}</option>
                            @else
                            <option value="{{ $branch->branch_id }}">{{ $branch->branch_name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

            </div>
        </div>

    </div>
</div>
<!-- 
<div class="x_panel pw-inner-tabs">
    <div class="x_title">
        <h2>Property Type</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="xpw-fields">
            <div class="row">
                <div class="col-md-12">
                    <div class="control-form">

                        <select id="li-feature" class="select-pw w-100 type-select" name="property_type_ids[]" data-placeholder="Please select..." multiple>
                            <?php
                            $ptypeArraySelected = !empty($property->property_type_ids) ? explode(',', $property->property_type_ids) : [];
                            $ptypeArray = prepare_dropdown_ptype($propertyTypes);
                            foreach ($ptypeArray as $id => $propertyType) {
                                if (!empty($id)) {
                            ?>
                                    <option value="<?= $id ?>" {{ in_array($id, $ptypeArraySelected) ? 'selected' : '' }} ><?= $propertyType ?></option>
                                <?php
                                }
                            }
                                ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
 -->
<div class="x_panel pw-inner-tabs" style="display: none;">
    <div class="x_title">
        <h2>Feed Export</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="xpw-fields">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-check">
                        <input
                            type="checkbox"
                            name="export_bhs"
                            id="export_bhs"
                            class="form-check-input"
                            value="1"
                            @if($property->export_bhs) checked @endif>
                        <label class="form-check-label" for="export_bhs">
                            Export to Brown Harris Stevens feed?
                        </label>
                        <small>
                            <a href="{{ route('bhs_feed') }}" target="_blank">(View feed)</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="x_panel pw-inner-tabs">
    <div class="x_title">
        <h2>General Info</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div class="xpw-fields">

            <!-- ROW 1 -->
            <div class="row">
                <div class="col-md-3">
                    <div class="control-form">
                        <label>Property Name:</label>
                        <input
                            type="text"
                            name="name"
                            id="id-name"
                            class="form-control"
                            value="{{ $property->name }}"
                            placeholder="Please enter..."
                            oninput='
                                var warning = document.getElementById("name-warning");
                                warning.textContent = this.value.length + "/50";
                                warning.style.color = this.value.length > 50 ? "rgba(255, 0, 0, 0.84)" : "rgba(0, 128, 0, 0.91)";
                                warning.style.fontStyle = "bold";
                                if(this.value.length > 50) {
                                    warning.textContent = "Maximum 50 characters reached!";
                                }
                            '>
                        <small id="name-warning" style="color: green; font-style: bold;"></small>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                var input = document.getElementById('id-name');
                                var warning = document.getElementById('name-warning');
                                warning.textContent = input.value.length + "/50";
                                warning.style.color = input.value.length > 50
                                    ? "rgba(255, 0, 0, 0.84)"
                                    : "rgba(0, 128, 0, 0.91)";
                                warning.style.fontStyle = "bold";
                                if (input.value.length > 50) {
                                    warning.textContent = "Maximum 50 characters reached!";
                                }
                            });
                        </script>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="control-form">
                        <label>Price qualifier:</label>
                        <select name="price_qualifier" class="form-control select-pw price_qualifier">
                            @foreach(p_priceQualifiers() as $key => $value)
                                <option value="{{ $key }}" @if($property->price_qualifier == $key) selected @endif>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="control-form">
                        <label>Price ({{ settings('currency_symbol') }}): {!! required_label() !!}</label>
                        <input
                            type="text"
                            name="price"
                            id="id-price"
                            class="form-control"
                            value="{{ $property->price }}"
                            placeholder="Please enter...">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="control-form">
                        <label>Minimum Price ({{ settings('currency_symbol') }}):</label>
                        <input
                            type="text"
                            name="price_min"
                            id="id-price_min"
                            class="form-control"
                            value="{{ $property->price_min }}"
                            placeholder="Please enter...">
                    </div>
                </div>
            </div>

            <!-- ROW 2 -->
            <div class="row">
                <div class="col-md-3">
                    <div class="control-form">
                        <label>Maximum Price ({{ settings('currency_symbol') }}):</label>
                        <input
                            type="text"
                            name="price_max"
                            id="id-price_max"
                            class="form-control"
                            value="{{ $property->price_max }}"
                            placeholder="Please enter...">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="control-form for-rent">
                        <label>Rent Period:</label>
                        <select name="rent_period" class="form-control select-pw">
                            @foreach(p_rentPeriod() as $key => $value)
                                <option value="{{ $key }}" @if($property->rent_period == $key) selected @endif>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="control-form">
                        <label>Property Type: {!! required_label() !!}</label>
                        <select name="property_type_id" class="form-control select-pw">
                            @foreach(prepare_dropdown_ptype($propertyTypes) as $key => $value)
                                <option value="{{ $key }}" @if($property->property_type_id == $key) selected @endif>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="control-form">
                        <label>Number of bedrooms: {!! required_label() !!}</label>
                        <select name="beds" class="form-control select-pw">
                            @foreach(p_beds() as $key => $value)
                                <option value="{{ $key }}" @if($property->beds == $key) selected @endif>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- ROW 3 -->
            <div class="row">
                <div class="col-md-3">
                    <div class="control-form">
                        <label>Number of bathrooms:</label>
                        <select name="baths" class="form-control select-pw">
                            @foreach(p_baths() as $key => $value)
                                <option value="{{ $key }}" @if($property->baths == $key) selected @endif>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="control-form">
                        <label>Terrace Size ({{ $property->AreaUnit }}):</label>
                        <input
                            type="text"
                            name="terrace_area"
                            id="id-terrace_area"
                            class="form-control"
                            value="{{ $property->terrace_area }}"
                            placeholder="Please enter...">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="control-form">
                        <label>Land Size ({{ $property->AreaUnit }}):</label>
                        <input
                            type="text"
                            name="land_area"
                            id="id-land_area"
                            class="form-control"
                            value="{{ $property->land_area }}"
                            placeholder="Please enter...">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="control-form">
                        <label>Area ({{ $property->AreaUnit }}):</label>
                        <input
                            type="text"
                            name="internal_area"
                            id="id-internal_area"
                            class="form-control"
                            value="{{ $property->internal_area }}"
                            placeholder="Please enter...">
                    </div>
                </div>
            </div>

            <!-- YOUTUBE -->
            <div class="row">
                <div class="col-md-6">
                    <div class="control-form">
                        <label>
                            YouTube® ID (11-digit code):
                            @if(!empty($property->youtube_id))
                                <a href="#" class="link-1" data-toggle="modal" data-target="#myModal">
                                    View sample video
                                </a>
                            @endif
                        </label>
                        <div class="input-group">
                            <div class="input-group-addon">https://youtube.com/watch?v=</div>
                            <input
                                type="text"
                                name="youtube_id"
                                id="id-youtube_id"
                                class="form-control"
                                value="{{ $property->youtube_id }}"
                                maxlength="11"
                                placeholder="Please enter...">
                        </div>
                    </div>

                    @if(!empty($property->youtube_id))
                        <div id="myModal" class="modal fade" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Video</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="video-display">
                                            <iframe
                                                src="https://www.youtube.com/embed/{{ $property->youtube_id }}"
                                                frameborder="0"
                                                allow="autoplay; encrypted-media"
                                                allowfullscreen>
                                            </iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(settings('new_developments') && $property->is_development == 'y' && 1 == 0)
<div class="x_panel pw-inner-tabs">
    <div class="x_title">
        <h2>Development Information</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div class="xpw-fields">

            <div class="row">
                <div class="col-md-4">
                    <div class="control-form">
                        <label>Development Name</label>
                        <input type="text"
                               name="development_title"
                               class="form-control"
                               placeholder="Please enter..."
                               value="{{ $property->development ? $property->development->development_title : '' }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="control-form">
                        <label>Development Heading</label>
                        <input type="text"
                               name="development_heading"
                               class="form-control"
                               placeholder="Please enter..."
                               value="{{ $property->development ? $property->development->development_heading : '' }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="control-form">
                        <label>Development Sub Heading</label>
                        <input type="text"
                               name="development_subheading"
                               class="form-control"
                               placeholder="Please enter..."
                               value="{{ $property->development ? $property->development->development_subheading : '' }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="control-form">
                        <label>Development Completion Date</label>
                        <input type="text"
                               name="completion_date"
                               class="form-control datepicker"
                               placeholder="Choose a date"
                               value="{{ $property->development ? $property->development->completion_date : '' }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="control-form">
                        <label>Developer</label>
                        <input type="text"
                               name="development_developer"
                               class="form-control"
                               placeholder="Please enter..."
                               value="{{ $property->development ? $property->development->development_developer : '' }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="control-form">
                        <label>Status</label>
                        <input type="text"
                               name="development_status"
                               class="form-control"
                               placeholder="Please enter..."
                               value="{{ $property->development ? $property->development->development_status : '' }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="control-form">
                        <label>Construction Start Date</label>
                        <input type="text"
                               name="development_construction_start"
                               class="form-control datepicker"
                               placeholder="Choose a date"
                               value="{{ $property->development ? $property->development->development_construction_start : '' }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="control-form">
                        <label>Price From ({{ settings('currency_symbol') }}):</label>
                        <input type="text"
                               name="development_price_from"
                               class="form-control"
                               placeholder="Please enter"
                               value="{{ $property->price ?: ($property->development->development_price_from ?? '') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="control-form">
                        <label>Price To ({{ settings('currency_symbol') }}):</label>
                        <input type="text"
                               name="development_price_to"
                               class="form-control"
                               placeholder="Please enter"
                               value="{{ $property->development ? $property->development->development_price_to : '' }}">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endif

<div class="x_panel pw-inner-tabs">
    <div class="x_title">
        <h2>Key Features</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div class="xpw-fields">
            <textarea name="add_info"
                      id="id-add_info"
                      class="tagEditor-style-1 style-1"
                      placeholder="Please enter...">{{ $property->add_info }}</textarea>
        </div>
    </div>
</div>

<div class="x_panel pw-inner-tabs">
    <div class="x_title">
        <h2>Amenities</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div class="xpw-fields">
            <textarea name="add_amenities"
                      id="id-add_amenities"
                      class="tagEditor-style-1 style-1"
                      placeholder="Please enter...">{{ $property->add_amenities }}</textarea>
        </div>
    </div>
</div>


<div class="x_panel pw-inner-tabs">
    <div class="x_title">
        <h2>Description {!! required_label() !!}</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="xpw-fields">
            <div class="row">
                <div class="col-md-12">
                    <textarea name="description"
                    id="id-description"
                    class="mceEditor description"
                    maxlength="60000"
                    placeholder="Please enter...">{{ $property->description }}</textarea>
                    <!--<div class="counter-display"><div class="counter-value"></div></div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<div spellcheck="true" lang="en-US">
    @if(settings('translations'))
    @if(isset($languages))
    @foreach($languages as $k => $v)
        <textarea name="description_{{ $v }}"
                class="mceEditor description"
                maxlength="60000"
                spellcheck="true">
        {{ $property->translations->where('language',$v)->first()->description ?? '' }}
        </textarea>
    @endforeach
    @endif
    @endif
</div>

<div class="form-group sticky-buttons">
    <button type="submit" class="btn btn-large btn-primary">Save</button>

    @if(isset($property->status) && $property->status != -1)
        <button type="button" class="btn btn-info" id="requestIndexingBtn">
            <i class="fab fa-google"></i> Request Indexing
        </button>

        @if(settings('social_sharing'))
            <a class="btn btn-primary social-share"
               data-share="facebook"
               data-url="{{ $property->url }}"
               data-message="Take a look at this property on our website">
                <i class="fab fa-facebook-square"></i> Share to Facebook
            </a>
        @endif
    @endif

    <a href="{{ route('properties.duplicate', $property->id) }}"
       class="btn btn-info"
       id="duplicateButton">
        Duplicate Property
    </a>

    @include('backend.properties.sticky-buttons')
</div>

@csrf
<input type="hidden" name="_method" value="PUT">
</form>

<!-- Modal -->
<div class="modal fade" id="addnewstatusmodal" tabindex="-1" role="dialog" aria-labelledby="addnewstatusmodalLabel" aria-hidden="true" style="z-index: 9999!important;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addnewstatusmodalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="container-fluid" style="height: 200px;overflow-y: auto; border-bottom: 1px solid; border-top: 1px solid;">
                @foreach(p_states() as $key=>$status)
                <div id="p-{{$key}}" style="display:flex; position: relative;"><span class="form-control">{{$status}}</span>@if(!array_key_exists($key,config('p_states')))<button type="button" class="btn btn-danger" style="margin: 0px; border-radius: 0px;" onclick="addRelement(`{{$key}}`)">X</button>@endif</div>
                @endforeach
            </div>
            <form action="{{ route('properties.addnewstatus') }}"
                method="POST">

                @csrf

                <div class="modal-body">
                    <div id="statuslist"></div>

                    <input type="text"
                        name="p_status_input"
                        class="form-control"
                        placeholder="New Status">
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                        Close
                    </button>

                    <button type="submit"
                            class="btn btn-primary">
                        Save
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection



@push('headerscripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="{{asset('assets/admin/build/vendors/tagEditor/jquery.tag-editor.css')}}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@endpush

@push('footerscripts')
<!-- Typo.js and Dictionary -->
<script src="https://cdn.jsdelivr.net/npm/typo-js@1.2.2/typo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/typo-js@1.2.2/dictionaries/en_US.aff"></script>
<script src="https://cdn.jsdelivr.net/npm/typo-js@1.2.2/dictionaries/en_US.dic"></script>
<!-- Typo.js and Dictionary -->
<script src="{{asset('assets/admin/build/js/pw-select2-ajax.js')}}"></script>
<script src="{{asset('assets/admin/build/js/page-detail.js')}}"></script>
<script src="{{asset('assets/admin/build/vendors/tagEditor/jquery.caret.min.js')}}"></script>
<script src="{{asset('assets/admin/build/vendors/tagEditor/jquery.tag-editor.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('duplicateButton').addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to duplicate this property?')) {
            window.location.href = this.getAttribute('href');
        }
    });
</script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#requestIndexingBtn').click(function() {
            var btn = $(this);
            var originalText = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> Requesting...');
            btn.prop('disabled', true);

            // Build the clean property URL path without any leading or trailing slashes
            var propertyName = '{{ $property->search_headline }}'.trim()
                .toLowerCase()
                .replace(/&amp;/g, 'and') // Replace &amp; with and
                .replace(/\bamp\b/g, '') // Remove standalone amp
                .replace(/[^a-z0-9]+/g, '-') // Replace any non-alphanumeric chars with dash
                .replace(/^-+|-+$/g, '') // Remove leading/trailing dashes
                .replace(/-+/g, '-'); // Replace multiple dashes with single dash
            var propertyId = '{{ $property->id }}'.trim();
            var propertyPath = 'property/' + propertyName + '/' + propertyId;

            console.log('Property name:', propertyName);
            console.log('Property ID:', propertyId);
            console.log('Submitting URL path:', propertyPath);

            $.ajax({
                url: '{{ route("admin.google.request-indexing") }}',
                type: 'POST',
                data: {
                    url: propertyPath
                },
                success: function(response) {
                    console.log('Success Response:', response);
                    if (response.success) {
                        var fullUrl = 'https://terezaestates.com/' + propertyPath;
                        Swal.fire({
                            title: '<strong>Google Indexing API Connected</strong>',
                            icon: 'success',
                            html: `
                            <div style="text-align: left; margin-top: 20px;">
                                <p>The following URL has been submitted for indexing:</p>
                                <p style="word-break: break-all; color: #4a5568; background: #f7fafc; padding: 10px; border-radius: 5px; margin-top: 10px;">
                                    ${fullUrl}
                                </p>
                            </div>
                        `,
                            showCloseButton: true,
                            showConfirmButton: true,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3085d6',
                            customClass: {
                                container: 'custom-swal-container',
                                popup: 'custom-swal-popup',
                                title: 'custom-swal-title',
                                htmlContainer: 'custom-swal-html'
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message + (response.details ? '\n' + JSON.stringify(response.details, null, 2) : ''),
                            icon: 'error',
                            confirmButtonColor: '#d33'
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error Response:', xhr);
                    var errorMsg = '';
                    try {
                        var response = xhr.responseJSON;
                        errorMsg = response ? response.message : xhr.statusText;
                    } catch (e) {
                        errorMsg = 'Failed to submit URL for indexing';
                    }
                    Swal.fire({
                        title: 'Error',
                        text: errorMsg,
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                },
                complete: function() {
                    btn.html(originalText);
                    btn.prop('disabled', false);
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let statusinput = document.getElementById('select2-id-status-container');
        statusinput.addEventListener('click', function() {
            let optionlist = document.getElementById('select2-id-status-results');
            optionlist.addEventListener('scroll', function() {
                // Create <li>
                let li = document.createElement('li');

                // Create <div>
                let div = document.createElement('div');

                let actbutton = document.createElement('button');
                actbutton.type = 'button';
                actbutton.id = 'addnewstatus';
                actbutton.style.width = '100%';
                actbutton.style.margin = '0px';
                actbutton.style.borderRadius = '0px';
                actbutton.innerHTML = "Add/Delete Status";
                actbutton.classList.add('btn', 'btn-large', 'btn-primary');
                actbutton.setAttribute('data-toggle', 'modal');
                actbutton.setAttribute('data-target', '#addnewstatusmodal');

                if (document.getElementById('addnewstatus')) {
                    return;
                }
                // Build structure
                div.appendChild(actbutton); // <div><actbutton></div>
                li.appendChild(div); // <li><div><actbutton></div></li>

                // Append wherever you want
                optionlist.appendChild(li);
            });
        });
    });

    function addRelement(e) {
        let element = document.getElementById('statuslist');
        let deleteinput = document.createElement('input');
        deleteinput.type = 'hidden';
        deleteinput.name = 'deletestatus[]';
        deleteinput.value = e;
        element.appendChild(deleteinput);
        document.getElementById(`p-${e}`).remove();
    }
</script>

<style>
    .custom-swal-container {
        z-index: 9999;
    }

    .custom-swal-popup {
        border-radius: 15px;
        padding: 20px;
        width: 600px !important;
        /* Increased width */
        max-width: 90vw !important;
        /* Responsive on mobile */
    }

    .custom-swal-title {
        color: #2d3748;
        font-size: 24px;
    }

    .custom-swal-html {
        font-size: 16px;
        line-height: 1.5;
    }

    .swal2-html-container {
        margin: 1em 1.6em 0.3em !important;
    }
</style>
@endpush