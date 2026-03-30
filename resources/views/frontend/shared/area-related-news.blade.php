<div class="flex-column flex-sm-row">
    @foreach($posts as $post)
        <a class="flex-fill related-news__article u-rounded-12 p-1 pl-4 pr-4 d-inline-block mt-3 mr-2" href="{{ url($post->slug) }}">{{ $post->title }} <span class="d-inline-block ml-1"><i class="fas fa-arrow-right"></i></span></a>
    @endforeach
</div><!-- /.row -->


