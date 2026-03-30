<p>Drag file to the box below to upload or click to select file. Maximum file allowed is 10MB.</p>

<form action="{{admin_url('slides/'.$slide->id.'/upload')}}" data-redirect="{{admin_url('slides/'.$slide->id.'/edit')}}?tab=photo" class="dropzone" id="slide_dropzone" style="min-height: 150px;" enctype="multipart/form-data">
	@csrf
	<div class="fallback">
	    <input name="file" type="file" multiple />
	    <input type="submit" name="Upload">
	</div>
</form>
<br />

@php
$img = asset('storage/slides/'.basename($slide->photo));
@endphp

@if(!empty($slide->photo))
	<div class="thumb-img">
		<img src="{{$img}}">
		<a href="{{admin_url('slides/'.$slide->id.'/delete_photo')}}" class="btn btn-danger" onclick="return confirm('Are you sure to delete the slide photo?')">Delete</a>
	</div>
@endif
