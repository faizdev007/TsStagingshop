@extends('backend.layouts.master')

@section('admin-content')

    <form method="post" action="{{ url('admin/branches/'. $branch->branch_id) }}" data-toggle="validator">
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
                                <h2>Edit Branch Details - {{ $branch->branch_name }}</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content pw-open">
                                <div class="xpw-fields">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label>Name: {!! required_label() !!}</label>
                                                <input name="branch_name" type="text" class="form-control" placeholder="Branch Name" value="{{ $branch->branch_name }}" required>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label>Address Line 1: {!! required_label() !!}</label>
                                                <input name="branch_address1" type="text" class="form-control" placeholder="Branch Address Line 1" value="{{ $branch->branch_address1 }}" required>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label>Address Line 2</label>
                                                <input name="branch_address2" type="text" class="form-control" placeholder="Branch Address Line 2" value="{{ $branch->branch_address2 }}">
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label>Town / City : {!! required_label() !!}</label>
                                                <input name="branch_town" type="text" class="form-control" placeholder="Branch Town / City" value="{{ $branch->branch_town }}" required>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label>Postal / Zip Code : {!! required_label() !!}</label>
                                                <input name="branch_postcode" type="text" class="form-control" placeholder="Branch Postal / Zip Code" value="{{ $branch->branch_postcode }}">
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label>Phone Number :</label>
                                                <input name="branch_phone" type="tel" class="form-control" placeholder="Branch Telephone Number" value="{{ $branch->branch_phone }}">
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label>Email Address : {!! required_label() !!}</label>
                                                <input name="branch_email" type="email" class="form-control" placeholder="Branch E-Mail Address" value="{{ $branch->branch_email }}" required>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
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
            <a href="{{admin_url('branches')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
        </div>
        @csrf
    </form>

@endsection
