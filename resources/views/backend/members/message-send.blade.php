@extends('backend.layouts.master')

@section('admin-content')
<div class="row current_url" data-url="{{url('admin/members')}}">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw-inner-tabs">
                <div class="x_title">
                    <h2>Send New Message @if($member) to user {{ $member->name }} @endif</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form method="post" action="{{ url('admin/members/messages/reply') }}" data-toggle="validator">
                        @csrf
                        @if(!$member)
                            <div class="form-group">
                                <label>Send to member</label>
                                <select name="to_id" class="form-control select-pw" data-placeholder="Choose Member">
                                    <option></option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="to_id" value="{{ $id }}">
                        @endif
                        <div class="form-group">
                            <label>Your Message {!! required_label() !!}</label>
                            <textarea name="message_content"
                                id="message_content"
                                class="style-1 description"
                                style="width:100%"
                                placeholder="Please enter..."
                                required>{{ old('message_content') }}</textarea>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <input type="hidden" name="message_type" value="new">
                        <button type="submit" class="btn btn-primary">Send Reply</button>
                    </form>
                </div>
            </div>
        </div>
</div>

@endsection