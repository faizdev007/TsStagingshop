<div class="x_panel  pw-inner-tab">
	<p>Drag file to the box below to upload or click to select file. Maximum file allowed is 10MB.</p>
	<?php
	$segment = request()->segment(2);
	?>
	<form action="{{admin_url('pages/'.$page->id.'/upload')}}" data-redirect="{{admin_url($segment.'/'.$page->id.'/edit')}}?tab=photo" class="dropzone" id="page_dropzone" style="min-height: 150px;" enctype="multipart/form-data">
		@csrf
		<div class="fallback">
		    <input name="file" type="file" multiple />
		    <input type="submit" name="Upload">
		</div>
	</form>
	<br />

	<br />

	@php
	$img = asset('storage/pages/'.basename($page->photo));
	@endphp

	@if(!empty($page->photo))
		<div class="thumb-img">
			<img src="{{$img}}">
			<a href="{{admin_url('pages/'.$page->id.'/delete_photo')}}" class="btn btn-danger" onclick="return confirm('Are you sure to delete the photo?')">Delete</a>
		</div>
	@endif

</div>
