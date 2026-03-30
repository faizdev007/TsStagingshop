@extends('backend.layouts.master')

@section('admin-content')

    <form method="post" action="{{admin_url('market-valuation/why-list/create')}}" data-toggle="validator">

    <div class="x_panel pw-inner-tabs">
        <div class="x_content">
            <div class="xpw-fields">
                <div class="row current_url" data-url="{{ admin_url('market-valuation') }}">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <legend>New List Item</legend>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fullname">Title: {!! required_label() !!}</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="form-control" value="" placeholder="Title" required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Icon: {!! required_label() !!}</label>
                            <input type="text" id="icon" name="icon" value="{{ old('icon') }}" class="form-control icon-picker" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="slug">Content: {!! required_label() !!}</label>
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
            </div>
        </div>
    </div>
        @csrf

        <div class="form-group sticky-buttons">
            <button type="submit" class="btn btn-large btn-primary" >Save</button>
            <a href="{{admin_url('pages')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
        </div>

    </form>

    @push('headerscripts')
        <link href="{{url('assets/admin/vendors/icon-chooser/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
    @endpush

    @push('footerscripts')
        <script src="{{url('assets/admin/vendors/icon-chooser/js/fontawesome-iconpicker.min.js')}}"></script>
    @endpush


@endsection