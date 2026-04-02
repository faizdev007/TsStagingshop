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

</style>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <h2>Property Search</h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li class="top-button"><a href="{{admin_url('properties/create')}}" class="btn btn-small btn-primary">Create Property</a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <div class="search-form-style-1">
                    @include('backend.properties.search-form')
                </div>

                <div class="pw-table">
                    @if(count($properties))
                    <div class="">
                        {{ $properties->links('pagination::bootstrap-4') }}
                        <div class="" style="margin-bottom:15px;">
                            <div class="btn-group" role="group">
                                <button id="gridViewBtn" class="btn btn-primary btn-sm" title="Grid View">
                                    <i class="fa fa-th" aria-hidden="true"></i>
                                </button>
                                <button id="listViewBtn" class="btn btn-secondary btn-sm" title="List View">
                                    <i class="fa fa-list" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    
                    <div id="gridView" class="row gap-2">
                        @foreach($properties as $key=>$property)
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <a onmouseenter="document.getElementById('note_{{ $property->id }}').style.display='block'" onmouseleave="document.getElementById('note_{{ $property->id }}').style.display='none'" href="{{admin_url('properties/'.$property->id.'/edit')}}" style="position: relative;">
                                    <img src="{{ $property->primary_photo }}" class="img-responsive mb-2">
                                    <div class="statustag">
                                        {{ $property->state_display }}
                                    </div>
                                    <div style="position: absolute;bottom: 0;margin: 0;font-weight: 700;padding: 5px; background: rgb(255 255 255 / 73%); width: 100%; color: #000;">
                                        <div style="display: flex;flex-wrap: nowrap;justify-content: space-between;">
                                            <span>{!! $property->display_price !!}</span>
                                            <div><strong>Featured?:</strong> @if($property->is_featured)<i class="fa fa-check"></i> @else <i class="fa fa-times" aria-hidden="true"></i> @endif</div>
                                        </div>
                                        <div id="note_{{ $property->id }}" hidden>
                                            @if($property->agent_notes)
                                                <hr>
                                                <strong style="border-bottom: 1px solid;">Notes</strong>
                                                <p>{{ strip_tags($property->agent_notes) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if((Auth::user()->role_id == 3 || Auth::id() != $property->user_id) && $property->status == -1 && $property->admin_approval == 0)
                                        <div class="text-nowrap" style="
                                            position: absolute;
                                            top: 0;
                                            right: 0;
                                            margin: 0;
                                            padding: 4px;
                                            border-radius: 0px 6px 0px 2px;
                                            background-color: #d9b382;
                                            color:#fff;
                                        ">Pending Approval</div>
                                    @endif
                                </a>
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
                                <div style="display: flex; margin-bottom:10px; flex-wrap: nowrap; gap: 8px;">
                                    <div style="display: flex; gap: 5px; align-items: center;" title="Beds">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="15px" height="15px"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M64 96C81.7 96 96 110.3 96 128L96 352L320 352L320 224C320 206.3 334.3 192 352 192L512 192C565 192 608 235 608 288L608 512C608 529.7 593.7 544 576 544C558.3 544 544 529.7 544 512L544 448L96 448L96 512C96 529.7 81.7 544 64 544C46.3 544 32 529.7 32 512L32 128C32 110.3 46.3 96 64 96zM144 256C144 220.7 172.7 192 208 192C243.3 192 272 220.7 272 256C272 291.3 243.3 320 208 320C172.7 320 144 291.3 144 256z"/></svg>
                                        <span> {{$property->beds}}</span>
                                    </div>
                                    <div style="display: flex; gap: 5px; align-items: center;" title="Baths">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="15px" height="15px"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M160 141.3C160 134 165.9 128 173.3 128C176.8 128 180.2 129.4 182.7 131.9L197.6 146.8C194 155.9 192.1 165.7 192.1 176C192.1 195.9 199.3 214 211.3 228C206 237.2 207.3 249.1 215.1 257C224.5 266.4 239.7 266.4 249 257L353 153C362.4 143.6 362.4 128.4 353 119.1C345.2 111.2 333.2 110 324 115.3C310 103.3 291.9 96.1 272 96.1C261.7 96.1 251.8 98.1 242.8 101.6L227.9 86.6C213.4 72.1 193.7 64 173.3 64C130.6 64 96 98.6 96 141.3L96 320C78.3 320 64 334.3 64 352C64 369.7 78.3 384 96 384L96 432C96 460.4 108.4 486 128 503.6L128 544C128 561.7 142.3 576 160 576C177.7 576 192 561.7 192 544L192 528L448 528L448 544C448 561.7 462.3 576 480 576C497.7 576 512 561.7 512 544L512 503.6C531.6 486 544 460.5 544 432L544 384C561.7 384 576 369.7 576 352C576 334.3 561.7 320 544 320L160 320L160 141.3z"/></svg>
                                        <span> {{abs($property->baths)}}</span>
                                    </div>
                                    @if($property->internal_area)
                                        <div style="display: flex; gap: 5px; align-items: center;" title="Intere Area">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="15px" height="15px"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M341.8 72.6C329.5 61.2 310.5 61.2 298.3 72.6L74.3 280.6C64.7 289.6 61.5 303.5 66.3 315.7C71.1 327.9 82.8 336 96 336L112 336L112 512C112 547.3 140.7 576 176 576L464 576C499.3 576 528 547.3 528 512L528 336L544 336C557.2 336 569 327.9 573.8 315.7C578.6 303.5 575.4 289.5 565.8 280.6L341.8 72.6zM304 384L336 384C362.5 384 384 405.5 384 432L384 528L256 528L256 432C256 405.5 277.5 384 304 384z"/></svg>
                                            <span> {{$property->internal_area ?? 'N/A'}} sqft</span>
                                        </div>
                                    @endif
                                    @if($property->terrace_area)
                                        <div style="display: flex; gap: 5px; align-items: center;" title="Terrace Area">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="15px" height="15px"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M561.5 405.1C555.6 421.8 536.2 428.1 520.4 420.2L342.2 331.1L340.6 334.3L251.8 512L544 512C561.7 512 576 526.3 576 544C576 561.7 561.7 576 544 576L96 576C78.3 576 64 561.7 64 544C64 526.3 78.3 512 96 512L180.2 512L283.4 305.7L285 302.5L119.6 219.8C103.8 211.9 97.2 192.5 107.1 177.8C153 109.2 231.2 64 320 64C461.4 64 576 178.6 576 320C576 349.8 570.9 378.5 561.5 405.1z"/></svg>
                                            <span> {{$property->terrace_area ?? 'N/A'}} sqft</span>
                                        </div>
                                    @endif
                                    @if($property->land_area)
                                        <div style="display: flex; gap: 5px; align-items: center;" title="Land Area">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="15px" height="15px"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M96 96C113.7 96 128 110.3 128 128L128 464C128 472.8 135.2 480 144 480L544 480C561.7 480 576 494.3 576 512C576 529.7 561.7 544 544 544L144 544C99.8 544 64 508.2 64 464L64 128C64 110.3 78.3 96 96 96zM304 160C310.7 160 317.1 162.8 321.7 167.8L392.8 245.3L439 199C448.4 189.6 463.6 189.6 472.9 199L536.9 263C541.4 267.5 543.9 273.6 543.9 280L543.9 392C543.9 405.3 533.2 416 519.9 416L215.9 416C202.6 416 191.9 405.3 191.9 392L191.9 280C191.9 274 194.2 268.2 198.2 263.8L286.2 167.8C290.7 162.8 297.2 160 303.9 160z"/></svg>
                                            <span> {{$property->land_area ?? 'N/A'}} sqft</span>
                                        </div>
                                    @endif
                                </div>
                                <p style="display: flex; flex-direction: row; flex-wrap: nowrap; align-items: center; gap: 2px;"><strong>Reference Number:</strong> <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="15px" height="15px"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M192 64C156.7 64 128 92.7 128 128L128 512C128 547.3 156.7 576 192 576L448 576C483.3 576 512 547.3 512 512L512 234.5C512 217.5 505.3 201.2 493.3 189.2L386.7 82.7C374.7 70.7 358.5 64 341.5 64L192 64zM453.5 240L360 240C346.7 240 336 229.3 336 216L336 122.5L453.5 240z"/></svg>
                                </span> {{ $property->ref }}</p>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                    <div style="display: flex; gap: 2px; align-items: start;">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15">
                                                <circle cx="12" cy="12" r="10" fill="currentColor"/>
                                                <path fill="#fff" d="M12 6.8l1.76 3.57 3.94.57-2.85 2.78.67 3.92L12 15.82 8.48 17.64l.67-3.92-2.85-2.78 3.94-.57L12 6.8z"/>
                                            </svg>
                                        </span> 
                                        <strong>{{ $property->user->name }}</strong>
                                    </div>
                                    <div class=""><strong>Published:</strong> <string>{{ \Carbon\Carbon::parse($property->updated_at)->diffForHumans() }}</string></div>
                                </div>
                                <div class="modal-body" style="display: flex;overflow-x: auto; width: 100%; flex-direction: row; flex-wrap: nowrap; align-content: center;">
                                    <a style="width: 100%;flex: inherit;" href="{{admin_url('properties/'.$property->id.'/edit')}}" class="btn btn-small btn-primary">Edit</a>

                                    @if($property->status == 1)
                                        <a style="width: 100%;flex: inherit;" href="{{admin_url('properties/'.$property->id.'/reactive')}}" class="confirm-action btn btn-small btn-info" title="reactivate this property">Reactivate</a>

                                        @if(  Auth::user()->role_id == '1' )
                                        <a style="width: 100%;flex: inherit;" href="{{admin_url('properties/'.$property->id.'/delete')}}" class="confirm-action btn btn-small btn-danger" title="permanently delete this property">Delete</a>
                                        @endif

                                    @endif



                                    @if($property->status >= 0 && $property->status <= 20)
                                    <a style="width: 100%;flex: inherit;" href="{{ url($property->url) }}" target="_blank" class="btn btn-small btn-info">View</a>
                                    @endif

                                    @if($property->status != -1)
                                        @if(settings('pdf_view') == 1)
                                            <a style="width: 100%;flex: inherit;" href="{{ url('/DownloadPdf/'.$property->id) }}" target="_blank" class="btn btn-small btn-success download-pdf">PDF</a>
                                        @endif
                                    @elseif(Auth::user()->role_id == 1 && $property->user_id != Auth::id() && $property->admin_approval == 0)
                                        <a style="width: 100%;flex: inherit;" onclick="return confirm('Are you sure you want to approve the property {{ $property->ref }} that was listed by {{ $property->user->name }}?')" href="{{ admin_url('approve-property/'.$property->id) }}" class="btn btn-small btn-success">Approve</a>
                                        <a style="width: 100%;flex: inherit;" onclick="return confirm('Are you sure you want to reject the property {{ $property->ref }} that was listed by {{ $property->user->name }}?')" href="{{ admin_url('approvel_reject_property/'.$property->id) }}" class="btn btn-small btn-danger">Reject</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div id="listView" style="display:none;">
                        <table class="table table-striped jambo_table bulk_action table-bordered-">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Ref.</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    @if(settings('branches_option') == 1 && Auth::user()->role_id <= '2')
                                        <th>Branch</th>
                                    @endif
                                    <th>Status</th>
                                    <th>Date Added</th>
                                    <th>Notes</th>
                                    <th class="text-center">Featured?</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($properties as $property)
                            <tr>
                                <td><div style="position: relative;">
                                        <a href="{{admin_url('properties/'.$property->id.'/edit')}}"><img src="{{ $property->primary_photo }}" class="pw-thumbnail-75"></a>
                                        @if((Auth::user()->role_id == 3 || Auth::id() != $property->user_id) && $property->status == -1 && $property->admin_approval == 0)
                                            <div class="text-nowrap" style="
                                                position: absolute;
                                                top: -10px;
                                                left: -8px;
                                                margin: 0;
                                                padding: 4px;
                                                border-radius: 0px 0px 0px 0px;
                                                background-color: #d9b382;
                                                color: #fff;
                                            ">Pending Approval</div>
                                        @endif
                                    </div>
                                </td>
                                <td style="text-wrap: nowrap;">{{ $property->ref }}</td>
                                <td>{{ $property->search_headline }}</td>
                                <td>{!! $property->display_price !!}</td>
                                @if(settings('branches_option') == 1 && Auth::user()->role_id <= '2')
                                    <td>
                                        @if($property->branch)
                                            {{ $property->branch->branch_name }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                @endif
                                <td>{{ $property->state_display }}</td>
                                <td>{{ $property->display_date }}</td>
                                <td>{{ strip_tags($property->agent_notes) }}</td>
                                <td class="text-center">
                                    @if($property->is_featured)
                                        <i class="fa fa-check"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{admin_url('properties/'.$property->id.'/edit')}}" class="btn btn-small btn-primary">Edit</a>

                                    @if($property->status == 1)
                                        | <a href="{{admin_url('properties/'.$property->id.'/reactive')}}" class="confirm-action btn btn-small btn-info" title="reactivate this property">Reactivate</a>
                                        @if(  Auth::user()->role_id == '1' )
                                        | <a href="{{admin_url('properties/'.$property->id.'/delete')}}" class="confirm-action btn btn-small btn-danger" title="permanently delete this property">Delete</a>
                                        @endif
                                    @endif



                                    @if($property->status >= 0 && $property->status <= 20)
                                    <a href="{{ url($property->url) }}" target="_blank" class="btn btn-small btn-info">View</a>
                                    @endif

                                    @if($property->status != -1)
                                        @if(settings('pdf_view') == 1)
                                            <a href="{{ url('property-pdf/view/'.$property->id) }}" target="_blank" class="btn btn-small btn-success download-pdf">PDF</a>
                                        @endif
                                    @elseif(Auth::user()->role_id == 1 && $property->user_id != Auth::id() && $property->admin_approval == 0)
                                        <a style="width: 100%;flex: inherit;" href="{{ admin_url('approve-property/'.$property->id) }}" onclick="return confirm('Are you sure you want to approve the property {{ $property->ref }} that was listed by {{ $property->user->name }}?')" class="btn btn-small btn-success">Approve</a>
                                        <a style="width: 100%;flex: inherit;" onclick="return confirm('Are you sure you want to reject the property {{ $property->ref }} that was listed by {{ $property->user->name }}?')" href="{{ admin_url('approvel_reject_property/'.$property->id) }}" class="btn btn-small btn-danger">Reject</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $properties->links('pagination::bootstrap-4') }}
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
    const listBtn = document.getElementById("listViewBtn");

    // Load previously selected view
    const savedView = localStorage.getItem("propertyView");
    if (savedView === "list") {
        gridView.style.display = "none";
        listView.style.display = "block";
        gridBtn.classList.remove("btn-primary");
        listBtn.classList.add("btn-primary");
    }

    gridBtn.addEventListener("click", function () {
        gridView.style.display = "block";
        listView.style.display = "none";
        localStorage.setItem("propertyView", "grid");

        gridBtn.classList.add("btn-primary");
        listBtn.classList.remove("btn-primary");
    });

    listBtn.addEventListener("click", function () {
        gridView.style.display = "none";
        listView.style.display = "block";
        localStorage.setItem("propertyView", "list");

        listBtn.classList.add("btn-primary");
        gridBtn.classList.remove("btn-primary");
    });

});
</script>
@endpush
