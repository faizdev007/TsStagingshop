@if ($posts->count())
<section class="blog-snippet--section u-mb-110 u-mb-md-60">
    <div class="container">
        <div class="u-mw-md-400-c">
            <div class="row">

                <div class="col-md-12 col-lg-7 order-2 order-lg-1">
                     @foreach ($posts as $bg)
                    <div class="u-item-shadowed--style-1 mb-4">
                        <div class="row g-0  {{ $loop->iteration % 2 ? '' : 'column-reverse-mobile' }}">
                            <div class="col-md-6 {{ $loop->iteration % 2 ? '' : 'order-last' }}">
                                <div class="blog-snippet--img">
                                    <div class="blog-snippet--img-inner go-center fill">
                                        <a href="{{$bg->url}}" class="u-hover-opacity-70 u-animate-02">
                                            <img src="{{ blankImg() }}" class="hvr-grow b-lazy"  data-src="{{ $bg->PrimaryPhoto }}" alt="{{$bg->title}}" ></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex">
                                <div class="p-2">
                                    <div class="f-19 f-sm-18 c-gray f-line-height-102 mb-3">
                                        <a href="{{$bg->url}}" class="c-gray-link f-semibold text-uppercase u-hover-opacity-70">{{$bg->title}}</a>
                                    </div>
                                    <div class="mb-2 mb-sm-4 f-13 f-sm-12 f-line-height-102">
                                        {!! $bg->shortExcerpt !!}
                                    </div>
                                    <div class="pt-3">
                                        <a href="{{$bg->url}}" class="button -default -left-liner f-15 f-sm-12" aria-label="View article about {{$bg->title}}">View article</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="col-md-12 col-lg-5 order-1 order-lg-2">
                    <div class="blog-snippet--right ps-0 pt-0 ps-lg-5 pt-lg-5 mb-5 mb-lg-0">
                        <div class="generic-header--section mt-4">
                            <h3 class="generic-header--large f-45 f-lg-35 f-md-30 f-two" data-aos="fade-right">BLOG</h3>
                            <div class="generic-header--small -right-liner f-16 f-md-14 c-light-brown f-medium" data-aos="fade-left">
                                <span class="liner">&amp; NEWS</span>
                            </div>
                        </div>
                        <div class="f-14 u-mb-50">
                          Discover with us the world of Luxury real estate in Dubai & beyond in our Blog & News section, where you can find articles carefully written and researched to be informative and accurate. At Tereza Estates we pride ourselves on cutting-edge and custom-written content, to help our clients feel informed and have complete peace of mind with their property or land investment with us.
                        </div>
                        <div class="">
                            <a href="{{'blog'}}" class="button -default -left-liner f-14">SEE ALL</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
