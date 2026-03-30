@php
// Ensure $template is not empty
$template = !empty($template) ? $template : 1;
$in = post_criteria($criteria, 'in');

$curr = get_current_currency();
$curr = (!empty($curr)) ? $curr : 'AED';

$prices = [];
$prices["0-5000000"] = "UPTO 5,000,000";
$prices["5000000-20000000"] = "5,000,000 - 20,000,000";
$prices["20000000-50000000"] = "20,000,000 - 50,000,000";
$prices["50000000-100000000"] = "50,000,000 - 100,000,000";

$prices["100000000-9999999999"] = "100,000,000 +";

@endphp

@push('meta')
    <meta name="google-site-verification" content="osZvETYfnZYt4pNjgt4qULeMYCc74I1NuB8n_ojrFrA" />
@endpush

@push('body_class') profile-page @endpush

<?php
$banner = !empty($page->photo) ? asset('storage/' . $page->photo) : false;
$banner_style = !empty($banner) ? 'style="background-image:url(' . $banner . ')"' : false;
?>

@extends('frontend.demo1.layouts.frontend')


@section('main_content')
<style>
    .whatsapp-btn {
        background-color: #68c665;
        color: white;
        font-weight: 500;
        font-family: 'Work Sans', sans-serif;
        border-radius: 0;
        padding: 12px 15px;
        transition: border-color 0.3s;
        border-color: #68c665;
    }

    @media screen and (max-width: 768px) {
        .whatsapp-btn{
            width: 100%;
        }
    }

    .navtabtitle{
        color: #d9b483!important;
        font-weight: 600;
    }

    .whatsapp-btn:hover {
        background-color: white;
        color: #68c665;
        border-color: #68c665;
    }

    .show-grid [class^=col-] span,
        .container .show-grid [class^=col-] {
        display: block;
        padding-top: 10px;
        padding-bottom: 10px;
        text-align: center;
        border: 1px solid #ddd;
        border: 1px solid rgba(86,61,124,.2);
    }

    .dropdown-item.active, .dropdown-item:active{
        background-color: #d9b483;
        color: #fff!important;
    }


    .shortpara {
        max-height: 135px;        /* collapsed height */
        overflow: hidden;
        transition: max-height 0.4s ease;
        text-align: justify;
        hyphens: auto;
        word-break: normal;
        overflow-wrap: break-word;
    }

    .shortpara.expanded {
        max-height: 5000px;       /* large enough to show full content */
    }

    .read-more {
        color: #d9b483;
        font-weight: 600;
        cursor: pointer;
        display: inline-block;
        margin-top: 5px;
    }

    .textpagecolor{
        color: #d9b483;
    }

</style>
<div class="container my-4">
    <!-- Agent Card -->
    <div class="card shadow rounded-3 mb-4">
        <div class="card-body p-4 d-md-flex align-items-center">
            <div class="d-flex justify-content-center mb-4 mb-md-0">
                <img src="{{$page->team_member_photo ? asset('/storage/'.$page->team_member_photo) : 'https://placehold.co/400'}}" class="rounded-circle me-md-4" width="200" height="200">
            </div>
            <div class="flex-grow-1 text-center text-md-start d-flex flex-column gap-2">
                @if(isset($page->team_member_name))<h3 class="mb-1 fw-bold">{{ $page->team_member_name ?? ''}}</h3>@endif
                @if(isset($page->team_member_role))<p class="mb-1">{{ $page->team_member_role ?? ''}}</p>@endif
                @if(isset($page->team_member_broker_licence))
                    <div class="d-flex gap-2">
                        <div>Dubai Broker License (BRN):</div>
                        {{ $page->team_member_broker_licence ?? '--'}}
                    </div>
                @endif
                @if(isset($page->team_member_languages) || isset($page->team_member_experience))<p class="text-muted mb-1">{{ $page->team_member_languages ?? ''}} @if(isset($page->team_member_languages) && isset($page->team_member_experience)),@endif {{ $page->team_member_experience ?? ''}}</p>@endif
                <!-- Broker -->
                <div class="d-flex justify-content-center justify-content-md-start gap-2">
                    <a href="https://api.whatsapp.com/send?phone={{$page->team_member_phone}}" class="align-items-center btn d-flex flex-1 gap-1 justify-content-center whatsapp-btn"> <span class="d-md-block d-none"><i class="fab align-content-around fa-whatsapp"></i></span> Whatsapp</a>
                    <a href="https://terezaestates.com/property-for-sale" class="-secondary align-items-center button d-flex flex-1 gap-1 justify-content-center">
                        <span class="d-md-block d-none">View</span>Properties
                    </a>
                </div>
            </div>

        </div>
        @if($counts['sale'] > 0 && $counts['rent'] > 0 && $counts['sold'] > 0)
        <div class="overflow-hidden container">
            <div class="border-top justify-content-around row text-center">
                @if(isset($counts['sale']))
                <div class="col-lg-2 col-sm-4 col-6 p-3">
                    <a class="text-decoration-none" href="https://terezaestates.com/property-for-sale">
                        <h5 class="fw-bold mb-0">{{$counts['sale']}}</h5>
                    </a>
                    <small class="text-muted">For Sale</small>
                </div>
                @endif
                @if(isset($counts['rent']))
                <div class="col-lg-2 col-sm-4 border-x col-6 p-3">
                    <a class="text-decoration-none" href="https://terezaestates.com/property-for-rent">
                        <h5 class="fw-bold mb-0">{{$counts['rent']}}</h5>
                    </a>
                    <small class="text-muted">For Rent</small>
                </div>
                @endif
                @if(isset($counts['sold']))
                <div class="col-lg-2 col-sm-4 col-6 p-3">
                    <h5 class="fw-bold mb-0 textpagecolor">{{$counts['sold']}}</h5>
                    <small class="text-muted">Closed Deals</small>
                </div>
                @endif
                @if($counts['CloseSaleDeal'] > 0 || $counts['CloseRentDeal'] > 0)
                <div class="col-lg-2 col-sm-4 col-6 p-3">
                    <div class="fw-bold textpagecolor text-nowrap">
                        @if($counts['CloseSaleDeal'] > 0)<span>{{$counts['CloseSaleDeal']}} {{$counts['CloseSaleDeal'] > 1 ? 'Sales' : 'Sale'}}</span>@endif
                        @if($counts['CloseSaleDeal'] > 0 && $counts['CloseRentDeal'] > 0)/@endif
                        @if($counts['CloseRentDeal'] > 0)<span>{{$counts['CloseRentDeal']}} {{$counts['CloseRentDeal'] > 1 ? 'Rentals' : 'Rental'}}</span>@endif
                    </div>
                    <small class="text-muted">Deal Type</small>
                </div>
                @endif
                @if($counts['total_deals'] != 'AED 0' && $counts['total_deals'] != '0')
                    <div class="col-lg-2 col-sm-4 col-6 p-3">
                        <h5 class="fw-bold mb-0 textpagecolor">{{$page->team_member_setting['total_deals_visibility'] == 'yes' ? $counts['total_deals'] : 'Confidential'}}</h5>
                        <small class="text-muted">Total Deals Value</small>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Track Record -->
    @if($selllist->count() > 0)
    <div class="card shadow rounded-3 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="fw-bold mb-0">Track Record</h4>
                <span class="text-muted small d-none">Last 12 months</span>
            </div>
            <div class="table-responsive d-md-block d-none overflow-auto">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 text-nowrap">Project Name</th>
                            <th class="py-3 text-nowrap">Deal Type</th>
                            <th class="py-3 text-nowrap">Date</th>
                            <th class="py-3 text-nowrap">Property Type</th>
                            <th class="py-3 text-nowrap">Bedrooms</th>
                            <th class="py-3 text-nowrap">Deal Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($selllist as $single)
                        <tr>
                            <td>
                                <p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="15px" height="15px"><!--!Font Awesome Free v7.1.0 by @fontawesome  - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                        <path d="M128 252.6C128 148.4 214 64 320 64C426 64 512 148.4 512 252.6C512 371.9 391.8 514.9 341.6 569.4C329.8 582.2 310.1 582.2 298.3 569.4C248.1 514.9 127.9 371.9 127.9 252.6zM320 320C355.3 320 384 291.3 384 256C384 220.7 355.3 192 320 192C284.7 192 256 220.7 256 256C256 291.3 284.7 320 320 320z"></path>
                                    </svg>
                                    {{$single->property->town}}
                                </p>
                                {{ $single->property->complex_name ?? '--' }}
                            </td>
                            <td>{{$single->property->is_rental == 0 ? 'Sale' : 'Rent'}}</td>
                            <td>{{date('M-Y',strtotime($single->property_provide_date))}}</td>
                            <td>{{$single->property->type->name ?? '--'}}</td>
                            <td>{{$single->property->beds ?? '--'}} {{$single->property->beds > 1 ? 'Beds' : 'Bed'}}</td>
                            <td>
                                @if($single->property_status)
                                    {{isset($single->property_status) ? $single->property_status : '--'}}
                                @else
                                    {{$single->convert_value}}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-block d-md-none">

                <!-- Track Record Item -->
                @foreach($selllist as $single)
                    <div class="card border shadow-sm mb-3 rounded-4 p-3">
                        <span class="position-absolute top-0 end-0 m-1 badge text-secondary border border-secondary bg-white ms-3 rounded-pill px-3 py-1">
                            {{$single->property->is_rental == 0 ? 'Sale' : 'Rent'}}
                        </span>
                        <span class="position-absolute top-0 start-0 m-1 badge text-white rounded-pill px-3 py-1" style="background-color: #d9b483; color:#fff;">
                            <i class="bi bi-building"></i> {{$single->property->type->name ?? '--'}}
                        </span>
                        <div class="mt-4">
                            @if(isset($single->property->town))
                            <span class="d-flex align-items-baseline gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="15px" height="15px"><!--!Font Awesome Free v7.1.0 by @fontawesome  - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                    <path d="M128 252.6C128 148.4 214 64 320 64C426 64 512 148.4 512 252.6C512 371.9 391.8 514.9 341.6 569.4C329.8 582.2 310.1 582.2 298.3 569.4C248.1 514.9 127.9 371.9 127.9 252.6zM320 320C355.3 320 384 291.3 384 256C384 220.7 355.3 192 320 192C284.7 192 256 220.7 256 256C256 291.3 284.7 320 320 320z"></path>
                                </svg>
                                {{$single->property->town}}
                            </span>
                            @endif
                            @if(isset($single->property->complex_name))
                            <!-- // complex name -->
                            <p>{{$single->property->complex_name}}</p>
                            @endif
                        </div>

                        <div class="border-top d-flex justify-content-between py-2">
                            <span class="d-flex align-items-center">
                                <i class="bi bi-door-open"></i> {{$single->property->beds ?? '--'}} {{$single->property->beds > 1 ? 'Beds' : 'Bed'}}
                            </span>
                            <span class="fw-semibold">{{date('M-Y',strtotime($single->property_provide_date))}}</span>
                        </div>
                        <div class="border-top d-block justify-content-between pt-2">
                            <div>
                                @if($single->property_status)
                                    {{isset($single->property_status) ? $single->property_status : '--'}}
                                @else
                                    {{$single->convert_value}}
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @if($selllist instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="product-wrp py-0">
                    <div class="pager-bx pb-4 pt-0">
                        {{ $selllist->onEachSide(1)->links('vendor.pagination.style-1') }}
                    </div>
                </div>
            @endif
        </div>
    @endif

    @if(count($communities) > 0)
        <!-- Area of Expertise -->
        <div class="card shadow rounded-3 mb-4">
            <div class="card-body">
                <h4 class="fw-bold mb-3">Areas of Expertise</h4>
                <div class="infinite-text d-md-none text-capitalize text-end text-secondary">
                    Swipe to filter
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                    </svg>
                </div>
                <div class="position-relative hover-arrow">
                    <button id="arrow-left" class="arrowleft rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                        </svg>
                    </button>
                    <div class="py-2 px-0 d-flex flex-nowrap overflow-x-auto gap-2 hide-scrollbar" id="profileTabs">
                        @foreach($communities as $key=>$single)
                        <a href="#AE{{$single->id}}" class="nav-link border text-dark button btn py-1 px-2 btn-sm tagstyle text-nowrap {{$key == 0 ? 'active navtabtitle' : ''}}" data-bs-toggle="tab">{{$single->name}}</a>
                        @endforeach
                    </div>
                    <button id="arrow-right"  class="arrowright rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                        </svg>
                    </button>
                </div>
                <hr class="mt-0">
                <div class="tab-content mt-3">
                    @foreach($communities as $key=>$detail)
                    <div id="AE{{$detail->id}}" class="tab-pane {{$key == 0 ? 'active show' : ''}}">
                        <div class="row">
                            <div class="col-md-4 mb-4 mb-md-0">
                                <div class="ratio ratio-16x9">
                                    <img src="{{$detail->photo ? asset('/storage/'.$detail->photo) : 'https://placehold.co/600x400'}}" class="img-fluid object-size-cover rounded">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h5 class="fw-bold navtabtitle">{{$detail->name}}</h5>
                                <div class="shortpara" id="content-{{ $detail->id }}">
                                    {!! $detail->content !!}
                                </div>

                                <a href="javascript:void(0)" class="read-more" data-target="content-{{ $detail->id }}">
                                    Read more
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if($page->team_member_name)
        <!-- About Me -->
        <div class="card shadow rounded-3 mb-4">
            <div class="card-body">
                <div class="text-center text-sm-start ">
                    <h4 class="fw-bold">About Me</h4>
                    @if(isset($page->team_member_name))
                        <h4 class="order-sm-0">{{ $page->team_member_name }} / <span class="order-sm-1">{!! $page->team_member_role !!}</span></h4>
                    @endif
                </div>
                <div class="team-box">
                    <div class="d-flex flex-column flex-sm-row ">
                        <div class="flex-fill">
                            <div class="team-dts d-flex flex-column" style="text-align: justify;">
                                <div class="text-justify order-sm-2 mb-sm-3 f-13 item">{!! $page->team_member_description !!}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Properties List -->
    @if($properties->count() > 0)
    <div class="card shadow rounded-3 mb-4">
        <div class="card-body">
            <div class="d-none">
                @foreach($properties as $single)
                @php
                $images = $single->propertyMediaPhotos->pluck('path')->toArray(); // if using relationship
                @endphp
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="row g-0">
                        <a href="{{lang_url($single->url)}}" class="col-md-4 position-relative">
                            <span class="property-grid--featured-ribbon position-absolute p-1 mt-2 bg-light-brown text-white">{{p_states()[$single->status]}}</span>
                            <img src="{{ asset('/storage/'.$images[0]) ?? 'https://placehold.co/600x400' }}"
                                class="img-fluid rounded-start h-100 property-img"
                                data-images='@json($images)'
                                alt="Property"
                                style="object-fit: cover;">
                        </a>

                        <div class="col-md-8">
                            <div class="card-body">

                                <h5 class="fw-bold">AED {{ number_format($single->price ?? '--') }}</h5>
                                <p class="mb-1 text-muted">{{$single->name}} | {{$single->complex_name}}</p>

                                <div class="small text-muted mb-2">
                                    <i class="bi bi-geo-alt"></i> {{$single->town}}, {{$single->city}}
                                </div>

                                <div class="d-flex gap-4 small mb-3">
                                    @if($single->internal_area)
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="15px" height="15px"><!--!Font Awesome Free v7.1.0 by @fontawesome  - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                            <path d="M341.8 72.6C329.5 61.2 310.5 61.2 298.3 72.6L74.3 280.6C64.7 289.6 61.5 303.5 66.3 315.7C71.1 327.9 82.8 336 96 336L112 336L112 512C112 547.3 140.7 576 176 576L464 576C499.3 576 528 547.3 528 512L528 336L544 336C557.2 336 569 327.9 573.8 315.7C578.6 303.5 575.4 289.5 565.8 280.6L341.8 72.6zM304 384L336 384C362.5 384 384 405.5 384 432L384 528L256 528L256 432C256 405.5 277.5 384 304 384z"></path>
                                        </svg>
                                        {{$single->internal_area}} Sqft
                                    </span>
                                    @endif
                                    @if($single->terrace_area)
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="15px" height="15px"><!--!Font Awesome Free v7.1.0 by @fontawesome  - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                            <path d="M561.5 405.1C555.6 421.8 536.2 428.1 520.4 420.2L342.2 331.1L340.6 334.3L251.8 512L544 512C561.7 512 576 526.3 576 544C576 561.7 561.7 576 544 576L96 576C78.3 576 64 561.7 64 544C64 526.3 78.3 512 96 512L180.2 512L283.4 305.7L285 302.5L119.6 219.8C103.8 211.9 97.2 192.5 107.1 177.8C153 109.2 231.2 64 320 64C461.4 64 576 178.6 576 320C576 349.8 570.9 378.5 561.5 405.1z"></path>
                                        </svg>
                                        {{$single->terrace_area}} Sqft
                                    </span>
                                    @endif
                                    @if($single->land_area)
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="15px" height="15px"><!--!Font Awesome Free v7.1.0 by @fontawesome  - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                            <path d="M96 96C113.7 96 128 110.3 128 128L128 464C128 472.8 135.2 480 144 480L544 480C561.7 480 576 494.3 576 512C576 529.7 561.7 544 544 544L144 544C99.8 544 64 508.2 64 464L64 128C64 110.3 78.3 96 96 96zM304 160C310.7 160 317.1 162.8 321.7 167.8L392.8 245.3L439 199C448.4 189.6 463.6 189.6 472.9 199L536.9 263C541.4 267.5 543.9 273.6 543.9 280L543.9 392C543.9 405.3 533.2 416 519.9 416L215.9 416C202.6 416 191.9 405.3 191.9 392L191.9 280C191.9 274 194.2 268.2 198.2 263.8L286.2 167.8C290.7 162.8 297.2 160 303.9 160z"></path>
                                        </svg>
                                        {{$single->land_area}} Sqft
                                    </span>
                                    @endif
                                    <span><i class="bi bi-door-open"></i> {{$single->beds}} Beds</span>
                                    <span><i class="bi bi-badge-hd"></i> {{$single->baths}} Baths</span>
                                </div>

                                <div class="d-flex justify-content-center justify-content-md-start gap-2">
                                    @if( !empty(setting('telephone')) )
                                    <a href="tel:{{ setting('telephone') }}" class="d-flex gap-2 text-decoration-none button -secondary f-14 f-sm-12" target="_blank">
                                        <i class="fa align-content-around fa-phone"> </i> <span class="d-none d-lg-block">Call</span>
                                    </a>
                                    @endif
                                    @if( !empty(setting('email')) )
                                    <button onclick='agentEmail({{$single}})' class="d-flex gap-2 text-decoration-none button -default f-14 f-sm-12">
                                        <i class="far align-content-around fa-envelope"></i> <span class="d-none d-lg-block">Email</span>
                                    </button>
                                    @endif
                                    @if( !empty(setting('whatsapp_url')) )
                                    <button onclick='agentwhatsapp({{$single}})' type="button" class="d-flex gap-2 text-decoration-none button -default f-14 f-sm-12">
                                        <i class="fab align-content-around fa-whatsapp"></i> <span class="d-none d-lg-block">Whatsapp</span>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <section class="blog-wrapper py-0 product-wrp u-circle-style-1">
                <div class="u-circle-style-2">
                    <div class="products-filter-wrp">
                        <div class="col-md-12 col-sm-12">
                            <div class="d-flex justify-content-between">
                                <div class="">
                                    <h4 class="fw-bold mb-4">Property For Sale</h4>
                                </div>
                                <div class="prod-sort-bx">
                                    <ul>
                                        <li class="">
                                            @if (!empty($properties->total()))
                                            <div class="search-form-home--item">
                                                <form action="{{url('/WorkingPage/profile')}}" id="sortform" method="Post">
                                                    @csrf
                                                    <select name="sortvalue" onchange="shortP()" class="-currency border p-1">

                                                        <option value="">SORT BY</option>

                                                        <option {{ set_selected('most-recent', post_criteria($criteria, 'most-recent')) }} value="most-recent">Most Recent</option>

                                                        <option {{ set_selected('lowest-price', post_criteria($criteria, 'lowest-price')) }} value="lowest-price">Lowest Price</option>

                                                        <option {{ set_selected('highest-price', post_criteria($criteria, 'highest-price')) }} value="highest-price">Highest Price</option>

                                                        <option {{ set_selected('name', post_criteria($criteria, 'name')) }} value="name">Name A-Z</option>

                                                    </select>
                                                </form>
                                            </div>
                                            @endif
                                        </li>
                                        <li class="-currency">
                                            @php $all_currencies = all_currencies(); @endphp
                                            <div class="search-form-home--item -country">
                                                <a class="nav-link dropdown-toggle border px-2" href="#" id="navbarDropdownCurrency" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-current="{{ !empty($all_currencies[get_current_currency()])? get_current_currency() :'Currency' }}">
                                                    <!-- GBP (£)  -->
                                                    {{ !empty($all_currencies[get_current_currency()])? get_current_currency() :'Currency' }}
                                                    <span class="pw-nav-hover"><span class="pw-nav-hover-inner"></span></span>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="navbarDropdownCurrency">
                                                    @foreach($all_currencies as $currency => $symbol)
                                                        @if( get_current_currency() != $currency)
                                                            <a href="{{lang_url('properties/set/currency/'.$currency)}}" rel="nofollow" class="dropdown-item hover-tick">
                                                                {{$currency}}
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="property-grid--style-1">
                            <div class="u-mw-md-400-c">
                                <div class="row">
                                    @if(count($properties)>0)
                                    @foreach ($properties as $property)
                                    <div class="col-md-6 col-lg-4 h-100">
                                        <div class="mb-4">
                                            @include('frontend.demo1.partials.front.properties.property-grid-style-1')
                                        </div>
                                    </div>
                                    @endforeach

                                    {{-- Use total() method correctly for pagination --}}
                                    @if($properties instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    <div class="pager-bx">
                                        {{ $properties->onEachSide(1)->links('vendor.pagination.style-1') }}
                                    </div>
                                    @endif
                                    @else
                                    <p class="text-center">No Results Found</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @endif
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.read-more').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const target = document.getElementById(this.dataset.target);

            target.classList.toggle('expanded');

            this.textContent = target.classList.contains('expanded')
                ? 'Read less'
                : 'Read more';
        });
    });
});
</script>
<script>
    function agentwhatsapp(e) {
        var phonenumber = "+971585365111";

        // Fetch current URL
        var currentURL = window.location.href;

        // Construct and open WhatsApp message
        var message = encodeURIComponent(
            `Hello Tereza Estates! I would like to get more information about this property I have seen on Your website:\n\n` +
            `*Reference ID:* ${e.ref}\n` +
            `*Price:* ${e.price}\n` +
            `*Location:* ${e.add_info}\n\n` +
            `*Link:* ${currentURL}\n\n` +
            `Any changes made to this WhatsApp inquiry will result in the inquiry not being sent.`
        );

        var url = `https://wa.me/${phonenumber}?text=${message}`;

        window.open(url, '_blank').focus();
    }

    function shortP(e){
        document.getElementById('sortform').submit();
    }

    function agentEmail(e) {
        const email = "hello@terezaestates.com";
        const subject = `Enquiry Request for ${e.name +' | '+ e.street +' | '+ e.town +' | '+e.city}`;
        const body = encodeURIComponent(`
            Hello,

            I hope you are doing well.

            I am interested in receiving more details about the following property:

            ------------------------------------------------------------
            Property Details
            ------------------------------------------------------------
            Price:       AED ${e.price ?? '--'}
            Title:       ${e.name ?? '--'}
            Location:    ${e.add_info ?? '--'}
            Bedrooms:    ${e.beds ?? '--'} Beds
            Bathrooms:   ${e.baths ?? '--'} Baths
            ------------------------------------------------------------

            Could you please provide the following information:
            - Availability status
            - Payment plan or financing options
            - Viewing appointment availability

            I look forward to your response.

            Thank you.
        `);

        const mailtoLink = `mailto:${email}?subject=${subject}&body=${body}`;

        window.location.href = mailtoLink;
    }

    document.querySelectorAll('.property-img').forEach(img => {
        let images = JSON.parse(img.dataset.images);
        let index = 0;
        let interval;

        img.addEventListener('mouseenter', () => {
            if (images.length <= 1) return;

            interval = setInterval(() => {
                index = (index + 1) % images.length;
                img.src = '/storage/' + images[index];
            }, 1000); // change every 1 sec
        });

        img.addEventListener('mouseleave', () => {
            clearInterval(interval);
            index = 0;
            img.src = '/storage/' + images[0]; // reset to first image
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const tabLinks = document.querySelectorAll('#profileTabs .nav-link');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            // Remove active state from all tabs
            tabLinks.forEach(l => l.classList.remove('active', 'navtabtitle'));
            tabPanes.forEach(p => p.classList.remove('active', 'show'));

            // Activate clicked tab
            this.classList.add('active', 'navtabtitle');

            const target = this.getAttribute('href');
            document.querySelector(target).classList.add('active', 'show');
        });
    });

    // const paralist = document.querySelectorAll('.shortpara');

    // paralist.forEach((p)=>{
    //     const children = p.children;

    //     for (let i = 1; i < children.length; i++) {
    //         children[i].classList.add('d-none');
    //     }
    // })
});
</script>
@endsection