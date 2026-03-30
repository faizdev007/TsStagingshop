@extends('backend.layouts.master')

@section('admin-content')
<div class="row" data-url="{{url('admin/members')}}">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <h2>Viewing Member - {{ $user->name }} <small> last login:  {{ Carbon\Carbon::parse($user->last_login_at)->diffForHumans()}} </small></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="x_panel pw-inner-tabs">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="{{(empty($tab)) ? 'active' : false}}"><a href="{{ url('admin/members/'.$user->id) }}" aria-expanded="true">Shortlist</a></li>
                            <li role="presentation" class="{{($tab == 'saved-searches') ? 'active' : false}}"><a href="{{ url('admin/members/'.$user->id.'?tab=saved-searches') }}" aria-expanded="true">Saved Searches</a></li>
                            <li role="presentation" class="{{($tab == 'property-alerts') ? 'active' : false}}"><a href="{{ url('admin/members/'.$user->id.'?tab=property-alerts') }}" aria-expanded="true">Property Alerts</a></li>
                            <li role="presentation" class="{{($tab == 'notes') ? 'active' : false}}"><a href="{{ url('admin/members/'.$user->id.'?tab=notes') }}" aria-expanded="true">Notes</a></li>
                            <li role="presentation" class="{{($tab == 'messages') ? 'active' : false}}"><a href="{{ url('admin/members/'.$user->id.'?tab=messages') }}" aria-expanded="true">Messages</a></li>
                            <li role="presentation" class="{{($tab == 'automated-messages') ? 'active' : false}}"><a href="{{ url('admin/members/'.$user->id.'?tab=automated-messages') }}" aria-expanded="true">Automated Messages</a></li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade {{empty($tab) ? 'active in' : false}}" id="tab_content1" aria-labelledby="home-tab">
                                @include('backend.members.sections.shortlist')
                            </div>
                            <div role="tabpanel" class="tab-pane fade {{($tab == 'saved-searches') ? 'active in' : false}}" id="tab_content2" aria-labelledby="profile-tab">
                                @include('backend.members.sections.saved-searches')
                            </div>
                            <div role="tabpanel" class="tab-pane fade {{($tab == 'property-alerts') ? 'active in' : false}}" id="tab_content3" aria-labelledby="profile-tab">
                                @include('backend.members.sections.property-alerts')
                            </div>
                            <div role="tabpanel" class="tab-pane fade {{($tab == 'notes') ? 'active in' : false}}" id="tab_content4" aria-labelledby="section-tab">
                                @include('backend.members.sections.notes')
                            </div>
                            <div role="tabpanel" class="tab-pane fade {{($tab == 'messages') ? 'active in' : false}}" id="tab_content5" aria-labelledby="section-tab">
                                @include('backend.members.sections.messages')
                            </div>
                            <div role="tabpanel" class="tab-pane fade {{($tab == 'automated-messages') ? 'active in' : false}}" id="tab_content6" aria-labelledby="section-tab">
                                @include('backend.members.sections.automated-messages')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection