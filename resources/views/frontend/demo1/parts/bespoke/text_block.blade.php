<section id="{{ $page_section->url }}" class="c-bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="text-center">
                    <h3 class="f-bold f-22">{{ $page_section->title }}</h3>
                    <div class="generic-border u-center u-mb2"></div>
                </div><!-- /.text-center -->
            </div><!-- /.col-lg-12 -->
        </div><!-- /.row -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="text-center">
                    <p>{!! $page_section->content !!}</p>
                </div>
            </div>
        </div>
    </div>
</section>