@extends('frontend.demo1.layouts.frontend')
@section('main_content')
    <section>
        <div class="container">
            @if (session('message_success'))
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-success">
                                {{ session('message_success') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col">
                    <h1 class="f-22 u-mb05">{{ greet() }}, {{ get_name('firstname') }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="u-mb1">Your Details</h5>
                                    <p><strong>Name: </strong>{{ get_name('') }}</p>
                                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                                </div><!-- /.col -->
                                <div class="col">
                                    <div class="d-flex justify-content-center align-self-center">
                                        <a class="button -primary" href="#">Edit Details</a>
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </div><!-- /.col -->
            </div><!-- /.row -->
            <div class="row u-mt1">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="u-mb1">Saved Searches</h5>
                            @if($searches->count() > 0)
                                <ul class="list-group">
                                    @foreach($searches as $search)
                                        <li id="save-search-{{ $search->saved_search_id }}" class="list-group-item f-13">
                                            <div class="float-right u-mr-2">
                                                <a class="c-primary modal-toggle" href="#"
                                                   data-item-id="{{ $search->saved_search_id }}"
                                                   data-toggle="modal"
                                                   data-modal-type="delete"
                                                   data-modal-title="Delete Saved Search"
                                                   data-modal-size="small"
                                                   data-delete-type="save-search"
                                                   data-target="#global-modal">
                                                    <i class="fas fa-times-circle"></i>
                                                </a>
                                            </div>
                                            <p class="text-truncate u-block u-mb0 u-mr2">
                                                <a class="c-dark" href="{{ url($search->saved_search_url) }}">{{ ucfirst($search->searchHeadline) }}</a>
                                            </p>
                                            <p><small class="text-muted">Created : {{ Carbon\Carbon::parse($search->created_at)->diffForHumans()}}</small></p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <a class="c-dark" href="{{ url('/property-for-sale') }}">Search for properties</a>
                            @endif
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                    <div class="card u-mt1">
                        <div class="card-body">
                            <h5 class="u-mb1">Property Alerts</h5>
                            @if($alerts->count() > 0)
                            <ul class="list-group">
                                @foreach($alerts as $alert)
                                    <li id="alert-{{ $alert->id }}" class="list-group-item f-13">
                                        <div class="float-right u-mr-2">
                                            <a class="c-primary modal-toggle" href="#"
                                               data-item-id="{{ $alert->id }}"
                                               data-toggle="modal"
                                               data-modal-type="delete"
                                               data-modal-title="Delete Property Alert"
                                               data-modal-size="small"
                                               data-delete-type="alert"
                                               data-target="#global-modal">
                                                <i class="fas fa-times-circle"></i>
                                            </a>
                                        </div>
                                        <p class="text-truncate u-block u-mb0 u-mr2" title="{{ $alert->detailsHeadline }}">{{ $alert->detailsheadline }}</p>
                                        <p><small class="text-muted">Created : {{ Carbon\Carbon::parse($alert->created_at)->diffForHumans()}}</small></p>
                                    </li>
                                @endforeach
                            </ul>
                                @else
                            <p>You have no Property Alerts</p>
                            @endif
                            <div class="text-center u-mt2">
                                <a class="button -primary u-inline-block" href="#" data-toggle="modal" data-target="#property-alert-modal">Create new alert</a>
                            </div>
                        </div>
                    </div>
                </div><!-- /.col -->
                <div class="col-12 col-md-6">
                    <div class="card" id="shortlist">
                        <div class="card-body">
                            <h5 class="u-mb1">Your Shortlist</h5>
                            @if($shortlist->count() > 0)
                                @foreach($shortlist as $shortlist)
                                    <div id="p-{{ $shortlist->property->id }}" class="card mb-3">
                                        <div class="row no-gutters">
                                            <div class="col-md-6">
                                                @foreach( $shortlist->property->propertyMediaPhotos->take(1) as $media )
                                                    <a href="{{ $shortlist->property->url }}">
                                                        <img src="{{ $media->photo_display }}" class="card-img h-100" alt="{{ $shortlist->property->search_headline }}">
                                                    </a>
                                                @endforeach
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card-body">
                                                    <a href="{{ $shortlist->property->url }}"><h5 class="c-primary u-mb0">{{ $shortlist->property->search_headline }}</h5></a>
                                                    <p class="f-12 u-mb0"><strong>{!! $shortlist->property->display_price !!}</strong></p>
                                                    <p class="f-12">{{ $shortlist->property->DisplayPropertyAddress }}</p>
                                                    <p><small class="text-muted">Added : {{ Carbon\Carbon::parse($shortlist->created_at)->diffForHumans()}}</small></p>
                                                    <a href="#"
                                                       class="button -shortlist f-two f-14 f-semibold u-inline-block u-block-mobile shortlist shortlist-add {{ ($shortlist->property->CheckShortlistIp) ? 'shortlist-confirm-action' : '' }}"
                                                       data-url="{{ url('shortlist/ajax/add') }}"
                                                       data-property-id="{{ $shortlist->property->id }}"
                                                       data-save-text="&nbsp;SAVE&nbsp;"
                                                       data-remove-text="&nbsp;REMOVE&nbsp;"

                                                    > <i class="fas fa-times-circle"></i> <span class="shortlist-text-label">&nbsp; REMOVE &nbsp;</span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>Your shortlist is empty</p>
                            @endif
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                    <div class="card u-mt1">
                        <div class="card-body">
                            <h5 class="u-mb1">Your Notes</h5>
                        </div>
                    </div>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container -->
    </section>
@endsection
