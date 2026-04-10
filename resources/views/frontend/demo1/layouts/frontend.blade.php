@include('frontend.demo1.layouts.header')
<div id="smooth-header" class="smooth-header">
    <header id="header" data-currency="{!! settings('currency_symbol') !!}" data-theme-asset="{!! themeAsset() !!}">
        <div class="container">
            <div class="text-right">
                <a href="tel:+971585365111" aria-label="Call us at +971 585 365 111" aria-label="Call us at +971 585 365 111"><i class="fas fa-phone"> </i> +971 585 365 111</a>
            </div>
        </div>
        <div class="container position-relative px-0 px-sm-3">
            <div class="header-logo">
                <a href="/">
                    <img src="{{ asset('assets/demo1/images/logos/logo.svg') }}" width="150" height="42" class="for--floating" alt="logo">
                </a>
            </div>

            <div class="desktop-nav">
                <nav class="main">
                    @include('frontend.demo1.parts.nav')
                </nav>
                <!-- Mobile -->
                <nav class="mobile-nav-menu">
                    <div class="navigation" style="padding: 0!important;">
                        <div class="responsive--nav position-relative">
                            <div class="" style="padding: 32px 15px 40px 45px; background-color:#171717;">
                                <div class="mobile-x pe-4 text-center c-white position-absolute end-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path fill="#8c8c8c" d="M6.4 19L5 17.6l5.6-5.6L5 6.4L6.4 5l5.6 5.6L17.6 5L19 6.4L13.4 12l5.6 5.6l-1.4 1.4l-5.6-5.6L6.4 19Z" />
                                    </svg>
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
                                                    <a href="#" class="slideMenu">PROPERTIES <i class="fas fa-chevron-down"></i></a>

                                                    <div class="slideContentMenu" style="display: block;">
                                                        <div class="responsive--nav-item">
                                                            <a href="{{ url('property-for-sale') }}" title="sale" class="dropdown-item">SALES</a>
                                                        </div>
                                                        <div class="responsive--nav-item">
                                                            <a href="{{ url('property-for-rent') }}" title="rent" class="dropdown-item">RENT </a>
                                                        </div>
                                                        <div class="responsive--nav-item">
                                                            <a href="{{ url('new-development-for-sale') }}" title="New Developments" class="dropdown-item">NEW DEVELOPMENTS</a>
                                                        </div>
                                                        <div class="responsive--nav-item">
                                                            <a href="{{ url('property-for-sale/in/international/') }}" title="sale" class="dropdown-item">INTERNATIONAL</a>
                                                        </div>

                                                    </div>
                                                </div>


                                                <div class="responsive--nav-heading mt-3">
                                                    <a href="{{ url('blog') }}">BLOG</a>
                                                </div>

                                                <div class="responsive--nav-heading mt-3">
                                                    <a href="#" class="slideMenu">ABOUT US <i class="fas fa-chevron-down"></i></a>

                                                    <div class="slideContentMenu" style="display: block;">
                                                        <div class="responsive--nav-item">
                                                            <a href="{{ url('about-us') }}">ABOUT COMPANY</a>
                                                        </div>
                                                        <div class="responsive--nav-item">
                                                            <a href="{{ url('about-us/#our-office-services') }}" title="Our Office & Services" class="dropdown-item">Our Office & Services</a>
                                                        </div>

                                                        <div class="responsive--nav-item">
                                                            <a href="{{ url('about-us/#our-team') }}" title="Our team" class="dropdown-item">Our Team</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="responsive--nav-heading mt-3">
                                                    <a href="{{ url('contact-us') }}">CONTACT US</a>
                                                </div>


                                                <ul class="dashboard-menu p-0 mt-3">


                                                    @if( settings('members_area') == 1 && Auth::user() && Auth::user()->role_id == '4' )
                                                    <li class="main-menu-container responsive--nav-heading mt-3">
                                                        <a class="main-menu dp-menu -underline-hover slideMenu" data-target=".sub-account">{{ trans_fb('app.app_Your_Account', 'Your Account') }} <i class="fas fa-chevron-down"></i></a>

                                                        <div class="sub-menu sub-account slideContentMenu" style="display: block;">
                                                            <ul class="dashboard-menu-item p-0">
                                                                <li class="responsive--nav-item"><a href="{{lang_url('account')}}">Account Details</a></li>
                                                                <li class="responsive--nav-item"><a href="{{lang_url('account/shortlist')}}">My Shortlist</a></li>
                                                                <li class="responsive--nav-item"><a href="{{lang_url('account/saved-searches')}}">Saved Searches</a></li>
                                                                <li class="responsive--nav-item"><a href="{{lang_url('account/property-alerts')}}">Property Alerts</a></li>
                                                                <li class="responsive--nav-item"><a href="{{lang_url('account/notes')}}">Property Notes</a></li>
                                                                <li class="responsive--nav-item"><a href="{{lang_url('account/messages')}}">Messages</a></li>
                                                                <li class="responsive--nav-item"><a href="{{lang_url('logout') }}">Logout</a></li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                    @elseif( Auth::user() && Auth::user()->role_id != '4' )
                                                    <li class="main-menu-container">
                                                        <a class="main-menu dp-menu -underline-hover slideMenu" href="#" data-target=".sub-account">{{ trans_fb('app.app_Your_Account', 'Your Account') }} <i class="fas fa-angle-down"></i></a>

                                                        <div class="sub-menu sub-account slideContentMenu" style="display: block;">
                                                            <ul class="dashboard-menu-item p-0">
                                                                <li class="responsive--nav-item"><a href="{{lang_url('/admin')}}">Dashboard</a></li>
                                                                <li class="responsive--nav-item"><a href="{{lang_url('logout') }}">Logout</a></li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                    @else
                                                    @if(settings('members_area') == 1)
                                                    <li class="login-register">
                                                        <a href="{{url('/login')}}" data-toggle="modal" data-target=".member-login-account" class="hvr-sweep-to-bottom1"><i class="fas fa-user"></i> Log In / Register</a>
                                                    </li>
                                                    @else
                                                    <li class="login-register {{ active_class('shortlist') }}">
                                                        <a href="{{lang_url('shortlist')}}">Shortlist <span class="shortlist-total" data-url="{{url('shortlist/ajax/total')}}"></span></a>
                                                    </li>
                                                    @endif
                                                    @endif


                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="responsive--nav-rows">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="responsive-sn--wrp">
    
                                                    @if( !empty(settings('telephone')) )
                                                    <div class="responsive-sn--item">
                                                        <a href="tel:{{ settings('telephone') }}" target="_blank" ><i class="fa fa-phone"></i></a>
                                                    </div>
                                                    @endif
                                                    @if( !empty(settings('whatsapp_url')) )
                                                    <div class="responsive-sn--item">
                                                        <a href="https://api.whatsapp.com/send?phone={{settings('whatsapp_url')}}" target="_blank" aria-label="Chat on WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                                    </div>
                                                    @endif
    
                                                    @if( !empty(settings('tiktok_url')) )
                                                    <div class="responsive-sn--item">
                                                        <a href="{{settings('tiktok_url')}}" target="_blank" aria-label="Follow us on TikTok"><svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24">
                                                                <path fill="white" d="M16.6 5.82s.51.5 0 0A4.278 4.278 0 0 1 15.54 3h-3.09v12.4a2.592 2.592 0 0 1-2.59 2.5c-1.42 0-2.6-1.16-2.6-2.6c0-1.72 1.66-3.01 3.37-2.48V9.66c-3.45-.46-6.47 2.22-6.47 5.64c0 3.33 2.76 5.7 5.69 5.7c3.14 0 5.69-2.55 5.69-5.7V9.01a7.35 7.35 0 0 0 4.3 1.38V7.3s-1.88.09-3.24-1.48z" />
                                                            </svg></a>
                                                    </div>
                                                    @endif
    
                                                    @if( !empty(settings('linkedin_url')) )
                                                    <div class="responsive-sn--item">
                                                        <a href="{{settings('linkedin_url')}}" target="_blank" aria-label="Follow us on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                                    </div>
                                                    @endif
    
                                                    @if( !empty(settings('instagram_url')) )
                                                    <div class="responsive-sn--item">
                                                        <a href="{{settings('instagram_url')}}" target="_blank" aria-label="Follow us on Instagram"><i class="fab fa-instagram"></i></a>
                                                    </div>
                                                    @endif
    
                                                    @if( !empty(settings('facebook_url')) )
                                                    <div class="responsive-sn--item">
                                                        <a href="{{settings('facebook_url')}}" target="_blank" aria-label="Follow us on Facebook"><i class="fab fa-facebook-f"></i></a>
                                                    </div>
                                                    @endif
                                                    @if( !empty(settings('youtube_url')) )
                                                    <div class="responsive-sn--item">
                                                        <a href="{{settings('youtube_url')}}" target="_blank" aria-label="Subscribe to our YouTube channel"><i class="fab fa-youtube-square"></i></a>
                                                    </div>@endif
                                                    @if( !empty(settings('twitter_url')) )
                                                    <div class="responsive-sn--item">
                                                        <a href="{{settings('twitter_url')}}" target="_blank" aria-label="Follow us on Twitter"><i class="fab fa-twitter"></i></a>
                                                    </div>@endif
                                                    @if( !empty(settings('pinterest_url')) )
                                                    <div class="responsive-sn--item">
                                                        <a href="{{settings('pinterest_url')}}" target="_blank" aria-label="Follow us on Pinterest"><i class="fab fa-pinterest-p"></i></a>
                                                    </div>@endif
    
                                                    @if( !empty(settings('email')) )
                                                    <div class="responsive-sn--item">
                                                        <a href="mailto:{{ settings('email') }}" target="_blank" aria-label="Send us an email"><i class="far fa-envelope"></i></a>
                                                    </div>@endif
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
                    <a href="#" class="burger-icon burger-icon--style d-block c-gray-link u-hover-opacity-70" aria-label="Toggle navigation">
                        <img src="{{ asset('assets/demo1/images/conrad-images/burger-menu.png') }}" alt="burger menu" width="26px" height="14px">
                        <div class="f-9 f-semibold">MENU</div>
                    </a>
                </div>
            </div>
        </div>
    </header>
</div>
<div class="dummy-header"></div>

{{-- end of :: HEADER --}}

@yield('main_content')

@include('frontend.demo1.layouts.footer')