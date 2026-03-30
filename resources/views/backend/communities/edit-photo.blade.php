<div class="x_panel pw-inner-tabs">
    <div class="x_title">
        <h2>Photos</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content pw-open">
		<div class="upload-info">
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<div class="xpw-notes">
						<p>Image types accepted: JPG, JPEG, PNG</p>
						<p>Maximum file size: 6MB</p>
					</div>
					<div class="dz-error-message-display"></div>
				</div>
				<div class="col-md-12 col-sm-12 dz-hide-error-message">
					<div id="upload-form" class="upload-form">
						<input type="hidden" class="entry-id" name="id" value="{{$item->id}}">
						<input type="hidden" class="redirect-photo-upload" name="redirect" value="{{ URL::current() }}?tab=photo">
						<form
							action="{{ route('communities.photo-upload.save', $item->id) }}"
							method="POST"
							enctype="multipart/form-data"
							id="photo-upload-form"
						>
							@csrf

							<div class="dropzone v1" id="{{ $folder }}-photo-upload"></div>

							<div class="upload-btn">
								<button type="submit" class="btn btn-success" id="submit-all">
									Upload
								</button>

								<a
									href="{{ url()->current() }}?tab=photo"
									class="btn btn-info"
									id="refresh-page"
								>
									Refresh
								</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div style="
			display: inline-flex;
			flex-wrap: wrap;
			flex-direction: row;
			align-content: center;
			justify-content: center;
			align-items: center;
			gap: 10px;
		">
			@if($item->photos)
				@foreach($item->photos as $key=>$photo)
					<div class="thumb-img">
						<img  src="{{asset('storage/'.$photo)}}" style="aspect-ratio: 16 / 9;">
						<a href="{{ admin_url($folder.'/'.$item->id.'/delete_photo?key='.$key) }}" class="btn btn-danger" style="border-radius: 0px 0px 5px 5px !important;" onclick="return confirm('Are you sure to delete the {{$module_title}} photo?')">Delete</a>
					</div>
				@endforeach
			@elseif($item->photo)
				@php
					$img = asset('storage/'.$item->photo);
				@endphp
				@if(!empty($item->photo))
					<div class="thumb-img">
						<img  src="{{$img}}">
						<a href="{{admin_url($folder.'/'.$item->id.'/delete_photo')}}" class="btn btn-danger" style="border-radius: 0px 0px 5px 5px !important;" onclick="return confirm('Are you sure to delete the {{$module_title}} photo?')">Delete</a>
					</div>
				@endif
			@endif
		</div>
    </div>
</div>
