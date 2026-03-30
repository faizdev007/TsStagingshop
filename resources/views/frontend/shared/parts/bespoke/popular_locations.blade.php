<?php
    $num_items = $page_section->section_contents->count();
    $col_size = 12 / $num_items;
?>
<section class="popular-locations" id="{{ $page_section->url }}">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="text-center">
                    <h3 class="f-bold f-22 u-mb2">{{ $page_section->title }}</h3>
                </div><!-- /.text-center -->
            </div><!-- /.col-lg-12 -->
        </div><!-- /.row -->
        @if($num_items > 0)
                <div class="row">
                    @foreach($page_section->section_contents as $content)
                        <div class="col-md-12 col-lg-{{ $col_size }}">
                            <a class="location-item -no-bg w-100 u-block u-mb2" href="{{ $content->page->route }}">
                                <img src="@if($content->page->photo) {{ asset('storage/'.$content->page->photo) }} @else {{ themeAsset('images') }}/location1.jpg @endif" class="w-100" alt="">
                                <span class="location-name">{{ $content->page->title }}</span>
                                <div class="cover"></div>
                            </a>
                        </div>
                    @endforeach
                </div>
        @endif
    </div>
</section>
