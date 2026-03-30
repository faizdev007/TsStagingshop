@extends('backend.properties.template')

@section('property-content')

<div class="x_panel pw-inner-tabs">
    <div class="x_title">
        <h2>Documents</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content pw-open">

        <div class="upload-info">
            <div class="row">
                <div class="col-md-3">
                    <div class="xpw-notes">
                        <p>Image types accepted: .PDF, .JPG, .DOC, .DOCX, .RTF</p>
                        <p>Maximum file size: 6MB</p>
                        <p>Maximum of 35 Photos</p>
                    </div>
                    <div class="dz-error-message-display"></div>
                </div>
                <div class="col-md-9">
                    @if(count($property->propertyMediaDocuments))
                    <div class="upload-form-container">
                        <div class="action-btn"><button class="btn btn-info" data-toggle="collapse" data-target="#upload-form">Add New</button></div>
                    </div>
                    @endif
                </div>
                <div class="col-md-12 dz-hide-error-message">
                    <div id="upload-form" class="upload-form {{ count($property->propertyMediaDocuments) ? 'collapse' : '' }}">
                        <input type="hidden" class="property-id" name="property_id" value="{{ $property->id }}">
                        <form
                            action="{{ route('properties.document-upload.save', $property->id) }}"
                            method="POST"
                            enctype="multipart/form-data"
                            id="document-upload-form"
                        >
                            @csrf

                            <div class="dropzone v1" id="document-upload"></div>

                            <div class="upload-btn mt-3">
                                <button type="button" class="btn btn-success" id="submit-all">
                                    Upload
                                </button>

                                <a href="{{ url()->current() }}" class="btn btn-info">
                                    Refresh
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <form
            action="{{ route('properties.document.destroy', $property->id) }}"
            method="POST"
        >
            @csrf
            @method('DELETE')

            <div class="media-container">

                <input
                    id="media-sort-url"
                    type="hidden"
                    name="sort_url"
                    value="{{ admin_url('properties/'.$property->id.'/photo-sort') }}"
                >

                <input
                    id="media-caption-url"
                    type="hidden"
                    name="caption_url"
                    value="{{ admin_url('properties/'.$property->id.'/photo-caption') }}"
                >

                <div id="property-media-sort" class="row">

                    @foreach($property->propertyMediaDocuments as $media)
                        <div id="item-{{ $media->id }}" class="col-md-55">
                            <div class="thumbnail pw">

                                <div class="image view view-first">
                                    <span></span>
                                    <a href="{{ $media->photo_display }}" target="_blank">
                                        <i class="fas fa-file"></i>
                                    </a>
                                </div>

                                <div class="caption">
                                    <textarea
                                        name="name"
                                        data-id="{{ $media->id }}"
                                        class="property-media-caption"
                                        placeholder="Please enter..."
                                    >{{ $media->title }}</textarea>

                                    <div class="pmc-loading pmcl-{{ $media->id }}">
                                        saving...
                                    </div>
                                </div>

                                <div class="pw-checkbox">
                                    <label>
                                        <input
                                            type="checkbox"
                                            name="delete_ids[]"
                                            value="{{ $media->id }}"
                                            class="flat"
                                        >
                                    </label>
                                </div>

                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            <div class="form-group sticky-buttons">
                <a class="btn btn-large btn-primary caption-save">
                    Save
                </a>

                <a
                    href="{{ admin_url('properties') }}"
                    class="btn btn-default btn-spacing"
                >
                    Cancel <span>and Return</span>
                </a>

                <button
                    type="submit"
                    class="confirm-action btn btn-danger btn-spacing v2"
                    title="delete"
                >
                    <i class="fas fa-trash"></i> Delete Selected
                </button>
            </div>
        </form>

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

<script>
// Add this script for Dropzone validation
Dropzone.options.document-upload = {
    paramName: "file", // Name of the file input field
    maxFilesize: 6, // Maximum file size in MB
    acceptedFiles: "image/jpeg, image/png, image/gif, image/webp", application/pdf // Accepted file types
    maxFiles: 35, // Maximum number of files allowed
    init: function() {
        this.on("addedfile", function(file) {
            // Validation for maximum file size
            if (file.size > (6 * 1024 * 1024)) {
                this.removeFile(file);
                alert("File size is too large (max 6MB).");
            }
        });

        this.on("error", function(file, errorMessage) {
            // Validation for file type
            if (errorMessage.indexOf("invalid file type") !== -1) {
                this.removeFile(file);
                alert("Invalid file type. Accepted types: JPG, GIF, PNG, TIF, WEBP.");
            }
        });

        this.on("maxfilesexceeded", function(file) {
            this.removeFile(file);
            alert("Maximum number of files exceeded.");
        });
    }
};
</script>
@endpush
