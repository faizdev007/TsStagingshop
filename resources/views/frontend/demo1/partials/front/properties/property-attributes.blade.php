
<div class="facility-bx--wrap">
    <h4>REFERENCE ID: <span class="text-uppercase f-three">{!!$property->ref!!}</span></h4>
    <!-- <h5>Complex: <span >{!!$property->complex_name!!}</span></h5> -->
    <!-- <h5>Complex: <span >{!!$property->town!!}</span></h5> -->
    <div class="facility-bx">
        <ul>
            @if($property->beds)
            <li>
                <div class="fac-bx">
                    <h5><img src="{{themeAsset('images/svg/pro-in-ic1.jpg')}}" alt="Bedrooms"> {{$property->beds}}</h5>
                    <p style="text-transform: capitalize;">Bedrooms</p>
                </div>
            </li>@endif
            @if($property->baths)
            <li>
                <div class="fac-bx">
                    <h5><img src="{{themeAsset('images/svg/pro-in-ic2.jpg')}}" alt="Bathrooms"> {{$property->baths}}</h5>
                    <p style="text-transform: capitalize;">Bathrooms</p>
                </div>
            </li>@endif

            @if( !empty($property->land_area) )
            <li>
                <div class="fac-bx">
                    <h5><img src="{{themeAsset('images/svg/pro-in-ic4.jpg')}}" alt="Internal Size"> {{$property->land_area}}</h5>
                    <p style="text-transform: capitalize;">Sq Ft</p>
                </div>
            </li>
            @endif
            @if( !empty($property->internal_area) )
            <li>
                <div class="fac-bx">
                    <h5><img src="{{themeAsset('images/svg/pro-in-ic3.jpg')}}" alt="Internal Size"> {{$property->internal_area}}</h5>
                    <p style="text-transform: capitalize;">Sq Ft</p>
                </div>
            </li>
            @endif

            <!-- For Balcony SIze -->
            @if( !empty($property->terrace_area) )
            <li>
                <div class="fac-bx">
                    <h5><img src="{{themeAsset('images/svg/Balcony-03.jpg')}}" alt="Internal Size"> {{$property->terrace_area}}</h5>
                    <p style="text-transform: capitalize;">Terrace</p>
                </div>
            </li>
            @endif
            <!-- For Balcony SIze -->

            @if( !empty($property->building_area) )
            <!--li>
                <div class="fac-bx">
                    <h5><img src="{{themeAsset('images/svg/pro-in-ic3.jpg')}}" alt="Internal Size"> {{$property->building_area}}</h5>
                    <p>SQ FT</p>
                </div>
            </li-->
            @endif
        </ul>
    </div>
</div>

<style>
.area_hide_for_whatsapp_message {
    display: none;
}
</style>

<div class="area_hide_for_whatsapp_message">
<h5>Complex: <span >{!!$property->town!!}</span></h5>
</div>
