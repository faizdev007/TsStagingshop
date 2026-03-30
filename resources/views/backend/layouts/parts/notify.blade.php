{!!get_flash_alert()!!}

@if(count($errors)>0)
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
        @foreach($errors->all() as $error)
            {!! $error !!}
        @endforeach
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
        {{session('success')}}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
        {!!session('error')!!}
    </div>
@endif
