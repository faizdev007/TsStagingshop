@extends('backend.layouts.master')

@section('admin-content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <h2>Property Types</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li class="top-button">
                        <a href="{{ admin_url('property-types/create') }}" class="btn btn-small btn-primary">Add New Property Type</a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="table-responsive pw-table">
                    <table class="table table-striped jambo_table bulk_action table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Properties Count</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($propertyTypes as $type)
                                <tr>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ $type->slug }}</td>
                                    <td>{{ $type->properties->count() }}</td>
                                    <td class="text-center table-active-btn">
                                        <a href="{{ admin_url('property-types/'.$type->id.'/edit') }}" class="btn btn-info btn-xs">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                        <form action="{{ admin_url('property-types/'.$type->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this property type?');">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection