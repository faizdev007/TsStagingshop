<section class="page-title c-bg-gray-8 -placeholder lazyBg"
    @if(!empty($page->photo))
    style="background-image: url({{ blankImg() }})"
    {!!$page->BannerDataBG!!}  @endif>
    <div class="container">
        <div class="-wrap">
            <div class="-inner-wrap">
                <h1 class="-page-title-heading f-four f-50 f-500 c-white text-center">
                    {!! $page->title !!}
					<span class="-border {{ !empty($page->header_title)?'border-remove-title':'' }}"></span>
				</h1>
                @if($page->header_title)
                    <h2 class="-page-subtitle-heading f-16 f-two f-500 c-white d-block text-center">{{ $page->header_title }}</h2>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="page-content u-mt405 u-mb4">
    <div class="container">
        <div class="property-content-wrapper">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-9 col-12 text-center">
                    <div class="generic-text-content">
						{!! $page->content !!}
                    </div>
                     <div class="-c-cta mt-5">
                                <a href="{{lang_url('contact-us')}}" class="cta -secondary -wider-3">REQUEST A VALUTION</a>
                            </div>
                </div>

            </div>
        </div>
    </div>
</section>
<section class="service-page-content">
<div class="content-right-text-section">
    <div class="container-fluid">
        <div class="-wrap">
            <div class="row no-gutters">
                <div class="col-md-6">
                    <div class="-image">
                        <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('images/service/service2.jpg') }}" alt="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="-content pw-aligner">
                        <div class="-c-wrap">
                            <h2 class="-title f-24 f-500 f-two"> 5 Reasons to use {{ settings('site_name', config('app.name')) }}</h2>
                            <div class="-c-body f-15">
                               <strong> 1. Multi-Award Winning</strong>
                               <p> We are proud to be recognised as Bridgwater’s leading estate agent, we have
                                won regional and national awards for our customer service and marketing.</p>

                                <strong> 2. Five Star Reviewed</strong>
                                 <p> We have top ratings on Google, Facebook and allAgents; meaning by choosing
                                Joseph Casson to assist you in your move, you can be confident you will get
                                the absolute best in marketing and customer service.</p>

                                <strong> 3. Exceptional Photography</strong>
                                 <p> Our managing director is an award winning photographer and registered
                                drone operator. As a result, you can be certain your house will look its absolute
                                best and will be photographed from the right angles to maximize the appeal
                                of your home.</p></p>

                               <strong>  4. Targeted Marketing</strong>
                                <p>  In addition to Rightmove and the other major website portals, your property
                                will receive an individually tailored social media advertising campaign.</p>

                                <strong> 5. We’re here to help</strong>
                                <p>  We will be with you every step of the way offering guidance and assistance
                                from day one to the handing over of keys upon completion. We work around
                                your schedule and requirements to provide you a class-leading service.</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
          <!-----end row----------->
          <div class="row no-gutters flex-row-reverse middle-section">
                <div class="col-md-6">
                    <div class="-image">
                        <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('images/service/service4.jpg') }}" alt="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="-content pw-aligner">
                        <div class="-c-wrap">
                             <h2 class="-title f-24 f-500 f-two">We include the following FREE of charge</h2>
                            <div class="-c-body f-15">
                              <ul>
                                <li>Market Valuation / Home Visit</li>
                                <li>Home Staging Advice</li>
                                <li>For Sale / Sold Board (optional)</li>
                                <li>Aerial Photography / Imaging</li>
                                <li>Professional Photography</li>
                                <li>Virtual tours & Promo Video</li>
                                <li>HD Video</li>
                                <li>Floorplans</li>
                                <li>Bespoke Electronic Property Particulars</li>
                                <li>Accompanied Viewings</li>
                                <li>Viewing Feedback</li>
                                <li>Regular Marketing Updates</li>
                                <li>Offer / Sale Negotiations</li>
                                <li>Production of Sale Paperwork</li>
                                <li>Sales Progression</li>
                                <li>Completion Assistance / Key Transfer</li>
                              </ul>


                            </div>

                        </div>
                    </div>
                </div>
            </div>
          <!-----end row----------->
          <div class="row no-gutters">
                <div class="col-md-6">
                    <div class="-image">
                        <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('images/service/service3.jpg') }}" alt="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="-content">
                        <div class="-c-wrap">
                            <h2 class="-title f-24 f-500 f-two">Extensive Social Media Advertising including:</h2>
                            <div class="-c-body f-15">
                                <ul>
                                <li>Facebook</li>
                                <li>Twitter</li>
                                <li>YouTube</li>
                                <li>Instagram</li>
                              </ul>
                            </div>
                        </div>

                        <div class="-c-wrap">
                            <h2 class="-title f-24 f-500 f-two">Premium Options <span>(additional upfront cost)</span> </h2>
                            <div class="-c-body f-15">
                               <ul>
                                <li> Externally Printed Full Glossy Brochures</li>
                               <li> 4K Aerial / Upgraded Video Tour</li>
                           </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
          <!-----end row----------->

        </div>
    </div>
  </div>
</section>

<section class="service-logo u-mb4 u-pb4 u-pt4">
    <div class="container">
        <div class="property-content-wrapper  -wrap">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title f-two text-center f-28">Comprehensive Marketing</div>
                    <div class="generic-logo-content">
                      <ul class="service-item-logo">
                            <li>
                                <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('/images/footer-logos/naea-white.png') }}" alt="Footer Logo">
                            </li>
                            <li>
                                <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('/images/footer-logos/tpo-white.png') }}" alt="Footer Logo">
                            </li>
                            <li>
                                <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('/images/footer-logos/tsi-white.png') }}" alt="Footer Logo">
                            </li>
                            <li>
                                <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('/images/footer-logos/rightmove-white.png') }}" alt="Footer Logo">
                            </li>
                            <li>
                                <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('/images/footer-logos/zoopla-white.png') }}" alt="Footer Logo">
                            </li>
                            <li>
                                <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('/images/footer-logos/onthemarket-white.png') }}" alt="Footer Logo">
                            </li>
                            <li>
                                <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('/images/footer-logos/homesearch-white.png') }}" alt="homesearch-white">
                            </li>
                            <li>
                                <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('/images/footer-logos/nethouseprices-white.png') }}" alt="nethouseprices-white">
                            </li>
                             <li>
                                <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('/images/footer-logos/Prime-Location-White.png') }}" alt="Prime-Location-White">
                            </li>
                            <li>
                                <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('/images/footer-logos/mail.png') }}" alt="mailonline">
                            </li>
                            <li>
                                <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('/images/footer-logos/the-sunday-times-logo.png') }}" alt="the-sunday-times">
                            </li>
                            <li>
                                <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('/images/footer-logos/the-telegraph.png') }}" alt="the-telegraph">
                            </li>
                              

                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>


<section class="other-service u-mt405 u-mb4">
    <div class="container">
        <div class="property-content-wrapper  -wrap">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title f-two text-center f-28 mb-3">Other Services</div>
                    <div class="generic-service-content">
                        <div class="row">
                      <div class="col-md-4 service-item">
                        <div class="service-block">
                            <div class="start-icon"><i class="fa fa-star" aria-hidden="true"></i></div>
                             <p class="f-22 mt-3">Virtual tours & Promo video</p>
                            <div class="-c-cta mt-5 mb-3">
                                <a href="{{lang_url('the-altman-real-estate-group')}}" class="cta -secondary -wider-3">VIEW MORE</a>
                            </div>
                        </div>
                      </div>
                    <div class="col-md-4 service-item">
                        <div class="service-block">
                            <div class="start-icon"><i class="fa fa-star" aria-hidden="true"></i></div>
                             <p class="f-22 mt-3">Brochures</p>
                            <div class="-c-cta mt-5 mb-3">
                                <a href="{{lang_url('the-altman-real-estate-group')}}" class="cta -secondary -wider-3">VIEW MORE</a>
                            </div>
                        </div>
                      </div>
                      <div class="col-md-4 service-item">
                        <div class="service-block">
                            <div class="start-icon"><i class="fa fa-star" aria-hidden="true"></i></div>
                             <p class="f-22 mt-3">Free HD videos</p>
                            <div class="-c-cta mt-5 mb-3">
                                <a href="{{lang_url('the-altman-real-estate-group')}}" class="cta -secondary -wider-3">VIEW MORE</a>
                            </div>
                        </div>
                      </div>
                      <div class="col-md-4 service-item">
                        <div class="service-block">
                            <div class="start-icon"><i class="fa fa-star" aria-hidden="true"></i></div>
                             <p class="f-22 mt-3">Premium video tour</p>
                            <div class="-c-cta mt-5 mb-3">
                                <a href="{{lang_url('the-altman-real-estate-group')}}" class="cta -secondary -wider-3">VIEW MORE</a>
                            </div>
                        </div>
                      </div>
                      <div class="col-md-4 service-item">
                        <div class="service-block">
                            <div class="start-icon"><i class="fa fa-star" aria-hidden="true"></i></div>
                             <p class="f-22 mt-3">NAEA Property Mark</p>
                            <div class="-c-cta mt-5 mb-3">
                                <a href="{{lang_url('the-altman-real-estate-group')}}" class="cta -secondary -wider-3">VIEW MORE</a>
                            </div>
                        </div>
                      </div>
                      <div class="col-md-4 service-item">
                        <div class="service-block">
                            <div class="start-icon"><i class="fa fa-star" aria-hidden="true"></i></div>
                             <p class="f-22 mt-3">Mortgage advice & solicitors</p>
                            <div class="-c-cta mt-5 mb-3">
                                <a href="{{lang_url('the-altman-real-estate-group')}}" class="cta -secondary -wider-3">VIEW MORE</a>
                            </div>
                        </div>
                      </div>

                     </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</section>

@include('frontend.demo1.forms.generic-bottom')
