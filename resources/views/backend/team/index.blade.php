@extends('backend.layouts.master')

@section('admin-content')

    <style>
        .btnflexcenter{
            display: flex;
            align-items: center;
            gap: 2px;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: center;
        }
    </style>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <ul class="nav navbar-right panel_toolbox">
                        <li class="top-button"><a href="{{admin_url('team/create')}}" class="btn btn-small btn-primary">Create Team Member</a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="table-responsive pw-table">
                        @if(!empty($teams))
                            <table class="table table-striped jambo_table bulk_action table-bordered-">
                                <thead>
                                <tr>
                                    <th width="200">Image</th>
                                    <th>Name</th>
                                    <th class="text-center" width="200">Action</th>
                                </tr>
                                </thead>
                                <tbody id="slides-sort" data-sorturl="{{ admin_url('team/sort') }}">
                                    @foreach($teams as $team)
                                        <tr id="item-{{$team->team_member_id}}">
                                            <td>
                                                @if($team->team_member_photo)
                                                    <img src="{{ storage_url($team->team_member_photo) }}" width="60" height="60" />
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{!! $team->team_member_name !!}</td>
                                            <td class="text-center">
                                                <div class="btnflexcenter">
                                                    <a href="{{Route('profile',$team->team_member_slug)}}" class="btn btn-small btn-dark">View</a>
                                                    <a href="{{admin_url('team/'.$team->team_member_id.'/edit')}}" class="btn btn-small btn-primary">Edit</a>
                                                    <a href="#" class="btn btn-small btn-danger modal-toggle"
                                                       data-item-id="{{ $team->team_member_id }}"
                                                       data-toggle="modal"
                                                       data-modal-type="delete"
                                                       data-modal-title="Delete"
                                                       data-modal-size="small"
                                                       data-delete-type="team"
                                                       data-target="#global-modal">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div style="display: flex; margin-top: 15px;">
                                {{$teams->links('pagination::bootstrap-4')}}
                            </div>
                        @else
                            <div class="no-data">
                                No team members at this time.
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
