@extends('backend.layouts.master')

@section('admin-content')

    <div class="row current_url" data-url="{{ admin_url('bespoke-pages') }}">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <h2>Edit Content - {{ $content->title }}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li class="top-button"><a href="{{ admin_url('bespoke-pages/'.$content->section->page_id.'/edit?tab=components') }}" class="btn btn-small btn-primary">Back</a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content ">
                    <p class="text-muted font-13 m-b-30"><br/></p>
                    <div class="x_panel pw-inner-tabs">
                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Details</a></li>
                                @if($content->type == 'things_to_do' || $content->type == 'popular_locations')
                                    <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Photo</a></li>
                                @endif
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                    <div class="x_content pw-open">
                                        <div class="xpw-fields">
                                            <form method="post" action="{{ admin_url('bespoke-section-content/'.$content->id.'/edit') }}" data-toggle="validator">
                                                <input type="hidden" name="type" value="{{ $content->type}}">
                                                @csrf
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label>Title: {!! required_label() !!}</label>
                                                        <input name="title" type="text" class="form-control" value="{{ $content->title }}" placeholder="Section Title" required>
                                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($content->type == 'popular_locations')
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label>URL: {!! required_label() !!}</label>
                                                        <input name="url" type="text" class="form-control" value="{{ $content->url }}" placeholder="Page URL" required>
                                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label>Content: {!! required_label() !!}</label>
                                                        <textarea id="content" name="content" class="mceEditor description" placeholder="Please enter" required>{{ $content->content }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group sticky-buttons">
                                        <button type="submit" class="btn btn-large btn-primary" name="action" >Save</button>
                                        <a href="#" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
                                    </div>

                                    </form>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_content2">
                                    <p>Drag file to the box below to upload or click to select file. Maximum file allowed is 10MB.</p>

                                    <form action="{{admin_url('things-to-do/'.$content->id.'/upload')}}" data-redirect="{{admin_url('bespoke-section-content/'.$content->id.'/edit')}}" class="dropzone" id="things_dropzone" style="min-height: 150px;" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="content_id" value="{{ $content->id }}">
                                        <div class="fallback">
                                            <input name="file" type="file" />
                                            <input type="submit" name="Upload">
                                        </div>
                                    </form>
                                    <br />
                                    @php
                                        $img = asset('storage/pages/bespoke/'.basename($content->image));
                                    @endphp

                                    @if(!empty($content->image))
                                        <div class="thumb-img">
                                            <img src="{{$img}}">
                                            <a href="#" class="btn btn-danger modal-toggle"
                                               data-item-id="{{ $content->id }}"
                                               data-toggle="modal"
                                               data-modal-type="delete"
                                               data-modal-title="Delete"
                                               data-modal-size="small"
                                               data-delete-type="things-to-do"
                                               data-target="#global-modal">Delete</a>
                                        </div>
                                    @endif
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