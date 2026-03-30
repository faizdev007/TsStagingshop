@push('body_class')news-page @endpush

@extends('frontend.demo1.layouts.frontend')

@section('main_content')
<!-- inner-hero -->
<section class="inner-hero">
    <div class="container">
       @include('frontend.demo1.partials.front.pw.breadcrumb')
        <h1 class="f-two">Blog</h1>
    </div>
</section>
<!-- End inner-hero -->

<!-- Blog Listing -->
<section class="blog-wrapper u-circle-style-1 u-circle-style-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12 col-sm-12">
          @if($posts->count())
                    <div class="blog-lst-blx">

                        @php $i = 1; @endphp
            @foreach ($posts as $post)
                        <div class="blog-block-bx">
                            @if(!empty($post->PrimaryPhoto))
                            <div class="blog-img">
                                 <a href="{{$post->url}}"><img class="b-lazy" src="{{ blankImg() }}" data-src="{{ $post->PrimaryPhoto }}" alt="{{ $post->title }}"></a>

                            </div>@endif

                            <div class="blog-data">
                                <h3>
                                <a href="{{$post->url}}" class="u-hover-opacity-50">{{ $post->title }}</a>
                                    </h3>
                                <div class="f-13">
                                    {!! $post->shortExcerptLong !!}
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="blog-readbtn mt-3">
                                            <a href="{{$post->url}}" class="button -default -left-liner f-14 f-sm-12 u-hover-opacity-50">READ MORE</a>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="social-bx">
                                            <a href="https://www.facebook.com/sharer.php?u={{$post->url}}" target="_blank"><i class="fab fa-facebook-f"></i></a>

                                           
                                            <a href="https://wa.me/?text={{$post->url}}&amp;description={{ $post->title }}" rel="nofollow" target="_blank"><i class="fab fa-whatsapp"></i></a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
            @endif
              </div>
            @if ($posts->hasPages())
                <div class="pagination-style-1 u-text-right pager-bx">
                    {{ $posts->appends(request()->input())->onEachSide(1)->links('vendor.pagination.style-1') }}
                </div>
            @endif
                       
          
          
        </div>

        <div class="offset-lg-1 col-lg-3 col-md-12 col-sm-12">
                <div class="blog-sidebar">
                    @include('frontend.demo1.partials.front.blog-search')
                    @include('frontend.demo1.partials.front.blog-others')
                </div>
            </div>

    </div>
</section>
<!-- End Blog Listing -->
@endsection
