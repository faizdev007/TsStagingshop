<div class="col-2">

    <a class="btn fancybox enquire" href="#enqiry">ENQUIRE NOW</a>

    <h3>Property Details </h3>

    <ul class="pro-list">
        @if($data['property']->getType()!="NULL")
            @if($data['property']->getType()=="Apart/Condos")
                {{--*/$type="icon-apart-condo";/*--}}
            @else
                {{--*/$type="icon-luxury-villa"/*--}}
            @endif
            <li>
                <span class="side1">Type</span>
                <span class="side2"><i class="{{$type}}" style="font-size: 45px;float: left;margin: -15px 10px 0 -8px;"></i> {!! $data['property']->getType() !!} </span>
            </li>
        @endif
        @if($data['property']->bedrooms)
            <li class="no-bg">
                <span class="side1">Bedrooms</span>
                <span class="side2"><i class="icon-bedroom" style="font-size: 45px;float: left;margin: -15px 10px 0 -8px;" ></i>{!! $data['property']->bedrooms !!} </span>
            </li>
        @endif
        @if($data['property']->bathrooms)
            <li>
                <span class="side1">Bathrooms</span>
                <span class="side2"><i class="icon-bath2" style="font-size: 45px;float: left;margin: -15px 10px 0 -8px;""></i>{!! $data['property']->bathrooms !!}</span>
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
                            <span class="side1">View</span>
                            <span class="side2"><i style="font-size: 45px;float: left;margin: -15px 10px 0 -8px;"> <img
                                            src="{{cdnUrl('/uploads/new1.png')}}" alt="Beachfront"></i>{!! $data['property']->view !!}</span>
                        </li>
                @elseif (isset($img_view_icon) AND $img_view_icon!='') 
					<li class="no-bg">
                            <span class="side1">View</span>
                            <span class="side2"><i style="font-size: 45px;float: left;margin: -15px 10px 0 -8px;"> <img
                                            src="{{$img_view_icon}}" alt="Beachfront"></i>{!! $data['property']->view !!}</span>
                        </li>
                @else
                <li class="no-bg">
                    <span class="side1">View</span>
                    <span class="side2"><i class="{{$side2}}" style="font-size: 45px;float: left;margin: -15px 10px 0 -8px;"></i>{!! $data['property']->view !!}</span>
                </li>
                @endif
            @endif
        @endif
        @if($data['property']->parking)
            <li>
                <span class="side1">Parking</span>
                <span class="side2"><i class="icon-parking" style="font-size: 45px;float: left;margin: -15px 10px 0 -8px;"></i>{!! $data['property']->parking !!}</span>
            </li>
        @endif
        @if($data['property']->swimming_pool)
            <li class="no-bg">
                <span class="side1">Swimming Pool</span>
                <span class="side2"><i
                            class="icon-swimming-pool" style="font-size: 45px;float: left;margin: -15px 10px 0 -8px;"></i>{!! $data['property']->swimming_pool !!}</span>
            </li>
        @endif
        @if($data['property']->building_area)
            <li>
                <span class="side1">Built Size</span>
                <span class="side2">{!! $data['property']->building_area." sqm" !!}</span>
            </li>
        @endif
        @if($data['property']->interior_area)
            <li class="no-bg">
                <span class="side1">Interior Size</span>
                <span class="side2">{!! $data['property']->interior_area." sqm" !!}</span>
            </li>
        @endif
        @if($data['property']->land_area)
            <li class="no-bg">
                <span class="side1">Land Size</span>
                <span class="side2">{!! $data['property']->land_area." sqm" !!}</span>
            </li>
        @endif

        
        @if($data['property']->living_room)
            <li>
                <span class="side1">Living Room</span>
                <span class="side2">{!! $data['property']->living_room !!}</span>
            </li>
        @endif
        @if($data['property']->dining_room)
            <li>
                <span class="side1">Dining Room</span>
                <span class="side2">{!! $data['property']->dining_room !!}</span>
            </li>
        @endif
        @if($data['property']->family_room)
            <li>
                <span class="side1">Family Room</span>
                <span class="side2">{!! $data['property']->family_room !!}</span>
            </li>
        @endif
        @if($data['property']->kitchen)
            <li>
                <span class="side1">Kitchen</span>
                <span class="side2">{!! $data['property']->kitchen !!}</span>
            </li>
        @endif
        @if($data['property']->study_office)
            <li>
                <span class="side1">Study/Office</span>
                <span class="side2">{!! $data['property']->study_office !!}</span>
            </li>
        @endif
        @if($data['property']->floors)
            <li>
                <span class="side1">Floors</span>
                <span class="side2">{!! $data['property']->floors !!}</span>
            </li>
        @endif
        @if($data['property']->pets)
            <li>
                <span class="side1">Pets</span>
                <span class="side2">{!! $data['property']->pets !!}</span>
            </li>
        @endif
        @if($data['property']->terrace_rooftop)
            <li class="no-bg">
                <span class="side1">Land Title</span>
                <span class="side2">{!! $data['property']->terrace_rooftop !!}</span>
            </li>
        @endif
        @if($data['property']->library)
            <li class="no-bg">
                <span class="side1">Ownership Type</span>
                <span class="side2">{!! $data['property']->library !!}</span>
            </li>
        @endif
        @if($data['property']->cinema_room)
            <li>
                <span class="side1">Cinema Room</span>
                <span class="side2">{!! $data['property']->cinema_room !!}</span>
            </li>
        @endif
        @if($data['property']->security)
            <li>
                <span class="side1">Security</span>
                <span class="side2">{!! $data['property']->security !!}</span>
            </li>
        @endif
        @if($data['property']->tennis)
            <li>
                <span class="side1">Tennis</span>
                <span class="side2">{!! $data['property']->tennis !!}</span>
            </li>
        @endif
        @if($data['property']->furnished)
            <li class="no-bg">
                <span class="side1">Furnished</span>
                <span class="side2">{!! $data['property']->furnished !!}</span>
            </li>
        @endif
        @if($data['property']->rental_program)
            <li>
                <span class="side1">Rental Program</span>
                <span class="side2">{!! $data['property']->rental_program !!}</span>
            </li>
        @endif
        @if($data['property']->restaurant)
            <li>
                <span class="side1">Restaurant</span>
                <span class="side2">{!! $data['property']->restaurant !!}</span>
            </li>
        @endif
        @if($data['property']->concierge)
            <li>
                <span class="side1">Concierge</span>
                <span class="side2">{!! $data['property']->concierge !!}</span>
            </li>
        @endif
        @if($data['property']->year_build)
            <li>
                <span class="side1">Build Year</span>
                <span class="side2">{!! $data['property']->year_build !!}</span>
            </li>
        @endif
        @if($data['property']->features)
            <li>
                <span class="side1">Features</span>
                <span class="side2"> {!! $data['property']->features !!}</span>
            </li>
        @endif
        @if($data['property']->community_features)
            <li>
                <span class="side1">Community Features</span>
                <span class="side2">{!! $data['property']->community_features !!}</span>
            </li>
        @endif
    </ul>
	<?php
	
	if (isset($data['property']->youtube_video_link) && $data['property']->getYoutubeVideoId()!='') {
		?>
		<br>
		<br>
		<br>
		<h3>Property video</h3>
		<iframe width="362" height="218" src="https://www.youtube.com/embed/{{$data['property']->getYoutubeVideoId()}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" class="video_frame" ></iframe>
		<?php
	}
	
	?>
</div>
