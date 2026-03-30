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
                            <h2>Language Settings</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content pw-open">
                            <form action="{{admin_url('settings/languages')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="xpw-fields">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="control-form">
                                                <label for="fullname">Default Language: {!! required_label() !!}</label>
                                                <select name="language_default" class="select-pw" data-placeholder="Choose a Default Language">
                                                    <option></option>
                                                    @foreach($languages as $k => $v)
                                                        <option @if($settings && $settings->language_default == $k) selected="selected" @endif value="{{ $k }}">{{ $v }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="control-form">
                                                <label for="fullname">Languages to Use:</label>
                                                <select multiple="multiple" name="language_settings[]" class="select-pw" data-placeholder="Languages to Use">
                                                    <option></option>
                                                    @foreach($languages as $k => $v)
                                                        <option @if($settings && in_array($k, $settings->languages_array)) selected="selected" @endif value="{{ $k }}">{{ $v }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group sticky-buttons">
                                        <button type="submit" class="btn btn-large btn-primary" >Save</button>
                                        <a href="{{admin_url('')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
                                    </div>

                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

