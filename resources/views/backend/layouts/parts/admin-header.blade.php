    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    	<link rel="icon" href="{{asset('assets/admin/build/images/pw-theme/favicon.ico')}}" type="image/ico" />

        <title>Dashboard</title>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Bootstrap -->
        <link href="{{asset('assets/admin/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
        <!-- JQuery UI -->
        <link href="{{asset('assets/admin/build/vendors/jquery-ui-1.12.1/jquery-ui.min.css')}}" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="{{asset('assets/admin/vendors/font-awesome/css/all.min.css')}}" rel="stylesheet">
        <!-- Toastr JS -->
        <link href="{{asset('assets/admin/vendors/toastr/css/toastr.min.css') }}" rel="stylesheet">

        @if(1)
        <!-- NProgress -->
        <link href="{{asset('assets/admin/vendors/nprogress/nprogress.css')}}" rel="stylesheet">
        <!-- iCheck -->
        <link href="{{asset('assets/admin/vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
        @endif

        <!-- SELECT2 -->
        <link href="{{asset('assets/admin/vendors/select2/dist/css/select2.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/admin/build/vendors/alertifyjs/css/alertify.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/admin/build/vendors/alertifyjs/css/themes/semantic.min.css')}}" rel="stylesheet">

        @stack('headerscripts')
        
        <!-- Custom Theme Style -->
        <link href="{{asset('assets/admin/build/css/custom.css')}}" rel="stylesheet">
        <!-- Custom Theme Style -->
        <link href="{{asset('assets/admin/build/sass/css/style.css')}}" rel="stylesheet">


        <meta name="robots" content="noindex, nofollow">
    </head>
