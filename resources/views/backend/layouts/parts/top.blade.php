<div class="nav_menu">
    <nav>
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>
        <div class="support-link__body">
            <a class="button -support d-inline-block" href="{{url('/')}}" target="_blank"><span>Visit Website</span></a>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <li class="">
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    @if(0)<img src="{{url('assets/admin/build/images/pw-theme/img.jpg')}}" alt="Profile">@endif
                    {{ Auth::user()->name }}
                    <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="{{ url('') }}" target="_blank"> View Website</a></li>
                    <li><a href="{{ admin_url('users/'.Auth::user()->id.'/edit') }}"> Profile</a></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
</div>
