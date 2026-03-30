@extends('backend.layouts.master')

@section('admin-content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <h2>Edit Property Type</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li class="top-button">
                        <a href="{{ admin_url('property-types') }}" class="btn btn-small btn-default">Back to List</a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ admin_url('property-types/'.$propertyType->id) }}" class="form-horizontal form-label-left">
                    @csrf
                    @method('PUT')

                    <div class="x_panel pw-inner-tabs">
                        <div class="x_title">
                            <h2>Property Type Details</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                                    Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="name" name="name" required class="form-control" value="{{ old('name', $propertyType->name) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group sticky-buttons">
                        <button type="submit" class="btn btn-large btn-primary">Update</button>
                        <a href="{{ admin_url('property-types') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection