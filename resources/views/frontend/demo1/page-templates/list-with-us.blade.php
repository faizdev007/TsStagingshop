<section class="page-title c-bg-gray-8 -list-with-us lazyBg"
    @if(!empty($page->photo))
    style="background-image: url({{ blankImg() }})"
    {!!$page->BannerDataBG!!}  @endif>
    <div class="container">
        <div class="-wrap">
            <div class="-inner-wrap">
                <h1 class="-page-title-heading f-four f-60 f-500 c-white text-center">
                    {!! $page->title !!}
					<span class="-border"></span>
				</h1>
                @if($page->header_title)
                    <h2 class="-page-subtitle-heading f-26 f-two f-500 c-white d-block text-center">{{ $page->header_title }}</h2>
                @endif
                <div class="-page-title-desc f-16 c-white text-center">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
        <div class="arrow-down-section text-center">
            <a href="#steps" class="linkSlide pw-arrow-down-cta"> <i class="fas fa-chevron-down"></i> </a>
        </div>
    </div>
</section>

<section id="steps" class="page-content u-mt6 u-mt-md-2 u-mt-xs-1 u-mb1">
    <div class="container">
        <div class="list-with-us-box">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-6">
                    <div class="list-with-us-grid -right-bordered -bordered text-center">
                        @if(0)<div class="-icon"><span class="-icon-pw icon-click"></span></div>@endif
                        <div class="-icon">
                            <img class="b-lazy" src="{{ blankImg() }}" data-src="{{themeAsset('images/icons/step-1.png')}}" alt="Step One">
                        </div>
                        <div class="-title f-30 f-two">Step One <div class="-bordered"></div></div>
                        <div class="-desc f-16">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt</div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-6">
                    <div class="list-with-us-grid -center-bordered -bordered text-center">
                        @if(0)<div class="-icon"><span class="-icon-pw icon-service-01"></span></div>@endif
                        <div class="-icon">
                            <img class="b-lazy" src="{{ blankImg() }}" data-src="{{themeAsset('images/icons/step-2.png')}}" alt="Step Two">
                        </div>
                        <div class="-title f-30 f-two">Step Two <div class="-bordered"></div></div>
                        <div class="-desc f-16">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt</div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-6">
                    <div class="list-with-us-grid -left-bordered -bordered text-center">
                        @if(0)<div class="-icon"><span class="-icon-pw icon-property-star"></span></div>@endif
                        <div class="-icon">
                            <img class="b-lazy" src="{{ blankImg() }}" data-src="{{themeAsset('images/icons/step-3.png')}}" alt="Step Three">
                        </div>
                        <div class="-title f-30 f-two">Step Three <div class="-bordered"></div></div>
                        <div class="-desc f-16">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt</div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-6">
                    <div class="list-with-us-grid -right-bordered -bordered text-center">
                        @if(0)<div class="-icon"><span class="-icon-pw icon-service-01"></span></div>@endif
                        <div class="-icon">
                            <img class="b-lazy" src="{{ blankImg() }}" data-src="{{themeAsset('images/icons/step-4.png')}}" alt="Step Four">
                        </div>
                        <div class="-title f-30 f-two">Step Four <div class="-bordered"></div></div>
                        <div class="-desc f-16">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt</div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-6">
                    <div class="list-with-us-grid -center-bordered -bordered text-center">
                        @if(0)<div class="-icon"><span class="-icon-pw icon-service-01"></span></div>@endif
                        <div class="-icon">
                            <img class="b-lazy" src="{{ blankImg() }}" data-src="{{themeAsset('images/icons/step-5.png')}}" alt="Step Five">
                        </div>
                        <div class="-title f-30 f-two">Step Five <div class="-bordered"></div></div>
                        <div class="-desc f-16">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt</div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-6">
                    <div class="list-with-us-grid -blue -left-bordered -bordered text-center pw-aligner-center">
                        <div>
                            <div class="-title f-30 f-bold f-two c-white">Ready to list?</div>
                            <div class="-desc f-24 f-two c-white">Get in touch with<br/> us today!</div>
                            <div class="-cta"><a href="{{lang_url('contact-us')}}" class="c-white f-14">CONTACT US</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.demo1.forms.generic-bottom')
