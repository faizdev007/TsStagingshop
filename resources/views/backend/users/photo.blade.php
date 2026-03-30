@extends('backend.users.template')

@section('user-content')

<div class="x_panel pw-inner-tabs">
    <div class="x_title">
        <h2>Photo <small> user image </small></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
            <li><a class="close-link"><i class="fa fa-close"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">

        <div class="upload-info">
            <div class="row">
                <div class="col-md-3">
                    <div class="xpw-notes">
                        <p>Image types accepted: JPG, GIF, PNG, TIF</p>
                        <p>Maximum file size: 6MB</p>
                        <p>Drag file to the box below to upload or click to select file.</p>
                    </div>
                </div>
                <div class="col-md-9">
                    @if( !empty($user->path) )
                    <div class="upload-form-container">
                        <div class="action-btn"><button class="btn btn-info" data-toggle="collapse" data-target="#upload-form">Change Photo</button></div>
                    </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <div id="upload-form" class="upload-form {{ !empty($user->path) ? 'collapse' : '' }}" >
                        <input type="hidden" class="user-id" name="user_id" value="{{ $user->id }}">
                        <form
                            action="{{ route('user.photo-upload.save', $user->id) }}"
                            method="POST"
                            enctype="multipart/form-data">

                            @csrf

                            <div class="dropzone" id="user-photo-upload"></div>

                            <div class="upload-btn">
                                <button type="submit" class="btn btn-success" id="submit-all">
                                    Upload
                                </button>
                                <a href="{{ URL::current() }}" class="btn btn-info" id="refresh-page">
                                    Refresh
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        @if( !empty($user->path) )
        	<div class="thumb-img">
        		<img src="{{ storage_url($user->primary_photo) }}">
        		<a href="{{admin_url('users/'.$user->id.'/delete-photo')}}" class="btn btn-danger confirm-action" title="delete">Delete</a>
        	</div>
        @endif
    </div>
</div>

@endsection

@push('headerscripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- Dropzone.js -->
<link href="{{url('assets/admin/vendors/dropzone/dist/min/dropzone.min.css')}}" rel="stylesheet">
@endpush

@push('footerscripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- Dropzone.js -->
<script src="{{url('assets/admin/vendors/dropzone/dist/dropzone.js')}}"></script>
<script src="{{url('assets/admin/build/js/pw-dropzone.js')}}"></script>
@endpush
