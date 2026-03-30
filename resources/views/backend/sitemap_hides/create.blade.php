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
                        <h2>URL Details</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content pw-open">

                        <form action="{{admin_url('sitemap_hides')}}" method="POST">
                            @csrf
                            <div class="xpw-fields">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="control-form">
                                            <label for="url">URL: {!! required_label() !!}</label>
                                            <input type="text" id="url" class="form-control" name="url" placeholder="Please enter..." />
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group sticky-buttons">
                                    <button type="submit" class="btn btn-large btn-primary" >Create</button>
                                    <a href="{{admin_url('sitemap_hides')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
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
