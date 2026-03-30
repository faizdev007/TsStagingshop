@extends('frontend.demo1.layouts.frontend')
@push('body_class')valuation-report @endpush
@section('main_content')
    <section class="top-page-section u-pt1 u-pb1 c-bg-primary u-mobile-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="c-white f-42 u-block u-mt1 u-mb1"><span class="f-light">Your</span> <strong>Valuation Report</strong></h1>
                </div>
            </div>
        </div>
    </section>
    <section class="c-bg-secondary u-mobile-center market-price">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="market-price__inner">
                        <h3 class="c-white f-light f-32 u-block u-mb0">Market Price</h3>
                        <span class="c-white f-black f-44 u-block"><?php $price_value = sprintf('%s%s', settings('currency_symbol'), number_format($data->client_valuation_price)); echo $price_value; ?></span>
                        <p class="f-18 u-block c-white f-light recommended">
                            @if($data->client_valuation_status == 'pending')
                                <strong>{{ $data->client_valuation_price_advice }}</strong>
                            @else
                                <strong>Congratulations, {{ settings('site_name') }} are now marketing your property</strong>
                            @endif
                        </p>
                        <p class="f-18 u-block c-white f-light response-message" style="display: none;"></p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <a class="button -tertiary -large text-uppercase f-18 text-uppercase list-property @if($data->client_valuation_status == 'instructed') -no-hover @endif" href="#" @if($data->client_valuation_status == 'pending') data-target="#list-property-modal" data-toggle="modal" @endif><i class="fas fa-check u-inline-block"></i> <span class="f-black">@if($data->client_valuation_status == 'pending') List My Property @else Accepted @endif</span></a>
                </div>
            </div>
        </div>
    </section>
    <section>
        <input type="hidden" class="client_valuation_id" value="{{ $data->client_valuation_id }}">
        <div class="container">
            <div class="row u-mt2">
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <h3 class="f-semibold c-secondary">Your Details</h3>
                    <div class="u-mt2 u-mb2">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                <strong>Full Name</strong>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-8">
                                <span class="f-light">{{ $data->client->client_name }}</span>
                            </div>
                        </div>
                        <div class="row u-mt1">
                            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                <strong>Email Address</strong>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-8">
                                <span class="f-light">{{ $data->client->client_email }}</span>
                            </div>
                        </div>
                        <div class="row u-mt1">
                            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                <strong>Full Address</strong>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-8 col-lg-8">
                                <p class="f-light">
                                    {{ $data->client_valuation_street }} @if($data->client_valuation_town)<br /> {{ $data->client_valuation_town }} @endif @if($data->client_valuation_city) <br /> {{ $data->client_valuation_city }} @endif <br /> {{ $data->client_valuation_postcode }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                    <h3 class="f-semibold c-secondary">Valuation Details</h3>
                    <div class="u-mt2 u-mb2">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                <strong>Date</strong>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-8">
                                <span class="f-light">{{ date("jS F Y", strtotime($data->client_valuation_date)) }}</span>
                            </div>
                        </div>
                        <div class="row u-mt1">
                            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                <strong>Bedrooms</strong>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-8">
                                <span class="f-light">{{ $data->client_valuation_beds }}</span>
                            </div>
                        </div>
                        <div class="row u-mt1">
                            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                <strong>Bathrooms</strong>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-8 col-lg-8">
                                <p class="f-light">
                                    <span class="f-light">{{ $data->client_valuation_baths }}</span>
                                </p>
                            </div>
                        </div>
                        @if($data->property_type->count() > 0)
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                    <strong>Property Type</strong>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-8 col-lg-8">
                                    <p class="f-light">
                                        <span class="f-light">{{ $data->property_type->name }}</span>
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($data->client_valuation_map == 'y')
        <section>
            <div id="c-map" class="map-object valuation-map u-fullwidth"
                 data-zoom="14"
                 data-lat="{{ $data->client_valuation_latitude }}"
                 data-lng="{{ $data->client_valuation_longitude }}"
                 style="height: 300px;"
            ></div>
        </section>
    @endif

    @if($data->client_valuation_location_info)
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <h3 class="f-semibold c-secondary u-block u-mb1">Local Information</h3>
                        {!! $data->client_valuation_location_info !!}
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if($data->client_valuation_property_description)
        <section>
            <div class="container">
                <div class="row @if($data->client_valuation_location_info) u-mt2 @endif">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <h3 class="f-semibold c-secondary u-block u-mb1">Property Description</h3>
                        {!! $data->client_valuation_property_description !!}
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if($why_items->count() > 0)
        <section class="c-bg-primary u-mobile-center">
            <div class="container">
                <div class="row u-mt2 u-mb2">
                    <div class="col">
                        <h3 class="c-white f-34 f-semibold u-block u-mb1">Why list your property with us?</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        @foreach($why_items as $item)
                            <div class="row d-flex align-items-start u-mb2">
                                <div class="col-md-12 col-lg-1">
                                    <span class="c-secondary f-48"><i class="{{ $item->icon }}"></i></span>
                                </div>
                                <div class="col-md-12 col-lg-11">
                                    <div class="u-mb2">
                                        <span class="c-white f-18 f-bold u-block u-mb05">{{ $item->title }}</span>
                                        <p class="c-white f-15 u-block">{{ $item->content }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if($testimonial->count() > 0 )
        <section class="u-mt2 u-mb2">
            <div class="container">
                <div class="row u-mt2 u-mb2">
                    <div class="col">
                        <div class="text-center">
                            <div class="section-header">
                                <h3 class="c-primary f-bold">What our customers say</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row u-mt2">
                    <div class="u-pl0 u-pr0 col-sm-12 col-lg-4">
                        <img class="h-100 w-100" alt="Testimonial" src="{{ asset('assets/demo1/images/temp/temp-testimonial.jpg') }}">
                    </div><!-- /.col-lg-5 -->
                    <div class="u-pl0 u-pr0 col-sm-12 col-lg-8">
                        <div class="u-pl2 u-pt05 u-pr2 h-100 c-border-gray">
                            <div class="testimonial-quote">
                                <span class="u-pl1 u-block">{!! $testimonial->quote !!}</span>
                                <span class="u-block c-secondary f-bold f-16 u-mt1 u-pl1">{{ $testimonial->name }}</span>
                            </div>
                        </div>
                    </div><!-- /.col-lg-7 -->
                </div>
            </div>
        </section>
    @endif

    <div id="list-property-modal" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="f-light f-22 property-modal-text">I confirm I am instructing {{ settings('site_name') }} to <span class="f-black">market my property</span></p>
                    <div class="u-mt2">
                        <a class="button -green text-uppercase f-bold c-white u-mr1 u-block-mobile u-inline-block u-pt05 u-pb05 accept-list-property" href="#"><i class="fas fa-check u-inline-block"></i> List My Property</a>
                        <a class="button -primary text-uppercase u-pt05 u-pb05 u-block-mobile f-bold c-white" href="#" data-dismiss="modal"><i class="fas fa-times c-red u-inline-block"></i> Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('frontend_css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.css">
    @endpush

    @push('frontend_footer-scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.js"></script>
    @endpush

@endsection