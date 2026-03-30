@extends('backend.layouts.master')

@section('admin-content')

    <form method="post" action="{{ url('admin/search-content') }}" data-toggle="validator">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <h2><br/></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content ">
                    <p class="text-muted font-13 m-b-30"><br/></p>
                    <div class="x_panel pw-inner-tabs">
                        <div class="x_title">
                            <h2>Page Detail</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content pw-open">

                            <div class="xpw-fields">


                            <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label>URL: {!! required_label() !!}</label>
                                            <input name="content_url" type="text" class="form-control" placeholder="Content URL" value="{{ old('content_url') }}" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            @if ($errors->has('content_url'))
                                                <span class="help-block">
                                                    <strong>{!! $errors->first('content_url') !!}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label>Meta Title: {!! required_label() !!}</label>
                                            
                                            <input name="content_title" type="text" class="form-control" placeholder="Content Title" value="{{ old('content_title') }}" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            @if ($errors->has('content_title'))
                                                <span class="help-block">
                                                    <strong>{!! $errors->first('content_title') !!}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label>Meta Description {!! required_label() !!}</label>
                                            <textarea id="content" name="content" maxlength="60000" class="mceEditor description" placeholder="Please enter" required>{{ old('content') }}</textarea>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            @if ($errors->has('content'))
                                                <span class="help-block">
                                                    <strong>{!! $errors->first('content') !!}</strong>
                                                </span>
                                            @endif
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

    <div class="additional-blocks">
        <!-- jQuery Populated -->
    </div>

    <!-- <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-default add-search-blocks" href="#">Add More Content Blocks</a>
        </div>
    </div> -->


    <div class="form-group sticky-buttons">
        <button type="submit" class="btn btn-large btn-primary" name="action" >Save</button>
        <a href="{{admin_url('search-content')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
    </div>
    @csrf
    </form>

@endsection
