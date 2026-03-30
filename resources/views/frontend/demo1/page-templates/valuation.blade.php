@push('body_class')valuation-page @endpush
<section class="page-title c-bg-gray-8 -generic lazyBg"
    @if(!empty($page->photo))
    style="background-image: url({{ blankImg() }})"
    {!!$page->BannerDataBG!!} @endif>
    <div class="container">
        <div class="-wrap">
            <div class="-inner-wrap">
                <div class="text-center">
                    <h1 class="-page-title-heading f-four f-50 f-500 c-white text-center">
                        {!! $page->title !!}
                        <span class="-border {{ !empty($page->header_title)?'border-remove-title':'' }}"></span>
                    </h1>
                    @if($page->header_title)
                        <h2 class="f-16 f-two f-400 c-white d-block text-uppercase">{{ $page->header_title }}</h2>
                    @endif
                </div>
            </div>
        </div>
        <div class="down-arrow"><a href="#valuationform"><i class="fas fa-angle-down"></i></a></div>
    </div>
</section>

<section class="page-content gray-bg">
    <div class="container">
        <div class="valuation-content-wrapper  u-pt405 u-pb405 ">
            <div class="row">
                <div class="col-md-4">
                    <div class="why_choose">
                        <h3 class="f-40">Why Choose Us?</h3>
                        <p class="f-20">Here are just a few reasons how we can help you sell or let your property</p>
                        <div class="-c-cta mt-3" id="valuationform">
                                <a href="{{lang_url('the-altman-real-estate-group')}}" class="cta -secondary -wider-3">GET VALUATION</a>
                            </div>
                    </div>
                </div>
                 <div class="col-md-8 why_choose_list">
                    <ul>
                        <li><i class="fa fa-star" aria-hidden="true"></i> Multi-Award Winning Estate Agent</li>
                        <li><i class="fa fa-star" aria-hidden="true"></i> Expert Local Knowledge</li>
                        <li><i class="fa fa-star" aria-hidden="true"></i> National, Regional & Local Advertising</li>
                        <li><i class="fa fa-star" aria-hidden="true"></i> Exceptional Modern Marketing</li>
                        <li><i class="fa fa-star" aria-hidden="true"></i> Personal, Tailored Service</li>
                        <li><i class="fa fa-star" aria-hidden="true"></i> No Sale, No Fee Agreements</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="page-content u-mt405 u-mb405" >
    <div class="container">
        <div class="property-content-wrapper page-inner-container">
            <div class="row">
                <div class="col-12">
                    <div class="f-30 f-400 text-center mb-5 mobile-heading-valuation" >Arrange your free market valuation</div>
                   @include('frontend.demo1.forms.valuation')
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.demo1.forms.generic-bottom')
