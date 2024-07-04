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
    
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(URL::asset('/asset/img/favicon.ico')); ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo e(URL::asset('/asset/img/favicon.ico')); ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo e(URL::asset('/asset/img/favicon.ico')); ?>">
    
    <?php echo $__env->make('layout.partials.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <?php if(session()->get('locale') == 'pap'): ?>
        <link rel="stylesheet" href="<?php echo e(url('assets/css/pap.css')); ?>">
    <?php endif; ?>
    
    <?php if(session()->get('locale') == 'nl'): ?>
        <link rel="stylesheet" href="<?php echo e(url('assets/css/nl.css')); ?>">
    <?php endif; ?>
    
    <link href="https://fonts.googleapis.com/css?family=Open Sans" rel="stylesheet" type="text/css"/>
    
    <style type="text/css">
        @font-face {
            font-family: "Intro Rust G Base";
            src: "<?php echo e(URL::asset('/assets/fonts/IntroRust-Base.otf')); ?>";
            src: "<?php echo e(URL::asset('/assets/fonts/IntroRustH2-Base2Line.otf')); ?>" ;
            src: "<?php echo e(URL::asset('/assets/fonts/IntroRustL-Base2Line.otf')); ?>"
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
    
    <input type="hidden" value="<?php echo e($global_setting->stripe_publishable_key); ?>" id="stripe_publishable_key">
    
    <?php $__env->startComponent('components.loader'); ?>
    <?php echo $__env->renderComponent(); ?>
    <!-- Main Wrapper -->
    <?php if(
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
        ])): ?>
        <div class="main-wrapper">
    <?php endif; ?>
    <?php if(Route::is(['our-teams'])): ?>
        <div class="main-wrapper ourteam-page">
    <?php endif; ?>
    <?php if(Route::is(['pricing'])): ?>
        <div class="main-wrapper pricing-page">
    <?php endif; ?>
    <?php if(Route::is(['invoice'])): ?>
        <div class="main-wrapper invoice-page innerpagebg">
    <?php endif; ?>
    <?php if(Route::is(['about-us'])): ?>
        <div class="main-wrapper aboutus-page">
    <?php endif; ?>
    <?php if(Route::is([
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
        ])): ?>
        <div class="main-wrapper blog">
    <?php endif; ?>
    <?php if(Route::is(['error-404'])): ?>
        <div class="main-wrapper error-page">
    <?php endif; ?>
    <?php if(Route::is(['coach-details', 'coach-order-confirm', 'coach-payment', 'coach-personalinfo', 'coach-timedate'])): ?>
        <div class="main-wrapper coach">
    <?php endif; ?>
    <?php if(Route::is(['coach-detail','company-details'])): ?>
        <div class="main-wrapper venue-coach-details coach-detail">
    <?php endif; ?>
    <?php if(Route::is(['venue-details'])): ?>
        <div class="main-wrapper venue-coach-details">
    <?php endif; ?>
    <?php if(Route::is(['add-court'])): ?>
        <div class="main-wrapper add-court venue-coach-details">
    <?php endif; ?>
    <?php if(Route::is(['login', 'forgot-password', 'register', 'change-password'])): ?>
        <div class="main-wrapper authendication-pages">
    <?php endif; ?>
    <?php if(Route::is(['contact-us'])): ?>
        <div class="main-wrapper contact-us-page">
    <?php endif; ?>
    <?php if(Route::is(['terms-condition', 'privacy-policy'])): ?>
        <div class="main-wrapper terms-page innerpagebg">
    <?php endif; ?>
    <?php if(Route::is(['testimonials'])): ?>
        <div class="main-wrapper testimonials-page innerpagebg">
    <?php endif; ?>
    <?php if(Route::is(['testimonials-carousel'])): ?>
        <div class="main-wrapper testimonials-carousel innerpagebg">
    <?php endif; ?>
    <?php if(Route::is(['coming-soon', 'maintenance'])): ?>
        <div class="main-wrapper coming-soon-page">
    <?php endif; ?>
    <?php if(Route::is(['event-details'])): ?>
        <div class="main-wrapper event-details-page">
    <?php endif; ?>
    <?php if(Route::is(['events'])): ?>
        <div class="main-wrapper events-page innerpagebg">
    <?php endif; ?>
    <?php if(Route::is(['faq'])): ?>
        <div class="main-wrapper innerpagebg">
    <?php endif; ?>
    <?php if(Route::is(['invoice'])): ?>
        <div class="main-wrapper invoice-page innerpagebg">
    <?php endif; ?>
    <?php if(Route::is(['service-detail'])): ?>
        <div class="main-wrapper services-detail-page">
    <?php endif; ?>
    <?php if(Route::is(['services'])): ?>
        <div class="main-wrapper services-page innerpagebg">
    <?php endif; ?>
    <?php if(Route::is(['gallery'])): ?>
        <div class="main-wrapper gallery-page innerpagebg">
    <?php endif; ?>
    <?php if(Route::is(['lesson-personalinfo', 'lesson-timedate', 'lesson-order-confirm', 'lesson-type', 'lesson-payment'])): ?>
        <div class="main-wrapper coach lessons">
    <?php endif; ?>
    <?php if(!Route::is(['login', 'forgot-password', 'register', 'change-password', 'coming-soon', 'maintenance', 'error-404'])): ?>
        <?php echo $__env->make('layout.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php echo $__env->yieldContent('content'); ?>
    <?php if(!Route::is(['login', 'forgot-password', 'register', 'change-password', 'coming-soon', 'maintenance', 'error-404'])): ?>
        <?php echo $__env->make('layout.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    </div>

    <?php $__env->startComponent('components.scrolltotop'); ?>
    <?php echo $__env->renderComponent(); ?>
    <?php echo $__env->make('layout.partials.footer-scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>

</html>
<?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/layout/mainlayout.blade.php ENDPATH**/ ?>