@extends('backend.layouts.master')

@section('admin-content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <ul class="nav navbar-right panel_toolbox">
                  <li class="top-button"><a href="{{admin_url('metadata/create')}}" class="btn btn-small btn-primary">Add Metadata</a></li>

                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="table-responsive pw-table">
                    @if(count($items))
                        <table class="table table-striped jambo_table bulk_action table-bordered-">
                            <thead>
                                <tr>
                                    <th>URL</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Date Added</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr id="item-{{$item->id}}">
                                        <td>{{strip_tags($item->url)}}</td>
                                        <td>{{strip_tags($item->title)}}</td>
                                        <td>{{strip_tags($item->excerpt)}}</td>
                                        <td>{{admin_date($item->created_at)}}</td>
                                        <td class="text-center table-active-btn">
                                            <a href="{{admin_url('metadata/'.$item->id.'/edit')}}" class="btn btn-small btn-primary">Edit</a>
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
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush