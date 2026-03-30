@extends('backend.layouts.master')

@section('admin-content')

<div class="row current_url" data-url="{{admin_url('bespoke-pages')}}">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <h2></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li class="top-button"><a href="{{url($page->route)}}" target="_blank" class="btn btn-small btn-primary">View Page</a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <div class="x_panel pw-inner-tabs">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="{{(empty($tab)) ? 'active' : false}}"><a href="{{ admin_url('bespoke-pages/'.$page->id.'/edit') }}" aria-expanded="true">Details</a></li>
                        <li role="presentation" class="{{($tab == 'photo') ? 'active' : false}}"><a href="{{ admin_url('bespoke-pages/'.$page->id.'/edit?tab=photo') }}" aria-expanded="false">Photo</a></li>
                        <li role="presentation" class="{{($tab == 'meta') ? 'active' : false}}"><a href="{{ admin_url('bespoke-pages/'.$page->id.'/edit?tab=meta') }}" aria-expanded="false">Meta Data</a></li>
                        <li role="presentation" class="{{($tab == 'components') ? 'active' : false}}"><a href="{{ admin_url('bespoke-pages/'.$page->id.'/edit?tab=components') }}" aria-expanded="false">Components</a> </li>
                      </ul>
                      <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade {{empty($tab) ? 'active in' : false}}" id="tab_content1" aria-labelledby="home-tab">
                          @include('backend.pages.edit-details')
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{($tab == 'photo') ? 'active in' : false}}" id="tab_content2" aria-labelledby="profile-tab">
                          @include('backend.pages.edit-photo')
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{($tab == 'meta') ? 'active in' : false}}" id="tab_content3" aria-labelledby="profile-tab">
                          @include('backend.pages.edit-meta')
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{($tab == 'components') ? 'active in' : false}}" id="tab_content4" aria-labelledby="section-tab">
                            @include('backend.bespoke_pages.sections')
                        </div>
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
    <link href="{{url('assets/admin/vendors/dropzone/dist/min/dropzone.min.css')}}" rel="stylesheet">
@endpush
@push('footerscripts')
    <script src="{{url('assets/admin/vendors/dropzone/dist/min/dropzone.min.js')}}"></script>
    <script src="{{url('assets/admin/build/js/pw-dropzone.js')}}"></script>
@endpush
