@push('body_class') generic-page article-page @endpush
@extends('frontend.demo1.layouts.frontend')
@section('main_content')
<!-- Bread Crumb -->
<section class="inner-hero bread-block">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="pager-bx">
                      @include('frontend.demo1.partials.front.pw.breadcrumb')
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="backstepbx">
                    <a href="{{url('blog')}}"><i class="fas fa-angle-left"></i> BACK TO BLOG</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Bread Crumb -->

<!-- Blog Listing -->
<section class="blog-wrapper u-circle-style-1 u-circle-style-2">
    <div class="container">
        <div class="blog-details-wrp">
            <h2>{{ $post->title }}</h2>
            <h6>{{ $post->full_date }}</h6>

@if($post->PrimaryPhotoFlag)
                        <div class="article-banner blog-img">
                            <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ $post->PrimaryPhoto }}" alt="{{ $post->title }}">
                        </div>@endif

        

            <div class="blog-details-wrp p">
                 {!! $post->content !!}
            </div>

            <div class="share-blog">
                <h5>Share</h5>                
                <a href="https://www.facebook.com/sharer.php?u={{$post->url}}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <!--a onclick="window.open('https://www.instagram.com/sharer.php?u=' + encodeURIComponent('{{$post->url}}'), 'instagram-share', 'width=550,height=450')" rel="nofollow" target="_blank"><i class="fab fa-instagram"></i></a--->
                <a href="https://wa.me/?text={{$post->url}}&amp;description={{ $post->title }}" rel="nofollow" target="_blank"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
</section>
<!-- End Blog Listing -->
@endsection