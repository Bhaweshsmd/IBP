<?php if(!Route::is(['index', '/'])): ?>
    <header class="header header-sticky">
<?php endif; ?>
<?php if(Route::is(['index', '/'])): ?>
    <header class="header header-trans">
<?php endif; ?>

<style>
    .header .main-menu-wrapper .main-nav{
        align-items: center;
    }
    .header .header-nav{
        padding: 0px 9px 0 0px !important;
    }
    
    .profile-area{
        background-color: #fff;
        padding: 2px 5px;
        border-radius: 5px;
        color: #000;
        font-weight: bold;
        font-size: 15px;
    }
    
    .header .header-navbar-rht.logged-in > li.has-arrow .dropdown-toggle:after{
        color : #000 !important;
        display: none;
    }
    
    .header .header-navbar-rht > li .nav-link:focus{
        color: #000;
    }
</style>

<?php 
    $notifications = fetchNotification();
    
    if(!empty($userDetails->profile_image)){
        $profile_image= url('/public/storage/'.$userDetails->profile_image);
    }else{
        $profile_image= "https://placehold.jp/150x150.png";
    }
    
    $language = session()->get('locale');
    if(empty($language)){
        $cont_lang = 'en';
    }else{
        $cont_lang = $language;
    }
?>
    
<div class="container-fluid">
    <nav class="navbar navbar-expand-lg header-nav">
        <div class="navbar-header">
            <a id="mobile_btn" href="javascript:void(0);">
                <span class="bar-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </a>
            <a href="<?php echo e(url('/')); ?>" class="navbar-brand logo">
                <?php if(!Route::is(['index', '/'])): ?>
                    <img src="<?php echo e(URL::asset('/assets/img/isidel.png')); ?>" class="img-fluid" alt="Logo" style="max-width: 120px; " >
                <?php endif; ?>
                <?php if(Route::is(['index', '/'])): ?>
                    <img src="<?php echo e(URL::asset('/assets/img/isidel.png')); ?>" class="img-fluid" alt="Logo" style="max-width: 120px; ">
                <?php endif; ?>
            </a>
            
            <?php if(Session::get('user_id')): ?>
                <ul class="nav header-navbar-rht logged-in d-lg-none" id="mobile_bt">
                    <li class="nav-item dropdown noti-nav">
                        <a href="javascript:void(0)" class="dropdown-toggle nav-link position-relative" data-bs-toggle="dropdown">
                            <i class="feather-bell"></i> <span class="alert-bg"></span>
                        </a>
                        <div class="dropdown-menu notifications dropdown-menu-end ">
                            <div class="topnav-dropdown-header">
                                <span class="notification-title"><?php echo e(__('string.notifications')); ?></span> 
                            </div>
                            <div class="noti-content">
                                <ul class="notification-list">
                                    <?php if(!empty($notifications)): ?>
                                        <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $template = DB::table('notification_template')->where('id', $notification->temp_id)->first();
                                                if(!empty($template)){
                                                    if($cont_lang == 'en'){
                                                        $subject = $template->notification_subjects;
                                                        $content = $template->notification_content;
                                                    }elseif($cont_lang == 'pap'){
                                                        $subject = $template->notification_subject_pap;
                                                        $content = $template->notification_content_pap;
                                                    }elseif($cont_lang == 'nl'){
                                                        $subject = $template->notification_subject_nl;
                                                        $content = $template->notification_content_nl;
                                                    }
                                                    
                                                    if($notification->temp_id == '1'){
                                                        $content = str_replace(["{amount}"],[$notification->amount], $content);
                                                    }elseif($notification->temp_id == '2'){
                                                        $subject = str_replace(["{booking_id}"],[$notification->booking_id], $subject);
                                                        $content = str_replace(["{item_name}","{booking_id}"],[$notification->item_name,$notification->booking_id], $content);
                                                    }elseif($notification->temp_id == '4'){
                                                        $content = str_replace(["{booking_id}"],[$notification->booking_id], $content);
                                                    }elseif($notification->temp_id == '5'){
                                                        $content = str_replace(["{booking_id}"],[$notification->booking_id], $content);
                                                    }elseif($notification->temp_id == '6'){
                                                        $content = str_replace(["{total}"],[$notification->total], $content);
                                                    }elseif($notification->temp_id == '7'){
                                                        $content = str_replace(["{total}"],[$notification->total], $content);
                                                    }elseif($notification->temp_id == '8'){
                                                        $content = str_replace(["{amount}"],[$notification->amount], $content);
                                                    }elseif($notification->temp_id == '9'){
                                                        $content = str_replace(["{card_number}"],[$notification->card_number], $content);
                                                    }elseif($notification->temp_id == '10'){
                                                        $content = str_replace(["{amount}","{card_number}"],[$notification->amount,$notification->card_number], $content);
                                                    }elseif($notification->temp_id == '11'){
                                                        $content = str_replace(["{ticket_id}"],[$notification->ticket_id], $content);
                                                    }elseif($notification->temp_id == '12'){
                                                        $content = str_replace(["{date_time}"],[$notification->date_time], $content);
                                                    }elseif($notification->temp_id == '14'){
                                                        $content = str_replace(["{ticket_id}"],[$notification->ticket_id], $content);
                                                    }elseif($notification->temp_id == '15'){
                                                        $subject = str_replace(["{ticket_id}"],[$notification->ticket_id], $subject);
                                                        $content = str_replace(["{ticket_id}"],[$notification->ticket_id], $content);
                                                    }
                                                }else{
                                                    $subject = $notification->title;
                                                    $content = $notification->description;
                                                }
                                            ?>
                                            <li class="notification-message">
                                                <a href="javascript:void(0)">
                                                    <div class="media d-flex">
                                                        <div class="media-body">
                                                            <h6><?php echo e($subject); ?></h6>
                                                            <p class="noti-details"><?php echo $content; ?></p>
                                                            <p><?php echo e(date('D,d M Y h:i A',strtotime($notification->created_at))); ?></p>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                             <div class="container form-group p-3 text-center">
                              <span class="notification-title"><a class="text-success" href="<?php echo e(route('user-notification')); ?>"><?php echo e(__('string.view_all')); ?></a></span>
                            </div>
                        </div>
                    </li>
                    
                    <li class="nav-item dropdown has-arrow logged-item">
                        <a href="#" class="dropdown-toggle nav-link profile-area" data-bs-toggle="dropdown">
                            <span class="user-img">
                                <img class="rounded-circle" src="<?php echo e($profile_image); ?>" width="31" alt="<?php echo e($userDetails->first_name??''); ?>"> <?php echo e($userDetails->first_name??''); ?> <?php echo e($userDetails->last_name??''); ?>

                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="user-header">
                                <div class="avatar avatar-sm">
                                    <img src="<?php echo e($profile_image); ?>" alt="<?php echo e($userDetails->first_name??''); ?>" class="avatar-img rounded-circle" style="width: 43px;height: 40px;">
                                </div>
                                <div class="user-text">
                                    <h6 class="py-2"><?php echo e($userDetails->first_name??''); ?>&nbsp;&nbsp;<?php echo e($userDetails->last_name??''); ?></h6>
                                    <a href="<?php echo e(url('user-profile')); ?>" class="text-profile mb-0"><?php echo e(__('string.go_to_profile')); ?></a>
                                </div>
                            </div>
                            <p class="paraspace"><a class="dropdown-item" href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('string.dashboard')); ?></a></p>
                            <p class="paraspace"><a class="dropdown-item" href="<?php echo e(route('user-bookings')); ?>"><?php echo e(__('string.my_bookings')); ?></a></p>
                            <p class="paraspace"><a class="dropdown-item" href="<?php echo e(route('user-chat')); ?>"><?php echo e(__('string.chat')); ?></a></p>
                            <p class="paraspace"><a class="dropdown-item" href="<?php echo e(route('user-wallet')); ?>"><?php echo e(__('string.ibp_account')); ?></a></p>
                            <p class="paraspace"><a class="dropdown-item" href="<?php echo e(route('user-card')); ?>"><?php echo e(__('string.ibp_card')); ?></a></p>
                            <p class="paraspace"><a class="dropdown-item" href="<?php echo e(route('favourite-services')); ?>"><?php echo e(__('string.my_favourite_items')); ?></a></p>
                            <p class="paraspace"><a class="dropdown-item" href="<?php echo e(route('event-enquiry-list')); ?>"><?php echo e(__('string.event_enquiry')); ?></a></p>
                            <p class="paraspace"><a class="dropdown-item" href="<?php echo e(route('tickets')); ?>"><?php echo e(__('string.ticket')); ?></a></p>
                            <p class="paraspace"><a class="dropdown-item" href="<?php echo e(url('user-profile')); ?>"><?php echo e(__('string.settings')); ?></a></p>
                            <p class="paraspace"><a class="dropdown-item" href="<?php echo e(route('user.logout')); ?>"><?php echo e(__('string.logout')); ?></a></p>
                        </div>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
        <div class="main-menu-wrapper">
            <div class="menu-header">
                <a href="<?php echo e(url('/')); ?>" class="menu-logo">
                    <img src="<?php echo e(URL::asset('/assets/img/beach-park-logo.png')); ?>" class="img-fluid" alt="Logo" style="max-width: 65px;">
                </a>
                <a id="menu_close" class="menu-close" href="javascript:void(0);"> <i class="fas fa-times"></i></a>
            </div>
            <ul class="main-nav">
                <li class="<?php echo e(Request::is('/', 'index') ? 'active' : ''); ?> mx-lg-2">
                    <a href="<?php echo e(url('/')); ?>"><?php echo e(__('string.home')); ?></a>
                </li>
                <li class="<?php echo e(Request::is('about-us') ? 'active' : ''); ?> mx-lg-2">
                    <a href="<?php echo e(route('web.pages', 'about-us')); ?>"><?php echo e(__('string.about_us')); ?></a>
                </li>
                <li class="<?php echo e(Request::is('items-facilities') ? 'active' : ''); ?> mx-lg-2">
                    <a href="<?php echo e(url('items-facilities')); ?>"><?php echo e(__('string.Item_facilities')); ?></a>
                </li>
                <li class="<?php echo e(Request::is('service-map') ? 'active' : ''); ?> mx-lg-2">
                    <a href="<?php echo e(url('service-map')); ?>"><?php echo e(__('string.map')); ?></a>
                </li>
                <li class="<?php echo e(Request::is('blogs') ? 'active' : ''); ?> mx-lg-2">
                    <a href="<?php echo e(route('event-enquiry')); ?>"><?php echo e(__('string.events')); ?></a>
                </li>
                <li class="<?php echo e(Request::is('faqs') ? 'active' : ''); ?> mx-lg-2">
                    <a href="<?php echo e(route('web.faqs')); ?>"><?php echo e(__('string.faqs')); ?></a>
                </li>
                <li class="<?php echo e(Request::is('contact-us') ? 'active' : ''); ?> mx-lg-2">
                    <a href="<?php echo e(url('contact-us')); ?>"><?php echo e(__('string.contact_us')); ?></a>
                </li>
                <li class=" mx-lg-2">
                    <span class="select-icon"><i class="feather-globe"></i></span>
                    <select class="select changeLang" id="changeLang" style="border:none;font-size:14px;">
                        <option value="en" <?php echo e(session()->get('locale') == 'en' ? 'selected' : ''); ?>>English (US)</option>
                        <option value="pap" <?php echo e(session()->get('locale') == 'pap' ? 'selected' : ''); ?>>Papiamentu</option>
                        <option value="nl" <?php echo e(session()->get('locale') == 'nl' ? 'selected' : ''); ?>>Dutch</option>
                    </select>
                </li>
                <?php if(!Session::get('user_id')): ?>
                    <li class="login-link">
                        <a href="<?php echo e(url('register')); ?>"><?php echo e(__('string.sign_up')); ?></a>
                    </li>
                    <li class="login-link">
                        <a href="<?php echo e(url('login')); ?>"><?php echo e(__('string.sign_in')); ?></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        
        <?php if(Session::get('user_id')): ?>
            <ul class="nav header-navbar-rht logged-in">
                <li class="nav-item dropdown noti-nav">
                    <a href="javascript:void(0)" class="dropdown-toggle nav-link position-relative"
                        data-bs-toggle="dropdown">
                        <i class="feather-bell"></i> <span class="alert-bg"></span>
                    </a>
                    <div class="dropdown-menu notifications dropdown-menu-end ">
                        <div class="topnav-dropdown-header">
                            <span class="notification-title"><?php echo e(__('string.notifications')); ?></span> 
                        </div>
                        <div class="noti-content">
                            <ul class="notification-list">
                                <?php if(!empty($notifications)): ?>
                                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $template = DB::table('notification_template')->where('id', $notification->temp_id)->first();
                                            if(!empty($template)){
                                                if($cont_lang == 'en'){
                                                    $subject = $template->notification_subjects;
                                                    $content = $template->notification_content;
                                                }elseif($cont_lang == 'pap'){
                                                    $subject = $template->notification_subject_pap;
                                                    $content = $template->notification_content_pap;
                                                }elseif($cont_lang == 'nl'){
                                                    $subject = $template->notification_subject_nl;
                                                    $content = $template->notification_content_nl;
                                                }
                                                
                                                if($notification->temp_id == '1'){
                                                    $content = str_replace(["{amount}"],[$notification->amount], $content);
                                                }elseif($notification->temp_id == '2'){
                                                    $subject = str_replace(["{booking_id}"],[$notification->booking_id], $subject);
                                                    $content = str_replace(["{item_name}","{booking_id}"],[$notification->item_name,$notification->booking_id], $content);
                                                }elseif($notification->temp_id == '4'){
                                                    $content = str_replace(["{booking_id}"],[$notification->booking_id], $content);
                                                }elseif($notification->temp_id == '5'){
                                                    $content = str_replace(["{booking_id}"],[$notification->booking_id], $content);
                                                }elseif($notification->temp_id == '6'){
                                                    $content = str_replace(["{total}"],[$notification->total], $content);
                                                }elseif($notification->temp_id == '7'){
                                                    $content = str_replace(["{total}"],[$notification->total], $content);
                                                }elseif($notification->temp_id == '8'){
                                                    $content = str_replace(["{amount}"],[$notification->amount], $content);
                                                }elseif($notification->temp_id == '9'){
                                                    $content = str_replace(["{card_number}"],[$notification->card_number], $content);
                                                }elseif($notification->temp_id == '10'){
                                                    $content = str_replace(["{amount}","{card_number}"],[$notification->amount,$notification->card_number], $content);
                                                }elseif($notification->temp_id == '11'){
                                                    $content = str_replace(["{ticket_id}"],[$notification->ticket_id], $content);
                                                }elseif($notification->temp_id == '12'){
                                                    $content = str_replace(["{date_time}"],[$notification->date_time], $content);
                                                }elseif($notification->temp_id == '14'){
                                                    $content = str_replace(["{ticket_id}"],[$notification->ticket_id], $content);
                                                }elseif($notification->temp_id == '15'){
                                                    $subject = str_replace(["{ticket_id}"],[$notification->ticket_id], $subject);
                                                    $content = str_replace(["{ticket_id}"],[$notification->ticket_id], $content);
                                                }
                                            }else{
                                                $subject = $notification->title;
                                                $content = $notification->description;
                                            }
                                        ?>
                                        <li class="notification-message">
                                            <a href="javascript:void(0)">
                                                <div class="media d-flex">
                                                    <div class="media-body">
                                                        <h6><?php echo e($subject); ?></h6>
                                                        <p class="noti-details"><?php echo $content; ?></p>
                                                        <p><?php echo e(date('D,d M Y h:i A',strtotime($notification->created_at))); ?></p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="container form-group p-3 text-center">
                          <span class="notification-title"><a class="text-success" href="<?php echo e(route('user-notification')); ?>"><?php echo e(__('string.view_all')); ?></a></span>
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown has-arrow logged-item">
                    <a href="#" class="dropdown-toggle nav-link profile-area" data-bs-toggle="dropdown">
                        <span class="user-img">
                            <img class="rounded-circle" src="<?php echo e($profile_image); ?>" width="31" alt="<?php echo e($userDetails->first_name??''); ?>" > <?php echo e($userDetails->first_name??''); ?> <?php echo e($userDetails->last_name??''); ?>

                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="user-header">
                            <div class="avatar avatar-sm">
                                <img src="<?php echo e($profile_image); ?>" alt="<?php echo e($userDetails->first_name??''); ?>" class="avatar-img rounded-circle" style="width: 43px;height: 40px;">
                            </div>
                            <div class="user-text">
                                <h6><?php echo e($userDetails->first_name??''); ?>&nbsp;&nbsp;<?php echo e($userDetails->last_name??''); ?></h6>
                                <a href="<?php echo e(url('user-profile')); ?>" class="text-profile mb-0"><?php echo e(__('string.go_to_profile')); ?></a>
                            </div>
                        </div>
                        <p><a class="dropdown-item" href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('string.dashboard')); ?></a></p>
                        <p><a class="dropdown-item" href="<?php echo e(route('user-bookings')); ?>"><?php echo e(__('string.my_bookings')); ?></a></p>
                        <p><a class="dropdown-item" href="<?php echo e(route('user-chat')); ?>"><?php echo e(__('string.chat')); ?></a></p>
                        <p><a class="dropdown-item" href="<?php echo e(route('user-wallet')); ?>"><?php echo e(__('string.ibp_account')); ?></a></p>
                        <p><a class="dropdown-item" href="<?php echo e(route('user-card')); ?>"><?php echo e(__('string.ibp_card')); ?></a></p>
                        <p><a class="dropdown-item" href="<?php echo e(route('favourite-services')); ?>"><?php echo e(__('string.my_favourite_items')); ?></a></p>
                        <p><a class="dropdown-item" href="<?php echo e(route('event-enquiry-list')); ?>"><?php echo e(__('string.event_enquiry')); ?></a></p>
                        <p><a class="dropdown-item" href="<?php echo e(route('tickets')); ?>"><?php echo e(__('string.ticket')); ?></a></p>
                        <p><a class="dropdown-item" href="<?php echo e(url('user-profile')); ?>"><?php echo e(__('string.settings')); ?></a></p>
                        <p><a class="dropdown-item" href="<?php echo e(route('user.logout')); ?>"><?php echo e(__('string.logout')); ?></a></p>
                    </div>
                </li>
            </ul>
        <?php endif; ?>
          
        <?php if(!Session::get('user_id')): ?>
            <ul class="nav header-navbar-rht">
                <li class="nav-item">
                    <?php if(Route::is(['index', '/'])): ?>
                        <a href="<?php echo e(url('login')); ?>" class="nav-link btn btn-white log-register">
                            <span><i class="feather-user"></i></span><?php echo e(__('string.log_in')); ?>

                        </a>
                    <?php endif; ?>
                    <?php if(!Route::is(['index', '/'])): ?>
                        <a href="<?php echo e(url('login')); ?>" class="nav-link btn btn-white log-register">
                            <span><i class="feather-user"></i></span><?php echo e(__('string.log_in')); ?>

                        </a>
                    <?php endif; ?>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(url('register')); ?>" class="nav-link btn btn-white log-register">
                        <span><i class="feather-users"></i></span><?php echo e(__('string.register')); ?>

                    </a>
                </li>
            </ul>
        <?php endif; ?>

        <?php if(
            !Route::is([
                'user-dashboard',
                'user-bookings',
                'user-chat',
                'user-invoice',
                'user-wallet',
                'user-profile',
                'user-setting-password',
                'user-profile-othersetting',
                'user-coaches',
                'user-complete',
                'user-ongoing',
                'user-cancelled',
                'blog-list',
                'blog-list-sidebar-right',
                'blog-list-sidebar-left',
                'blog-grid-sidebar-right',
                'blog-grid-sidebar-left',
                'blog-grid',
                'blog-details-sidebar-right',
                'blog-carousel',
                'blog-details-sidebar-left',
                'blog-details',
            ])): ?>
        <?php endif; ?>
    </nav>
</div>

    <style type="text/css">
        @font-face {
            font-family: "Intro Rust G Base";
            src: "<?php echo e(URL::asset('/assets/fonts/IntroRust-Base.otf')); ?>";
            src: "<?php echo e(URL::asset('/assets/fonts/IntroRustH2-Base2Line.otf')); ?>" ;
            src: "<?php echo e(URL::asset('/assets/fonts/IntroRustL-Base2Line.otf')); ?>"
        }
    </style>

</header>
<?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/layout/partials/header.blade.php ENDPATH**/ ?>