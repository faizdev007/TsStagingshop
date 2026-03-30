<div class="x_panel pw-inner-tab">
	<p>Drag file to the box below to upload or click to select file. Maximum file allowed is 10MB.</p>

	<form action="{{admin_url('news/'.$article->id.'/upload')}}" data-redirect="{{admin_url('news/'.$article->id.'/edit')}}?tab=photo" class="dropzone" id="news_dropzone" style="min-height: 150px;" enctype="multipart/form-data">
		@csrf
		<div class="fallback">
		    <input name="file" type="file" />
		    <input type="submit" name="Upload">
		</div>
	</form>
	<br />

	@php
	$img = asset('storage/posts/'.basename($article->photo));
	@endphp

	@if(!empty($article->photo))
		<div class="thumb-img">
			<img src="{{$img}}">
			<a href="{{admin_url('news/'.$article->id.'/delete_photo')}}" class="btn btn-danger" onclick="return confirm('Are you sure to delete the article photo?')">Delete</a>
		</div>
	@endif
</div>
