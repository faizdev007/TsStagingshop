<div class="recent-box">
    <h3>recent Posts</h3>
    <ul>
        @foreach($posts as $bo)
        <li><a href="{{$bo->url}}">
            <h4>{!! $bo->title !!}</h4>
            <p>{!! Str::limit($bo->shortExcerptLong, 110,'...')!!}</p>
        </a></li>
        @endforeach
    </ul>
</div>
