@extends('backend.layouts.master')

@section('admin-content')

<div class="row current_url" data-url="{{admin_url('market-valuation')}}">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="x_panel pw">
            <div class="x_title">
                <ul class="nav navbar-right panel_toolbox">
                    <li class="top-button"><a href="{{ url('valuation-report/'.$data->uuid.'') }}" target="_blank" class="btn btn-small btn-primary">View</a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="x_content">
            <div class="x_panel pw-inner-tabs">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="{{(empty($tab)) ? 'active' : false}}"><a href="{{ admin_url('market-valuation/'.$data->client_valuation_id.'/edit') }}" id="details-tab" aria-expanded="true">Details</a></li>
                        <li role="presentation" class="{{($tab == 'emails') ? 'active' : false}}"><a href="{{ admin_url('market-valuation/'.$data->client_valuation_id.'/edit?tab=emails') }}" aria-expanded="false">Emails</a></li>
                        <li role="presentation" class="{{($tab == 'notes') ? 'active' : false}}"><a href="{{ admin_url('market-valuation/'.$data->client_valuation_id.'/edit?tab=notes') }}" aria-expanded="false">Notes</a></li>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade {{empty($tab) ? 'active in' : false}}" id="tab_content1" aria-labelledby="home-tab">
                            @include('backend.client_valuations.edit-details')
                        </div>
                        <div id="tab_content2" role="tabpanel" class="tab-pane fade {{($tab == 'emails') ? 'active in' : false}}">
                            @include('backend.client_valuations.emails')
                        </div>
                        <div id="tab_content3" role="tabpanel" class="tab-pane fade {{($tab == 'notes') ? 'active in' : false}}">
                            @include('backend.client_valuations.notes')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection