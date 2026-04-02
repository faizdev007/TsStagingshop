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
          margin: 80px 30px 50px 30px;
        }
        
        body {
          margin: 0;
          font-family: "Roboto", DejaVu Sans, sans-serif;
          font-size: 12px;
        }
        
        /* ================= HEADER ================= */
        .header {
          position: fixed;
          top: -60px;
          left: 0;
          right: 0;
          height: 50px;
          text-align: right;
        }
        
        .header img {
          height: 50px;
        }
        
        /* ================= PAGE CONTROL ================= */
        /* PAGE */
        .page {
          width: 100%;
          position: relative;
          page-break-after: always;
        }
        
        .page:last-child {
          page-break-after: auto;
          padding-bottom: 120px;
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
          position: fixed;
          top: -60px;
          right: 0;
          text-align: right;
        }
        
        /* ================= PAGE INNER ================= */
        .page .page-wrap {
          padding-left: 40px;
          padding-right: 40px;
        }
        
        /* ================= EXISTING DESIGN (SAFE) ================= */
        .italic { font-style: italic; }
        .clear { clear: both; }
        .break { page-break-after: always; }
        
        .table-fix-layout { table-layout: fixed; }
        
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
            height: auto;        /* ✅ no stretch */
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
        
        .property-img-box{
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
            height: 140px;   /* fixed height */
            overflow: hidden;
        }
        
        .thumb-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;  /* ✅ crop, no stretch */
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <img src="{{ themeAsset('images/logos/logo.png') }}" alt="Logo">
    </div>

    <!-- PAGE 1 -->
    <div class="page">
        <div class="content-block">
            <div class="box">
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
                                    <img src="{{ storage_url($property->SecondaryPhoto) }}" class=""
                                        alt="Property photo 1">
                                </div>
                            </td>
                            <td width="33.33%">
                                <div
                                    class="thumb-box {{ ( $property->ThirdPhotoOrientation == 'portrait' ) ?'portrait':'' }}">
                                    <img src="{{ storage_url($property->ThirdPhoto) }}" class=""
                                        alt="Property photo 2">
                                </div>
                            </td>
                            <td width="33.33%">
                                <div
                                    class="thumb-box {{ ( $property->FourthPhotoOrientation == 'portrait' ) ?'portrait':'' }}">
                                    <img src="{{ storage_url($property->FourthPhoto) }}" class=""
                                        alt="Property photo 3">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="box">
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
    $fieldtype = '<img src="'.$mainurl.'/assets/demo1/images/svg/Property%20Feild%20Type.png" alt="Field Type" style="width: 21px; height: 19px;">';
    $Propertystatus = '<img src="'.$mainurl.'/assets/demo1/images/svg/Property%20Status.png" alt="Property Status" style="width: 21px; height: 19px;">';
    $Propertytype = '<img src="'.$mainurl.'/assets/demo1/images/svg/Property%20Type.png" alt="Property Type" style="width: 21px; height: 19px;">';
    $bedroomsIcon = '<img src="'.$mainurl.'/assets/demo1/images/svg/pro-in-ic1.jpg" alt="Bedrooms Icon">';
    $bathroomsIcon = '<img src="'.$mainurl.'/assets/demo1/images/svg/pro-in-ic2.jpg" alt="Bathrooms Icon">';
    $areaicon = '<img src="'.$mainurl.'/assets/demo1/images/svg/pro-in-ic3.jpg" alt="Area Icon">';
    $landicon = '<img src="'.$mainurl.'/assets/demo1/images/svg/pro-in-ic4.jpg" alt="Land Icon">';
    $terraceicon = '<img src="'.$mainurl.'/assets/demo1/images/svg/Balcony-03.jpg" alt="Terrace Icon" style="width: 21px; height: 19px;">';


    $additionalarray[] = $fieldtype . '&nbsp;&nbsp;&nbsp;Field Type: <strong>' . $property->ModeDisplay.'</strong>';
    if (!empty($property->state_display)) {
        $additionalarray[] =  $Propertystatus . '&nbsp;&nbsp;&nbsp;Property Status: <strong>' . $property->state_display.'</strong>';
    }

    if (!empty($property->PropertyTypeName)) {
        $additionalarray[] = $Propertytype . '&nbsp;&nbsp;&nbsp;Property Type: <strong>' . $property->PropertyTypeName.'</strong>';
    }

    if (!empty($property->beds)) {
        $additionalarray[] = $bedroomsIcon . '&nbsp;&nbsp;&nbsp;Number of Bedrooms: <strong>' . $property->beds.'</strong>';
    }

    if (!empty($property->baths)) {
        $additionalarray[] = $bathroomsIcon . '&nbsp;&nbsp;&nbsp;Number of Bathrooms: <strong>' . $property->baths.'</strong>';
    }

    if (!empty($property->Subtype)) {
        $additionalarray[] = 'Subtype: <strong>' . $property->Subtype.'</strong>';
    }

    if (!empty($property->Community)) {
        $additionalarray[] = 'Community: <strong>' . $property->Community.'</strong>';
    }

    if (!empty($property->internal_area)) {
        $additionalarray[] = $areaicon . '&nbsp;&nbsp;&nbsp;Area: <strong>' . $property->displayInternal.'</strong>';
    }

    if (!empty($property->land_area)) {
        $additionalarray[] = $landicon.'&nbsp;&nbsp;&nbsp;Area: <strong>' . $property->displayland.'</strong>';
    }

    if (!empty($property->terrace_area)) {
        $additionalarray[] = $terraceicon . '&nbsp;&nbsp;&nbsp;Terrace: <strong>' . $property->terrace_area . ' sq ft </strong>';
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
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper photos">
                        @php 
                            $photos = $property->propertyMediaPhotos;
                            $total = count($photos);
                        @endphp
                        
                        @for($i = 0; $i < $total; $i += 3)
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
                    <?php if($ctrFt==1){ ?>
                    <tr>
                        <?php } ?>
                        <td valig="top" width="50%" align="center">
                            <div class="item-ai">
                                <div class="item-ai-inner">
                                    {!!$info!!}
                                </div>
                            </div>
                        </td>
                        <?php if($ctrFt==3){ $ctrFt=0; ?>
                    </tr>
                    <?php } ?>
                    @endforeach
                </table>
            </div>
            @if(count($property->DisplayAmenitiesArray) > 0)
                <h2 class="page-heading">Amenities</h2>
                <div class="add-info-container" style="margin: 0px -5px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                        @php $ctrFt=0; $ctrFtt=0; @endphp
                        @foreach ($property->DisplayAmenitiesArray as $info)
                        @php $ctrFt++; $ctrFtt++; @endphp
                        <?php if($ctrFt==1){ ?>
                        <tr>
                            <?php } ?>
                            <td valig="top" width="50%" align="center">
                                <div class="item-ai">
                                    <div class="item-ai-inner">
                                        {!!$info!!}
                                    </div>
                                </div>
                            </td>
                            <?php if($ctrFt==3){ $ctrFt=0; ?>
                        </tr>
                        <?php } ?>
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
                    <img src="{{ themeAsset('images/logos/logo.png') }}" width="250px"/>
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