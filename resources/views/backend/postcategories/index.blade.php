@extends('backend.layouts.master')

@section('admin-content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_content">
                <div class="table-responsive pw-table">
                    @if(!empty($items))
                        <table class="table table-striped jambo_table bulk_action table-bordered-">
                            <thead>
                                <tr>
                                    <th width="10%">ID</th>
                                    <th width="60%">Name</th>
                                    <th width="20%">Is Published?</th>
                                    <th width="10%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr id="item-{{$item->id}}">
                                        <td>{{strip_tags($item->id)}}</td>
                                        <td>{{strip_tags($item->name)}}</td>
                                        <td>{{$item->IsPublishDisplay}}</td>
                                        <td class="text-center table-active-btn">
                                            <a href="{{admin_url($folder.'/'.$item->id.'/edit')}}" class="btn btn-small btn-primary">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

@push('headerscripts')
<link rel="stylesheet" href="{{asset('assets/admin/build/vendors/jquery-ui-1.12.1/jquery-ui.min.css')}}">
@endpush

@push('footerscripts')
<script src="{{asset('assets/admin/build/vendors/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
@endpush
