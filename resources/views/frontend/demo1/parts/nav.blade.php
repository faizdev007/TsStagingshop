 <ul class="d-md-flex d-none justify-content-end px-0 mx-0 mb-0">
                <li class="h-nav-item">
                    <a href="{{ url('/') }}" class="h-nav-link">Home</a>
                </li>
                <li class="h-nav-item main-nav-dropdown">
                    <a href="#" class="h-nav-link nav-dropdown-toggle" data-target=".properties-sub">
                        Properties <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="h-nav-item-sub-1 position-absolute">
                        <ul class="nav-dropdown-item properties-sub">
                            
                            <li><a href="{{ url('property-for-sale') }}" title="Sales" class="dropdown-item">Sales</a></li>
                            <li><a href="{{ url('property-for-rent') }}" title="Rentals" class="dropdown-item">Rent</a></li>
                            <li><a href="{{ url('property-for-development') }}" title="New Developments" class="dropdown-item">New Developments</a></li>
                            <li><a href="{{ url('property-for-sale/in/international/') }}" title="Rentals" class="dropdown-item">International</a></li>                            
                                             
                        </ul>
                    </div>
                </li>
                <li class="h-nav-item">
                    <a href="{{ url('blog') }}" class="h-nav-link">Blog</a>
                </li>
                <li class="h-nav-item main-nav-dropdown">
                    <a href="{{ url('about-us') }}" class="h-nav-link nav-dropdown-toggle" data-target=".about-sub">
                        About <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="h-nav-item-sub-1 position-absolute">
                        <ul class="nav-dropdown-item about-sub">
                            <li><a href="{{ url('about-us') }}" title="About Company" class="dropdown-item">About Company</a></li> 
                             <li><a href="{{ url('about-us/#our-office-services') }}" title="Our Office & Services" class="dropdown-item">Our Office & Services</a></li> 
                             <li><a href="{{ url('about-us/#our-team') }}" title="Our team" class="dropdown-item">Our Team</a></li> 
                        </ul>
                    </div>
                </li>
                <li class="h-nav-item">
                    <a href="{{ url('contact-us') }}" class="h-nav-link">Contact</a>
                </li>
                <li class="h-nav-item -burger-menu burger-icon mobile-nav-menu-trigger">
                    <a href="#" class="burger-icon burger-icon--style d-block c-gray-link u-hover-opacity-70">
                        <img src="{{ asset('assets/demo1/images/conrad-images/burger-menu.png') }}" alt="burger menu" width="26px" height="14px">
                        <div class="f-9 f-semibold">MENU</div>
                    </a>
                </li>
                
            </ul>