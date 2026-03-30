<header>
    <div class="container position-relative px-0 px-sm-3">
        <div class="header-logo">
            <a href="/">
                <img src="{{ asset('assets/pw/images/logos/logo.svg') }}" width="150" height="42" class="for--floating" alt="logo">
                </a>
        </div>
        @if($deviceType == "phone")
        <div class="none-floating-crown--div">
            <a href="https://luxurylifestyleawards.com/" target="_blank">
            <img src="{{ asset('assets/pw/images/winner-banner-2021-landscape.jpg') }}" alt="Luxury LifeStyle" width="90" height="43"></a>
        </div>
        @endif
        <div class="desktop-nav">
            <nav class="main">
                <ul class="h-navbar-nav d-flex justify-content-end px-0 mx-0 mb-0">
                <li class="h-nav-item">
                    <a href="{{ url('/') }}" class="h-nav-link">Home</a>
                </li>
                <li class="h-nav-item main-nav-dropdown">
                    <a href="#" class="h-nav-link nav-dropdown-toggle" data-target=".properties-sub">
                        Properties <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="h-nav-item-sub-1 position-absolute">
                        <ul class="nav-dropdown-item properties-sub">
                            <li><a href="{{ url('koh-samui-property-for-sale') }}" title="Koh Samui" class="dropdown-item">Koh Samui</a></li>
                            <li><a href="{{ url('property-for-sale-phuket') }}" title="Phuket" class="dropdown-item">Phuket</a></li>
                            <li><a href="{{ url('property-for-sale-bangkok') }}" title="Bangkok" class="dropdown-item">Bangkok</a></li>
                            <li><a href="{{ url('property-for-sale-koh-phangan') }}" title="Koh Phangan" class="dropdown-item">Koh Phangan</a></li>
                            <li><a href="{{ url('property-for-sale-chiang-mai') }}" title="Chiang Mai" class="dropdown-item">Chiang Mai</a></li>
                            <li><a href="{{ url('property-for-sale-hua-hin') }}" title="Hua Hin" class="dropdown-item">Hua Hin</a></li>
                            <li><a href="{{ url('property-for-sale-pattaya') }}" title="Pattaya" class="dropdown-item">Pattaya</a></li>
                            <li><a href="{{ url('property-for-sale-bali') }}" title="Bali" class="dropdown-item">Bali</a></li>
                            <li><a href="{{ url('global-luxury-search') }}" title="Bali" class="dropdown-item">International</a></li>
                            <li><a href="{{ url('covid-property-discounts-thailand') }}" title="Covid Special Offers" class="dropdown-item">Covid Special Offers</a></li>
                            <li><a href="{{ url('buy-real-estate-in-thailand-with-bitcoin') }}" title="Crypto Investments" class="dropdown-item">Crypto Investments</a></li>
                        </ul>
                    </div>
                </li>
                <li class="h-nav-item">
                    <a href="{{ url('blog-news') }}" class="h-nav-link">Blog</a>
                </li>
                <li class="h-nav-item main-nav-dropdown">
                    <a href="{{ url('aboutus') }}" class="h-nav-link nav-dropdown-toggle" data-target=".about-sub">
                        About <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="h-nav-item-sub-1 position-absolute">
                        <ul class="nav-dropdown-item about-sub">
                            <li><a href="{{ url('aboutus') }}" title="About Company" class="dropdown-item">About Company</a></li>
                            <li><a href="{{ url('our-office') }}" title="Our Office" class="dropdown-item">Our Office</a></li>
                            <li><a href="{{ url('our-partners') }}" title="Our Partners" class="dropdown-item">Our Partners</a></li>
                        </ul>
                    </div>
                </li>
                <li class="h-nav-item">
                    <a href="{{ url('contact-us') }}" class="h-nav-link">Contact</a>
                </li>
                <li class="h-nav-item -burger-menu burger-icon mobile-nav-menu-trigger">
                    <a href="#" class="burger-icon burger-icon--style d-block c-gray-link u-hover-opacity-70">
                        <img src="{{ url('assets/pw/conrad-images/burger-menu.png') }}" alt="burger menu" width="26px" height="14px">
                        <div class="f-9 f-semibold">MENU</div>
                    </a>
                </li>
            </ul>
            </nav>
            <!-- Mobile -->
            <nav class="mobile-nav-menu">
                <div class="navigation">
                    <div class="responsive--nav position-relative">
                        <div class="mobile-x text-center c-white position-absolute end-0">
                            <img src="{{ url('assets/pw/conrad-images/site-close-a.png') }}" class="close-btn" alt="Close Button" width="18px" height="18px">
                            <div class="text-uppercase f-8">
                                CLOSE
                            </div>
                        </div>
                        <div class="responsive--nav-rows-wrp">
                            <div class="responsive--nav-rows">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="responsive--nav-heading">
                                            <a href="{{ url('/') }}">HOME</a>
                                        </div>
                                        <div class="responsive--nav-heading mt-3">
                                            <a href="#">PROPERTIES</a>
                                        </div>

                                        <div class="responsive--nav-item">
                                            <a href="{{ url('koh-samui-property-for-sale') }}">KOH SAMUI</a>
                                        </div>
                                        <div class="responsive--nav-item">
                                            <a href="{{ url('property-for-sale-phuket') }}">PHUKET</a>
                                        </div>
                                        <div class="responsive--nav-item">
                                            <a href="{{ url('property-for-sale-bangkok') }}">BANGKOK</a>
                                        </div>
                                        <div class="responsive--nav-item">
                                            <a href="{{ url('property-for-sale-koh-phangan') }}">KOH PHANGAN</a>
                                        </div>
                                        <div class="responsive--nav-item">
                                            <a href="{{ url('property-for-sale-chiang-mai') }}">CHIANG MAI</a>
                                        </div>
                                        <div class="responsive--nav-item">
                                            <a href="{{ url('property-for-sale-hua-hin') }}">HUA HIN</a>
                                        </div>
                                        <div class="responsive--nav-item">
                                            <a href="{{ url('property-for-sale-pattaya') }}">PATTAYA</a>
                                        </div>
                                        <div class="responsive--nav-item">
                                            <a href="{{ url('property-for-sale-bali') }}">BALI</a>
                                        </div>

                                        <div class="responsive--nav-heading mt-3">
                                            <a href="{{ url('global-luxury-search') }}">INTERNATIONAL</a>
                                        </div>

                                        <div class="responsive--nav-heading mt-3">
                                            <a href="{{ url('blog-news') }}">BLOG</a>
                                        </div>

                                        <div class="responsive--nav-heading mt-3">
                                            <a href="{{ url('aboutus') }}">ABOUT US</a>
                                        </div>
                                        <div class="responsive--nav-item">
                                            <a href="{{ url('aboutus') }}">OUR TEAM</a>
                                        </div>
                                        <div class="responsive--nav-item">
                                            <a href="{{ url('our-office') }}">OUR OFFICES</a>
                                        </div>
                                        <div class="responsive--nav-item">
                                            <a href="{{ url('testimonials') }}">TESTIMONIALS</a>
                                        </div>

                                        <div class="responsive--nav-heading mt-3">
                                            <a href="{{ url('contact-us') }}">CONTACT US</a>
                                        </div>

                                        <div class="responsive--nav-heading mt-3">
                                            <form action="{{ url('find') }}" method="post">
                                                @csrf
                                                <label class="text-white text-uppercase f-14 f-medium mb-2">Search by Ref ID</label>
                                                <div class="position-relative search--ref-input-group">
                                                    <input type="text" name="ref" class="search--ref-id" placeholder="Enter property ID"/>
                                                    <button type="submit" class="search--button position-absolute top-50 end-0 translate-middle-y"><i class="fa fa-search"></i></button>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="responsive--nav-heading mt-3">
                                            @include('partials.front.pw.heading-settings')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="responsive--nav-rows">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="responsive-sn--wrp">
                                            <div class="responsive-sn--item">
                                                <a href="tel:+66 92-959-1299" target="_blank"><i class="fa fa-phone"></i></a>
                                            </div>
                                            <div class="responsive-sn--item">
                                                <a href="https://www.facebook.com/conradluxuryvillas" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                            </div>
                                            <div class="responsive-sn--item">
                                                <a href="https://www.instagram.com/conradvillas/ " target="_blank"><i class="fab fa-instagram"></i></a>
                                            </div>
                                            <div class="responsive-sn--item">
                                                <a href="https://www.linkedin.com/company/conrad-villas/ " target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                            </div>
                                            {{--
                                            <div class="responsive-sn--item">
                                                <a href="https://twitter.com/Property_Conrad" target="_blank"><i class="fab fa-twitter"></i></a>
                                            </div>--}}
                                            <div class="responsive-sn--item">
                                                <a href="mailto:info@conradvillas.com" target="_blank"><i class="far fa-envelope"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="background-black"></div>
                <div class="clear"></div>
            </nav>
        </div>
        <div class="mobile-nav mobile-nav-menu-trigger position-absolute">
            <div class="mobile-nav--burger burger-icon">
                <a href="#" class="burger-icon burger-icon--style d-block c-gray-link u-hover-opacity-70">
                    <img src="{{ asset('assets/pw/conrad-images/burger-menu.png') }}" alt="burger menu" width="26px" height="14px">
                    <div class="f-9 f-semibold">MENU</div>
                </a>
            </div>
        </div>
    </div>
</header>
<div class="dummy-header"></div>
