@extends('backend.layouts.master')

@section('admin-content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <ul class="nav navbar-right panel_toolbox">
                        <li class="top-button"><a href="{{admin_url('slides/create')}}" class="btn btn-small btn-primary">Add Slide</a></li>

                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="table-responsive pw-table">
                        @if(!empty($slides))
                            <table class="table table-striped jambo_table bulk_action table-bordered-">
                                <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Tagline 1</th>
                                    <th>Tagline 2</th>
                                    <th>Type</th>
                                    <th>Date Added</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody  id="slides-sort" data-sorturl="{{ admin_url('slides/sort') }}">
                                @foreach($slides as $slide)
                                    <tr id="item-{{$slide->id}}">
                                        <td>
                                            @if($slide->type == "image")
                                                @php
                                                    $thumb = !empty($slide->photo) ? asset('storage/slides/'.basename($slide->photo)) : url('assets/admin/build/images/pw-theme/placeholder/small.png');
                                                @endphp
                                                <img src="{{$thumb}}" alt="" width="70" style="width:75px;max-height:75px;object-fit:cover;">
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{strip_tags($slide->text_line1)}}</td>
                                        <td>{{strip_tags($slide->text_line2)}}</td>
                                        <td>{{ ucwords($slide->type) }}</td>
                                        <td>{{admin_date($slide->created_at)}}</td>
                                        <td class="text-center table-active-btn">
                                            <a href="{{admin_url('slides/'.$slide->id.'/edit')}}" class="btn btn-small btn-primary">Edit</a>
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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

@push('footerscripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('assets/admin/build/js/pw-slides.js')}}"></script>
@endpush
