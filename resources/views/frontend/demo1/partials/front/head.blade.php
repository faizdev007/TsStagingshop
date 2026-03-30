<meta charset="UTF-8">
@if(isset($meta_title))
    <meta name="title" content="{{$meta_title}}">
@endif
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

if($_SERVER['REQUEST_URI'] == '/properties/list'){ ?>
    <meta name="description" content="List for Sale and Rent real estate with Conrad Properties; receive a reliable and honest service - matching the available properties with the current market.">
<?php }elseif (isset($meta_description)){ ?>
    <meta name="description"  content="{{$meta_description}} ">
<?php
} else { ?>
    {{--<meta name="description" content="{{preg_replace('/\s+/', ' ', ($meta_description)? $meta_description : '' )}}">--}}
<?php } ?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
@if(
    (isset($noindex) && $noindex) ||
    (strpos($_SERVER['REQUEST_URI'],'currency') !== false) ||
    (strpos($_SERVER['REQUEST_URI'],'display') !== false) ||
    (strpos($_SERVER['REQUEST_URI'],'sortBy') !== false) ||
    (strpos($_SERVER['REQUEST_URI'],'uploads') !== false)
    //(strpos($_SERVER['REQUEST_URI'],'page') !== false)
)
    <!--content="noindex" />-->
    <meta name="robots" content="noindex, nofollow">
@else
    <!--content="index, follow"/>-->
    @if(env('APP_PW_ENV')=='production')
    <!--  content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1"> -->
    <meta name="robots" content="noindex, nofollow">
    @else
    <meta name="robots" CONTENT="noindex, nofollow">
    @endif
@endif

@if(env('APP_PW_ENV')=='production')
    @if(Cookie::get('cookie_consent') && json_decode(Cookie::get('cookie_consent'))->analytics)
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-TRD4KVD');</script>
        <!-- End Google Tag Manager -->
    @endif
@endif

<link rel="shortcut icon" href="<?php echo Config::get('conrad.cdn') ?>/favicon.ico" type="image/x-icon">
{{--*/$url = \request()->url();/*--}}
<link rel="canonical" href="{{str_replace("http://", "https://",url()->full())}}">
{{--@if(strpos($url,'/buy') !== false)--}}
{{--<link rel="canonical" href="/search/all/buy?page=all">--}}
{{--@elseif(strpos($url,'/rent') !== false)--}}
{{--<link rel="canonical" href="/search/all/rent?page=all">--}}
{{--@else--}}
{{--<link rel="canonical" href="{{str_replace("http://", "https://",request()->url())}}">--}}
{{--@endif--}}
<?php if($_SERVER['REQUEST_URI'] == '/properties/list'){ ?>
<title>Sell &amp; Rent your Koh Samui Real Estate | List Property Here </title>
<?php } elseif (isset($meta_title)){
?>
<title>{{$meta_title}}</title>
<?php
} else { ?>
<title>{{$head_title}}</title>
<?php } ?>
<?php
$og = str_replace("http://", "https://",Request::fullUrl());
if(isset($_COOKIE['lan'])){
    if(strpos($og,'?') === false){
        $og .= "?lang=".$_COOKIE['lan'];
    }
    elseif(strpos($og,'lang') === false){
        $og .= "&lang=".$_COOKIE['lan'];
    }
}
else{
    if(strpos($og,'?') === false){
        $og .= "?lang=eng";
    }
    elseif(strpos($og,'lang') === false){
        $og .= "&lang=eng";
    }
}
?>

@if(isset($og_data))
    <!-- Open Graph data -->
    <meta property="og:site_name" content="Conrad Properties"/>
    <meta property="og:title" content="{{$og_data['title']}}"/>
    <meta property="og:description" content="{{$og_data['description']}}"/>
    <meta property="og:url" content="{{explode('?', $og)[0]}}"/>
    {{--<meta property="og:url" content="{{explode('?', $og_data['url'])[0]}}"/>--}}
    <meta property="og:image" content="{{$og_data['image']}}"/>
    <link rel="alternate" href="{{explode('?', $og)[0]}}" hreflang="en-th" />
@else
    <!-- Open Graph data -->
    <meta property="og:site_name" content="Conrad Properties"/>
    <meta property="og:title" content="{{$head_title}}"/>
    <meta property="og:description" content="{{$meta_description}}"/>
    <meta property="og:url" content="{{explode('?', $og)[0]}}"/>
    <meta property="og:image" content="{{ asset('images/open-graph-image.jpg') }}"/>
    <link rel="alternate" href="{{explode('?', $og)[0]}}" hreflang="en-th" />
@endif
@if(isset($meta_title))
    <meta name="DC.Title" content="{{$meta_title}}">
@else
    <meta name="DC.Title" content="Real Estate Agency in Koh Samui Thailand | Conrad Properties">
@endif
<meta name="DC.Creator" content="conradvillas.com">
<meta name="DC.Subject" content="Real Estate, Property Koh Samui, Villa for Sale Koh Samui, Real Estate Koh Samui">
<meta name="DC.Description" content="{{$meta_description}}">
<meta name="DC.Publisher" content="conradvillas.com">
<meta name="DC.Contributor" content="conradvillas.com">
<meta name="DC.Language" content="en">
<meta name="DC.Coverage" content="Thailand">
<meta name="DC.Coverage" content="Koh Samui, Thailand">

<meta name="facebook-domain-verification" content="mmzanby78fcdmtjagcyq75sd399i7g" />


<script type="application/ld+json">
{
  "@context" : "http://schema.org",
  "@type" : "Organization",
  "legalName" : "Conrad Villas",
  "url" : "https://www.conradvillas.com/",
  "contactPoint" : [{
    "@type" : "ContactPoint",
    "telephone" : "+66 0 92 959 1299",
    "contactType" : "customer support",
	"availableLanguage": ["English"]
  }],
  "logo": "{{ asset('assets/images/logo.png') }}",
  "sameAs" : [
    "https://www.facebook.com/conradluxuryvillas",
    "https://www.instagram.com/conradvillas/",
    "https://www.linkedin.com/company/conrad-villas"
  ]
}
</script>

@if(isset($previous_url))
    <link rel="prev" href="{{$previous_url}}" />
@endif
@if(isset($next_url))
    <link rel="next" href="{{$next_url}}" />
@endif

@if(env('APP_PW_ENV')=='production')
<script>
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function () {
        var s1 = document.createElement('script'), s0 = document.getElementsByTagName('script')[0]
        s1.async = true
        s1.src = 'https://embed.tawk.to/619607106885f60a50bc5cc9/1fkp0n6km'
        s1.charset = 'UTF-8'
        s1.setAttribute('crossorigin', '*')
        s0.parentNode.insertBefore(s1, s0)

    })()
</script>

@if(Cookie::get('cookie_consent') && json_decode(Cookie::get('cookie_consent'))->analytics)
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-215063055-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-215063055-1');
</script>
<!-- End Global site tag (gtag.js) - Google Analytics -->
@endif
<!-- Facebook Pixel Code -->
<script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '454166952976947');
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=454166952976947&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Facebook Pixel Code -->
@endif

<script>
    Laravel = {
        'url': "{{url('')}}"
    }
</script>
