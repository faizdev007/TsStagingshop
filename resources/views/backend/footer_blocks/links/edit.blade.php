@extends('backend.layouts.master')

@section('admin-content')

    <form method="post" action="{{ url('admin/footer-blocks/links/'.$item->footer_link_id.'/edit') }}" data-toggle="validator">
        @method('PUT')
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
                                <h2>Link Detail</h2>
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
                                                <label>Title: {!! required_label() !!}</label>
                                                <input name="title" type="text" class="form-control" value="{{ $item->footer_link_title }}" placeholder="Title" required>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label>URL Type {!! required_label() !!}</label>
                                            </div>
                                            <div class="form-group">
                                                <label class="radio-inline">
                                                    <input class="url_type" type="radio" name="url_type" value="custom-link" @if($item->footer_link_type == 'custom-link') checked="checked" @endif> Custom Link
                                                </label>
                                                <label class="radio-inline">
                                                    <input class="url_type" type="radio" name="url_type" value="existing-url" @if($item->footer_link_type == 'existing-url') checked="checked" @endif> Existing URL
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="existing-url" style="@if($item->footer_link_type == 'existing-url') display:block; @else display:none; @endif ">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <label>Existing Page URL {!! required_label() !!}</label>
                                                    <select name="footer_link_url[]" class="select-pw" data-placeholder="Link URL">
                                                        <option></option>
                                                        @foreach($pages as $page)
                                                            <option @if($item->footer_link_url == $page->route) selected="selected" @endif value="{{ $page->route }}">{{ $page->route }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('footer_link_url'))
                                                        <span class="help-block">
                                                            <strong>{!! $errors->first('footer_link_url') !!}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom-link" style="@if($item->footer_link_type == 'custom-link') display:block; @else display:none; @endif ">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <label>Custom Page URL {!! required_label() !!}</label>
                                                    <input name="footer_link_url[]" type="text" class="form-control" placeholder="Page URL" value="@if($item->footer_link_type == 'custom-link') {{ $item->footer_link_url }} @endif" required>
                                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                    @if ($errors->has('footer_link_url'))
                                                        <span class="help-block">
                                                            <strong>{!! $errors->first('footer_link_url') !!}</strong>
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
        </div>

        <div class="form-group sticky-buttons">
            <button type="submit" class="btn btn-large btn-primary" name="action" >Save</button>
            <a href="{{admin_url('footer-blocks')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
        </div>
        @csrf
    </form>

@endsection
