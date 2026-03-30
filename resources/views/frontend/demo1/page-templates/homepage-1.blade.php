@push('body_class') home-page-1 -full-banner @endpush

    @include('frontend.demo1.partials.front.home.banner')
    <div class="u-circle-style-1">
        <div class="u-circle-style-2 pb-3">
            @widget('featuredProperties', ['limit' => 3])
        </div>
    </div>

@include('frontend.demo1.partials.front.home.destinations')
@include('frontend.demo1.partials.front.home.selling')
@widget('testimonials')
@widget('latestNews')

