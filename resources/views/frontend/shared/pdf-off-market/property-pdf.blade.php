<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{$property->details_headline_v2}} - {{settings('site_name', config('app.name'))}}</title>
        <link type="text/css" href="{{ url('assets/shared/pdf-assets/css/property-pdf.css') }}" rel="stylesheet" />
    </head>

    <body marginwidth="0" marginheight="0">

        <!-- - - - - - - - - -  - - - - - - - - - - - - - - - - -  - - - - - - - -
            PAGE 1 - Cover page
        !- - - - - - - - - -  - - - - - - - - - - - - - - - - -  - - - - - - - -->
        <div class="page -page-1">

            <div class="main-img {{ ($property->PrimaryPhotoOrientation == 'portrait') ? 'portrait':'' }}">
                <img src="{{ $property->PrimaryPhoto }}" alt="">
            </div>

            <div class="property-images">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper photos" style="table-layout:fixed;">
                    <tr class="property-img-wrap">
                        <td width="33.33%">
                            <div class="thumb-box {{ ( $property->SecondaryPhotoOrientation == 'portrait' ) ?'portrait':'' }}">
                                <img src="{{ storage_url($property->SecondaryPhoto) }}" class="property-img" alt="Property photo 1">
                            </div>
                        </td>
                        <td width="33.33%">
                            <div class="thumb-box {{ ( $property->ThirdPhotoOrientation == 'portrait' ) ?'portrait':'' }}">
                                <img src="{{ storage_url($property->ThirdPhoto) }}" class="property-img" alt="Property photo 2">
                            </div>
                        </td>
                        <td width="33.33%">
                            <div class="thumb-box {{ ( $property->FourthPhotoOrientation == 'portrait' ) ?'portrait':'' }}">
                                <img src="{{ storage_url($property->FourthPhoto) }}" class="property-img" alt="Property photo 3">
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="title-pricing-row -page-margin">
                <table class="title-table">
                    <tr>
                        <td class="title-pane">
                            <h1>{{$property->details_headline_v2}}</h1>
                            <h2>{{$property->DisplayPropertyAddress}}</h2>
                        </td>
                        <td class="pricing-pane">
                            <h2>{!!$property->display_price!!}</h2>
                            <h3>Ref: {!!$property->ref!!}</h3>
                        </td>
                    </tr>
                </table>
               <div class="add-info-container">

               <ul style="list-style: none; text-align: left;padding-left: 0px;">
    <?php
    $additionalarray = [];

    // Define icons
    $fieldtype = '<img src="https://terezaestates.com/assets/demo1/images/svg/Property%20Feild%20Type.png" alt="Field Type" style="width: 21px; height: 19px;">';
    $Propertystatus = '<img src="https://terezaestates.com/assets/demo1/images/svg/Property%20Status.png" alt="Property Status" style="width: 21px; height: 19px;">';
    $Propertytype = '<img src="https://terezaestates.com/assets/demo1/images/svg/Property%20Type.png" alt="Property Type" style="width: 21px; height: 19px;">';
    $bedroomsIcon = '<img src="https://terezaestates.com/assets/demo1/images/svg/pro-in-ic1.jpg" alt="Bedrooms Icon">';
    $bathroomsIcon = '<img src="https://terezaestates.com/assets/demo1/images/svg/pro-in-ic2.jpg" alt="Bathrooms Icon">';
    $areaicon = '<img src="https://terezaestates.com/assets/demo1/images/svg/pro-in-ic3.jpg" alt="Area Icon">';
    $terraceicon = '<img src="https://terezaestates.com/assets/demo1/images/svg/Balcony-03.jpg" alt="Terrace Icon" style="width: 21px; height: 19px;">';


    $additionalarray[] = $fieldtype . '&nbsp;&nbsp;&nbsp;Field Type: ' . $property->ModeDisplay;
    if (!empty($property->state_display)) {
        $additionalarray[] =  $Propertystatus . '&nbsp;&nbsp;&nbsp;Property Status: ' . $property->state_display;
    }

    if (!empty($property->PropertyTypeName)) {
        $additionalarray[] = $Propertytype . '&nbsp;&nbsp;&nbsp;Property Type: ' . $property->PropertyTypeName;
    }

    if (!empty($property->beds)) {
        $additionalarray[] = $bedroomsIcon . '&nbsp;&nbsp;&nbsp;Number of Bedrooms: ' . $property->beds;
    }

    if (!empty($property->baths)) {
        $additionalarray[] = $bathroomsIcon . '&nbsp;&nbsp;&nbsp;Number of Bathrooms: ' . $property->baths;
    }

    if (!empty($property->Subtype)) {
        $additionalarray[] = 'Subtype: ' . $property->Subtype;
    }

    if (!empty($property->Community)) {
        $additionalarray[] = 'Community: ' . $property->Community;
    }

    if (!empty($property->internal_area)) {
        $additionalarray[] = $areaicon . '&nbsp;&nbsp;&nbsp;Area: ' . $property->displayInternal;
    }

    if (!empty($property->land_area)) {
        $additionalarray[] = 'Land Area: ' . $property->displayland;
    }

    if (!empty($property->terrace_area)) {
        $additionalarray[] = $terraceicon . '&nbsp;&nbsp;&nbsp;Terrace: ' . $property->terrace_area . ' sq ft';
    }

    $ctrFt = 0;
    $ctrFtt = 0;
    ?>
    @foreach ($additionalarray as $info)
        @php $ctrFt++; $ctrFtt++; @endphp
        @if($ctrFt == 1)
            <tr>
        @endif

        <li class="item-ai text-left">
            <div class="item-ai-inners">
                {!! $info !!}
            </div>
        </li>

        @if($ctrFt == 3)
            </tr>
            @php $ctrFt = 0; @endphp
        @endif
    @endforeach
</ul>
            </div>
            <div class="clear"></div>
            </div>
            @include('frontend.shared.pdf.property-pdf-footer-common') 
        </div>

        <!-- - - - - - - - - -  - - - - - - - - - - - - - - - - -  - - - - - - - -
            PAGE 2 - Gallery page
        !- - - - - - - - - -  -  - - - - - - - - - - - - - - - -  - - - - - - - -->
        @if(count($property->propertyMediaPhotos))
        <div class="page">
            <h2 class="page-heading -page-margin">Gallery</h2>
            <div class="property-gallery -page-margin">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper photos">
                    @php $ctrFt=0; $ctrFtt=0; @endphp
                    @foreach( $property->propertyMediaPhotos as $media )
                        @php $ctrFt++; $ctrFtt++; @endphp
                        @php if($ctrFt==1){ @endphp <tr> @php } @endphp
                            <td valig="top" width="30%" align="center">
                                <div class="property-img-box">
                                    <img src="{{ storage_url($media->path) }}" class="property-img"  alt="Property photo">
                                </div>
                            </td>
                        @php if($ctrFt==3){ $ctrFt=0; @endphp </tr> @php } @endphp
                        @php if($ctrFtt==15) { break; } @endphp
                    @endforeach
                </table>
            </div>
            @include('frontend.shared.pdf.property-pdf-footer-common') 
        </div>
        @endif

        <!-- - - - - - - - - -  - - - - - - - - - - - - - - - - -  - - - - - - - -
            PAGE 3 - Description page
        !- - - - - - - - - -  -  - - - - - - - - - - - - - - - -  - - - - - - - -->
        <div class="page">
            <h2 class="page-heading -page-margin">Property Description</h2>
            <div class="-page-margin">
                <div class="tab-property-location">
                    <strong>Location:</strong> {{ $property->DisplayPropertyAddress }}
                </div>
                {!! $property->description !!}
            </div>
            <div class="clear"></div>
            @include('frontend.shared.pdf.property-pdf-footer-common') 
        </div>

        <!-- - - - - - - - - -  - - - - - - - - - - - - - - - - -  - - - - - - - -
            PAGE 4 - Additional Information
        !- - - - - - - - - -  -  - - - - - - - - - - - - - - - -  - - - - - - - -->
        <div class="page">
            <h2 class="page-heading -page-margin">Additional Info</h2>
            <div class="add-info-container -page-margin">

                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                    @php
                        $additionalarray = [];
                        $additionalarray[] = $property->ModeDisplay;                      

                        $ArrayAddInfo = array_merge($additionalarray,$property->ArrayAddInfo);
                        $ctrFt=0; $ctrFtt=0;
                    @endphp
                    @foreach( $ArrayAddInfo as $info )
                        @php $ctrFt++; $ctrFtt++; @endphp
                        <?php if($ctrFt==1){ ?><tr><?php } ?>
                            <td valig="top" width="50%" align="center">
                                <div class="item-ai">
                                    <div class="item-ai-inner">
                                        {!!$info!!}
                                    </div>
                                </div>
                            </td>
                        <?php if($ctrFt==3){ $ctrFt=0; ?></tr><?php } ?>
                    @endforeach
                </table>

                <h2 class="page-heading -page-margin">Amenities</h2>
            
                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                    @php $ctrFt=0; $ctrFtt=0; @endphp
                    @foreach ($property->DisplayAmenitiesArray as $info)
                        @php $ctrFt++; $ctrFtt++; @endphp
                        <?php if($ctrFt==1){ ?><tr><?php } ?>
                            <td valig="top" width="50%" align="center">
                                <div class="item-ai">
                                    <div class="item-ai-inner">
                                        {!!$info!!}
                                    </div>
                                </div>
                            </td>
                        <?php if($ctrFt==3){ $ctrFt=0; ?></tr><?php } ?>
                    @endforeach
                </table>

            </div>
            <!-- <div class="clear"></div>

            @if (empty($property->add_amenities) && !count($property->propertyMediaFloorplans))
                @include('frontend.shared.pdf.property-pdf-footer')
            @endif
        </div> -->

        <div class="clear"></div>
            
            @if (!count($property->propertyMediaFloorplans))
                @include('frontend.shared.pdf.property-pdf-footer')
            @endif
        </div>

        <!-- - - - - - - - - -  - - - - - - - - - - - - - - - - -  - - - - - - - -
            PAGE  - Amenities
        !- - - - - - - - - -  -  - - - - - - - - - - - - - - - -  - - - - - - - -->
        <!-- @if( !empty($property->add_amenities) )
        <div class="page">
            <h2 class="page-heading -page-margin">Amenities</h2>
            <div class="add-info-container -page-margin">

                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                    @php $ctrFt=0; $ctrFtt=0; @endphp
                    @foreach ($property->DisplayAmenitiesArray as $info)
                        @php $ctrFt++; $ctrFtt++; @endphp
                        <?php if($ctrFt==1){ ?><tr><?php } ?>
                            <td valig="top" width="50%" align="center">
                                <div class="item-ai">
                                    <div class="item-ai-inner">
                                        {!!$info!!}
                                    </div>
                                </div>
                            </td>
                        <?php if($ctrFt==3){ $ctrFt=0; ?></tr><?php } ?>
                    @endforeach
                </table>
            </div> -->
            <!-- <div class="clear"></div>
            
            @if (!count($property->propertyMediaFloorplans))
                @include('frontend.shared.pdf.property-pdf-footer')
            @endif
        </div>
        @endif -->

        <!-- - - - - - - - - -  - - - - - - - - - - - - - - - - -  - - - - - - - -
            PAGE 5 - FloorPlan
        !- - - - - - - - - -  -  - - - - - - - - - - - - - - - -  - - - - - - - -->
        @if( count($property->propertyMediaFloorplans) )
        <div class="page">
            <h2 class="page-heading -page-margin">FloorPlans</h2>
            <div class="property-gallery -page-margin">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper photos">
                    @php $ctrFt=0; $ctrFtt=0; @endphp
                    @foreach( $property->propertyMediaFloorplans as $media )
                        @php $ctrFt++; $ctrFtt++; @endphp
                        @php if($ctrFt==1){ @endphp<tr>@php } @endphp
                            <td valig="top" width="30%" align="center">
                                <div class="property-img-box">
                                    @if($media->extension == 'pdf')
                                        <a href="{{ $media->photo_display }}" target="_blank" class="pdf-icon">PDF</a>
                                    @else
                                        <img src="{{ $media->photo_display }}" class="property-img"  alt="Property photo">
                                    @endif
                                </div>
                            </td>
                        @php if($ctrFt==3){ $ctrFt=0; @endphp</tr>@php } @endphp
                        @php if($ctrFtt==15){ break; } @endphp
                    @endforeach
                </table>
            </div>
            @include('frontend.shared.pdf.property-pdf-footer')
        </div>
        @endif

    </body>
</html>
