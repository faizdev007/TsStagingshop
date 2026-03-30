<ul class="nav side-menu">
    <li><a href="{{admin_url()}}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    <li><a><i class="fa fa-home"></i> Properties <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="{{admin_url('properties')}}">All Properties</a></li>
            @if(settings('new_developments'))
                <li><a href="{{admin_url('developments')}}">All Developments</a></li>
            @endif
            <!-- <li><a href="{{admin_url('properties/create')}}">Create Property</a></li> -->
            <li><a href="{{admin_url('properties-claim')}}">Claim  Properties</a></li>
        </ul>
    </li>
    <li><a href="{{url('admin/enquiries')}}"><i class="fas fa-envelope"></i> Enquiries </a></li>
</ul>
