@extends('backend.layouts.master')

@section('admin-content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <h2><br/></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="x_panel pw-inner-tabs">
                        <div class="x_title">
                            <h2>Slide Fields</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content pw-open">

                            <form action="{{admin_url('slides')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="xpw-fields">
                                    <div class="row">

                                        <div class="@if(settings('translations')) col-md-4 @else col-md-4 @endif">
                                            <div class="control-form">
                                                <label for="fullname">Text Line 1: {!! required_label() !!}</label>
                                                <input type="text" id="text_line1" class="form-control" name="text_line1" placeholder="Please enter..." />
                                            </div>
                                        </div>

                                        <div class="@if(settings('translations')) col-md-4 @else col-md-4 @endif">
                                            <div class="control-form">
                                                <label for="fullname">Text Line 2:</label>
                                                <input type="text" id="text_line2" class="form-control" name="text_line2" placeholder="Please enter..." />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="control-form">
                                                <label>Slide Type:</label>
                                                <select class="form-control select-pw" name="type" data-placeholder="Slide Type">
                                                    <option></option>
                                                    <option value="image">Image</option>
                                                    <option value="video">Video</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group sticky-buttons">
                                        <button type="submit" class="btn btn-large btn-primary" >Create</button>
                                        <a href="{{admin_url('slides')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
                                    </div>

                                </div>

                                @if(settings('translations'))
                                    @if(isset($languages))
                                        @foreach($languages as $k => $v)
                                            <div class="xpw-fields">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h3>{{ $k }}</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="control-form">
                                                            <label for="fullname">Text Line 1: {!! required_label() !!}</label>
                                                            <input type="text" id="text_line1_{{ $v }}" class="form-control translate-{{$v}}" name="text_line1_{{ $v }}" placeholder="Please enter..." />
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="control-form">
                                                            <label for="fullname">Text Line 2:</label>
                                                            <input type="text" id="text_line2_{{ $v }}" class="form-control translate-{{$v}}" name="text_line2_{{ $v }}" placeholder="Please enter..." />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endif
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
