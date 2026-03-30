@extends('backend.layouts.master')

@section('admin-content')

<form method="post" action="{{ url('admin/pages') }}" data-toggle="validator">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <h2><br /></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content pw-open">
                <div class="xpw-fields">
                    <div class="row">

                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label for="fullname">Title: {!! required_label() !!}</label>
                                <input
                                    type="text"
                                    name="title"
                                    id="location"
                                    class="form-control get-slug"
                                    data-type="pages"
                                    placeholder="Please enter..."
                                    required
                                    value="{{ old('title') }}"
                                >
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-4">
                            <div class="control-form">
                                <label for="fullname">H1 Title: {!! required_label() !!}</label>
                                <input type="text" name="header_title" value="{{ old('header_title') }}" class="form-control" placeholder="Please enter....">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label for="slug">Page Slug (URL): {!! required_label() !!}</label>
                                <input name="route" type="text" class="form-control slug-return" value="{{ old('route') }}" required="required" placeholder="Page Slug (URL)">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                        </div>

                        @if( settings('bespoke_pages') == 1)
                            <input type="hidden" name="page_type" value="bespoke">
                        @else
                            <input type="hidden" name="page_type" value="page">
                        @endif

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="fullname">Content: {!! required_label() !!}</label>
                                <textarea
                                    name="content"
                                    id="content"
                                    class="mceEditor description"
                                    style="width:100%"
                                    placeholder="Please enter..."
                                    maxlength="60000"
                                >{{ old('content') }}</textarea>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                        </div>

                    </div>

                    @csrf

                    @include('backend.pages.sticky-buttons')

                </div>
            </div>
            </div>
        </div>
    </div>
</form>


@endsection