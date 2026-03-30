<ul>
    @if($data['property']->getType()!="NULL")
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" checked>
                <label><span>Type {!! $data['property']->getType() !!}</span></label>
            </div>
        </li>
    @endif
    @if($data['property']->bedrooms)
    <li>
        <div class="customcheck">
            <input class="styled-checkbox" type="checkbox" value="value1" checked>
            <label><span>Bedrooms</span> {!! $data['property']->bedrooms !!}</label>
        </div>
    </li>
    @endif
    @if($data['property']->bathrooms)
    <li>
        <div class="customcheck">
            <input class="styled-checkbox" type="checkbox" value="value1" checked>
            <label><span>Bathrooms</span> {!! $data['property']->bathrooms !!}</label>
        </div>
    </li>
    @endif

    @if($data['property']->view)
        @if($data['property']->view=="Sea View")
            @php $side2="icon-sea-view"; @endphp

        @elseif($data['property']->view=="Mountain View")
            @php $side2="icon-view-mountain"; @endphp

        @elseif($data['property']->view=="Pool View")
            @php $side2="icon-swimming-pool"; @endphp

        @elseif($data['property']->view=="Garden View")
            @php $side2="icon-view-garden"; @endphp
        @elseif($data['property']->view=="Beachfront")
            @php $side2= "Beachfront"; @endphp
        @elseif($data['property']->view=="City View")
            @php $side2= "city_view";
                 $img_view_icon = '/assets/images/City_View_icon.png';
            @endphp
        @elseif($data['property']->view=="River View")
            @php $side2= "river_view";
                 $img_view_icon = '/assets/images/River_View_icon.png';
            @endphp

        @else
            @php $side2=""; @endphp
        @endif

        @if( isset($side2))

            @if($side2 == 'Beachfront')
                    <li class="no-bg">
                        <div class="customcheck">
                            <input class="styled-checkbox" type="checkbox" value="value1" checked>
                            <label><span>View</span> {!! $data['property']->view !!}</label>
                        </div>
                    </li>
            @elseif (isset($img_view_icon) AND $img_view_icon!='')
                <li class="no-bg">
                    <div class="customcheck">
                        <input class="styled-checkbox" type="checkbox" value="value1" checked>
                        <label><span>View</span> {!! $data['property']->view !!}</label>
                    </div>
                </li>
            @else
            <li class="no-bg">
                <div class="customcheck">
                    <input class="styled-checkbox" type="checkbox" value="value1" checked>
                    <label><span>View</span> {!! $data['property']->view !!}</label>
                </div>
            </li>
            @endif
        @endif
    @endif

    @if($data['property']->parking)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Parking</span> {!! $data['property']->parking !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->swimming_pool)
        <li class="no-bg">
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label  ><span>Swimming Pool</span> {!! $data['property']->swimming_pool !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->building_area)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Built Size</span> {!! $data['property']->building_area." sqm" !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->interior_area)
        <li class="no-bg">
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Interior Size</span> {!! $data['property']->interior_area." sqm" !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->land_area)
        <li class="no-bg">
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Land Size</span> {!! $data['property']->land_area." sqm" !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->living_room)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Living Room</span> {!! $data['property']->living_room !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->dining_room)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Dining Room</span> {!! $data['property']->dining_room !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->family_room)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Family Room</span> {!! $data['property']->family_room !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->kitchen)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Kitchen</span> {!! $data['property']->kitchen !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->study_office)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Study/Office</span> {!! $data['property']->study_office !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->floors)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Floors</span> {!! $data['property']->floors !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->pets)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Pets</span> {!! $data['property']->pets !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->terrace_rooftop)
        <li class="no-bg">
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Land Title</span> {!! $data['property']->terrace_rooftop !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->library)
        <li class="no-bg">
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Ownership Type</span> {!! $data['property']->library !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->cinema_room)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Cinema Room</span> {!! $data['property']->cinema_room !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->security)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Security</span> {!! $data['property']->security !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->tennis)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Tennis</span> {!! $data['property']->tennis !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->furnished)
        <li class="no-bg">
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Furnished</span> {!! $data['property']->furnished !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->rental_program)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Rental Program</span> {!! $data['property']->rental_program !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->restaurant)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Restaurant</span> {!! $data['property']->restaurant !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->concierge)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Concierge</span> {!! $data['property']->concierge !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->year_build)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Build Year</span> {!! $data['property']->year_build !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->features)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Features</span> {!! $data['property']->features !!}</label>
            </div>
        </li>
    @endif
    @if($data['property']->community_features)
        <li>
            <div class="customcheck">
                <input class="styled-checkbox" type="checkbox" value="value1" checked>
                <label><span>Community Features</span> {!! $data['property']->community_features !!}</label>
            </div>
        </li>
    @endif
</ul>
