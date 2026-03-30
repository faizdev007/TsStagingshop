<?php

use Illuminate\Http\Request;

$current_url = request()->url();
$custom_metadata = get_custom_metadata($current_url);
$meta = !empty($meta) ? $meta : false;
$meta = !empty($custom_metadata) ? $custom_metadata : $meta;
?>
{{-- HEADER --}}
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <!-- Preconnect -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    
    <!-- ✅ Minimal preconnect (ONLY fonts) -->
    <link rel="preconnect" href="https://fonts.googleapis.com"  crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Fonts -->
    <link rel="preload" as="style"
    href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600&family=Cormorant:wght@400;600&display=swap"
    onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600&family=Cormorant:wght@400;600&display=swap" rel="stylesheet">
    </noscript>
    
    @stack('frontend_scripts')
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0"/>
    {{-- META --}}
    <title>{{ !empty($meta->title) ? $meta->title : settings('site_name', config('app.name')) }}</title>
    @if (!empty($meta->description))
        <meta name="description" content="{{ $meta->description }}">
    @endif
    @if (!empty($meta->keywords))
        <meta name="keywords" content="{{ $meta->keywords }}">
    @endif
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(settings('recaptcha_enabled') == '1')
        <meta name="recaptcha-key" content="{{ settings('recaptcha_public_key') }}">
    @endif

    {{-- FACEBOOK META --}}
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ !empty($meta->title) ? $meta->title : settings('site_name', config('app.name')) }}" />
    <meta property="og:site_name" content="{{ settings('site_name', config('app.name')) }}" />
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:locale" content="en_GB" />
    @if (!empty($meta->image))
        <meta property="og:image" content="@if(isset($page) && $page == 'property'){{ $property->primary_photo }}@else{{ $meta->image }}@endif" />
    @endif

    {{-- TWITTER META --}}
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="{{ !empty($meta->title) ? $meta->title : settings('site_name', config('app.name')) }}" />
    <meta name="twitter:url" content="{{ url()->current() }}">

    @if (!empty($meta->description))
        <meta name="twitter:description" content="{{ $meta->description }}" />
    @endif
    @if (!empty($meta->image))
        <meta name="twitter:image" content="@if(isset($page) && $page == 'property'){{ $property->primary_photo }}@else{{ $meta->image }}@endif">
    @endif

    @if(settings('search_engine_visible') == 1)
        <meta name="robots" content="index, follow">
    @else
        <meta name="robots" content="noindex, nofollow">
    @endif

    @stack('meta')

    @if(request()->is('/'))
        <link rel="canonical" href="{{ url('') }}" />
    @endif
    
    
    <!-- Vite handles everything -->
    @vite([
        'resources/assets/demo1/js/app.js',
        'resources/assets/demo1/sass/app.scss'
    ])
    
    <!-- Optional (for legacy jQuery support) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" onload="this.media='all'">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox/fancybox.css" onload="this.media='all'">

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.2.1/css/intlTelInput.css">

    @stack('frontend_css')

    <!-- Google tag (gtag.js) -->
    @if(Cookie::get('cookie_consent') && json_decode(Cookie::get('cookie_consent'))->analytics)
        <!-- Google tag (gtag.js) -->
        <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-YDGXMTWHWH" defer></script>
        <script loading=async>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-YDGXMTWHWH');
        </script> -->
    @endif

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{asset('favicon.ico')}}">
    <!--<link rel="manifest" href="/manifest.json">-->
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{asset('ms-icon-144x144.png')}}">
    <meta name="theme-color" content="#ffffff">
    <style>
        #header{
            position: relative!important;
        }
        
        .smooth-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 130px;
            background: #fff;
            display: flex;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            z-index: 9999;

            /* smooth motion like Louvre Abu Dhabi */
            transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .smooth-header.hidden {
            transform: translateY(-100%);
        }

        /* Chrome, Edge, Safari */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Firefox */
        .hide-scrollbar {
            scrollbar-width: none;
        }

        /* Internet Explorer & old Edge */
        .hide-scrollbar {
            -ms-overflow-style: none;
        }

        .hover-arrow {
            position: relative;
            cursor: pointer;
            display: flex;
            gap: 5px;
            flex-wrap: nowrap;
            align-items: center;
            flex-direction: row;
        }

        .hover-arrow .arrowright {
            /* position: absolute;
            right: 0;
            top: 50%; */
            /* transform: translateY(-50%); */
            /* opacity: 0; */
            transition: 0.3s ease;
            font-size: 23px;
            font-weight: 800;
            background: #d9b483e6;
            color: #fff;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #0000002e;
            z-index: 99;
        }

        .arrowleft{
            /* position: absolute;
            left: 0;
            top: 50%; */
            /* transform: translateY(-50%); */
            /* opacity: 0; */
            transition: 0.3s ease;
            font-size: 23px;
            font-weight: 800;
            background: #d9b483e6;
            color: #fff;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #0000002e;
            z-index: 99;
        }

        .hover-arrow:hover .arrowleft,
        .hover-arrow:hover .arrowright {
            opacity: 1;
        }

        #profileTabs {
            overflow-x: auto;
            white-space: nowrap;
            scroll-behavior: smooth;
        }

        #arrow-left,
        #arrow-right {
            display: none;
            align-items: center;
            cursor: pointer;
        }
        
        @media (max-width: 767px) {
            #arrow-left,
            #arrow-right {
                display: none !important;
            }
        }

        .select2-dropdown--above {
            top: 100% !important;
            bottom: auto !important;
        }

        .infinite-text {
            animation: fadeText 3s ease-in-out infinite;
        }

        .button.-default.-left-liner:before, .cta.-default.-left-liner:before{
            top: 50%!important;
        }

        @keyframes fadeText {
            0% {
                opacity: 0;
                transform: translateY(6px);
            }
            20% {
                opacity: 1;
                transform: translateY(0);
            }
            80% {
                opacity: 1;
                transform: translateY(0);
            }
            100% {
                opacity: 0;
                transform: translateY(6px);
            }
        }
    </style>

</head>

<body class="@stack('body_class')">
<input type="hidden" class="site_url" value="{{ url('') }}">
<input type="hidden" class="site_currency" data-conversion='{!! json_encode(config('data.currencies')) !!}' value="{!! settings('currency_symbol') !!}">
