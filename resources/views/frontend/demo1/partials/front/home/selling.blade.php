<section class="destination--section destination-grid--style-1 u-py-50 u-py-lg-50 u-py-md-30">
    <div class="container">
        <div class="generic-header--section text-center text-md-start">
            <h3 class="generic-header--large f-45 f-lg-35 f-md-30 f-two" data-aos="fade-left">FIND YOUR DREAM</h3>
            <h1 class="generic-header--small -left-liner f-16 f-md-14 c-light-brown" data-aos="fade-right">
                <span class="liner text-uppercase">{{ $page->title }}</span>
            </h1>
        </div>
        <div class="u-generic-text-content f-13 f-md-12">
            {!! $page->content !!}
        </div>
        <div id="additional-selling-text" style="display: none" width="100%" class="selling-hide">
            {!! $page->content1 !!}
        </div>
        <div class="text-center">
            <button class="button -default -left-liner f-14 ms-0 text-uppercase" id="home-read-more">read more</button>
        </div>
    </div>
</section>
