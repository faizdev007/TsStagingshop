<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Property Report - {{$property->ref}}</title>
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 210mm;
            margin: 0 auto;
            padding: 10mm;
        }
        
        .pdf-container {
            width: 100%;
            max-width: 190mm;
            margin: 0 auto;
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 4mm;
        }
        
        .logo {
            max-width: 60mm;
            max-height: 24mm;
            object-fit: contain;
        }
        
        .primary-image {
            width: 100%;
            max-height: 120mm;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10mm;
        }
        
        .report-title {
            text-align: center;
            font-size: 16pt;
            color: #d9b483;
            margin-bottom: 3mm;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .property-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8mm;
            padding-bottom: 3mm;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .property-title {
            font-size: 14pt;
            font-weight: bold;
            color:rgb(21, 21, 21);
            margin-top: 2mm;
        }
        
        .property-location {
            font-size: 12pt;
            color:rgb(52, 52, 52);
         
        }
        
        .property-price {
            font-size: 18pt;
            color: #000000;
            font-weight: bold;
            text-align: left;
            margin-bottom: 1mm
        }
        
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8mm;
        }
        
        .stats-table th, 
        .stats-table td {
            border: 1px solid #e0e0e0;
            padding: 3mm;
            text-align: left;
            font-size: 10pt;
        }
        
        .stats-table thead {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .stats-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .section-title {
            text-align: center;
            font-size: 16pt;
            color: #d9b483;
            margin-bottom: 3mm;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .logo2-section {
            page-break-before: always;
            text-align: center;
            margin-bottom: 3mm;
        }
        
        .logo2 {
            max-width: 60mm;
            max-height: 24mm;
            object-fit: contain;
        }
        .performance-metrics {
            
            padding-top: 10mm;
        }
        
        .performance-metrics .section-title {
            margin-top: 10mm;
        }
        
        .sec_footer {
            margin-top: 10mm;
            text-align: center;
            font-size: 8pt;
            color: #7f8c8d;
            border-top: 1px solid #e0e0e0;
            padding-top: 3mm;
        }
        
        .no-data-message {
            text-align: center; 
            color: #666; 
            font-style: italic; 
            margin-top: 10mm;
        }
        .page .footer {
  position: absolute;
  width: 100%;
  left: 0;
  bottom: 0;
  padding: 15px 30px 20px 30px;
  /* height: 72px; */
  color: #000;
}
.page .footer .f-info { 
  padding: 10px;
}
.page .footer .f-info h3 {
  margin: 0;
  padding: 0;
  font-size: 15px;
}
.page .footer .f-info p {
  margin: 0;
  padding: 0;
  font-size: 14px;
}
.page .footer .f-info a {
  color: #000;
  text-decoration: none;
}
.page .footer .f-info a.text-red{color:#ff0000;}
.page .footer .f-logo {
  text-align: center;
    border-right: 3px solid #ff0000;
}
.page .footer .f-logo1 {
  text-align: center;
}

.add-info-container .item-ai {
  padding: 3px 5px;
}
.add-info-container .item-ai .item-ai-inner {
  background-color: #e0e0e0;
  padding: 15px 0px;
}
.property-details-cust-grid{
    display: flex;

    
}
    </style>
</head>
<body>
    <div class="pdf-container">
        {{-- Logo Section --}}
        <div class="logo-section">
            <img src="https://terezaestates.com/assets/demo1/images/logos/logo.svg" alt="Tereza Estates Logo" class="logo">
        </div>

        {{-- Primary Image --}}
        @php
        $primaryPhotoPath = null;
        try {
            $primaryPhotoPath = storage_url($property->PrimaryPhoto);
            $imageInfo = @getimagesize($primaryPhotoPath);
        } catch (Exception $e) {
            $primaryPhotoPath = null;
        }
        @endphp
        @if($primaryPhotoPath)
            <img src="{{ $primaryPhotoPath }}" alt="Primary Property Photo" class="primary-image">
        @else
            <div style="width: 100%; height: 120mm; background-color: #f0f0f0; display: flex; justify-content: center; align-items: center;">
                No Primary Photo Available
            </div>
        @endif


        {{-- Report Title --}}
        <h1 class="report-title">Property Performance Report</h1>

        {{-- Property Header --}}
        <div class="property-header">
            <div>
                <br>

                <div class="property-title">{{ $property->details_headline_v2 ?? 'Property Details' }}</div>
                <div class="property-location">{{ $property->DisplayPropertyAddress ?? 'Location Not Available' }}</div>
            </div>
            <div class="property-price">{!! $property->display_price ?? 'Price Not Listed' !!}</div>
            <br>
        </div>

        <div style="padding-bottom:-20px">
            

<ul >
    @php
    $additionalDetails = [];

    // Define icons
    $fieldTypeIcon = '<img src="https://terezaestates.com/assets/demo1/images/svg/Property%20Feild%20Type.png" alt="Field Type" style="width: 21px; height: 19px; vertical-align: middle; margin-right: 8px;">';
    $propertyStatusIcon = '<img src="https://terezaestates.com/assets/demo1/images/svg/Property%20Status.png" alt="Property Status" style="width: 21px; height: 19px; vertical-align: middle; margin-right: 8px;">';
    $propertyTypeIcon = '<img src="https://terezaestates.com/assets/demo1/images/svg/Property%20Type.png" alt="Property Type" style="width: 21px; height: 19px; vertical-align: middle; margin-right: 8px;">';
    $bedroomsIcon = '<img src="https://terezaestates.com/assets/demo1/images/svg/pro-in-ic1.jpg" alt="Bedrooms Icon" style="width: 21px; height: 19px; vertical-align: middle; margin-right: 8px;">';
    $bathroomsIcon = '<img src="https://terezaestates.com/assets/demo1/images/svg/pro-in-ic2.jpg" alt="Bathrooms Icon" style="width: 21px; height: 19px; vertical-align: middle; margin-right: 8px;">';
    $areaIcon = '<img src="https://terezaestates.com/assets/demo1/images/svg/pro-in-ic3.jpg" alt="Area Icon" style="width: 21px; height: 19px; vertical-align: middle; margin-right: 8px;">';
    $terraceIcon = '<img src="https://terezaestates.com/assets/demo1/images/svg/Balcony-03.jpg" alt="Terrace Icon" style="width: 21px; height: 19px; vertical-align: middle; margin-right: 8px;">';

    // Populate additional details with icons and values
    if (!empty($property->ModeDisplay)) {
        $additionalDetails[] = $fieldTypeIcon . 'Field Type: ' . $property->ModeDisplay;
    }
    if (!empty($property->state_display)) {
        $additionalDetails[] = $propertyStatusIcon . 'Property Status: ' . $property->state_display;
    }
    if (!empty($property->PropertyTypeName)) {
        $additionalDetails[] = $propertyTypeIcon . 'Property Type: ' . $property->PropertyTypeName;
    }
    if (!empty($property->beds)) {
        $additionalDetails[] = $bedroomsIcon . 'Bedrooms: ' . $property->beds;
    }
    if (!empty($property->baths)) {
        $additionalDetails[] = $bathroomsIcon . 'Bathrooms: ' . $property->baths;
    }
    if (!empty($property->Subtype)) {
        $additionalDetails[] = 'Subtype: ' . $property->Subtype;
    }
    if (!empty($property->Community)) {
        $additionalDetails[] = 'Community: ' . $property->Community;
    }
    if (!empty($property->internal_area)) {
        $additionalDetails[] = $areaIcon . 'Area: ' . $property->displayInternal;
    }
    if (!empty($property->land_area)) {
        $additionalDetails[] = 'Land Area: ' . $property->displayland;
    }
    if (!empty($property->terrace_area)) {
        $additionalDetails[] = $terraceIcon . 'Terrace: ' . $property->terrace_area . ' sq ft';
    }
    @endphp

    @foreach ($additionalDetails as $detail)
        <li style="margin-bottom: 4mm; font-size: 11pt; color: #555;">
            {!! $detail !!}
        </li>
    @endforeach
</ul>

</div>

{{-- Logo Section --}}
        <div class="logo2-section">
            <img src="https://terezaestates.com/assets/demo1/images/logos/logo.svg" alt="Tereza Estates Logo" class="logo2">
        </div>

        {{-- Property Performance Metrics --}}
        <div class="performance-metrics">
            <h2 class="section-title">Performance Stats </h2>
            
            <br>
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Stats>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Property Views (30 Days)</td>
                        <td>{{ number_format($past_30_days_views ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td>Property Views (All Time)</td>
                        <td>{{ number_format($all_time_views ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td>Search Views (30 Days)</td>
                        <td>{{ number_format($past_30_days_search_views ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td>Search Views (All Time)</td>
                        <td>{{ number_format($all_time_search_views ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td>WhatsApp Clicks (30 Days)</td>
                        <td>{{ number_format($past_30_days_whatsapp_clicks ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td>WhatsApp Clicks (All Time)</td>
                        <td>{{ number_format($all_time_whatsapp_clicks ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td>Enquiries (30 Days)</td>
                        <td>{{ number_format($past_30_days_enquiries ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td>Enquiries (All Time)</td>
                        <td>{{ number_format($all_time_enquiries ?? 0) }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Search URLs --}}
            @php
            $filteredSearchUrls = collect($property_search_stats ?? [])
                ->filter(function($stat) {
                    return !empty($stat['url']) && 
                           filter_var($stat['url'], FILTER_VALIDATE_URL) !== false && 
                           ($stat['count'] ?? 0) > 0;
                })
                ->sortByDesc('count')
                ->take(10); // Limit to top 10 URLs
            @endphp

            @if($filteredSearchUrls->isNotEmpty())
                <h2 class="section-title">Search URLs - All-time</h2>
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>URL</th>
                            <th>Visits</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($filteredSearchUrls as $stat)
                        <tr>
                            <td style="word-break: break-all;">
                                @php
                                    $parsedUrl = parse_url($stat['url']);
                                    $displayUrl = ($parsedUrl['host'] ?? '') . ($parsedUrl['path'] ?? '');
                                @endphp
                                {{ $displayUrl }}
                            </td>
                            <td>{{ number_format($stat['count'] ?? 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-data-message">
                    No search URLs found for this property.
                </p>
            @endif
        </div>
                <br>
                
        <div class="footer">
    <table border="0" width="100%" style="padding-top: 40px;">
        <tr>
            <td class="f-logo" width="50%">
                <img src="{{ themeAsset('images/logos/logo.png') }}" width="300px">
            </td>
            <td class="f-info" width="50%">
                <p>Mobile: <a href="tel:{{ settings('telephone') }}" class="text-red">{{ settings('telephone') }}</a></p>
                <p>Email: <a href="mailto:{{ settings('email') }}">{{ settings('email') }}</a></p>
                <p>{!! settings('footer_address') !!} <br/>  {{ url('/') }} </p>
            </td>
            
        </tr>
    </table>
</div>


        {{-- Footer --}}
        <div class="sec_footer">
            &copy; {{ date('Y') }} {{ settings('site_name', config('app.name')) }} | Property Ref: {{ $property->ref ?? 'N/A' }}
        </div>
    </div>

    <script>
        // Optional: Add print trigger for browsers
        window.onload = function() {
            // Automatically trigger print dialog
            window.print();
        }
    </script>
</body>
</html>