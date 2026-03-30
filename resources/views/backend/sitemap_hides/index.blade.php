@extends('backend.layouts.master')

@section('admin-content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <div class="search-form-style-1">
                    @include('backend.sitemap_hides.search-form')
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="table-responsive pw-table">
                    {{$items->links('pagination::bootstrap-4')}}

                    @if(!empty($items->count()))
                    <table class="table table-striped jambo_table bulk_action table-bordered-">
                        <thead>
                            <tr>
                                <th>URL</th>
                                <th>Date Added</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr id="item-{{$item->id}}">
                                    <td>{{strip_tags($item->url)}}</td>
                                    <td width="150">{{($item->created_at) ? admin_date($item->created_at) : ''}}</td>
                                    <td width="150" class="text-center table-active-btn">
                                        <a href="{{admin_url('sitemap_hides/'.$item->id.'/edit')}}" class="btn btn-small btn-primary">Edit</a>
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
