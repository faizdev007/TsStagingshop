@extends('backend.layouts.master')

@section('admin-content')

<div class="row current_url" data-url="{{admin_url($folder)}}">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <h2></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li class="top-button"><a href="{{admin_url($folder)}}" class="btn btn-small btn-primary">View {{$module_title}}</a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="{{($tab != 'photo') ? 'active' : false}}"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Details</a>
                    </li>
                  </ul>
                  <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade {{($tab != 'photo') ? 'active in' : false}}" id="tab_content1" aria-labelledby="home-tab">
                      @include('backend.'.$folder.'.edit-details')
                    </div>
                  </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

{{-- Dropzone.js --}}
@push('headerscripts')
    <link href="{{asset('assets/admin/vendors/dropzone/dist/min/dropzone.min.css')}}" rel="stylesheet">
@endpush
@push('footerscripts')
    <script src="{{asset('assets/admin/vendors/dropzone/dist/min/dropzone.min.js')}}"></script>
    <script src="{{asset('assets/admin/build/js/pw-dropzone.js')}}"></script>
@endpush
