@extends('backend.layouts.master')

@section('admin-content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_content">
                <div class="table-responsive pw-table">
                    @if(!empty($items))
                        {{ $items->links('pagination::bootstrap-4') }}
                        <table class="table table-striped jambo_table bulk_action table-bordered-">
                            <thead>
                                <tr>
                                    <th width="10%">Image</th>
                                    <th width="60%">Name</th>
                                    <th width="20%">Is Published?</th>
                                    <th width="10%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="{{$folder}}-sort" data-url="{{ admin_url($folder.'/sequence') }}">
                                @foreach($items as $item)
                                    <tr id="item-{{$item->id}}">
                                        <td>
                                            <img src="{{$item->PhotoDisplay}}" alt="" width="70" style="width:75px;max-height:75px;object-fit:cover;">
                                        </td>
                                        <td>{{strip_tags($item->name)}}</td>
                                        <td>{{$item->IsPublishDisplay}}</td>
                                        <td class="text-center table-active-btn">
                                            <a href="{{admin_url($folder.'/'.$item->id.'/edit')}}" class="btn btn-small btn-primary">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div style="display: flex; justify-content: center; margin-top: 15px;">
                            {{ $items->links('pagination::bootstrap-4') }}
                        </div>
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
