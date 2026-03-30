@extends('backend.layouts.master')

@section('admin-content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <ul class="nav navbar-right panel_toolbox">
                        <li class="top-button"><a href="{{ url('admin/members/message') }}" class="btn btn-small btn-primary">Create New</a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="table-responsive pw-table">
                        @if(!empty($messages->count()))
                            <table class="table table-striped jambo_table bulk_action table-bordered-">
                                <thead>
                                <tr>
                                    <th>Message From</th>
                                    <th>Message Content</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($messages as $message)
                                    @if($message->sender)
                                        <tr>
                                            <td>
                                                {{ $message->sender->name }}
                                            </td>
                                            <td>{{ substr($message->message_content, 0, 150) . '...' }}</td>
                                            <td class="text-center table-active-btn">
                                                <a href="{{url('admin/members/messages/'.$message->message_id.'')}}" class="btn btn-small btn-primary">Reply</a>
                                            </td>
                                        </tr>
                                     @endif
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
