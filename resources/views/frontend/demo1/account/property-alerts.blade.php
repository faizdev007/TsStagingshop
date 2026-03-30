@extends('frontend.demo1.layouts.frontend')
@push('body_class')property-alerts @endpush
@section('main_content')
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<style>
    .tooltip-wrapper {
    position: relative;
    display: inline-block;
    cursor: pointer;
}

.tooltip-icon {
    color: #555;
}

/* Tooltip box */
.custom-tooltip {
    position: absolute;
    bottom: 130%;
    left: 50%;
    transform: translateX(-50%);

    min-width: 220px;
    /* max-width: 280px; */
    background: #1f2937; /* dark gray */
    color: #fff;
    padding: 10px 12px;
    border-radius: 6px;

    font-size: 13px;
    line-height: 1.5;

    opacity: 0;
    visibility: hidden;
    pointer-events: none;

    transition: opacity 0.2s ease, transform 0.2s ease;
    z-index: 1000;
}

/* Tooltip arrow */
.custom-tooltip::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border-width: 6px;
    border-style: solid;
    border-color: #1f2937 transparent transparent transparent;
}

/* Show on hover */
.tooltip-wrapper:hover .custom-tooltip {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(-4px);
}


.confirm-modal {
    position: fixed;
    inset: 0;
    display: none;
    z-index: 9999;
}

.confirm-modal.active {
    display: block;
}

.confirm-modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.5);
}

.confirm-modal-box {
    position: relative;
    max-width: 420px;
    margin: 15% auto;
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    z-index: 1;
    text-align: center;
}

.confirm-modal-box h4 {
    margin-bottom: 10px;
}

.confirm-actions {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 20px;
}

.btn-cancel {
    background: #e5e7eb;
    border: none;
    padding: 8px 14px;
    border-radius: 5px;
    cursor: pointer;
}

</style>
@include('frontend.demo1.account.parts.banners')
<section class="u-pt0 u-pb2">
    <div class="container">
        @if (session('message_success'))
        <div class="row">
            <div class="col">
                <div class="alert alert-success">
                    {{ session('message_success') }}
                </div>
            </div>
        </div>
        @endif
        @include('frontend.demo1.account.nav.nav')
        <div class="create-alert">
            <div class="row u-mt1">
                <div class="col">
                    <div id="property-alert-form" class="col -property-alert-form u-p2">
                        <div class="text-center">
                            <span class="f-24 f-bold u-block u-mb05">Create New Property Alert</span>
                        </div>
                        @include('frontend.demo1.forms.property-alert', ['uniqueID'=>'property-alert-form-v2'])
                    </div>
                </div>
            </div>
        </div>
        <!-- /.create-alert -->
        @if($alerts->count() > 0 )
        @foreach($alerts as $alert)
        <div class="update-alert edit-alert-{{ $alert->id }}" style="display: none;">
            <div class="row u-mt1">
                <div class="col">
                    <form method="post" action="{{ url('property-alert/'.$alert->id.'/edit') }}">
                        @csrf
                        <div class="property-alert-form -property-alert-form u-p2">
                            <div class="text-center">
                                <span class="c-white f-24 f-bold u-block u-mb05">Edit Your Property Alert</span>
                            </div>
                            <div class="alert-content">
                                <div class="property-alert-inner">
                                    <div class="alert-fields select-style-1">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 @if(Auth::user()) d-none @endif">
                                                <div class="form-group u-mb05">
                                                    <input @if(Auth::user()) type="hidden" @else type="text" @endif name="fullname" placeholder="Full Name *" required="required" @if(Auth::user()) value="{{ Auth::user()->name }}" @endif>
                                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 @if(Auth::user()) d-none @endif">
                                                <div class="form-group u-mb05">
                                                    <input @if(Auth::user()) type="hidden" @else type="email" @endif name="email" placeholder="Email Address *" required="required" @if(Auth::user()) value="{{ Auth::user()->email }}" @endif>
                                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 @if(Auth::user()) d-none @endif">
                                                <div class="form-group u-mb05">
                                                    <input @if(Auth::user()) type="hidden" @else type="tel" @endif name="contact" placeholder="Telephone" @if(Auth::user()) value="{{ Auth::user()->telephone }}" @endif>
                                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                </div>
                                            </div>


                                            <div class="col-md-12 col-sm-12">
                                                <div class="form-group u-mb05">
                                                    @php
                                                    $inDisplay = !empty($alert->in) ? urldecode($alert->in) : 'Location';
                                                    $inValue = !empty($alert->in) ? $alert->in : '';
                                                    @endphp
                                                    <select
                                                        name="in"
                                                        id="location_list-alert-update-{{ $alert->id }}"
                                                        class="form-control select-pw-ajax-locations"
                                                    >
                                                        @foreach(get_locations() as $key => $value)
                                                            <option
                                                                value="{{ $key }}"
                                                                {{ $alert->in == $key ? 'selected' : '' }}
                                                            >
                                                                {{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            @if(settings('sale_rent') == 'sale_rent')
                                            <div class="@if(Auth::user()) col-md-4 @else col-md-6 @endif col-sm-6">
                                                <div class="form-group u-mb05">
                                                    <select
                                                        name="is_rental"
                                                        id="id-status-alert{{ $alert->id }}"
                                                        class="sale-multiple select-pw price-rage-dynamic-id-{{ $alert->id }}"
                                                        data-targetclass=".price-{{ $alert->id }}"
                                                    >
                                                        @foreach(p_fieldTypes_no_default() as $key => $value)
                                                            <option
                                                                value="{{ $key }}"
                                                                {{ $alert->is_rental == $key ? 'selected' : '' }}
                                                            >
                                                                {{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @elseif(settings('sale_rent') == 'sale')
                                            <input type="hidden" name="is_rental" value="0" class="price-rage-dynamic-id-{{$alert->id}}">
                                            @else
                                            <input type="hidden" name="is_rental" value="1" class="price-rage-dynamic-id-{{$alert->id}}">
                                            @endif

                                            <div class="col-md-4 col-sm-6">
                                                @if(0)
                                                <div class="form-group u-mb05">
                                                    <select name="property_type_id"
                                                            class="property select-pw">

                                                        @foreach(prepare_dropdown_ptype($propertyTypes, 'Property Type') as $key => $value)
                                                            <option value="{{ $key }}"
                                                                {{ (string) old('property_type_id', $alert->property_type_id) === (string) $key ? 'selected' : '' }}>
                                                                {{ $value }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>@endif

                                                <div id="search-type-selection-{{$alert->id}}" class="form-group u-mb05 mutliple-selection--attr multiple-container-o multiple-container-o-dark">

                                                    <div class="multiple-selected">
                                                        <label for="property_feature" class="c-white f-15 f-500">0</label>
                                                    </div>
                                                    <select id="li-feature-{{$alert->id}}" class="select-pw-mutiple w-100 type-select" name="property_type_id[]" data-placeholder="Property Type" multiple>
                                                        <?php
                                                        $ptypeArray = prepare_dropdown_ptype($propertyTypes);
                                                        $ptypeArraySelected = (!empty($alert->property_type_ids)) ? explode(',', $alert->property_type_ids) : [];
                                                        foreach ($ptypeArray as $slug => $propertyType) {
                                                        ?>
                                                            <option value="<?= $slug ?>" {{ in_array($slug, $ptypeArraySelected) ? 'selected' : '' }}><?= $propertyType ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>

                                                </div>

                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group u-mb05">
                                                    <select name="beds" class="beds select-pw">
                                                        @foreach(p_beds_frontend(trans_fb('app.app_Min_Beds','BEDS')) as $key => $value)
                                                            <option
                                                                value="{{ $key }}"
                                                                {{ $alert->beds == $key ? 'selected' : '' }}
                                                            >
                                                                {{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            @php

                                            $prices = [];
                                            $prices["0-5000000"] = "UPTO 5,000,000";
                                            $prices["5000000-20000000"] = "5,000,000 - 20,000,000";
                                            $prices["20000000-50000000"] = "20,000,000 - 50,000,000";
                                            $prices["50000000-100000000"] = "50,000,000 - 100,000,000";

                                            $prices["100000000-9999999999"] = "100,000,000 +";
                                            @endphp

                                            <div class="col-md-6 col-sm-6">
                                                <div class="form-group u-mb05">
                                                    <select name="price_range" class="select-pw min_price">
                                                        <option value="">{{ trans_fb('app.app_Price', 'Price') }}</option>
                                                        @foreach ($prices as $price_val => $price_txt)
                                                        <option value="{{ $price_val }}" @if(isset($price_range)) @if($price_range==$price_txt) selected="selected" @endif @endif>{{ $price_txt }}</option>
                                                        @endforeach
                                                        <option value="">ANY</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>



                                        <div class="property-alert-submit  u-mt2">
                                            <div class="text-center">
                                                <button type="submit" name="submit" class="button -primary f-16 f-bold text-uppercase u-rounded-10 u-pr2 u-pl2">Update Alert</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
        @endif
        <div class="row">
            @if($alerts->count() > 0 )
            <div class="col u-mt2">
                <div class="c-bg-gray u-p2 table-responsive">
                    <table class="border-0 searches-table table table-bordered text-nowrap">
                        <thead class="table-active">
                            <tr>
                                <th>Type</th>
                                <th>Location</th>
                                <th class="desktop" width="200">Property Type</th>
                                <th class="desktop">Min Price</th>
                                <th class="desktop">Max Price</th>
                                <th class="desktop">Beds</th>
                                <th class="mobile-table">Info</th>
                                <th class="desktop">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alerts as $alert)
                            <tr id="alert-{{ $alert->id }}">
                                <td>{{ $alert->DisplayMode }}</td>
                                <td>
                                    @if($alert->in)
                                    {{ urldecode($alert->in) }}
                                    @else
                                    Not set
                                    @endif
                                </td>
                                <td class="desktop">
                                    @if(!empty($alert->property_type_ids))
                                    {{ $alert->DisplayPropertyTypes }}
                                    @else
                                    Not Set
                                    @endif
                                </td>
                                <td class="desktop">
                                    @if($alert->price_from)
                                    {!! settings('currency_symbol') !!}{{ thousandscurrencyformat($alert->price_from) }}
                                    @else
                                    Not set
                                    @endif
                                </td>
                                <td class="desktop">
                                    @if($alert->price_to)
                                    @if( $alert->price_to == '200000000')
                                    {!! settings('currency_symbol') !!} 5,000,000+
                                    @else
                                    {!! settings('currency_symbol') !!}{{ thousandscurrencyformat($alert->price_to) }}
                                    @endif
                                    @else
                                    Not set
                                    @endif
                                </td>
                                <td class="desktop">
                                    @if($alert->beds)
                                    {{ $alert->beds }}
                                    @else
                                    Not set
                                    @endif
                                </td>
                                <td class="mobile position-relative">
                                    @php
                                    $tooltip = '';
                                    $tooltip .= '<div class="tooltip_templates u-text-left u-p1">';
                                        $tooltip .='<span id="tooltip_content-'.$alert->id.'">';
                                            $alerType = !empty($alert->property_type_ids)?$alert->DisplayPropertyTypes:'Not Set';
                                            $priceRange = 'Not Set';
                                            if(isset($alert->price_from) && isset($alert->price_to)){
                                                $priceRange = settings('currency_symbol').thousandscurrencyformat($alert->price_from).'-'.
                                                settings('currency_symbol').thousandscurrencyformat($alert->price_to);
                                            }
                                            $alertBeds = 'Not Set';
                                            if($alert->beds){
                                                $alertBeds = $alert->beds;
                                            }
                                            $tooltip .='<strong>Property Type:</strong>'.$alerType.'<br />';
                                            $tooltip .='<strong>Price Range:</strong>'.$priceRange.'<br />';
                                            $tooltip .='<strong>Beds:</strong>'.$alertBeds.'<br />';

                                            $tooltip .= '</span>';
                                        $tooltip .= '</div>';
                                    @endphp

                                    <span class="tooltip-wrapper">
                                        <i class="fas fa-info-circle tooltip-icon"></i>

                                        <span class="custom-tooltip">
                                            <strong>Property Type:</strong> {{ $alert->DisplayPropertyTypes ?? 'Not Set' }}<br>
                                            <strong>Price Range:</strong> {{ $priceRange }}<br>
                                            <strong>Beds:</strong> {{ $alert->beds ?? 'Not Set' }}
                                        </span>
                                    </span>
                                </td>
                                <td class="desktop">
                                    <a href="#property-alert-form" class="-secondary button edit-alert f-13 f-bold jump-link p-1 rounded text-uppercase" data-alert-id="{{ $alert->id }}">
                                        <span class="c-tertiary f-18"><i class="fas fa-pencil-alt"></i></span>
                                    </a>
                                    <a class="-secondary delete-btn button edit-alert f-13 f-bold jump-link p-1 rounded text-uppercase modal-toggle" href="#"
                                        data-item-id="{{ $alert->id }}"
                                        data-toggle="modal"
                                        data-modal-type="delete"
                                        data-modal-title="Delete Property Alert"
                                        data-modal-size="small"
                                        data-id="{{ $alert->id }}"
                                        data-url="/alert/{{ $alert->id }}"
                                        data-delete-type="alert"
                                        data-target="#global-modal">
                                        <span class="c-tertiary f-18"><i class="far fa-trash-alt"></i></span>
                                    </a>
                                </td>
                            </tr>
                            <tr id="alert-mobile-{{ $alert->id }}" class="mobile-table-row d-none">
                                <td colspan="6">
                                    <a href="#property-alert-form" class="button -primary -normal f-13 text-uppercase f-bold edit-alert jump-link" data-alert-id="{{ $alert->id }}">
                                        <span class="c-tertiary f-13"><i class="fas fa-pencil-alt"></i></span>
                                    </a>
                                    <a class="button -primary -normal f-13 text-uppercase f-bold modal-toggle" href="#"
                                        data-item-id="{{ $alert->id }}"
                                        data-toggle="modal"
                                        data-modal-type="delete"
                                        data-modal-title="Delete Property Alert"
                                        data-modal-size="small"
                                        data-delete-type="alert"
                                        data-target="#global-modal">
                                        <span class="c-white f-13"><i class="far fa-trash-alt"></i> Remove</span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="col">
                <p class="u-mt1">No property alerts saved yet. <a class="c-primary f-bold jump-link" href="#property-alert-form">Add a property alert now!</a></p>
            </div>
            @endif
        </div>
    </div>
</section>

<div id="confirmModal" class="confirm-modal">
    <div class="confirm-modal-backdrop"></div>
    <form id="delete_alert" action="{{url('/alert/81')}}" method="post">
        @csrf   
        @method('delete')
        <div class="confirm-modal-box">
            <h4>Confirm Delete</h4>
            <p>Are you sure you want to delete Property Alert?  
            This action cannot be undone.</p>

            <div class="confirm-actions">
                <button type="button" class="btn-cancel">Cancel</button>
                <button class="-secondary button f-13 f-bold jump-link p-1 rounded text-uppercase">Yes, Delete</button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded',function(){
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-bottom-left',
            timeOut: 3000,
            extendedTimeOut: 1000,
            preventDuplicates: true
        };
    });
    
    let deleteUrl = '';

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            let formblock = document.getElementById('delete_alert');
            deleteUrl = this.dataset.url;
            formblock.setAttribute('action',deleteUrl);
            document.getElementById('confirmModal').classList.add('active');
        });
    });

    // Cancel button
    document.querySelector('.btn-cancel').addEventListener('click', closeModal);

    // Backdrop click
    document.querySelector('.confirm-modal-backdrop')
        .addEventListener('click', closeModal);

    // Confirm delete
    // document.querySelector('.btn-delete').addEventListener('click', function () {
    //     if (deleteUrl) {
    //         let formblock = document.getElementById('delete_alert');
    //         formblock.submit();
    //     }
    // });

    function closeModal() {
        document.getElementById('confirmModal').classList.remove('active');
        deleteUrl = '';
    }

    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert-success');

        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';

            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
@endsection