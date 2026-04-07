@php
$mainurl = url('/').'/public';
@endphp

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$property->details_headline_v2}} - {{setting('site_name', config('app.name'))}}</title>

    <style>
        /* ================= PAGE SETUP ================= */
        @page {
            margin: 30px;
        }
        
        body {
            margin: 0;
            padding-top: 80px; /* space for header */
            font-family: "Roboto", DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        /* ================= HEADER ================= */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
        }

        .header img {
            height: 60px;
        }

        /* ================= PAGE CONTROL ================= */
        /* PAGE */
        .page {
            width: 100%;
            position: relative;
            page-break-after: auto;
        }

        .page:last-child {
            page-break-after: auto;
            padding-bottom: 120px;
        }
        
        
        .page + .page {
            page-break-before: always;
        }

        /* ================= FIX BREAKING ================= */
        /* BREAK SAFETY */
        .box,
        .property-gallery,
        .add-info-container {
            page-break-inside: avoid;
        }

        /* ================= LAST FOOTER ================= */
        .last-footer {
            bottom: 10px;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        /* ================= REMOVE FLEX POSSIBILITY ================= */
        .header {
            text-align: right;
        }

        /* ================= PAGE INNER ================= */
        .page .page-wrap {
            padding-left: 40px;
            padding-right: 40px;
        }

        /* ================= EXISTING DESIGN (SAFE) ================= */
        .italic {
            font-style: italic;
        }

        .clear {
            clear: both;
        }

        .break {
            page-break-after: always;
        }

        .table-fix-layout {
            table-layout: fixed;
        }

        .page-heading {
            padding: 20px 0;
            font-size: 24px;
        }

        .-page-margin {
            padding-left: 35px;
            padding-right: 35px;
        }

        /* ================= IMAGES ================= */
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        .main-img img {
            width: 100%;
            height: auto;
            /* ✅ no stretch */
            max-height: 400px;
            object-fit: cover;
        }

        .property-img {
            width: 100%;
            max-height: 180px;
            object-fit: cover;
        }

        /* ================= TABLE SAFETY ================= */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td {
            vertical-align: top;
            word-wrap: break-word;
        }

        /* ================= PROPERTY GALLERY ================= */
        .property-gallery {
            text-align: center;
        }

        .property-gallery .property-img {
            max-height: 150px;
            margin-bottom: 15px;
        }

        .property-img-box {
            height: 150px;
            overflow: hidden;
        }

        .property-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .property-img-box {
            border: 2px solid #d9b483;
        }

        /* ================= TITLE / PRICING ================= */
        .title-pricing-row table.title-table {
            width: 100%;
        }

        .title-pricing-row table.title-table .title-pane {
            width: 50%;
        }

        .title-pricing-row table.title-table .pricing-pane {
            width: 50%;
            text-align: right;
        }

        /* ================= ADDITIONAL INFO ================= */
        .add-info-container .item-ai {
            padding: 3px 5px;
        }

        .add-info-container .item-ai .item-ai-inner {
            background-color: #e0e0e0;
            padding: 10px;
        }

        /* ================= NEW LAST PAGE FOOTER ================= */
        .last-footer {
            position: absolute;
            bottom: 10px;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        /* Prevent overlap */
        .page:last-child {
            padding-bottom: 120px;
        }

        .thumb-box {
            width: 100%;
            height: 140px;
            /* fixed height */
            overflow: hidden;
        }

        .thumb-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* ✅ crop, no stretch */
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <img src="{{ asset('/assets/demo1/images/logos/logo.png') }}" alt="Logo">
    </div>
    
    <div class="main-img {{ ($property->PrimaryPhotoOrientation == 'portrait') ? 'portrait':'' }}">
        <img src="{{ $property->PrimaryPhoto }}" style="width:100%; padding-left:-40px; padding-right:-40px;">
    </div>
    <!-- PAGE 1 -->
    <div class="page" style="padding-top:0px!important;">
        <div class="content-block">
            <div class="box">
                <!--<div class="property-images">-->
                <!--    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper photos"-->
                <!--        style="table-layout:fixed;">-->
                <!--        <tr class="property-img-wrap">-->
                <!--            <td width="33.33%">-->
                <!--                <div-->
                <!--                    class="thumb-box {{ ( $property->SecondaryPhotoOrientation == 'portrait' ) ?'portrait':'' }}">-->
                <!--                    <img src="{{ storage_url($property->SecondaryPhoto) }}" class=""-->
                <!--                        alt="Property photo 1">-->
                <!--                </div>-->
                <!--            </td>-->
                <!--            <td width="33.33%">-->
                <!--                <div-->
                <!--                    class="thumb-box {{ ( $property->ThirdPhotoOrientation == 'portrait' ) ?'portrait':'' }}">-->
                <!--                    <img src="{{ storage_url($property->ThirdPhoto) }}" class=""-->
                <!--                        alt="Property photo 2">-->
                <!--                </div>-->
                <!--            </td>-->
                <!--            <td width="33.33%">-->
                <!--                <div-->
                <!--                    class="thumb-box {{ ( $property->FourthPhotoOrientation == 'portrait' ) ?'portrait':'' }}">-->
                <!--                    <img src="{{ storage_url($property->FourthPhoto) }}" class=""-->
                <!--                        alt="Property photo 3">-->
                <!--                </div>-->
                <!--            </td>-->
                <!--        </tr>-->
                <!--    </table>-->
                <!--</div>-->
                
                <table width="100%" cellspacing="0" cellpadding="5" style="table-layout:fixed;">
                    <tr>
                        <!-- LEFT SIDE -->
                        <td width="100%" valign="top" style="overflow:hidden;">

                            <h1 style="font-size:22px; margin:0; word-break:break-word;">
                                {{$property->details_headline_v2}}
                            </h1>

                        </td>
                    </tr>
                    <tr>
                        <h2 style="font-size:14px; margin:5px; word-break:break-word;">
                            {{$property->DisplayPropertyAddress}}
                        </h2>
                    </tr>
                    <tr>
                        <!-- RIGHT SIDE -->
                        <td width="100%" valign="top" style="overflow:hidden; display:flex; justfiy-content:between;">

                            <h2 style="margin:0; font-size:18px;">
                                {!! $property->display_price !!}
                            </h2>

                            <h3 style="margin-top:5px; font-size:12px;">
                                Ref: {!! $property->ref !!}
                            </h3>

                        </td>
                    </tr>
                </table>
                
                <div style="margin-top:20px;">

                    <!-- CARD CONTAINER -->
                    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:separate; border-spacing:8px;">
                        
                        @php
                            // Define icons
                            $fieldtype = '<img src="' . $mainurl . '/assets/demo1/images/svg/Property%20Feild%20Type.png" alt="Field Type" style="width: 21px; height: 19px;">';
                            $Propertystatus = '<img src="' . $mainurl . '/assets/demo1/images/svg/Property%20Status.png" alt="Property Status" style="width: 21px; height: 19px;">';
                            $Propertytype = '<img src="' . $mainurl . '/assets/demo1/images/svg/Property%20Type.png" alt="Property Type" style="width: 21px; height: 19px;">';
                            $bedroomsIcon = '<img src="' . $mainurl . '/assets/demo1/images/svg/pro-in-ic1.jpg" alt="Bedrooms Icon">';
                            $bathroomsIcon = '<img src="' . $mainurl . '/assets/demo1/images/svg/pro-in-ic2.jpg" alt="Bathrooms Icon">';
                            $areaicon = '<img src="' . $mainurl . '/assets/demo1/images/svg/pro-in-ic3.jpg" alt="Area Icon">';
                            $landicon = '<img src="' . $mainurl . '/assets/demo1/images/svg/pro-in-ic4.jpg" alt="Land Icon">';
                            $terraceicon = '<img src="' . $mainurl . '/assets/demo1/images/svg/Balcony-03.jpg" alt="Terrace Icon" style="width: 21px; height: 19px;">';
                            
                            $rows = [];
                
                            function row($icon, $label, $value) {
                                return compact('icon','label','value');
                            }
                
                            $rows[] = row($fieldtype, 'Field Type', $property->ModeDisplay);
                
                            if (!empty($property->state_display)) {
                                $rows[] = row($Propertystatus, 'Status', $property->state_display);
                            }
                
                            if (!empty($property->PropertyTypeName)) {
                                $rows[] = row($Propertytype, 'Type', $property->PropertyTypeName);
                            }
                
                            if (!empty($property->beds)) {
                                $rows[] = row($bedroomsIcon, 'Bedrooms', $property->beds);
                            }
                
                            if (!empty($property->baths)) {
                                $rows[] = row($bathroomsIcon, 'Bathrooms', $property->baths);
                            }
                
                            if (!empty($property->Subtype)) {
                                $rows[] = row('', 'Subtype', $property->Subtype);
                            }
                
                            if (!empty($property->Community)) {
                                $rows[] = row('', 'Community', $property->Community);
                            }
                
                            if (!empty($property->internal_area)) {
                                $rows[] = row($areaicon, 'Built-up Area', $property->displayInternal);
                            }
                
                            if (!empty($property->land_area)) {
                                $rows[] = row($landicon, 'Land Area', $property->displayland);
                            }
                
                            if (!empty($property->terrace_area)) {
                                $rows[] = row($terraceicon, 'Terrace', $property->terrace_area . ' sq ft');
                            }
                        @endphp
                
                        @foreach($rows as $i => $row)
                            @if($i % 2 == 0)
                                <tr>
                            @endif
                
                            <td width="50%" valign="top">
                                <!-- CARD -->
                                <table width="100%" cellpadding="8" cellspacing="0"
                                    style="
                                        border:1px solid #d9b483;
                                        border-radius:6px;
                                        background:#fafafa;
                                    ">
                                    <tr>
                                        <!-- TEXT -->
                                        <td>
                                            <div style="display:flex;">
                                                <div style="">
                                                    {!! $row['icon'] !!}
                                                </div>
                                                <div style="flex:1;">
                                                    <div style="font-size:10px; color:#888;">
                                                        {{ strtoupper($row['label']) }}
                                                    </div>
                                                    <div style="font-size:13px; font-weight:bold; color:#222;">
                                                        {{ $row['value'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                
                            @if($i % 2 == 1)
                                </tr>
                            @endif
                        @endforeach
                
                    </table>
                
                </div>
            </div>
        </div>
    </div>

    @if(count($property->propertyMediaPhotos))
    <!-- PAGE 2 -->
    <div class="page">
        <div class="content-block">
            <div class="box">
                <h2 class="page-heading">Gallery</h2>
                <div class="property-gallery">
                    <table width="100%" border="0" cellspacing="4" cellpadding="8" align="center" class="wrapper photos">
                        @php
                        $photos = $property->propertyMediaPhotos;
                        $total = count($photos);
                        @endphp

                        @for($i = 0; $i < $total; $i +=3)
                            <tr>
                            @for($j = 0; $j < 3; $j++)
                                @if(isset($photos[$i + $j]))
                                <td width="33.33%" align="center">
                                    <div class="property-img-box thumb-box">
                                        <img src="{{ storage_url($photos[$i + $j]->path) }}">
                                    </div>
                                </td>
                                @else
                                <td width="33.33%"></td>
                                @endif
                            @endfor
                            </tr>
                        @endfor
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- PAGE 3 -->
    <div class="page">
        <div class="content-block">
            <div class="box">
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
    </div>


    <!-- PAGE 4 -->
    <div class="page">
        <div class="content-block">
            <div class="box">
                <div style="margin-top:50px">
                    <div class="content">
                        <h2 class="page-heading">Key Features</h2>
                        <div>
                            <table width="100%" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
                                @php
                                    $additionalarray = [];
                                    $additionalarray[] = $property->ModeDisplay;
                        
                                    $ArrayAddInfo = array_merge($additionalarray, $property->ArrayAddInfo);
                                    $chunks = array_chunk($ArrayAddInfo, 3); // 3 column layout
                                @endphp
                        
                                @foreach($chunks as $row)
                                    <tr>
                                        @foreach($row as $info)
                                            <td width="50%" valign="top" style="padding:6px 4px;">
                        
                                                <!-- ITEM -->
                                                <table width="100%" cellpadding="4" cellspacing="0">
                                                    <tr>
                                                        <!-- ICON -->
                                                        <td width="10%" valign="top" style="color:#fff; background:#000; border-radius:4px; padding:2px 10px; display:flex; align-content-item:center; justify-content:center; text-align:center; font-size:12px;">
                                                            ✓
                                                        </td>
                        
                                                        <!-- TEXT -->
                                                        <td width="90%" style="font-size:12px; color:#333;">
                                                            {!! $info !!}
                                                        </td>
                                                    </tr>
                                                </table>
                        
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </table>
                        
                        </div>
                        @if(count($property->DisplayAmenitiesArray) > 0)
                        <h2 class="page-heading">Amenities</h2>

                            <div>
                            
                                <table width="100%" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
                                    @php
                                        $chunks = array_chunk($property->DisplayAmenitiesArray, 3);
                                    @endphp
                            
                                    @foreach($chunks as $row)
                                        <tr>
                                            @foreach($row as $info)
                                                <td width="50%" valign="top" style="padding:6px 4px;">
                            
                                                    <table width="100%" cellpadding="4">
                                                        <tr>
                                                            <td width="10%" valign="top" style="color:#fff; background:#000; border-radius:4px; padding:4px 10px; display:flex; align-content-item:center; justify-content:center; text-align:center; font-size:12px;">
                                                                ✓
                                                            </td>
                                                            <td width="90%" style="font-size:12px; color:#333;">
                                                                {!! $info !!}
                                                            </td>
                                                        </tr>
                                                    </table>
                            
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </table>
                            
                            </div>
                            
                            @endif
                    </div>
                </div>
            </div>
            @if( count($property->propertyMediaFloorplans) )
            <div class="box">
                <h2 class="page-heading">FloorPlans</h2>
                <div class="property-gallery">
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
            @endif
        </div>
        <!-- ✅ FOOTER INSIDE LAST PAGE -->
        <div class="last-footer">
            <table border="0" width="100%">
                <tr>
                    <td width="50%" align="right">
                        <img src="{{ themeAsset('images/logos/logo.png') }}" width="250px" />
                    </td>

                    <td width="50%" align="start" style="border-left: 3px solid #db381e; padding-left: 10px;">
                        <div><strong>Mobile: <a href="tel:{{ settings('telephone') }}">{{ settings('telephone') }}</a></strong></div>
                        <div><strong>Email: <a href="mailto:{{ settings('email') }}">{{ settings('email') }}</a></strong></div>
                        <div>{!! settings('footer_address') !!}</div>
                        <div><strong><a href="{{ url('/') }}">{{ url('/') }}</a></strong></div>
                    </td>
                </tr>
            </table>
        </div>
</body>

</html>