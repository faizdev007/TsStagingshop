<section id="{{ $page_section->url }}">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="text-center">
                    <h3 class="f-bold f-22">{{ $page_section->title }}</h3>
                    <div class="generic-border u-center u-mb2"></div>
                </div><!-- /.text-center -->
            </div><!-- /.col-lg-12 -->
        </div><!-- /.row -->
        @if($page_section->section_contents->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <?php $count = 0; ?>
                        @foreach($page_section->section_contents as $content)
                            <?php $count++; ?>
                            @if($content->type == 'local_information')
                                <div class="col-md-12 col-lg-6 @if($count % 2 == 0) border-left @endif">
                                    <div class="u-pb2 u-pl2">
                                        <span class="f-16 text-uppercase c-secondary f-bold u-block u-mb1">{{ $content->title }}</span>
                                        <p class="f-semibold">{!! $content->content  !!}</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>