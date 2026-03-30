<div class="left_col scroll-view">
    

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile clearfix">
        <a href="{{ url('/') }}" target="_blank">
            <img src="{{themeAsset('images/logos/logo.png')}}" alt="Logo" class="img-circle profile_img @if(themeOptions() == "demo4") -d4 @endif">
        </a>
        <div class="profile_info">
            <span>Welcome,</span>
            <h2>{{ Auth::user()->name }}</h2>
            @if(settings('branches_option') == 1 && Auth::user()->role_id == '3' && Auth::user()->branch)
                <span>{{ Auth::user()->branch->branch_name }}</span>
            @endif
        </div>
    </div>
    <!-- /menu profile quick info -->
    <br />
    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">
            <!-- Load Specific Nav per user level -->
            @include('backend.layouts.parts.nav.'.strtolower(str_replace(' ', '', Auth::user()->role->role_title)))
        </div>
    </div>
    <!-- /sidebar menu -->

    <!-- /menu footer buttons -->
    <div class="sidebar-footer hidden-small">
      @if(0)
        <a data-toggle="tooltip" data-placement="top" title="Settings">
          <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
          <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="Lock">
          <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
          <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
        </a>
      @else
        <br/><br/>
      @endif
    </div>
    <!-- /menu footer buttons -->

</div>
