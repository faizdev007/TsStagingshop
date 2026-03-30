<div class="footer-nav-container">
    <div class="row no-gutters">
        <div class="col-md-7 col-sm-12">
            <div class="row -inner">
                <div class="col-md-3 col-sm-6">
                        <div class="footer-nav">
                            <div class="-footer-nav-title">MENU</div>
                            <div class="-footer-nav-list">
                                <ul>
                                    <li><a href="{{ lang_url('properties') }}">Properties</a></li>
                                    <li><a href="{{ lang_url('valuation') }}">Valuation</a></li>
                                     <li><a href="{{ lang_url('services') }}">Awards</a></li>
                                    <li><a href="{{ lang_url('services') }}">Services</a></li>
                                    <li><a href="{{ lang_url('the-team') }}">Meet The Team</a></li>
                                    <li><a href="{{ lang_url('testimonials') }}">Testimonials</a></li>
                                </ul>
                            </div>
                        </div>
                </div>
                <div class="col-md-3 col-sm-6">
                        <div class="footer-nav">
                            <div class="-footer-nav-title"></div>
                            <div class="-footer-nav-list">
                                <ul>
                                    <li><a href="{{lang_url('blog')}}">Blog</a></li>
                                    <li><a href="{{lang_url('contact-us')}}">Contact Us</a></li>
                                    <li><a href="{{lang_url('terms')}}">Terms & Conditions</a></li>
                                    <li><a href="{{lang_url('privacy-policy')}}">Privacy policy</a></li>
                                    <li><a href="{{lang_url('cookie-policye')}}">Cookie policy</a></li>
                                </ul>
                            </div>
                        </div>
                </div>
                <div class="col-md-6 col-sm-6 pr-0 pl-0">
                        <div class="footer-nav">
                            <div class="-footer-nav-list">
                                <div class="-footer-nav-title">BRIDGWATER</div>
                                    <p> <a href="tel:{{ settings('telephone') }}">{{ settings('telephone') }}</a> |
                                    <a href="mailto:{{ settings('email') }}">{{ settings('email') }}</a></p>


                                    <div class="-footer-nav-title">TAUNTON</div>
                                  <p> <a href="tel:{{ settings('telephone') }}">{{ settings('telephone') }}</a> |
                                    <a href="mailto:{{ settings('email') }}">{{ settings('email') }}</a></p>


                        <li class="-sn">
                                @if( !empty(settings('twitter_url')) )
                                <a href="{{settings('twitter_url')}}" target="_blank"><i class="fab fa-twitter"></i></a>@endif
                                @if( !empty(settings('facebook_url')) )
                                <a href="{{settings('facebook_url')}}" target="_blank"><i class="fab fa-facebook-f"></i></a>@endif
                                @if( !empty(settings('instagram_url')) )
                                <a href="{{settings('instagram_url')}}" target="_blank"><i class="fab fa-instagram"></i></a>@endif
                                @if( !empty(settings('youtube_url')) )
                                <a href="{{settings('youtube_url')}}" target="_blank"><i class="fab fa-youtube-square"></i></a>@endif
                            </li>
                        </div>
                </div>
               </div>
            </div>
        </div>
        <div class="col-md-5 col-sm-12">
            <div class="row -inner">
                <div class="col-md-12 col-sm-6">

                <div class="footer-nav">
                    <div class="-footer-nav-list -last footer-logos">
                        <ul>
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
                                <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('/images/footer-logos/homesearch-white.png') }}" alt="Footer Logo">
                            </li>
                            <li>
                                <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('/images/footer-logos/nethouseprices-white.png') }}" alt="Footer Logo">
                            </li>
                             <li>
                                <img class="b-lazy" src="{{ blankImg() }}" data-src="{{ themeAsset('/images/footer-logos/Prime-Location-White.png') }}" alt="Footer Logo">
                            </li>

                        </ul>
                    </div>
                </div>
               </div>
            </div>
        </div>
    </div>
</div>
