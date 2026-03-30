@extends('backend.layouts.master')

@section('admin-content')
<style>
    .card {
        border-radius: 8px;
        border: 1px solid #ddd;
        background: #fff;
        padding: 8px;
        margin: 10px auto;
        color:#000;
        width: 100%;
    }

    .card img {
        border-radius: 5px;
        width: fit-content;
        height: fit-content;
        object-fit: cover;
        aspect-ratio: 2 / 1.3;
        border: 1px solid #c8c8c8;
    }

    .ellipsis-one-line {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        align-items: anchor-center;
    }

    .property-thumb {
        position: relative;
        display: block;
    }

    .statustag{
        position: absolute;
        top: 0;
        background-color: #d9b382;
        color:#fff;
        padding: 4px;
        margin: 10px 0;
    }

    .claiminput{
        display: flex;
        position: absolute;
        top: 0;
        right: 0;
        background-color: #d9b382;
        color: #fff;
        padding: 0px 5px;
        margin: 0px;
        border-radius: 0px 6px 0px 4px;
        height: 30px;
        align-items: anchor-center;
        gap: 5px;
    }

    .price-overlay {
        position: absolute;
        bottom: 0;
        margin: 0;
        font-weight: 700;
        padding: 5px;
        background: rgba(255, 255, 255, 0.73);
        width: 100%;
        color: #000;
    }

    .note-display {
        display: none;
        font-weight: normal;
        margin-top: 5px;
    }

    .property-thumb:hover .note-display {
        display: block;
    }

    .note-display {
        opacity: 0;
        max-height: 0;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .property-thumb:hover .note-display {
        opacity: 1;
        max-height: 200px;
    }

    .modal-body::-webkit-scrollbar {
        display: none;
    }

    .modal-body {
        scrollbar-width: none;
    }

    /* Container styles */
    .tooltip2 {
        position: relative;
        display: flex; /* allows other elements to flow naturally around it */
        cursor: default; /* changes cursor to a standard arrow */
    }

    .tooltip3{
        position: relative;
        display: flex; /* allows other elements to flow naturally around it */
        cursor: default; /* changes cursor to a standard arrow */
    }

    /* Hidden text styles */
    .tooltiptext {
        visibility: hidden; /* keeps element in flow but invisible */
        background-color: black;
        color: #fff;
        text-align: start;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        right: 0px;
        text-wrap: nowrap;
        top: 0px;
        bottom: 0px;
        left: 0px;
        overflow: auto;
    }

    .tooltiptextlist{
        visibility: hidden;
        position: absolute;
        left: 0px;
        right: 0px;
        top: 0px;
        padding: 5px;
        background-color: black;
        z-index: 2;
        color: #fff;
    }

    /* Show the tooltip text when the container is hovered over */
    .tooltip2:hover .tooltiptext{
        visibility: visible;
    }

    .tooltip3:hover .tooltiptextlist{
        visibility: visible;
    }

                /* Chrome, Edge, Safari */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Firefox */
    .hide-scrollbar {
        scrollbar-width: none;
    }

    /* Internet Explorer & old Edge */
    .hide-scrollbar {
        -ms-overflow-style: none;
    }
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="search-form-style-1 x_panel">
           
            <div class="x_content">
                @include('backend.properties_claim.search')
            </div>
        </div>
        <div class="x_panel pw">
            <form action="{{ route('properties.claim.save') }}"
                method="POST">

                @csrf
                <div class="x_title">
                <h2>Property Lists</h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li class="top-button"><button type="submit" class="btn btn-small btn-primary">Save Changes</button></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                {{ $properties->links('pagination::bootstrap-4') }}
                <div class="pw-table">
                    @if(count($properties))
                    <div class="">
                        <div class="" style="margin-bottom:15px;">
                            <div class="btn-group" role="group" style="display: flex;">
                                <button id="gridViewBtn" type="button" class="btn btn-primary btn-sm" title="Grid View">
                                    <i class="fa fa-th" aria-hidden="true"></i>
                                </button>
                                <button id="listViewBtn" type="button" class="btn btn-secondary btn-sm" title="List View">
                                    <i class="fa fa-list" aria-hidden="true"></i>
                                </button>
                                <div class="btn-sm btn-success" style="padding: 3px 7px; border-radius: 0px 4px 4px 0px;">
                                    <input type="checkbox" id="selectallcheckbox"/>
                                    <span><strong>Select All</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="gridView" class="row gap-2"></div>
                    <div id="listView" style="display:none;"></div>
                    {{ $properties->links('pagination::bootstrap-4') }}
                    @else
                        <div class="no-data">
                            No data found.
                        </div>
                    @endif
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('headerscripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('footerscripts')
<script src="{{asset('assets/admin/build/vendors/jquery/jquery.ui.touch-punch.min.js')}}"></script>
<script src="{{asset('assets/admin/build/vendors/jquery/jquery.formatCurrency-1.4.0.min.js')}}"></script>
<script src="{{asset('assets/admin/build/js/price-range.js')}}"></script>
<script src="{{asset('assets/admin/build/js/pw-select2-ajax.js')}}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const gridView = document.getElementById("gridView");
    const listView = document.getElementById("listView");
    const gridBtn = document.getElementById("gridViewBtn");
    const griddata = `@foreach($properties as $key=>$property)
                        @php
                            $currUserClaim = $property->claimedByUsers()
                            ->where('users.id', Auth::id())
                            ->latest('created_at')
                            ->first();
                            $isCurrentUserClaim = $currUserClaim && Auth::id() === $currUserClaim->id && isset($currUserClaim->pivot->property_claim_approved) && $currUserClaim->pivot->property_claim_approved == 0;
                            $count = $property->claimedByUsers()->count();
                        @endphp
                        <div class="">
                            <input type="hidden" value="false" name="claim[{{$property->id}}][claim]"/> <input type="hidden" value="{{isset($currUserClaim->id) ? $currUserClaim->id : Auth::id()}}" name="claim[{{$property->id}}][agent]"/> 
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div style="position: relative;">
                                    <img src="{{ $property->primary_photo }}" class="img-responsive mb-2">
                                    <div class="statustag">
                                        {{ $property->state_display }}
                                    </div>
                                    <div class="claiminput claimcheck">
                                        @if($count > 0)
                                            <div class="tooltip2" data-propertyid="{{$property->id}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                                                </svg>
                                            </div>
                                        @endif
                                        @if(isset($currUserClaim->pivot->property_claim_approved) && $currUserClaim->pivot->property_claim_approved == 0)
                                            <div class="text-nowrap" style="">Approval Pending</div>
                                        @endif
                                        @if($count < 2 && !$isCurrentUserClaim)
                                            <input type="checkbox" name="claim[{{$property->id}}][claim]" value="true" class="form-control checkCP" data-checkbox="inputbox-{{$property->id}}" {{isset($currUserClaim) && $currUserClaim->pivot->property_claim_approved == 0 ? 'checked' : ''}} style="width: 15px; margin: 0px !important;">
                                        @endif
                                    </div>
                                    <div id="inputbox-{{$property->id}}" style="position:absolute; bottom:0px; width:100%; {{isset($currUserClaim) && $currUserClaim->pivot->property_claim_approved == 0 ? 'display:flex;' : 'display:none;'}}">
                                        <input type="text" class="form-control price_input col-6" placeholder="Price Value" value="{{isset($currUserClaim) && $currUserClaim->pivot->property_claim_approved == 0 ? (isset($currUserClaim->pivot->property_status) ? $currUserClaim->pivot->property_status : (isset($currUserClaim->pivot->property_value) ? $currUserClaim->pivot->property_value : '')) : '' }}" name="claim[{{$property->id}}][property_value]"/>
                                        <input type="date" class="form-control price_input col-6" value="{{isset($currUserClaim) && $currUserClaim->pivot->property_claim_approved == 0 && isset($currUserClaim->pivot->property_provide_date) ? date('Y-m-d',strtotime($currUserClaim->pivot->property_provide_date)) : ''}}" name="claim[{{$property->id}}][provide_date]"/>
                                    </div>
                                    <div id="tooltiplist{{$property->id}}" class="tooltiptext hide-scrollbar">
                                        <h5>Claimed by</h5>
                                        <hr style="padding:0px; margin:0px;"/>
                                        @foreach($property->claimedByUsers as $key=>$single)
                                            <div class="text-nowrap" style="display: flex; justify-content: space-between;  margin-top:5px; align-items: center;" title="{{$single->name}} ({{date('M Y',strtotime($single->pivot->property_provide_date))}})">{{$single->name}} ({{date('M Y',strtotime($single->pivot->property_provide_date))}})
                                                @if(Auth::user()->role_id == 1 && isset($single->pivot->property_claim_approved) && $single->pivot->property_claim_approved == 0) 
                                                    <div>
                                                        <a style="flex: inherit; padding:2px 3px;" href="{{ admin_url('approve-claim-property/'.$single->pivot->property_id.'/'.$single->pivot->user_id) }}" onclick="return confirm('Are you sure you want to approve the property claim for {{ $property->ref }} submitted by {{ $single->name }}?')" class="btn btn-small btn-success p-0 m-0">Approve</a>
                                                        <a style="flex: inherit; padding:2px 3px;" href="{{ admin_url('approvel_reject_claim_property/'.$single->pivot->property_id.'/'.$single->pivot->user_id) }}" onclick="return confirm('Are you sure you want to reject the property claim for {{ $property->ref }} submitted by {{ $single->name }}?')" class="btn btn-small btn-danger p-0 m-0">Reject</a>
                                                    </div>
                                                @elseif(Auth::user()->role_id == 1 && isset($single->pivot->property_claim_approved) && $single->pivot->property_claim_approved == 1)
                                                    <div>
                                                        <a style="flex: inherit; padding:2px 3px;" href="{{ admin_url('revoke_claim_property/'.$single->pivot->property_id.'/'.$single->pivot->user_id.'/'.$single->pivot->property_provide_date) }}" onclick="return confirm('Are you sure you want to revoke the property claim for {{ $property->ref }} submitted by {{ $single->name }}({{date('M Y',strtotime($single->pivot->property_provide_date))}})?')" class="btn btn-small btn-danger p-0 m-0">Revoke</a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div style="display: flex; margin:10px 0; gap:9px; font-weight:600;" title="{{ $property->search_headline }}">
                                    @if($property->status !== -1)
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10" fill="#16a34a"/>
                                            </svg>
                                        </div>
                                    @else
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10" fill="#bf1111ff"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="ellipsis-one-line">
                                        {{ $property->search_headline }}
                                    </div>
                                </div>
                                <div class="ellipsis-one-line" style="display: flex; margin-bottom:10px; flex-wrap: nowrap; gap: 8px;" title="{{ $property->complex_name }}, {{ $property->town }}, {{ $property->city }}">
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="15px" height="15px"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M128 252.6C128 148.4 214 64 320 64C426 64 512 148.4 512 252.6C512 371.9 391.8 514.9 341.6 569.4C329.8 582.2 310.1 582.2 298.3 569.4C248.1 514.9 127.9 371.9 127.9 252.6zM320 320C355.3 320 384 291.3 384 256C384 220.7 355.3 192 320 192C284.7 192 256 220.7 256 256C256 291.3 284.7 320 320 320z"/></svg>
                                    </div>
                                    @if($property->complex_name && $property->town && $property->city)
                                    <div class="ellipsis-one-line">
                                        <span>{{$property->complex_name}}</span>, <span>{{$property->town}}</span>, <span>{{$property->city}}</span>
                                    </div>
                                    @endif
                                </div>
                                    <p style="display: flex; flex-direction: row; flex-wrap: nowrap; align-items: center; gap: 2px;"><strong>Reference Number:</strong> <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="15px" height="15px"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M192 64C156.7 64 128 92.7 128 128L128 512C128 547.3 156.7 576 192 576L448 576C483.3 576 512 547.3 512 512L512 234.5C512 217.5 505.3 201.2 493.3 189.2L386.7 82.7C374.7 70.7 358.5 64 341.5 64L192 64zM453.5 240L360 240C346.7 240 336 229.3 336 216L336 122.5L453.5 240z"/></svg>
                                    </span> {{ $property->ref }}</p>
                                    <p style="display: flex; flex-direction: row; flex-wrap: nowrap; align-items: center; gap: 2px;"><strong>Listed Price:</strong> {!! $property->display_price !!}</p>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                    @if(Auth::user()->role_id == 1)
                                    <div style="display: flex; gap: 2px; align-items: start;">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15">
                                                <circle cx="12" cy="12" r="10" fill="currentColor"/>
                                                <path fill="#fff" d="M12 6.8l1.76 3.57 3.94.57-2.85 2.78.67 3.92L12 15.82 8.48 17.64l.67-3.92-2.85-2.78 3.94-.57L12 6.8z"/>
                                            </svg>
                                        </span> 
                                        <strong>{{ $property->user->name }}</strong>
                                    </div>
                                    @endif
                                    <div class=""><strong>Date:</strong> <string>{{ date('M-Y',strtotime($property->display_updated_date)) }}</string></div>
                                </div>
                            </div>
                        </div>
                        @endforeach`;
    const listBtn = document.getElementById("listViewBtn");
    const listdata = `
        <table class="table table-striped jambo_table bulk_action table-bordered-">
            <thead>
                <tr>
                    <th>Claim</th>
                    <th>Image</th>
                    <th>Ref.</th>
                    <th>Title</th>
                    <th>For</th>
                    <th>Status</th>
                    @if(Auth::user()->role_id == 1)
                        <th>Listed By</th>
                    @endif
                    <th>Date</th>
                    <th>Property sale Detail</th>
                </tr>
            </thead>
            <tbody>
            @foreach($properties as $key=>$property)
            @php
                $currUserClaim = $property->claimedByUsers()->where('id',Auth::id())->first();
                $count = $property->claimedByUsers()->count();
            @endphp
            <tr style="position: relative;">
                <td style="position: relative;"><div class="claimcheck" style="display: flex; align-items: anchor-center; gap: 4px;">
                    <input type="hidden" value="false" name="claim[{{$property->id}}][claim]"/> 
                    <input type="hidden" value="{{isset($currUserClaim->id) ? $currUserClaim->id : Auth::id()}}" name="claim[{{$property->id}}][agent]"/> 
                    @if($count > 0)
                        <div class="tooltip3" data-propertyid="{{$property->id}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                            </svg>
                        </div>
                    @endif
                    @if(isset($currUserClaim->pivot->property_claim_approved) && $currUserClaim->pivot->property_claim_approved == 0)
                        <div class="text-nowrap" style="
                            position: absolute;
                            top: 0;
                            background: #d9b382;
                            color: #fff;
                            padding: 2px 6px;
                            border: 1px solid;
                        ">Approval Pending</div>
                    @endif
                    @if($count < 2 && !$isCurrentUserClaim)
                        <div class="">
                            <input type="checkbox" name="claim[{{$property->id}}][claim]" value="true" data-checkbox="inputbox-{{$property->id}}" class="checkCP" {{isset($currUserClaim) && $currUserClaim->pivot->property_claim_approved == 0 ? 'checked' : ''}} style="width: 15px;">
                        </div>
                    @endif
                    </div>
                </td>
                <td><a href="{{admin_url('properties/'.$property->id.'/edit')}}"><img src="{{ $property->primary_photo }}" class="pw-thumbnail-75"></a></td>
                <td style="text-wrap: nowrap;">{{ $property->ref }}</td>
                <td>{{ $property->search_headline }}</td>
                <td>{{ $property->is_rental == 1 ? 'Rent' : 'Sell' }}</td>
                <td>{{ $property->state_display }}</td>
                @if(Auth::user()->role_id == 1)
                    <td>{{ $property->user->name }}</td>
                @endif
                <td>{{ date('M-Y',strtotime($property->display_updated_date)) }}</td>
                <td>
                    <div id="inputbox-{{$property->id}}" style="width:100%; {{isset($currUserClaim) && $currUserClaim->pivot->property_claim_approved == 0 ? 'display:flex;' : 'display:none;'}}">
                        <input type="text" class="form-control price_input col-6" placeholder="Price Value" value="{{isset($currUserClaim) && $currUserClaim->pivot->property_claim_approved == 0 ? (isset($currUserClaim->pivot->property_status) ? $currUserClaim->pivot->property_status : (isset($currUserClaim->pivot->property_value) ? $currUserClaim->pivot->property_value : '')) : '' }}" name="claim[{{$property->id}}][property_value]"/>
                        <input type="date" class="form-control price_input col-6" value="{{isset($currUserClaim) && $currUserClaim->pivot->property_claim_approved == 0 && isset($currUserClaim->pivot->property_provide_date) ? date('Y-m-d',strtotime($currUserClaim->pivot->property_provide_date)) : '' }}" name="claim[{{$property->id}}][provide_date]"/>
                    </div>
                    <div id="tooltiplist{{$property->id}}" class="tooltiptextlist">
                        <h5>Claimed by</h5>
                        <hr style="padding:0px; margin:0px;"/>
                        @foreach($property->claimedByUsers as $key=>$single)
                            <div class="text-nowrap" style="display: flex; justify-content: space-between;  margin-top:5px; align-items: center;" title="{{$single->name}} ({{date('M Y',strtotime($single->pivot->property_provide_date))}})">{{$single->name}} ({{date('M Y',strtotime($single->pivot->property_provide_date))}})
                                @if(Auth::user()->role_id == 1 && isset($single->pivot->property_claim_approved) && $single->pivot->property_claim_approved == 0) 
                                    <div> 
                                        <a style="flex: inherit; padding:2px 3px;" href="{{ admin_url('approve-claim-property/'.$single->pivot->property_id.'/'.$single->pivot->user_id) }}" onclick="return confirm('Are you sure you want to approve the property claim for {{ $property->ref }} submitted by {{ $single->name }}?')" class="btn btn-small btn-success p-0 m-0">Approve</a> 
                                        <a style="flex: inherit; padding:2px 3px;" href="{{ admin_url('approvel_reject_claim_property/'.$single->pivot->property_id.'/'.$single->pivot->user_id) }}" onclick="return confirm('Are you sure you want to reject the property claim for {{ $property->ref }} submitted by {{ $single->name }}?')" class="btn btn-small btn-danger p-0 m-0">Reject</a>
                                    </div>
                                @elseif(Auth::user()->role_id == 1 && isset($single->pivot->property_claim_approved) && $single->pivot->property_claim_approved == 1)
                                    <div>
                                        <a style="flex: inherit; padding:2px 3px;" href="{{ admin_url('revoke_claim_property/'.$single->pivot->property_id.'/'.$single->pivot->user_id.'/'.$single->pivot->property_provide_date) }}" onclick="return confirm('Are you sure you want to revoke the property claim for {{ $property->ref }} submitted by {{ $single->name }}({{date('M Y',strtotime($single->pivot->property_provide_date))}})?')" class="btn btn-small btn-danger p-0 m-0">Revoke</a>
                                    </div>
                                @elseif(Auth::user()->role_id == 3 && Auth::id() == $single->pivot->user_id && isset($single->pivot->property_claim_approved) && $single->pivot->property_claim_approved == 0)
                                    <span class="badge badge-warning">Approval Pending</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    `;

    // Load previously selected view
    const savedView = localStorage.getItem("propertyView");
    if (savedView === "list") {
        listView.innerHTML = listdata;
        gridView.style.display = "none";
        listView.style.display = "block";
        gridBtn.classList.remove("btn-primary");
        listBtn.classList.add("btn-primary");
    }else{
        gridView.innerHTML = griddata;
        listView.style.display = "none";
        gridView.style.display = "block";
        gridBtn.classList.add("btn-primary");
        listBtn.classList.remove("btn-primary");
    }

    gridBtn.addEventListener("click", function () {
        listView.innerHTML = '';
        gridView.innerHTML = griddata;
        gridView.style.display = "block";
        listView.style.display = "none";
        localStorage.setItem("propertyView", "grid");

        gridBtn.classList.add("btn-primary");
        listBtn.classList.remove("btn-primary");
        claimcall();
        visibilitycall();
    });

    listBtn.addEventListener("click", function () {
        gridView.innerHTML = '';
        listView.innerHTML = listdata;
        gridView.style.display = "none";
        listView.style.display = "block";
        localStorage.setItem("propertyView", "list");

        listBtn.classList.add("btn-primary");
        gridBtn.classList.remove("btn-primary");
        claimcall();
        visibilitycall();
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        claimcall();
        visibilitycall();
    });


    function visibilitycall(){
        document.addEventListener('mouseover', function (e) {
            if (e.target.closest('.tooltip2')) {
                const propertyId = e.target.closest('.tooltip2').dataset.propertyid;
                const tooltipList = document.getElementById('tooltiplist' + propertyId);
                if (tooltipList) {
                    tooltipList.style.visibility = 'visible';
                }
                document.querySelectorAll('.tooltiptext').forEach(tooltiph2 => {
                    tooltiph2.addEventListener('mouseleave', () => {
                        tooltiph2.style.visibility = 'hidden';
                    });
                });
            }
        });

        document.addEventListener('mouseover', function (e) {
            if (e.target.closest('.tooltip3')) {
                const propertyId = e.target.closest('.tooltip3').dataset.propertyid;
                const tooltipList = document.getElementById('tooltiplist' + propertyId);
                if (tooltipList) {
                    tooltipList.style.visibility = 'visible';
                }
                document.querySelectorAll('.tooltiptextlist').forEach(tooltiph2 => {
                    tooltiph2.addEventListener('mouseleave', () => {
                        tooltiph2.style.visibility = 'hidden';
                    });
                });
            }
        });
    }

    function claimcall() {

        const selectAllCheck = document.getElementById('selectallcheckbox');
        const propertyCheckboxes = document.querySelectorAll('.checkCP');

        // Select All checkbox change
        selectAllCheck.addEventListener('change', function () {
            const isCheckedAll = this.checked;

            propertyCheckboxes.forEach(function (checkbox) {
                checkbox.checked = isCheckedAll;
                checkbox.dispatchEvent(new Event('change')); // trigger existing logic
            });
        });

        // Initial sync (important)
        syncSelectAll(propertyCheckboxes, selectAllCheck);

        // Sync Select All when individual checkbox changes
        propertyCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                syncSelectAll(propertyCheckboxes, selectAllCheck);
            });
        });

        document.querySelectorAll('.checkCP').forEach(function (checkbox) {

            checkbox.addEventListener('change', function () {

                const isChecked = this.checked;
                const claimCheck = this.dataset.checkbox;
                const priceWrapper = document.getElementById(claimCheck);

                if (!priceWrapper) return;

                priceWrapper.style.display = isChecked ? 'flex' : 'none';

                // Clear price input when unchecked
                if (!isChecked) {
                    const input = priceWrapper.querySelector('input');
                    if (input) input.value = '';
                }
            });

        });
    }

    function syncSelectAll(propertyCheckboxes, selectAllCheck) {
        if (!propertyCheckboxes.length) {
            selectAllCheck.checked = false;
            return;
        }

        selectAllCheck.checked = [...propertyCheckboxes].every(cb => cb.checked);
    }
</script>
@endpush
