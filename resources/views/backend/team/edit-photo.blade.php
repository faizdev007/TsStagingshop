<p>Drag file to the box below to upload or click to select file. Maximum file allowed is 10MB.</p>

<form action="{{admin_url('team/'.$team->team_member_id.'/upload')}}" data-redirect="{{admin_url('team/'.$team->team_member_id.'/edit')}}?tab=photo" class="dropzone" id="slide_dropzone" style="min-height: 150px;" enctype="multipart/form-data">
    @csrf
    <div class="fallback">
        <input name="file" type="file" multiple />
        <input type="submit" name="Upload">
    </div>
</form>
<br />

@php
    $img = asset('storage/team/'.basename($team->team_member_photo));
@endphp

@if(!empty($team->team_member_photo))
    <div class="thumb-img">
        <img src="{{$img}}">
        <a href="{{admin_url('team/'.$team->team_member_id.'/delete-photo')}}" class="btn btn-danger" onclick="return confirm('Are you sure to delete the team members photo?')">Delete</a>
    </div>
@endif
