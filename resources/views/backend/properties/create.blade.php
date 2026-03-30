@extends('backend.layouts.master')

@section('admin-content')

<form action="{{ route('properties.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <h2><br /></h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <p class="text-muted font-13 m-b-30"><br /></p>

                    <!-- Property Fields -->
                    <div class="x_panel pw-inner-tabs">
                        <div class="x_title">
                            <h2>Property Fields</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content pw-open">
                            <div class="xpw-fields">

                                <input type="hidden" name="is_featured" value="0">
                                <input
                                    type="hidden"
                                    name="country"
                                    value="{{ !empty(settings('overseas_country')) ? settings('overseas_country') : 'United Kingdom' }}">

                                <div class="row">

                                    @if(Auth::check() && Auth::user()->role_id != 3)
                                        <!-- Status -->
                                        <div class="col-md-4">
                                            <div class="control-form">
                                                <label>Status: {!! required_label() !!}</label>
                                                <select
                                                    name="status"
                                                    id="id-status"
                                                    class="form-control select-pw">
                                                    @foreach(p_states() as $key => $value)
                                                        <option value="{{ $key }}" @if($key == 0) selected @endif>
                                                            {{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Sale / Rent -->
                                    @if(settings('sale_rent') == 'sale_rent')
                                        <div class="col-md-4">
                                            <div class="control-form">
                                                <label>Field Type: {!! required_label() !!}</label>
                                                <select
                                                    name="is_rental"
                                                    id="id-mode"
                                                    class="form-control select-pw mode-attr"
                                                    data-category=".p-category">
                                                    @foreach(p_fieldTypes() as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @elseif(settings('sale_rent') == 'sale')
                                        <input type="hidden" name="is_rental" value="0">
                                    @else
                                        <input type="hidden" name="is_rental" value="1">
                                    @endif

                                    <!-- Agent -->
                                    @if(Auth::user()->role_id == '1')
                                        <div class="col-md-4">
                                            <div class="control-form">
                                                <label>Agent: {!! required_label() !!}</label>
                                                <select
                                                    name="user_id"
                                                    class="form-control select-pw-ajax-agent"
                                                    readonly>
                                                    <option value="">Please Select</option>
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                    @endif

                                    <!-- Property Type -->
                                    <div class="col-md-4">
                                        <div class="control-form">
                                            <label>Property Type: {!! required_label() !!}</label>
                                            <select
                                                name="property_type_id"
                                                class="form-control select-pw">
                                                @foreach(prepare_dropdown_ptype($propertyTypes) as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Branch -->
                                    @if(settings('branches_option') == 1 && Auth::user()->role_id <= '2')
                                        <div class="col-md-3">
                                            <div class="control-form">
                                                <label>Branch</label>
                                                <select
                                                    id="id-branch"
                                                    class="form-control select-pw"
                                                    name="branch_id"
                                                    data-placeholder="Optional....">
                                                    <option></option>
                                                    @foreach($branches as $branch)
                                                        <option value="{{ $branch->branch_id }}">
                                                            {{ $branch->branch_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        @if(settings('branches_option') == 1)
                                            <input type="hidden" name="branch_id" value="{{ Auth::user()->branch_id }}">
                                        @endif
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- General Info -->
                    <div class="x_panel pw-inner-tabs">
                        <div class="x_title">
                            <h2>General Info</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <div class="xpw-fields">
                                <div class="row">

                                    <!-- Price -->
                                    <div class="col-md-3">
                                        <div class="control-form">
                                            <label>
                                                Price ({{ settings('currency_symbol') }}):
                                                {!! required_label() !!}
                                            </label>
                                            <input
                                                type="text"
                                                name="price"
                                                id="id-price"
                                                class="form-control"
                                                placeholder="Please enter...">
                                        </div>
                                    </div>

                                    <!-- Beds -->
                                    <div class="col-md-3">
                                        <div class="control-form">
                                            <label>Number of bedrooms: {!! required_label() !!}</label>
                                            <select
                                                name="beds"
                                                class="form-control select-pw">
                                                @foreach(p_beds() as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Baths -->
                                    <div class="col-md-3">
                                        <div class="control-form">
                                            <label>Number of bathrooms:</label>
                                            <select
                                                name="baths"
                                                class="form-control select-pw">
                                                @foreach(p_baths() as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="form-group sticky-buttons">
        <button type="submit" class="btn btn-large btn-primary" name="action">
            Create
        </button>
        <a href="{{ admin_url('properties') }}" class="btn btn-default btn-spacing">
            Cancel <span>and Return</span>
        </a>
    </div>

</form>

<!-- Modal -->
<div class="modal fade" id="addnewstatusmodal" tabindex="-1" role="dialog" aria-labelledby="addnewstatusmodalLabel" aria-hidden="true" style="z-index: 9999!important;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addnewstatusmodalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="container-fluid" style="height: 200px;overflow-y: auto; border-bottom: 1px solid; border-top: 1px solid;">
            @foreach(p_states() as $key=>$status)
                <div id="p-{{$key}}" style="display:flex; position: relative;"><span class="form-control">{{$status}}</span>@if(!array_key_exists($key,config('p_states')))<button type="button" class="btn btn-danger" style="margin: 0px; border-radius: 0px;" onclick="addRelement(`{{$key}}`)">X</button>@endif</div>
            @endforeach
        </div>
        <form action="{{ route('properties.addnewstatus') }}" method="POST">
            @csrf

            <div class="modal-body">
                <div id="statuslist"></div>

                <input
                    type="text"
                    class="form-control"
                    name="p_status_input"
                    placeholder="New Status">
            </div>

            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal">
                    Close
                </button>

                <button
                    type="submit"
                    class="btn btn-primary">
                    Save
                </button>
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
<script src="{{asset('assets/admin/build/js/pw-select2-ajax.js')}}"></script>
<script src="{{asset('assets/admin/build/js/page-detail.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded',function(){
        let statusinput = document.getElementById('select2-id-status-container');
        statusinput.addEventListener('click', function () {
            let optionlist = document.getElementById('select2-id-status-results');
            optionlist.addEventListener('scroll',function(){
                // Create <li>
                let li = document.createElement('li');
    
                // Create <div>
                let div = document.createElement('div');
    
                let actbutton = document.createElement('button');
                actbutton.type = 'button';
                actbutton.id = 'addnewstatus';
                actbutton.style.width = '100%';
                actbutton.style.margin = '0px';
                actbutton.style.borderRadius = '0px';
                actbutton.innerHTML = "Add/Delete Status";
                actbutton.classList.add('btn', 'btn-large', 'btn-primary');
                actbutton.setAttribute('data-toggle', 'modal');
                actbutton.setAttribute('data-target', '#addnewstatusmodal');
                
                if(document.getElementById('addnewstatus')){
                    return;
                }
                // Build structure
                div.appendChild(actbutton);  // <div><actbutton></div>
                li.appendChild(div);     // <li><div><actbutton></div></li>
    
                // Append wherever you want
                optionlist.appendChild(li);
            });
        });
    });

    function addRelement(e){
        let element = document.getElementById('statuslist');
        let deleteinput = document.createElement('input');
        deleteinput.type = 'hidden';
        deleteinput.name='deletestatus[]';
        deleteinput.value = e;
        element.appendChild(deleteinput);
        document.getElementById(`p-${e}`).remove();
    }
</script>
@endpush
