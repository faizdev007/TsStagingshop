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
                        <h2>Metadata Fields</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content pw-open">

                        <form action="{{admin_url('metadata')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="xpw-fields">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="control-form">
                                            <label for="url">URL: {!! required_label() !!}</label>
                                            <input
                                                type="text"
                                                name="url"
                                                id="url"
                                                class="form-control"
                                                placeholder="Please enter..."
                                                value="{{ old('url') }}"
                                            >
                                        </div>
                                    </div>

                                    <div class="clear"></div>

                                    <div class="col-md-6">
                                        <div class="control-form">
                                            <label for="title">Title: {!! required_label() !!}</label>
                                            <input
                                                type="text"
                                                name="title"
                                                id="title"
                                                class="form-control"
                                                placeholder="Please enter..."
                                                value="{{ old('title') }}"
                                            >
                                        </div>
                                    </div>

                                    <div class="clear"></div>

                                    <div class="col-md-12">
                                        <div class="control-form">
                                            <label for="description">Description:</label>
                                            <textarea
                                                name="description"
                                                id="description"
                                                class="form-control"
                                                rows="3"
                                                placeholder="Please enter..."
                                            >{{ old('description') }}</textarea>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group sticky-buttons">
                                    <button type="submit" class="btn btn-large btn-primary" >Create</button>
                                    <a href="{{admin_url('metadata')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
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
