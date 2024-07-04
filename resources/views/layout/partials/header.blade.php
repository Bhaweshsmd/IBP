@if (!Route::is(['index', '/']))
    <header class="header header-sticky">
@endif
@if (Route::is(['index', '/']))
    <header class="header header-trans">
@endif

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
            <a href="{{ url('/') }}" class="navbar-brand logo">
                @if (!Route::is(['index', '/']))
                    <img src="{{ URL::asset('/assets/img/isidel.png') }}" class="img-fluid" alt="Logo" style="max-width: 120px; " >
                @endif
                @if (Route::is(['index', '/']))
                    <img src="{{ URL::asset('/assets/img/isidel.png') }}" class="img-fluid" alt="Logo" style="max-width: 120px; ">
                @endif
            </a>
            
            @if (Session::get('user_id'))
                <ul class="nav header-navbar-rht logged-in d-lg-none" id="mobile_bt">
                    <li class="nav-item dropdown noti-nav">
                        <a href="javascript:void(0)" class="dropdown-toggle nav-link position-relative" data-bs-toggle="dropdown">
                            <i class="feather-bell"></i> <span class="alert-bg"></span>
                        </a>
                        <div class="dropdown-menu notifications dropdown-menu-end ">
                            <div class="topnav-dropdown-header">
                                <span class="notification-title">{{__('string.notifications')}}</span> 
                            </div>
                            <div class="noti-content">
                                <ul class="notification-list">
                                    @if(!empty($notifications))
                                        @foreach($notifications as $notification)
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
                                                            <h6>{{$subject}}</h6>
                                                            <p class="noti-details"><?php echo $content; ?></p>
                                                            <p>{{date('D,d M Y h:i A',strtotime($notification->created_at))}}</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                             <div class="container form-group p-3 text-center">
                              <span class="notification-title"><a class="text-success" href="{{route('user-notification')}}">{{__('string.view_all')}}</a></span>
                            </div>
                        </div>
                    </li>
                    
                    <li class="nav-item dropdown has-arrow logged-item">
                        <a href="#" class="dropdown-toggle nav-link profile-area" data-bs-toggle="dropdown">
                            <span class="user-img">
                                <img class="rounded-circle" src="{{ $profile_image }}" width="31" alt="{{$userDetails->first_name??''}}"> {{$userDetails->first_name??''}} {{$userDetails->last_name??''}}
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="user-header">
                                <div class="avatar avatar-sm">
                                    <img src="{{ $profile_image }}" alt="{{$userDetails->first_name??''}}" class="avatar-img rounded-circle" style="width: 43px;height: 40px;">
                                </div>
                                <div class="user-text">
                                    <h6 class="py-2">{{$userDetails->first_name??''}}&nbsp;&nbsp;{{$userDetails->last_name??''}}</h6>
                                    <a href="{{ url('user-profile') }}" class="text-profile mb-0">{{__('string.go_to_profile')}}</a>
                                </div>
                            </div>
                            <p class="paraspace"><a class="dropdown-item" href="{{ route('user-dashboard') }}">{{__('string.dashboard')}}</a></p>
                            <p class="paraspace"><a class="dropdown-item" href="{{ route('user-bookings') }}">{{__('string.my_bookings')}}</a></p>
                            <p class="paraspace"><a class="dropdown-item" href="{{ route('user-chat') }}">{{__('string.chat')}}</a></p>
                            <p class="paraspace"><a class="dropdown-item" href="{{ route('user-wallet') }}">{{__('string.ibp_account')}}</a></p>
                            <p class="paraspace"><a class="dropdown-item" href="{{ route('user-card') }}">{{__('string.ibp_card')}}</a></p>
                            <p class="paraspace"><a class="dropdown-item" href="{{ route('favourite-services') }}">{{__('string.my_favourite_items')}}</a></p>
                            <p class="paraspace"><a class="dropdown-item" href="{{ route('event-enquiry-list') }}">{{__('string.event_enquiry')}}</a></p>
                            <p class="paraspace"><a class="dropdown-item" href="{{ route('tickets') }}">{{__('string.ticket')}}</a></p>
                            <p class="paraspace"><a class="dropdown-item" href="{{ url('user-profile') }}">{{__('string.settings')}}</a></p>
                            <p class="paraspace"><a class="dropdown-item" href="{{ route('user.logout') }}">{{__('string.logout')}}</a></p>
                        </div>
                    </li>
                </ul>
            @endif
        </div>
        <div class="main-menu-wrapper">
            <div class="menu-header">
                <a href="{{ url('/') }}" class="menu-logo">
                    <img src="{{ URL::asset('/assets/img/beach-park-logo.png') }}" class="img-fluid" alt="Logo" style="max-width: 65px;">
                </a>
                <a id="menu_close" class="menu-close" href="javascript:void(0);"> <i class="fas fa-times"></i></a>
            </div>
            <ul class="main-nav">
                <li class="{{ Request::is('/', 'index') ? 'active' : '' }} mx-lg-2">
                    <a href="{{ url('/') }}">{{__('string.home')}}</a>
                </li>
                <li class="{{ Request::is('about-us') ? 'active' : '' }} mx-lg-2">
                    <a href="{{ route('web.pages', 'about-us') }}">{{__('string.about_us')}}</a>
                </li>
                <li class="{{ Request::is('items-facilities') ? 'active' : '' }} mx-lg-2">
                    <a href="{{ url('items-facilities') }}">{{__('string.Item_facilities')}}</a>
                </li>
                <li class="{{ Request::is('service-map') ? 'active' : '' }} mx-lg-2">
                    <a href="{{ url('service-map') }}">{{__('string.map')}}</a>
                </li>
                <li class="{{ Request::is('blogs') ? 'active' : '' }} mx-lg-2">
                    <a href="{{ route('event-enquiry') }}">{{__('string.events')}}</a>
                </li>
                <li class="{{ Request::is('faqs') ? 'active' : '' }} mx-lg-2">
                    <a href="{{ route('web.faqs') }}">{{__('string.faqs')}}</a>
                </li>
                <li class="{{ Request::is('contact-us') ? 'active' : '' }} mx-lg-2">
                    <a href="{{ url('contact-us') }}">{{__('string.contact_us')}}</a>
                </li>
                <li class=" mx-lg-2">
                    <span class="select-icon"><i class="feather-globe"></i></span>
                    <select class="select changeLang" id="changeLang" style="border:none;font-size:14px;">
                        <option value="en" {{ session()->get('locale') == 'en' ? 'selected' : '' }}>English (US)</option>
                        <option value="pap" {{ session()->get('locale') == 'pap' ? 'selected' : '' }}>Papiamentu</option>
                        <option value="nl" {{ session()->get('locale') == 'nl' ? 'selected' : '' }}>Dutch</option>
                    </select>
                </li>
                @if (!Session::get('user_id'))
                    <li class="login-link">
                        <a href="{{ url('register') }}">{{__('string.sign_up')}}</a>
                    </li>
                    <li class="login-link">
                        <a href="{{ url('login') }}">{{__('string.sign_in')}}</a>
                    </li>
                @endif
            </ul>
        </div>
        
        @if(Session::get('user_id'))
            <ul class="nav header-navbar-rht logged-in">
                <li class="nav-item dropdown noti-nav">
                    <a href="javascript:void(0)" class="dropdown-toggle nav-link position-relative"
                        data-bs-toggle="dropdown">
                        <i class="feather-bell"></i> <span class="alert-bg"></span>
                    </a>
                    <div class="dropdown-menu notifications dropdown-menu-end ">
                        <div class="topnav-dropdown-header">
                            <span class="notification-title">{{__('string.notifications')}}</span> 
                        </div>
                        <div class="noti-content">
                            <ul class="notification-list">
                                @if(!empty($notifications))
                                    @foreach($notifications as $notification)
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
                                                        <h6>{{$subject}}</h6>
                                                        <p class="noti-details"><?php echo $content; ?></p>
                                                        <p>{{date('D,d M Y h:i A',strtotime($notification->created_at))}}</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="container form-group p-3 text-center">
                          <span class="notification-title"><a class="text-success" href="{{route('user-notification')}}">{{__('string.view_all')}}</a></span>
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown has-arrow logged-item">
                    <a href="#" class="dropdown-toggle nav-link profile-area" data-bs-toggle="dropdown">
                        <span class="user-img">
                            <img class="rounded-circle" src="{{ $profile_image }}" width="31" alt="{{$userDetails->first_name??''}}" > {{$userDetails->first_name??''}} {{$userDetails->last_name??''}}
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="user-header">
                            <div class="avatar avatar-sm">
                                <img src="{{ $profile_image }}" alt="{{$userDetails->first_name??''}}" class="avatar-img rounded-circle" style="width: 43px;height: 40px;">
                            </div>
                            <div class="user-text">
                                <h6>{{$userDetails->first_name??''}}&nbsp;&nbsp;{{$userDetails->last_name??''}}</h6>
                                <a href="{{ url('user-profile') }}" class="text-profile mb-0">{{__('string.go_to_profile')}}</a>
                            </div>
                        </div>
                        <p><a class="dropdown-item" href="{{ route('user-dashboard') }}">{{__('string.dashboard')}}</a></p>
                        <p><a class="dropdown-item" href="{{ route('user-bookings') }}">{{__('string.my_bookings')}}</a></p>
                        <p><a class="dropdown-item" href="{{ route('user-chat') }}">{{__('string.chat')}}</a></p>
                        <p><a class="dropdown-item" href="{{ route('user-wallet') }}">{{__('string.ibp_account')}}</a></p>
                        <p><a class="dropdown-item" href="{{ route('user-card') }}">{{__('string.ibp_card')}}</a></p>
                        <p><a class="dropdown-item" href="{{ route('favourite-services') }}">{{__('string.my_favourite_items')}}</a></p>
                        <p><a class="dropdown-item" href="{{ route('event-enquiry-list') }}">{{__('string.event_enquiry')}}</a></p>
                        <p><a class="dropdown-item" href="{{ route('tickets') }}">{{__('string.ticket')}}</a></p>
                        <p><a class="dropdown-item" href="{{ url('user-profile') }}">{{__('string.settings')}}</a></p>
                        <p><a class="dropdown-item" href="{{ route('user.logout') }}">{{__('string.logout')}}</a></p>
                    </div>
                </li>
            </ul>
        @endif
          
        @if (!Session::get('user_id'))
            <ul class="nav header-navbar-rht">
                <li class="nav-item">
                    @if (Route::is(['index', '/']))
                        <a href="{{ url('login') }}" class="nav-link btn btn-white log-register">
                            <span><i class="feather-user"></i></span>{{__('string.log_in')}}
                        </a>
                    @endif
                    @if (!Route::is(['index', '/']))
                        <a href="{{ url('login') }}" class="nav-link btn btn-white log-register">
                            <span><i class="feather-user"></i></span>{{__('string.log_in')}}
                        </a>
                    @endif
                </li>
                <li class="nav-item">
                    <a href="{{ url('register') }}" class="nav-link btn btn-white log-register">
                        <span><i class="feather-users"></i></span>{{__('string.register')}}
                    </a>
                </li>
            </ul>
        @endif

        @if (
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
            ]))
        @endif
    </nav>
</div>

    <style type="text/css">
        @font-face {
            font-family: "Intro Rust G Base";
            src: "{{ URL::asset('/assets/fonts/IntroRust-Base.otf') }}";
            src: "{{ URL::asset('/assets/fonts/IntroRustH2-Base2Line.otf') }}" ;
            src: "{{ URL::asset('/assets/fonts/IntroRustL-Base2Line.otf') }}"
        }
    </style>

</header>
