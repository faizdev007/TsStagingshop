@extends('backend.properties.template')

@section('property-content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <h2>Development - {{ $development->development_title }}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li class="top-button"><a href="{{admin_url('properties/'.$property->id.'/create-unit')}}" class="btn btn-small btn-primary">Create Unit</a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="search-form-style-1">

                    </div>
                    <div class="table-responsive pw-table">
                        @if(count($units))
                            <table class="table table-striped jambo_table bulk_action table-bordered-">
                                <thead>
                                    <tr>
                                        <th>Unit Name</th>
                                        <th>Property Type</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="sortable" data-sorturl="{{ admin_url('development-units/sort') }}">
                                    @foreach($units as $unit)
                                        <tr id="item-{{ $unit->development_unit_id }}">
                                            <td>{{ $unit->development_unit_name }}</td>
                                            <td>{{ $unit->property_type->name }}</td>
                                            <td>{{ $unit->development_unit_status }}</td>
                                            <td>{{ $unit->display_price }}</td>
                                            <td>
                                                <a href="{{admin_url('development-unit/'.$unit->development_unit_id.'/edit')}}" class="btn btn-small btn-primary">Edit</a>
                                                |
                                                <a href="{{admin_url('development-unit/'.$unit->development_unit_id.'/duplicate')}}" class="btn btn-small btn-info">Duplicate</a>
                                                |
                                                <a href="#" class="btn btn-small btn-danger modal-toggle"
                                                   data-item-id="{{ $unit->development_unit_id }}"
                                                   data-toggle="modal"
                                                   data-modal-type="delete"
                                                   data-modal-title="Delete"
                                                   data-modal-size="small"
                                                   data-delete-type="development-unit"
                                                   data-target="#global-modal">Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                        <div class="no-data">
                            No Units / Plots created.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
