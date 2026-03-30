@extends('backend.layouts.master')

@section('admin-content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_content">
                <p class="text-muted font-13 m-b-30"></p>
                <div class="table-responsive pw-table">
                    @if(count($alerts))
                    {{ $alerts->links('pagination::bootstrap-4') }}
                    <table class="table table-striped jambo_table bulk_action table-bordered-">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Property Type</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alerts as $alert)
                            <tr>
                                <td class="ref-link"><a href="{{admin_url('property-alerts/'.$alert->id.'/edit')}}">{{ $alert->id }}</a></td>
                                <td>{{ $alert->fullname }}</td>
                                <td>{{ $alert->email }}</td>
                                <td>{{ $alert->DisplayPropertyTypes }}</td>
                                <td>{{ $alert->DisplayStatus }}</td>
                                <td class="text-center table-active-btn">
                                    <a href="{{admin_url('property-alerts/'.$alert->id.'/edit')}}" class="btn btn-small btn-primary">Edit</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $alerts->links('pagination::bootstrap-4') }}
                    @else
                        <div class="no-data">
                            No data found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
