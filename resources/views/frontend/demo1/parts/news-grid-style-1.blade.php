<div class="news-grid-style-1">
    <div class="-image-box">
        <div class="-image {{($post->photo)?'fill':''}}">
            <span></span>
            <a href="{{$post->url}}"><img class="hvr-grow b-lazy" src="{{ blankImg() }}" data-src="{{ $post->PrimaryPhoto }}" alt="{{ $post->title }}"></a>
        </div>
        @if(count($post->CategoryTagsArray))
            <div class="-categrories f-14">
                @foreach($post->CategoryTagsArray as $catUrl => $category)
                    <a href="{{$catUrl}}">{{$category}}</a>
                @endforeach
            </div>
        @endif
    </div>
    <div class="-cg-body">
        <div class="-date f-14 f-500">
            {{ $post->full_date }}
        </div>
        <div class="u-mb1 -cg-title fix-line">
            <a href="{{$post->url}}" class="f-22 f-500 c-gray-3">{{ $post->title }}</a>
        </div>
        <div class="f-15 c-gray-3 u-mb1 f-xs-12 -cg-desc">
            {!! $post->shortExcerpt !!}
        </div>
        <div class="-cg-cta">
            <a href="{{$post->url}}" class="f-14 f-600">READ MORE</a>
        </div>
    </div>
</div>
