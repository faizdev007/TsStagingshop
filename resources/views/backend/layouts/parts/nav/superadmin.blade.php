<ul class="nav side-menu">
    <li><a href="{{admin_url()}}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>

    <li>
        <a>
            <i class="fa fa-user"></i> Users <span class="fa fa-chevron-down"></span>
        </a>
        <ul class="nav child_menu">
            <li><a href="{{admin_url('users')}}">All Users</a></li>
            <li><a href="{{admin_url('users/create')}}">Create User</a></li>
        </ul>
    </li>

    <li>
        <a>
            <i class="fa fa-home"></i> Properties <span class="fa fa-chevron-down"></span>
        </a>
        <ul class="nav child_menu">
            <li><a href="{{admin_url('properties')}}">All Properties</a></li>
            @if(settings('new_developments'))
                <li><a href="{{admin_url('developments')}}">All Developments</a></li>
            @endif
            @if(0)
            <li><a href="{{admin_url('properties/create')}}">Create Property</a></li>@endif
            <li><a href="{{admin_url('properties-claim')}}">Claim  Properties</a></li>
        </ul>
    </li>

    @if(settings('seo_search'))
        <li>
            <a>
                <i class="fa fa-search"></i> SEO Links <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
                <!-- <li><a href="{{admin_url('search-content')}}">Google Links</a></li> -->
                <li><a href="{{admin_url('search-content/create')}}">Create Google SEO Links</a></li>
            </ul>
        </li>
    @endif

     @if(!settings('propertybase'))
        <li>
            <a>
                <i class="fa fa-location-arrow" aria-hidden="true"></i> Communities <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
                <li><a href="{{admin_url('communities')}}">All Communities</a></li>
                <li><a href="{{admin_url('communities/create')}}">Create Community</a></li>
            </ul>
        </li>

    @endif

    @if(settings('team_page') == 1)
        <li><a href="{{url('admin/team')}}"><i class="fa fa-users"></i> Team Members </a></li>
    @endif

    @if(settings('members_area'))
        <li>
            <a>
                <i class="fa fa-users"></i> Members <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
                <li><a href="{{ admin_url('members') }}">All Members</a></li>
                <li><a href="{{admin_url('members/messages')}}">Messages</a></li>
            </ul>
        </li>
    @endif

    @if(settings('branches_option') == 1)
        <li>
            <a>
                <i class="fa fa-building"></i> Branches <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
                <li><a href="{{admin_url('branches')}}">All Branches</a></li>
                <li><a href="{{admin_url('branches/create')}}">Create Branch</a></li>
            </ul>
        </li>
    @endif

    <li><a href="{{url('admin/enquiries')}}"><i class="fas fa-envelope"></i> Enquiries </a></li>
    <li><a href="{{url('admin/whatsapp')}}"><i class="fas fa-mouse-pointer"></i> Whatsapp Clicks </a></li>

    @if(settings('show_subscribers'))
        <li>
            <a href="{{url('admin/subscribers')}}"><i class="fa fa-envelope"></i> Subscribers</a>
        </li>
    @endif

    <li>
        <a>
            <i class="fa fa-list-alt"></i> Slides <span class="fa fa-chevron-down"></span>
        </a>
        <ul class="nav child_menu">
            <li><a href="{{admin_url('slides')}}">All Slides</a></li>
            <li><a href="{{admin_url('slides/create')}}">Create Slides</a></li>
        </ul>
    </li>

    @if(settings('show_testimonials'))
        <li>
            <a>
                <i class="fas fa-quote-left"></i> Testimonials <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
                <li><a href="{{admin_url('testimonials')}}">All Testimonials</a></li>
                <li><a href="{{admin_url('testimonials/create')}}">Create Testimonials</a></li>
            </ul>
        </li>
    @endif

    <li>
        <a>
            <i class="fa fa-file"></i> Pages <span class="fa fa-chevron-down"></span>
        </a>
        <ul class="nav child_menu">
            <li><a href="{{admin_url('pages')}}">All Pages</a></li>
            <li><a href="{{admin_url('pages/create')}}">Create Page</a></li>
        </ul>
    </li>

    @if(settings('bespoke_pages'))
        <li>
            <a>
                <i class="fa fa-file"></i> Bespoke Pages <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
                <li><a href="{{admin_url('bespoke-pages')}}">All Pages</a></li>
                <li><a href="{{admin_url('pages/create')}}">Create Page</a></li>
            </ul>
        </li>
    @endif

    @if(settings('market_valuation'))
        <li>
            <a>
                <i class="fas fa-pound-sign"></i> Market Valuations <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
                <li><a href="{{admin_url('market-valuation')}}">View All</a></li>
                <li><a href="{{admin_url('market-valuation/create')}}">Create New</a></li>
                <li><a href="{{admin_url('market-valuation/why-list')}}">List Content</a></li>
            </ul>
        </li>
    @endif

    @if(settings('seo_search'))
        <li>
            <a>
                <i class="fa fa-search"></i> Search SEO Content <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
                <li><a href="/">All Content</a></li>
                <li><a href="/">Create New</a></li>
            </ul>
        </li>
    @endif

    

    @if(settings('show_blog'))
        <li>
            <a>
                <i class="fa fa-rss"></i> Blog <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
                <li><a href="{{admin_url('news')}}">All Articles</a></li>
                <li><a href="{{admin_url('news/create')}}">Create Article</a></li>
            </ul>
        </li>
        <li>
            <a>
                <i class="fa fa-tag"></i> Blog Categories<span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
                <li><a href="{{admin_url('postcategories')}}">All Categories</a></li>
                <li><a href="{{admin_url('postcategories/create')}}">Create Category</a></li>
            </ul>
        </li>
    @endif

    @if(settings('footer_blocks'))
        <li>
            <a>
                <i class="fa fa-link"></i> Footer Blocks <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
                <li><a href="{{admin_url('footer-blocks')}}">All Blocks</a></li>
                <li><a href="{{admin_url('footer-blocks/create')}}">Create New</a></li>
            </ul>
        </li>
    @endif

    @if(1)
    <li>
        <a href="{{url('admin/property-alerts')}}"><i class="fa fa-envelope"></i> Alerts</a>
    </li>@endif

    <li>
        <a>
            <i class="fa fa-cog"></i> Superadmin <span class="fa fa-chevron-down"></span>
        </a>
        <ul class="nav child_menu">
            <li><a href="{{admin_url('settings')}}">Configure Site</a></li>
            @if(settings('translations'))
            <li><a href="{{admin_url('settings/languages')}}">Configure Languages</a></li>
            @endif
            <li><a href="{{admin_url('sitemap_hides')}}">Sitemap Hides</a></li>
            <li><a href="{{admin_url('login-history')}}">Login History</a></li>
            <li><a href="{{admin_url('logs')}}">View Logs</a></li>
            
        </ul>
    </li>
</ul>
