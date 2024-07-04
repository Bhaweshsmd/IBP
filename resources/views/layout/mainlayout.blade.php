<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Isidel Beach Park</title>
    <meta name="robots" content="noindex">
    <meta name="twitter:description" content="Enjoy Beautifull Beaches.">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Enjoy Beautifull Beaches.">
    <meta name="keywords" content="Beaches">
    <meta name="author" content="Isidel Beach Park">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@dreamguystech">
    <meta name="twitter:title" content="Isidel Beach Park">
    <meta name="twitter:image" content="assets/img/isidel.png">
    <meta name="twitter:image:alt" content="Isidel Beach Park">
    <meta property="og:url" content="https://IsidelBeachPark.com/">
    <meta property="og:title" content="Isidel Beach Park">
    <meta property="og:description" content="Beaches.">
    <meta property="og:image" content="assets/img/isidel.png">
    <meta property="og:image:secure_url" content="assets/img/isidel.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">
    
    <link rel="shortcut icon" type="image/x-icon" href="{{URL::asset('/asset/img/favicon.ico')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{URL::asset('/asset/img/favicon.ico')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{URL::asset('/asset/img/favicon.ico')}}">
    
    @include('layout.partials.head')
    
    @if(session()->get('locale') == 'pap')
        <link rel="stylesheet" href="{{ url('assets/css/pap.css') }}">
    @endif
    
    @if(session()->get('locale') == 'nl')
        <link rel="stylesheet" href="{{ url('assets/css/nl.css') }}">
    @endif
    
    <link href="https://fonts.googleapis.com/css?family=Open Sans" rel="stylesheet" type="text/css"/>
    
    <style type="text/css">
        @font-face {
            font-family: "Intro Rust G Base";
            src: "{{ URL::asset('/assets/fonts/IntroRust-Base.otf') }}";
            src: "{{ URL::asset('/assets/fonts/IntroRustH2-Base2Line.otf') }}" ;
            src: "{{ URL::asset('/assets/fonts/IntroRustL-Base2Line.otf') }}"
        }
        
        h1, h2 {
            font-family:"Intro Rust G Base";
        }
            
        body {
            font-family:"Open Sans";
        }
        
        ul.main-nav{
            font-family: "Intro Rust G Base";
        }
        
        .swal2-popup .swal2-styled.swal2-cancel{
            background-color: #1e425e !important;
        }
        
        .swal2-popup .swal2-styled.swal2-cancel a{
            color: #fff !important;
        }
    </style>
</head>

<body>
    <?php   
        $userDetails = getUserDetails(Session::get('user_id'));
        $global_setting = DB::table('global_settings')->where('id', '1')->first();
    ?>
    
    <input type="hidden" value="{{$global_setting->stripe_publishable_key}}" id="stripe_publishable_key">
    
    @component('components.loader')
    @endcomponent
    <!-- Main Wrapper -->
    @if (
        !Route::is([
            'coach-details',
            'coach-detail',
            'company-details',
            'venue-details',
            'add-court',
            'login',
            'forgot-password',
            'register',
            'terms-condition',
            'testimonials',
            'coming-soon',
            'event-details',
            'invoice',
            'service-detail',
            'lesson-personalinfo',
            'lesson-timedate',
            'lesson-order-confirm',
            'lesson-type',
            'lesson-payment',
            'gallery',
            'about-us',
            'blog-carousel',
            'blog-details-sidebar-left',
            'blog-details-sidebar-right',
            'blog-details',
            'blog-grid-sidebar-left',
            'blog-grid-sidebar-right',
            'blog-grid',
            'blog-list-sidebar-left',
            'blog-list-sidebar-right',
            'blog-list',
            'change-password',
            'coach-order-confirm',
            'coach-payment',
            'coach-personalinfo',
            'coach-timedate',
            'error-404',
            'events',
            'faq',
            'invoice',
            'our-teams',
            'pricing',
            'maintenance',
            'services',
            'testimonials-carousel',
            'contact-us',
            'privacy-policy',
        ]))
        <div class="main-wrapper">
    @endif
    @if (Route::is(['our-teams']))
        <div class="main-wrapper ourteam-page">
    @endif
    @if (Route::is(['pricing']))
        <div class="main-wrapper pricing-page">
    @endif
    @if (Route::is(['invoice']))
        <div class="main-wrapper invoice-page innerpagebg">
    @endif
    @if (Route::is(['about-us']))
        <div class="main-wrapper aboutus-page">
    @endif
    @if (Route::is([
            'blog-carousel',
            'blog-details-sidebar-left',
            'blog-details-sidebar-right',
            'blog-details',
            'blog-grid-sidebar-left',
            'blog-grid-sidebar-right',
            'blog-grid',
            'blog-list-sidebar-left',
            'blog-list-sidebar-right',
            'blog-list',
        ]))
        <div class="main-wrapper blog">
    @endif
    @if (Route::is(['error-404']))
        <div class="main-wrapper error-page">
    @endif
    @if (Route::is(['coach-details', 'coach-order-confirm', 'coach-payment', 'coach-personalinfo', 'coach-timedate']))
        <div class="main-wrapper coach">
    @endif
    @if (Route::is(['coach-detail','company-details']))
        <div class="main-wrapper venue-coach-details coach-detail">
    @endif
    @if (Route::is(['venue-details']))
        <div class="main-wrapper venue-coach-details">
    @endif
    @if (Route::is(['add-court']))
        <div class="main-wrapper add-court venue-coach-details">
    @endif
    @if (Route::is(['login', 'forgot-password', 'register', 'change-password']))
        <div class="main-wrapper authendication-pages">
    @endif
    @if (Route::is(['contact-us']))
        <div class="main-wrapper contact-us-page">
    @endif
    @if (Route::is(['terms-condition', 'privacy-policy']))
        <div class="main-wrapper terms-page innerpagebg">
    @endif
    @if (Route::is(['testimonials']))
        <div class="main-wrapper testimonials-page innerpagebg">
    @endif
    @if (Route::is(['testimonials-carousel']))
        <div class="main-wrapper testimonials-carousel innerpagebg">
    @endif
    @if (Route::is(['coming-soon', 'maintenance']))
        <div class="main-wrapper coming-soon-page">
    @endif
    @if (Route::is(['event-details']))
        <div class="main-wrapper event-details-page">
    @endif
    @if (Route::is(['events']))
        <div class="main-wrapper events-page innerpagebg">
    @endif
    @if (Route::is(['faq']))
        <div class="main-wrapper innerpagebg">
    @endif
    @if (Route::is(['invoice']))
        <div class="main-wrapper invoice-page innerpagebg">
    @endif
    @if (Route::is(['service-detail']))
        <div class="main-wrapper services-detail-page">
    @endif
    @if (Route::is(['services']))
        <div class="main-wrapper services-page innerpagebg">
    @endif
    @if (Route::is(['gallery']))
        <div class="main-wrapper gallery-page innerpagebg">
    @endif
    @if (Route::is(['lesson-personalinfo', 'lesson-timedate', 'lesson-order-confirm', 'lesson-type', 'lesson-payment']))
        <div class="main-wrapper coach lessons">
    @endif
    @if (!Route::is(['login', 'forgot-password', 'register', 'change-password', 'coming-soon', 'maintenance', 'error-404']))
        @include('layout.partials.header')
    @endif
    @yield('content')
    @if (!Route::is(['login', 'forgot-password', 'register', 'change-password', 'coming-soon', 'maintenance', 'error-404']))
        @include('layout.partials.footer')
    @endif
    </div>

    @component('components.scrolltotop')
    @endcomponent
    @include('layout.partials.footer-scripts')
</body>

</html>
