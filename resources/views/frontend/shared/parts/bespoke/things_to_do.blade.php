<section id="{{ $page_section->url }}">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="text-center">
                    <h3 class="f-bold f-22 u-mb2">{{ $page_section->title }}</h3>
                </div><!-- /.text-center -->
            </div><!-- /.col-lg-12 -->
        </div><!-- /.row -->
        @if($page_section->section_contents->count() > 0)
                @foreach($page_section->section_contents as $content)
                    @if($content->type == 'things_to_do')
                        <div class="row u-mb1">
                            <div class="col-12">
                                <div class="row">
                                    <div class="u-pl0 u-pr0 col-sm-12 col-lg-5">
                                        <img class="h-100 w-100" alt="Image" src="@if($content->image) {{ storage_url($content->image) }} @else {{ asset('assets/demo1/images/news2.jpg') }} @endif" />
                                    </div><!-- /.col-lg-5 -->
                                    <div class="u-pl0 u-pr0 col-sm-12 col-lg-7">
                                        <div class="u-pl2 u-pt2 c-bg-gray h-100 u-pb2">
                                            <span class="f-18 f-bold u-block u-mb1">{{ $content->title }}</span>
                                            {!! $content->content !!}
                                        </div>
                                    </div><!-- /.col-lg-7 -->
                                </div><!-- /.row -->
                            </div><!-- /.col-12 -->
                        </div><!-- /.row -->
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</section>