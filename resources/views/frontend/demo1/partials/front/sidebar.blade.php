<div class="index-block other">
    <?php $segment = \request()->segment(1); ?>
    @if($segment=="blog-news")
    @include('partials.front.blog-search')
    @include('partials.front.blog-others')
    @include('partials.front.tags')
    @endif
    @include('partials.front.newsletter')
</div>