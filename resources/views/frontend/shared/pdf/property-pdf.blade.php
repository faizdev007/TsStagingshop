<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$property->details_headline_v2}} - {{setting('site_name', config('app.name'))}}</title>
    <link type="text/css" href="{{ asset('assets/shared/pdf-assets/css/property-pdf.css') }}" rel="stylesheet" />
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 100%;
            overflow: hidden;
            page-break-after: auto;
        }

        .page:not(:first-child) {
            page-break-after: always;
        }

        @page {
            margin: 50px 20px 80px 20px;
            /* top right bottom left */
        }

        .header {
            position: fixed;
            top: -10px;
            width: 100%;
            height: 60px;
            display: flex;
            justify-content: flex-end;
            /* push logo to right */
            align-items: center;
        }

        .footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            /* 🔥 critical */
        }

        td {
            vertical-align: top;
            word-wrap: break-word;
            overflow: hidden;
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        .property-img {
            width: 100%;
            height: auto;
            max-height: 200px;
            /* 🔥 prevents overflow */
            object-fit: cover;
        }

        .main-img img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
        }

        .item-ai-inners,
        .item-ai-inner {
            font-size: 11px;
            line-height: 1.4;
            word-break: break-word;
        }

        h1,
        h2,
        h3 {
            margin: 5px 0;
            word-break: break-word;
        }

        .page-heading {
            font-size: 18px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body marginwidth="0" marginheight="0">
    @include('frontend.shared.pdf.property-pdf-footer-common')
    <!-- - - - - - - - - -  - - - - - - - - - - - - - - - - -  - - - - - - - -
        PAGE 1 - Cover page   -page-1
    !- - - - - - - - - -  - - - - - - - - - - - - - - - - -  - - - - - - - -->
    <div class="page -page-1">
        <div class="content">
            <div class="main-img {{ ($property->PrimaryPhotoOrientation == 'portrait') ? 'portrait':'' }}">
                <img src="{{ $property->PrimaryPhoto }}" style="width:100%; max-height:400px;">
            </div>

            <div class="property-images">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper photos"
                    style="table-layout:fixed;">
                    <tr class="property-img-wrap">
                        <td width="33.33%">
                            <div
                                class="thumb-box {{ ( $property->SecondaryPhotoOrientation == 'portrait' ) ?'portrait':'' }}">
                                <img src="{{ storage_url($property->SecondaryPhoto) }}" class="property-img"
                                    alt="Property photo 1">
                            </div>
                        </td>
                        <td width="33.33%">
                            <div
                                class="thumb-box {{ ( $property->ThirdPhotoOrientation == 'portrait' ) ?'portrait':'' }}">
                                <img src="{{ storage_url($property->ThirdPhoto) }}" class="property-img"
                                    alt="Property photo 2">
                            </div>
                        </td>
                        <td width="33.33%">
                            <div
                                class="thumb-box {{ ( $property->FourthPhotoOrientation == 'portrait' ) ?'portrait':'' }}">
                                <img src="{{ storage_url($property->FourthPhoto) }}" class="property-img"
                                    alt="Property photo 3">
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="page -page-1">
                <div class="title-pricing-row" style="margin: 5px 0;">
                    <table width="100%" cellspacing="0" cellpadding="5" style="table-layout:fixed;">
                        <tr>
                            <!-- LEFT SIDE -->
                            <td width="70%" valign="top" style="overflow:hidden;">

                                <h1 style="font-size:22px; margin:0; word-break:break-word;">
                                    {{$property->details_headline_v2}}
                                </h1>

                                <h2 style="font-size:14px; margin:5px 0; word-break:break-word;">
                                    {{$property->DisplayPropertyAddress}}
                                </h2>

                            </td>

                            <!-- RIGHT SIDE -->
                            <td width="30%" valign="top" align="right" style="overflow:hidden;">

                                <h2 style="margin:0; font-size:18px;">
                                    {!! $property->display_price !!}
                                </h2>

                                <h3 style="margin-top:5px; font-size:12px;">
                                    Ref: {!! $property->ref !!}
                                </h3>

                            </td>
                        </tr>
                    </table>

                    <div class="add-info-container" style="page-break-inside: avoid;">

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
                            <li class="item-ai text-left">
                                <div class="item-ai-inners">
                                    {!! $info !!}
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
    <div style="page-break-after: always;"></div>
    <!-- - - - - - - - - -  - - - - - - - - - - - - - - - - -  - - - - - - - -
            PAGE 2 - Gallery page
        !- - - - - - - - - -  -  - - - - - - - - - - - - - - - -  - - - - - - - -->
    @if(count($property->propertyMediaPhotos))
    <div class="page -page-1">
        <div class="content -page-margin">
            <h2 class="page-heading">Gallery</h2>
            <div class="property-gallery">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper photos">
                    @php $ctrFt=0; $ctrFtt=0; @endphp
                    @foreach( $property->propertyMediaPhotos as $media )
                    @php $ctrFt++; $ctrFtt++; @endphp
                    @php if($ctrFt==1){ @endphp <tr> @php } @endphp
                        <td valig="top" width="30%" align="center">
                            <div class="property-img-box">
                                <img src="{{ storage_url($media->path) }}" style="width:100%; max-height:200px;">
                            </div>
                        </td>
                        @php if($ctrFt==3){ $ctrFt=0; @endphp
                    </tr> @php } @endphp
                    @php if($ctrFtt==15) { break; } @endphp
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div style="page-break-after: always;"></div>
    @endif

    <!-- - - - - - - - - -  - - - - - - - - - - - - - - - - -  - - - - - - - -
            PAGE 3 - Description page
        !- - - - - - - - - -  -  - - - - - - - - - - - - - - - -  - - - - - - - -->
    <div class="page -page-1">
        <div class="content -page-margin">
            <h2 class="page-heading">Property Description</h2>
            <div>
                <div class="tab-property-location">
                    <strong>Location:</strong> {{ $property->DisplayPropertyAddress }}
                </div>
                <div style="word-break: break-word; overflow: hidden;">
                    {!! $property->description !!}
                </div>
            </div>
        </div>
    </div>

    <div style="page-break-after: always;"></div>
    <!-- - - - - - - - - -  - - - - - - - - - - - - - - - - -  - - - - - - - -
            PAGE 4 - Additional Information
        !- - - - - - - - - -  -  - - - - - - - - - - - - - - - -  - - - - - - - -->
    <div class="page -page-1">
        <div class="content -page-margin">
            <h2 class="page-heading">Additional Info</h2>
            <div class="add-info-container" style="margin: 0px -5px;">

                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                    @php
                    $additionalarray = [];
                    $additionalarray[] = $property->ModeDisplay;

                    $ArrayAddInfo = array_merge($additionalarray,$property->ArrayAddInfo);
                    $ctrFt=0; $ctrFtt=0;
                    @endphp
                    @foreach( $ArrayAddInfo as $info )
                    @php $ctrFt++; $ctrFtt++; @endphp
                    <?php if ($ctrFt == 1) { ?>
                        <tr>
                        <?php } ?>
                        <td valig="top" width="50%" align="center">
                            <div class="item-ai">
                                <div class="item-ai-inner">
                                    {!!$info!!}
                                </div>
                            </div>
                        </td>
                        <?php if ($ctrFt == 3) {
                            $ctrFt = 0; ?>
                        </tr>
                    <?php } ?>
                    @endforeach
                </table>
            </div>
            <h2 class="page-heading">Amenities</h2>
            <div class="add-info-container" style="margin: 0px -5px;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                    @php $ctrFt=0; $ctrFtt=0; @endphp
                    @foreach ($property->DisplayAmenitiesArray as $info)
                    @php $ctrFt++; $ctrFtt++; @endphp
                    <?php if ($ctrFt == 1) { ?>
                        <tr>
                        <?php } ?>
                        <td valig="top" width="50%" align="center">
                            <div class="item-ai">
                                <div class="item-ai-inner">
                                    {!!$info!!}
                                </div>
                            </div>
                        </td>
                        <?php if ($ctrFt == 3) {
                            $ctrFt = 0; ?>
                        </tr>
                    <?php } ?>
                    @endforeach
                </table>
            </div>
        </div>

        <div style="page-break-after: always;"></div>
        <!-- - - - - - - - - -  - - - - - - - - - - - - - - - - -  - - - - - - - -
            PAGE 5 - FloorPlan
        !- - - - - - - - - -  -  - - - - - - - - - - - - - - - -  - - - - - - - -->
        @if( count($property->propertyMediaFloorplans) )
        <div class="page -page-1">
            <div class="content">
                <h2 class="page-heading -page-margin">FloorPlans</h2>
                <div class="property-gallery -page-margin">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"
                        class="wrapper photos">
                        @php $ctrFt=0; $ctrFtt=0; @endphp
                        @foreach( $property->propertyMediaFloorplans as $media )
                        @php $ctrFt++; $ctrFtt++; @endphp
                        @php if($ctrFt==1){ @endphp<tr>@php } @endphp
                            <td valig="top" width="30%" align="center">
                                <div class="property-img-box">
                                    @if($media->extension == 'pdf')
                                    <a href="{{ $media->photo_display }}" target="_blank" class="pdf-icon">PDF</a>
                                    @else
                                    <img src="{{ $media->photo_display }}" class="property-img" alt="Property photo">
                                    @endif
                                </div>
                            </td>
                            @php if($ctrFt==3){ $ctrFt=0; @endphp
                        </tr>@php } @endphp
                        @php if($ctrFtt==15){ break; } @endphp
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        @endif

        @include('frontend.shared.pdf.property-pdf-footer')

</body>

</html>