<?php
$num_items = $page_section->section_contents->count();
if($num_items > 0)
{
    $col_size = 12 / $num_items;
}
else
{
    $col_size = 12;
}
?>
<section id="{{ $page_section->url }}" class="home-3-popular-locations">
    <div class="container">
        <div class="wrap">
            <div class="section-header generic-header">
                <h2 class="f-two f-24 f-bold c-dark f-two u-block u-mb1">Popular Locations</h2>
                <div class="generic-border u-center"></div>
            </div>
            <div class="locations-box">
                <div class="row">
                    @foreach($page_section->section_contents as $content)
                        <div class="col-md-12 col-lg-{{ $col_size }}">
                            <a href="@if($content->url) {{url($content->url)}} @else # @endif" >
                                <div class="location-item">
                                    <img src="@if($content->image) {{ asset('storage/'.$content->image) }} @else {{ themeAsset('images') }}/home-3/pop-location-1.jpg @endif" alt="">
                                    <div class="location-label">
                                        <span>{{ $content->title }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
