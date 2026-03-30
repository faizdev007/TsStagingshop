@if(!empty($search_content))
    <section class="default-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <!-- <h2 class="d-block mb-2">{{ $search_content->content_title }}</h2>
                    <p>{!! $search_content->content !!}</p> -->
                    @if($search_content->blocks->first())
                        @foreach($search_content->blocks as $block)
                            <h3 class="d-block mb-2">{{ $block->heading }}</h3>
                            <p>{!! $block->content !!}</p>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif
