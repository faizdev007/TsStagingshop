@if(!empty($post))
<div class="news-grid-style-2">
    <div class="-image-box">
        <div class="-image {{($post->photo)?'fill':''}}">
            <span></span>
            <a href="{{$post->url}}"><img class="hvr-grow b-lazy" src="{{ blankImg() }}" data-src="{{ $post->PrimaryPhoto }}" alt="{{ $post->title }}"></a>
        </div>
        <div class="-date f-15 f-500">
            <span>{{ $post->full_date }}</span>
        </div>
    </div>
    <div class="-cg-body text-center">
        <div class="u-mb1 -cg-title fix-line">
            <a href="{{$post->url}}" class="f-22 f-xs-17 f-500 c-gray-3">{{ $post->title }}</a>
        </div>
        <div class="f-15 c-gray-3 u-mb1 u-line-height103 f-xs-12 -cg-desc">
            {!! $post->shortExcerpt !!}
        </div>
        <div class="-cg-cta">
            <a href="{{$post->url}}" class="f-15 f-600">READ MORE</a>
        </div>
    </div>
</div>
@endif
