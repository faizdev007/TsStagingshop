@extends('backend.layouts.master')

@section('admin-content')

<div class="row current_url" data-url="{{admin_url('metadata')}}">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <h2></h2>
                <ul class="nav navbar-right panel_toolbox">
                  @if(!empty($metadata->url))
                  <li class="top-button"><a href="http://{{$metadata->url}}" target="_blank" class="btn btn-small btn-primary">View Page</a></li>
                  @endif
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

              <form action="{{admin_url('metadata/'.$metadata->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="xpw-fields">
                    <div class="row">

                      <div class="col-md-6">
                          <div class="control-form">
                              <label for="url">URL: {!! required_label() !!}</label>
                                <input
                                    type="text"
                                    name="url"
                                    id="url"
                                    class="form-control"
                                    placeholder="Please enter..."
                                    value="{{ old('url', $metadata->url) }}"
                                >
                          </div>
                      </div>

                      <div class="clear"></div>

                      <div class="col-md-6">
                          <div class="control-form">
                              <label for="title">Title: {!! required_label() !!}</label>
                              <input
                                    type="text"
                                    name="title"
                                    id="title"
                                    class="form-control"
                                    placeholder="Please enter..."
                                    value="{{ old('title', $metadata->title) }}"
                                >
                          </div>
                      </div>

                      <div class="clear"></div>

                      <div class="col-md-12">
                          <div class="control-form">
                              <label for="description">Description:</label>
                              <textarea
                                    name="description"
                                    id="description"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Please enter..."
                                >{{ old('description', $metadata->description) }}</textarea>
                          </div>
                      </div>

                    </div>

                    <div class="form-group sticky-buttons">
                        <button type="submit" class="btn btn-large btn-primary" >Save</button>
                        <a href="{{admin_url('metadata')}}" class="btn btn-default btn-spacing">Cancel <span>and Return</span></a>
                        <a href="{{admin_url('metadata/'.$metadata->id.'/delete')}}" class="confirm-action btn btn-danger btn-spacing" title="delete this metadata"><i class="fas fa-trash"></i> Delete</a>
                    </div>

                </div>

              </form>


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
