<footer class="bg-gray">
    <div class="container">
        <div class="footer--wrap ms-auto me-auto">
            <div class="text-center mb-5">
                <a href="#"><img src="{{asset('assets/pw/images/logos/footer-logo.svg')}}" width="231" height="63" alt="Footer Logo"></a>
            </div>
            <div class="c-white text-center f-13 u-mw-711 ms-auto me-auto mb-5">
                We represent some of the most sought after Ultra-Luxury Properties for Sale in Thailand; including such destinations as Koh Samui, Koh Phangan, Phuket & Bangkok.
            </div>

            <div class="footer-sn--wrap text-center mb-5">
                <div class="footer-sn--item">
                    <a href="tel:+66929591299" class="u-hover-opacity-70" target="_blank">
                        <i class="fa fa-phone"></i></a>
                </div>
                <div class="footer-sn--item">
                    <a href="https://www.facebook.com/conradluxuryvillas" class="u-hover-opacity-70" target="_blank">
                        <i class="fab fa-facebook-f"></i></a>
                </div>
                <div class="footer-sn--item">
                    <a href="https://www.instagram.com/conradvillas/" class="u-hover-opacity-70" target="_blank">
                        <i class="fab fa-instagram"></i></a>
                </div>
                <div class="footer-sn--item">
                    <a href="https://www.linkedin.com/company/conrad-villas" class="u-hover-opacity-70" target="_blank">
                        <i class="fab fa-linkedin-in"></i></a>
                </div>
                {{--<div class="footer-sn--item">
                    <a href="https://twitter.com/Property_Conrad" class="u-hover-opacity-70" target="_blank">
                        <i class="fab fa-twitter"></i></a>
                </div>--}}
                <div class="footer-sn--item">
                    <a href="mailto:info@conradvillas.com" class="u-hover-opacity-70" target="_blank">
                        <i class="far fa-envelope"></i></a>
                </div>
            </div>

            <div class="footer-link--container">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <div class="footer-link--container-divider">
                            <div class="row">
                                <div class="col-sm-6 col-md-2">
                                    <div class="footer-link--header c-white f-14 f-medium mb-4" data-target=".footer-link-content-info">
                                        INFORMATION
                                        <i class="fas fa-angle-down"></i>
                                    </div>
                                    <div class="footer-link--header-content footer-link-content-info">
                                        <div class="">
                                            <a href="{{ url('/') }}" class="c-white-link f-13 f-regular u-hover-opacity-70">
                                                Home</a>
                                        </div>
                                        <div class="">
                                            <a href="{{ url('aboutus') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                About Us</a>
                                        </div>
                                        <div class="">
                                            <a href="{{ url('buyers-guide') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                Guide</a>
                                        </div>
                                        <div class="">
                                            <a href="{{ url('our-partners') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                Our Partners</a>
                                        </div>
                                        <div class="">
                                            <a href="{{ url('blog-news') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                Blog &amp; News</a>
                                        </div>
                                        <div class="">
                                            <a href="{{ url('privacy') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                Privacy</a>
                                        </div>
                                        <div class="">
                                            <a href="{{ url('terms-conditions') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                Terms</a>
                                        </div>

                                        <div class="footer-link--header-content-settings">
                                            @include('partials.front.pw.footer-settings')
                                        </div>

                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-5">
                                    <div class="ps-0 ps-md-5 mt-4 mt-sm-0 mt-md-0 mt-lg-0">
                                        <div class="footer-link--header c-white f-14 f-medium mb-4" data-target=".footer-link-content-properties">
                                            PROPERTIES
                                            <i class="fas fa-angle-down"></i>
                                        </div>
                                        <div class="footer-link--header-content footer-link-content-properties">
                                            <div>
                                                <a href="{{ url('property-for-sale-in-thailand') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                    Property for Sale in Thailand</a>
                                            </div>
                                            <div>
                                                <a href="{{ url('koh-samui-property-for-sale') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                    Property for Sale in Koh Samui</a>
                                            </div>
                                            <div>
                                                <a href="{{ url('property-for-sale-phuket') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                    Property for Sale in Phuket</a>
                                            </div>
                                            <div>
                                                <a href="{{ url('property-for-sale-bangkok') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                    Property for Sale in Bangkok</a>
                                            </div>
                                            <div>
                                                <a href="{{ url('property-for-sale-koh-phangan') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                    Property for Sale in Koh Phangan</a>
                                            </div>
                                            <div>
                                                <a href="{{ url('property-for-sale-chiang-mai') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                    Property for Sale in Chiang Mai</a>
                                            </div>
                                            <div>
                                                <a href="{{ url('property-for-sale-pattaya') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                    Property for Sale in Pattaya</a>
                                            </div>
                                            <div>
                                                <a href="{{ url('property-for-sale-bali') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                    Property for Sale in Bali</a>
                                            </div>
                                            <div>
                                                <a href="{{ url('covid-property-discounts-thailand') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                    Covid Property Discounts</a>
                                            </div>
                                            <div>
                                                <a href="{{ url('buy-real-estate-in-thailand-with-bitcoin') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                    Crypto Property Investments</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-5 mt-4 mt-md-0 mt-lg-0">
                                    <div class="footer-link--header c-white f-14 f-medium mb-4" data-target=".footer-link-content-blog">
                                        BLOG &amp; UPDATES
                                        <i class="fas fa-angle-down"></i>
                                    </div>
                                    <div class="footer-link--header-content footer-link-content-blog mb-4">
                                        <div>
                                            <a href="{{ url('buyers-guide/why-buy-property-koh-samui') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                Why Buy Property on Koh Samui?</a>
                                        </div>
                                        <div>
                                            <a href="{{ url('buyers-guide/thailand-property-ownership') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                Thailand Property Ownership</a>
                                        </div>
                                        <div>
                                            <a href="{{ url('buyers-guide/thailand-land-titles') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                Thailand Land Titles</a>
                                        </div>
                                        <div>
                                            <a href="{{ url('buyers-guide/thailand-property-taxes-fees') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                Thailand Property Taxes & Fees</a>
                                        </div>
                                        <div>
                                            <a href="{{ url('buyers-guide/thailand-land-measurements') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                Land Measurement in Thailand</a>
                                        </div>
                                        <div>
                                            <a href="{{ url('/buyers-guide/property-real-estate-faq') }}" class="c-white-link f-13 u-hover-opacity-70">
                                                Koh Samui Property &amp; Real Estate FAQ</a>
                                        </div>
                                    </div>
                                    <form action="{{ url('find') }}" method="post">
                                        @csrf
                                        <label class="text-white text-uppercase f-14 f-medium mb-2">Search by Ref ID</label>
                                        <div class="position-relative search--ref-input-group">
                                            <input type="text" name="ref" class="search--ref-id" placeholder="Enter property ID"/>
                                            <button type="submit" class="search--button position-absolute top-50 end-0 translate-middle-y"><i class="fa fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">

                        <div class="footer-link-settings-desktop row mt-5 mt-lg-0">
                            <div class="col-md-6 col-lg-7">
                                <div class="ps-0 ps-lg-5 text-center text-md-end text-lg-start">
                                    <div class="u-mb-40">
                                        <img src="/assets/pw/conrad-images/footer-partner-1.png" class="img-fluid" width="139" height="24" alt="LuxuryEstate">
                                    </div>
                                    <div class="u-mb-40">
                                        <img src="/assets/pw/conrad-images/winner-black-vert-1.svg" class="img-fluid" width="139" height="19" alt="JamesEdition">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-5 text-center text-md-start text-lg-end">
                                <img src="/assets/pw/conrad-images/footer-partner-3.svg" class="img-fluid" width="104" height="259" alt="ThailandAwards">
                            </div>
                        </div>


                        <div class="footer-link-settings-responsive">
                            <div class="row">
                                <div class="col-4">
                                    @include('partials.front.pw.footer-settings')
                                </div>
                                <div class="col-3">
                                    <div>
                                        <img src="/assets/pw/conrad-images/footer-partner-3.svg" class="img-fluid" width="139" height="19" alt="JamesEdition">
                                    </div>
                                    <div>
                                        <img src="/assets/pw/conrad-images/winner-black-vert-1.svg" class="img-fluid" width="139" height="19" alt="JamesEdition">
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div>
                                        <img src="{{ asset('images/AP2022-Award-470-min.png') }}" width="55" height="auto" style="display: inline"/>
                                        <img src="{{ asset('images/AP2022-Award-471-min.png') }}" width="55" height="auto" style="display: inline"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="text-center c-white f-12 f-regular py-4">
                Copyright © {{ date('Y') }} Conrad Properties Co., Ltd. All rights reserved.
            </div>
        </div>
    </div>
</footer>

<!-- Social Fix  -->
@if(!request()->is('global-luxury-search'))
<div class="social-bx-fix">
    <div class="container">
        <div class="social-bx-fix--wrap">
            <ul>
                <li><a href="tel:+66929591299" class="tellb" target="_blank"><i class="fa fa-phone"></i></a></li>
                <li><a href="https://www.facebook.com/conradluxuryvillas" target="_blank" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="https://www.instagram.com/conradvillas/" target="_blank" target="_blank"><i class="fab fa-instagram"></i></a></li>
                <li><a href="https://www.linkedin.com/company/conrad-villas" target="_blank" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                {{--<li><a href="https://twitter.com/Property_Conrad" target="_blank" target="_blank"><i class="fab fa-twitter"></i></a></li>--}}
                <li><a href="mailto:info@conradvillas.com" target="_blank" target="_blank"><i class="far fa-envelope"></i></a></li>
            </ul>
        </div>
    </div>
</div>
@endif
