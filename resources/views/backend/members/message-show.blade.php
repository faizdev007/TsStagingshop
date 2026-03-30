@extends('backend.layouts.master')

@section('admin-content')

    <div class="row current_url" data-url="{{url('admin/members')}}">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw-inner-tabs">
                <div class="x_title">
                    <h2>Viewing Message from {{ $message->sender->name }} | Sent : {{ $message->friendly_date }} at {{ $message->friendly_time }}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-lg-12">
                            <h4>Message Content</h4>
                            {{ $message->message_content }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw-inner-tabs">
                <div class="x_title">
                    <h2>Send a reply to {{ $message->sender->name }}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form method="post" action="{{ url('admin/members/messages/reply') }}" data-toggle="validator">
                        @csrf
                        <div class="form-group">
                            <label>Your Reply {!! required_label() !!}</label>
                            <textarea
                                name="message_content"
                                id="message_content"
                                class="style-1 description"
                                style="width:100%"
                                placeholder="Please enter..."
                                required
                            >{{ old('message_content') }}</textarea>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <input type="hidden" name="to_id" value="{{ $message->from_id }}">
                        <input type="hidden" name="message_id" value="{{ $message->message_id }}">
                        <button type="submit" class="btn btn-primary">Send Reply</button>
                    </form>
                    <div class="form-group sticky-buttons">
                        <a href="{{url('admin/members/messages')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($other_messages->count() > 0)
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel pw-inner-tabs">
                    <div class="x_title">
                        <h2>Previous messages with {{ $message->sender->name }}</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <ul class="list-unstyled top_profiles scroll-view" style="height: auto;">
                        @foreach($other_messages as $message)
                                <li class="media event">
                                    <a class="pull-left border-aero profile_thumb">
                                        <i class="fa fa-user aero"></i>
                                    </a>
                                    <div class="media-body">
                                        <a class="title" href="#">{{ $message->sender->name }} @if($message->sender->role_id < 4) <strong>(Agent)</strong> @endif</a>
                                        <p>{{ $message->message_content }}</p>
                                        <p> <small>{{ $message->friendly_date }} at {{ $message->friendly_time }}</small></p>
                                    </div>
                                </li>
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection