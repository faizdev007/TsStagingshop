<style>
.nav-block .nav li a{
		padding:13px 15px 13px 20px;
		}
</style>

<div class="top <?php if(isset($home)){ echo 'home-top';} ?>">
    <div class="centering">
        <div class="logo ">
            <a href="/">
                <i class="icon-logo icon-logo-main"></i>
                <img src="data:image/png;base64,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" alt="Koh Samui Property and Real Estate" width="130" height="" />
            </a>
        </div>

        <div class="date-block">
            <div class="tele tele_txt" >
                <span>Luxury Villas  and Property for Sale and Rent in Dubai</span>
            </div>
            <div class="tele new">
                <i class="icon-phone-outline"></i>
                <figure>
                </figure>
                <span><a href="tel:+66 (0)92-959-1299">+66 (0)92-959-1299</a></span>
                <a><span class="e-mail" data-user="ofni" data-website="aisa.seitreporpdarnoc"></span>
                </a>
            </div>

        </div>

        <div class="social-block">

            <ul>
                <?php if ($settings->twitter_url): ?>
                <li><a href="{{$settings->twitter_url}}" target="_blank" rel="nofollow" ><i class="icon-twitter"></i></a></li>
                <?php endif; ?>
                <?php if ($settings->facebook_url): ?>
                <li><a href="{{$settings->facebook_url}}" target="_blank" rel="nofollow" ><i class="icon-facebook"></i></a></li>
                <?php endif; ?>
                <?php if ($settings->instagram_url): ?>
                <li><a href="{{$settings->instagram_url}}" target="_blank" rel="nofollow" ><i class="icon-instagram"></i></a></li>
                <?php endif; ?>

            <!--<?php if ($settings->google_plus_url): ?>-->
            <!--    <li><a href="{{$settings->google_plus_url}}" target="_blank"><i class="icon-gplus"></i></a></li>-->
            <!--<?php endif; ?>-->
                <?php if ($settings->linkedin_url): ?>
                <li><a href="{{$settings->linkedin_url}}" target="_blank" rel="nofollow" ><i class="icon-linkedin"></i></a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="translation-list">
            <a class="fra" id="selected" >&nbsp; </a>
            <ul class="translation-links">
                @foreach(Config::get('conrad.languages') as $index => $language)
                    <li><a href="#" class="{{$index}}" data-lang="{{$language}}">{{$index}}</a></li>
                @endforeach
            </ul>
            <!-- Code provided by Google -->
            <div id="google_translate_element" style="display: none"></div>

        </div>

        <div class="logo awards hide-in-mobile" style="position: absolute;top: -28px; right:-226px; width: 220px; z-index: 9999;">
            <img data-src="/images/luxury.svg" height="120"/>
            @if(request()->is('/'))
                <script src="https://luxurylifestyleawards.com/js/winner_2021.js"></script>
            @endif
        </div>

        <div class="clear"></div>

    </div>
</div>

<!-- begin nav -->
<div class="nav-block">

    <a id="opner" href="#">Navigation</a>

    <div class="centering">

        <div class="nav">
            <?php
            $segment = \request()->segment(1);
            $segment1 = \request()->segment(2);
            $segment2 = \request()->segment(3);
            ?>
            <ul style="margin: 0;">
                <li class="<?php echo ($segment == "") ? 'active' : ''; ?>"><a href="{{url('/')}}">HOME</a></li>
                <li class="<?php  echo ($segment == "search" && $segment1 == "all" && $segment2 == "buy") ? 'active' : ''; ?> parent-menu" >
                    <a  >BUY &nbsp;<span class="icon-arrow-down"></span></a>
                    <ul class="heapOptions">
                       
                            <li class="heapOption"><a href="{{ url('property-for-sale-dubai') }}" title="Dubai" class="dropdown-item">Dubai</a></li>
                            <li class="heapOption"><a href="{{ url('property-for-sale-greece') }}" title="greec" class="dropdown-item">Greece</a></li>
                            <li class="heapOption"><a href="{{ url('property-for-sale-marbella') }}" title="marbella" class="dropdown-item">marbella</a></li>
                            <li class="heapOption"><a href="{{ url('property-for-sale-portugal') }}" title="portugal" class="dropdown-item">Portugal</a></li>
                    </ul>
                </li>

                <li class="<?php echo ($segment == "properties" && $segment1 == "sell") ? 'active' : ''; ?>"><a
                            href="{{url('/properties/list')}}">SELL</a></li>

               
                <li class="<?php echo ($segment == "buyers-guide") ? 'active' : ''; ?>"><a
                            href="{{url('/buyers-guide')}}">BUYERS GUIDE</a></li>
                <li class="<?php echo ($segment == "blog-news") ? 'active' : ''; ?>"><a href="{{url('/blog-news')}}">BLOG & NEWS</a></li>

            </ul>

        </div>

        <div class="links">

            <ul>
				<?php /*
                @if (!isset($_COOKIE['testcode']))
                <li><a class="<?php echo ($segment == "aboutus") ? 'active' : ''; ?>" href="{{url('aboutus')}}">ABOUT US</a>
                </li>
                @endif
                @if (isset($_COOKIE['testcode']))
				<li class="parent-menu about_us_menu_link">
                    <a class="about_us_menu">ABOUT US &nbsp;<span class="icon-arrow-down"></span></a>
                    <ul class="heapOptions">
                        <li class="heapOption"><a class="" href="{{ url('aboutus') }}">About Company</a></li>
                        <li class="heapOption"><a class="" href="{{ url('testimonials') }}">Testimonials</a></li>

                    </ul>
                </li>
				@endif
				*/?>
				<li class="parent-menu about_us_menu_link">
                    <a class="about_us_menu">ABOUT US &nbsp;<span class="icon-arrow-down"></span></a>
                    <ul class="heapOptions">
                        <li class="heapOption"><a class="" href="{{ url('aboutus') }}">About Company</a></li>
                        <li class="heapOption"><a class="" href="{{ url('testimonials') }}">Testimonials</a></li>
                        <li class="heapOption"><a class="" href="{{ url('our-partners') }}">Our partners</a></li>
                        <li class="heapOption"><a class="" href="{{ url('our-office') }}">Our office</a></li>

                    </ul>
                </li>
                <li><a class="<?php echo ($segment == "contact-us") ? 'active' : ''; ?>" href="{{url('contact-us')}}">CONTACT
                        US</a></li>
                <li><a class="<?php echo ($segment == "favorites") ? 'active' : ''; ?>" href="{{url('favorites')}}">FAVORITES
                        (<span id="total-favourite-count"><?php echo CustomHelper::countFavorites(); ?></span>)</a>
                </li>
                <li class="nav-phone">
                    <a href="tel:{{$settings->display_phone}}"><i class="icon-phone"></i> {{$settings->display_phone}}</a>
                </li>
            </ul>

        </div>

        <div class="clear"></div>

    </div>
</div>
<!-- finish nav -->

<div class="clear"></div>
