@extends('backend.layouts.master')

@section('admin-content')



<div class="row current_url" data-url="{{admin_url('properties')}}">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <h2>{{$property->ref}} - {{$property->search_headline}}</h2>
                @if($property->status == 0 )
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a href="{{ url($property->url) }}" target="_blank" class="btn btn-large btn-primary">
                            <i class="fa fa-eye"></i>
                            @if(settings('new_developments') && $property->is_development == 'y')
                                View Development
                            @else
                                View Property
                            @endif

                        </a>
                    </li>
                    @if(settings('market_valuation'))
                        <li>
                            @if($property->valuation)
                                <a href="{{ url('valuation-report/'.$property->valuation->uuid.'') }}" target="_blank" class="btn btn-large btn-primary">View Valuation</a>
                            @else
                                @if($property->is_development == 'n')
                                    <a href="{{ admin_url('properties/'.$property->id.'/create-valuation') }}" class="btn btn-large btn-primary">Create Valuation</a>
                                @endif
                            @endif
                        </li>
                    @endif
                    @if(settings('social_sharing'))
                        <li><a class="btn btn-primary social-share" href="#" data-share="facebook" data-url="{{ $property->url }}" data-message="Take a look at this property on our website"><i class="fab fa-facebook-square"></i> Share to Facebook</a></li>
                    @endif
                </ul>
                @endif
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="template-collapse">
                    <a href="#" data-toggle="collapse" data-target=".title-template" class="cta-collapse">Tabs <span class="fa fa-chevron-down"></span></a>
                </div>
                <div class="pw-tabs" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs title-template" role="tablist">
                        <li role="presentation" class="{{ active_class('edit',4) }}">
                            <a href="{{admin_url('properties/'.$property->id.'/edit')}}" class="check-unsave" role="tab" aria-expanded="true">Details</a>
                        </li>
                        @if(settings('new_developments') && $property->is_development == 'y' && 1==0)
                            <li role="presentation" class="{{ active_class('units',4) }}">
                                <a href="{{admin_url('properties/'.$property->id.'/units')}}" class="check-unsave" role="tab" aria-expanded="true">Development Units / Plots</a>
                            </li>
                        @endif
                        <li role="presentation" class="{{ active_class('location',4) }}">
                            <a href="{{admin_url('properties/'.$property->id.'/location')}}" class="check-unsave" role="tab" aria-expanded="true">Location</a>
                        </li>
                        <li role="presentation" class="{{ active_class('photos',4) }}">
                            <a href="{{admin_url('properties/'.$property->id.'/photos')}}" class="check-unsave" role="tab" aria-expanded="false">Photos</a>
                        </li>
                        <li role="presentation" class="{{ active_class('floorplans',4) }}">
                            <a href="{{admin_url('properties/'.$property->id.'/floorplans')}}" class="check-unsave" role="tab" aria-expanded="false">Floorplans</a>
                        </li>
                        <li role="presentation" class="{{ active_class('documents',4) }}">
                            <a href="{{admin_url('properties/'.$property->id.'/documents')}}" class="check-unsave" role="tab" aria-expanded="false">Documents</a>
                        </li>
                        <li role="presentation" class="{{ active_class('notes',4) }}">
                            <a href="{{admin_url('properties/'.$property->id.'/notes')}}" class="check-unsave" role="tab" aria-expanded="false">Notes</a>
                        </li>
                        <li role="presentation" class="{{ active_class('leads',4) }} {{ active_class('response',4) }}">
                            <a href="{{admin_url('properties/'.$property->id.'/leads')}}" class="check-unsave" role="tab" aria-expanded="false">
                                Leads
                                {!! count($property->propertyEnquiries) ? '<span class="counter">'.count($property->propertyEnquiries).'</span>' : '' !!}
                            </a>
                        </li>
                        <li role="presentation" class="{{ active_class('stats',4) }}">
                            <a href="{{admin_url('properties/'.$property->id.'/stats')}}" class="check-unsave" role="tab" aria-expanded="false">Stats</a>
                        </li>
                        <li role="presentation" class="{{ active_class('history',4) }}">
                            <a href="{{admin_url('properties/'.$property->id.'/history')}}" class="check-unsave" role="tab" aria-expanded="false">History</a>
                        </li>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in"  aria-labelledby="property-tab">
                            @yield('property-content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
