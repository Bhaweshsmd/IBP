<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ __('App Name') }}</title>
    <meta name="robots" content="noindex">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    @yield('header')

    <link rel="stylesheet" href="{{ asset('asset/css/app.min.css') }}">
    <link href="{{ asset('asset/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/custom.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('asset/img/favicon.ico') }}' style="width: 2px !important;" />
    <link rel="stylesheet" href="{{ asset('asset/bundles/codemirror/lib/codemirror.css') }}">
    <link rel="stylesheet" href=" {{ asset('asset/bundles/codemirror/theme/duotone-dark.css') }} ">
    <link rel="stylesheet" href=" {{ asset('asset/bundles/jquery-selectric/selectric.css') }}">
    <script src="{{ asset('asset/cdnjs/iziToast.min.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/cdncss/iziToast.css') }}" />
    <script src="{{ asset('asset/cdnjs/sweetalert.min.js') }}"></script>
    <script src="{{ asset('asset/script/env.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/style/app.css') }}">
    <style>
        .navbar-nav{
            align-items: center;
        }
        
        .swal2-popup .swal2-styled.swal2-cancel {
            background-color: #1E425E !important;
            border-color: #1E425E;
            color: #000;
            border-radius: 5rem !important;
            font-weight: bold !important;
        }
        
        .swal2-popup .swal2-styled.swal2-cancel a {
            color: #fff !important;
        }
        
        .swal2-popup .swal2-styled.swal2-confirm {
            background-color: #23d9a6 !important;
            border-color: #23d9a6;
            color: #000;
            border-radius: 5rem !important;
            font-weight: bold !important;
        }
        
        .swal2-popup .swal2-styled.swal2-confirm a {
            color: #000 !important;
        }
        
        .swal2-popup .swal2-styled:focus {
            outline: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
        }
        
        .swal2-confirm{
            border-radius: 5rem !important;
            background: black !important;
            color: #fff !important;
        }
        
        .swal2-popup{
            border-radius: 25px !important;
        }
        .totalNoti{
            background: #009544;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: block;
            position: absolute;
            top: 8%;
            color: #fff;
            line-height: 20px;
        }
        #pullDownNoti{
          width: max-content;
          padding: 20px;
        }
        
        .scan-card{
            background-color: #009544 !important; 
            border-color: #009544 !important; 
            padding: 8px 15px !important; 
            font-size: 18px !important;
        }
        
        @media only screen and (max-width: 767px) {
            .scan-card{ 
                padding: 8px 10px !important; 
                font-size: 11px !important;
            }
            
            .pos-scan-card{
                background-color: #009544 !important; 
                border-color: #009544 !important; 
            }
            
            .theme-white .navbar {
                height: 115px;
            }
            
            .main-content {
                margin-top: 45px;
            }
        }
        
        #qr-reader__dashboard_section_csr button{
            background-color: #fb160a;
            border-color: transparent !important;
            color: #fff;
            font-size: 15px;
            padding: 7px 17px;
            border-radius: 5px;
            margin-bottom: 20px;
            margin-top: 20px;
        }
        
        #qr-reader__dashboard_section_swaplink{
            display: none !important;
        }
        
        #qr-reader div span{
            display: none;
        }
        
        #qr-reader #qr-reader__dashboard_section_csr span{
            display: block;
        }
        .media{
            width:400px;
        }
   </style>
</head>

<body>
    <div class="loader"></div> 
    
    <?php
        use App\Models\GlobalFunction;
        $checkadmin = DB::table('admin_user')->where('user_name', session()->get('user_name'))->first();
        if(!empty($checkadmin->picture)){
            $imgUrl = GlobalFunction::createMediaUrl($checkadmin->picture);
            $image = '<img src="' . $imgUrl . '" width="50" height="auto">';
        }else{
            $image = '<img src="https://placehold.jp/150x150.png" width="50" height="auto">';
        }
        
        $total_unread = App\Models\SalonNotifications::where('is_read',null)->orWhere('is_read',0)->count();
        $total_unread_noti = App\Models\SalonNotifications::latest()->limit(5)->get();
        
        $availcards = App\Models\Card::whereNull('assigned_to')->get();
        $availusers = App\Models\User::where('is_block', '!=', '1')->whereNull('card_id')->orderBy('first_name', 'asc')->get();
        $cards = App\Models\Card::whereNotNull('assigned_to')->get();
        $unread_messages = App\Models\ChatMessage::where('read', '0')->count();
    ?>

    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar sticky">
                @if(session()->get('user_type') != '20')
                    <div class="form-inline mr-auto">
                        <ul class="navbar-nav mr-3">
                            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn"><i data-feather="align-justify"></i></a></li>
                        </ul>
                    </div>
                
                    <ul class="navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="javascript::void(0)" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user"> 
                                <span class="d-sm-none d-lg-inline-block btn"> <i class="fa fa-bell"></i><span class="totalNoti">{{$total_unread??0}}</span></span>
                            </a>
                            @if($total_unread_noti)
                                <div class="dropdown-menu dropdown-menu-right pullDown" id="pullDownNoti">
                                    @foreach($total_unread_noti as $noti)
                                        <a href="javascript:void(0)">
                                            <div class="media d-flex">
                                                <div class="media-body">
                                                    <h6>{{$noti->title}}<span></span></h6>
                                                    <p class="noti-details" style="line-height: 18px;">{{$noti->description}}<br><span class="notification-time">{{date('D, d M Y h:i A ',strtotime($noti->created_at))}}</span></p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                    <a href="{{ route('notifications') }}" class="dropdown-item has-icon text-white btn btn-success text-center"> 
                                        {{ __('View All') }}
                                    </a>
                                </div>
                            @endif
                        </li>
                        
                        <li>
                            @if(has_permission(session()->get('user_type'), 'view_chat'))
                                <a href="{{route('chat')}}" class="ml-auto btn btn-info text-white scan-card" style="background-color: #3abaf4 !important; border-color: #3abaf4 !important;"><i class="fa fa-comment"></i> {{ __('Chat') }} <span class="badge badge-success">{{$unread_messages}}</span></a>
                            @endif
                        </li>
                    
                        <li>
                            @if(has_permission(session()->get('user_type'), 'view_scan_card'))
                                <a data-toggle="modal" data-target="#scancard" href="" class="ml-2 btn btn-primary text-white scan-card"><i class="fa fa-qrcode"></i> {{ __('Scan Card') }}</a>
                            @endif
                        </li>
                        
                        <li>
                            @if(has_permission(session()->get('user_type'), 'add_card_topup'))
                                <a data-toggle="modal" data-target="#cardtopupsHeader" href="" class="ml-2 btn btn-warning text-white text-white scan-card" style="background-color: #ffa426 !important; border-color: #ffa426 !important;"><i class="fa fa-plus" style="font-size: 18px;"></i> {{ __('Card Topup') }}</a>
                            @endif
                        </li>
                    
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user"> 
                            <span class="d-sm-none d-lg-inline-block btn btn-light"> <?php echo $image; ?> {{ $checkadmin->first_name }} {{ $checkadmin->last_name }} </span></a>
                            <div class="dropdown-menu dropdown-menu-right pullDown">
                                <a href="{{ route('admins.profile') }}" class="dropdown-item has-icon text-dark"> 
                                    <i class="fas fa-user"></i> {{ __('Profile') }}
                                </a>
                                <a href="{{ route('logout') }}" class="dropdown-item has-icon text-dark"> 
                                    <i class="fas fa-sign-out-alt"></i> {{ __('Log Out') }}
                                </a>
                            </div>
                        </li>
                    </ul>
                @else
                    <div class="sidebar-brand">
                        <a href="{{ route('dashboard') }}"> <span class="logo-name"> <img src="{{ asset('assets/img/isidel.png') }}" style="width: 80px;"> </span>
                        </a>
                    </div>
                
                    <ul class="navbar-nav navbar-right ml-auto">
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user"> 
                            @if(session()->get('user_type') == '20')
                                <?php
                                    if(!empty($checkadmin->picture)){
                                        $posimgUrl = GlobalFunction::createMediaUrl($checkadmin->picture);
                                        $posimage = '<img src="' . $posimgUrl . '" style="width: 60px; height: auto; border-radius: 30px;">';
                                    }else{
                                        $posimage = '<img src="https://placehold.jp/150x150.png" style="width: 60px; height: auto; border-radius: 30px;">';
                                    }
                                ?>
                                <span class=""> <?php echo $posimage; ?> </span></a>
                            @else
                                <span class="d-sm-none d-lg-inline-block btn btn-light"> <?php echo $image; ?> {{ $checkadmin->first_name }} {{ $checkadmin->last_name }} </span></a>
                            @endif
                            <div class="dropdown-menu dropdown-menu-right pullDown">
                                <a href="{{ route('admins.profile') }}" class="dropdown-item has-icon text-dark"> 
                                    <i class="fas fa-user"></i> {{ __('Profile') }}
                                </a>
                                <a href="{{ route('logout') }}" class="dropdown-item has-icon text-dark"> 
                                    <i class="fas fa-sign-out-alt"></i> {{ __('Log Out') }}
                                </a>
                            </div>
                        </li>
                    </ul>
                @endif
            </nav>
            
            @if(session()->get('user_type') != '20')
                <div class="main-sidebar sidebar-style-2">
                    <aside id="sidebar-wrapper">
                        <div class="sidebar-brand">
                            <a href="{{ route('dashboard') }}"> <span class="logo-name"> {{ __('App Name') }} </span>
                            </a>
                        </div>
                        <ul class="sidebar-menu">
    
                            <li class="menu-header">{{ __('Main') }}</li>
    
                            <li class="sideBarli  indexSideA">
                                <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i><span> {{ __('Dashboard') }} </span></a>
                            </li>
                            
                            @if(has_permission(session()->get('user_type'), 'view_admin'))
                                <li class="sideBarli adminsSideA">
                                    <a href="{{ route('admins') }}" class="nav-link"><i class="fa fa-user-md"></i><span> {{ __('Admins') }} </span></a>
                                </li>
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_user'))
                                <li class="sideBarli  usersSideA">
                                    <a href="{{ route('users') }}" class="nav-link"><i class="fa fa-users"></i><span> {{ __('All Customers') }} </span></a>
                                </li>
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_categories'))
                                <li class="sideBarli  salonCategoriesSideA">
                                    <a href="{{ route('all.categories') }}" class="nav-link"><i class="fas fa-grip-horizontal"></i><span> {{ __('Categories') }} </span></a>
                                </li>
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_services'))
                                <li class="sideBarli  servicesSideA">
                                    <a href="{{ route('services') }}" class="nav-link"><i class="fas  fa-tasks"></i><span> {{ __('Services') }} </span></a>
                                </li>
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_bookings'))
                                <li class="sideBarli  bookingsSideA">
                                    <a href="{{ route('bookings') }}" class="nav-link"><i class="fas  fa-calendar-check"></i><span> {{ __('Bookings') }} </span></a>
                                </li>
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_reviews'))
                                <li class="sideBarli  reviewsSideA">
                                    <a href="{{ route('reviews') }}" class="nav-link"><i class="fas fa-star"></i><span> {{ __('Reviews') }} </span></a>
                                </li>
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_enquiries'))
                                 <li class="sideBarli  eventSideA">
                                    <a href="{{ route('events') }}" class="nav-link"><i class="fa fa-users"></i><span> {{ __('Event Enquiry') }} </span></a>
                                </li>
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_coupons'))
                                <li class="sideBarli  couponsSideA">
                                    <a href="{{ route('coupons') }}" class="nav-link"><i class="fas fa-tag"></i><span> {{ __('Coupons') }} </span></a>
                                </li>
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_banners'))
                                <li class="sideBarli  bannersSideA">
                                    <a href="{{ route('banners') }}" class="nav-link"><i class="far fa-image"></i><span> {{ __('App Banners') }} </span></a>
                                </li>
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_web_banners'))
                                <li class="sideBarli webbannersSideA">
                                    <a href="{{ route('web.banners') }}" class="nav-link"><i class="far fa-image"></i><span> {{ __('Web Banners') }} </span></a>
                                </li>
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_faqs'))
                                <li class="sideBarli  faqsSideA">
                                    <a href="{{ route('faqs') }}" class="nav-link"><i class="fas fa-question-circle"></i><span> {{ __('FAQs') }} </span></a>
                                </li>
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_notifications'))
                                <li class="sideBarli  notificationsSideA">
                                    <a href="{{ route('notifications') }}" class="nav-link"><i class="fa fa-bell"></i><span> {{ __('Notifications') }} </span></a>
                                </li>
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_tickets'))
                                <li class="sideBarli supportSideA">
                                    <a href="{{ route('tickets') }}" class="nav-link"><i class="fas fa-money-bill"></i><span> {{ __('Support Tickets') }} </span></a>
                                </li>
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_chat'))
                                <li class="sideBarli chatSideA">
                                    <a href="{{ route('chat') }}" class="nav-link"><i class="fa fa-comment"></i><span> {{ __('Chat') }} </span></a>
                                </li>
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_cards') || has_permission(session()->get('user_type'), 'view_card_topup') || has_permission(session()->get('user_type'), 'view_assign_card') || has_permission(session()->get('user_type'), 'view_card_transactions'))
                                <li class="menu-header">{{ __('IBP Cards') }}</li>
                                @if(has_permission(session()->get('user_type'), 'view_cards'))
                                    <li class="sideBarli cardSideA">
                                        <a href="{{ route('cards') }}" class="nav-link"><i class="fas fa-credit-card"></i><span> {{ __('All Cards List') }} </span></a>
                                    </li>
                                @endif
                                @if(has_permission(session()->get('user_type'), 'view_assign_card'))
                                    <li class="sideBarli assigncardSideA">
                                        <a href="{{ route('cards.assign') }}" class="nav-link"><i class="fas fa-credit-card"></i><span> {{ __('Assigned Card') }} </span></a>
                                    </li>
                                @endif
                                  @if(has_permission(session()->get('user_type'), 'view_card_topup'))
                                    <li class="sideBarli cardtopupSideA">
                                        <a href="{{ route('cards.topups') }}" class="nav-link"><i class="fas fa-credit-card"></i><span> {{ __('Card Topups') }} </span></a>
                                    </li>
                                @endif
                                
                                @if(has_permission(session()->get('user_type'), 'view_card_transactions'))
                                    <li class="sideBarli cardtransSideA">
                                        <a href="{{ route('card.transactions') }}" class="nav-link"><i class="fas fa-credit-card"></i><span> {{ __('Card Transactions') }} </span></a>
                                    </li>
                                @endif
                                
                                @if(has_permission(session()->get('user_type'), 'view_card_membership'))
                                    <li class="sideBarli cardmembfeeSideA">
                                        <a href="{{ route('card.memberships') }}" class="nav-link"><i class="fas fa-credit-card"></i><span> {{ __('Card Membership') }} </span></a>
                                    </li>
                                @endif
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_withdrawal') || has_permission(session()->get('user_type'), 'view_earnings') || has_permission(session()->get('user_type'), 'view_balance'))
                                <li class="menu-header">{{ __('Business') }}</li>
                                   @if(has_permission(session()->get('user_type'), 'view_earnings'))
                                    <li class="sideBarli  platformEarningsSideA">
                                        <a href="{{ route('platform.earnings') }}" class="nav-link"><i class="fas fa-percentage"></i><span> {{ __('Platform Earnings') }} </span></a>
                                    </li>
                                @endif
                                   @if(has_permission(session()->get('user_type'), 'view_admin_withdrawal'))
                                    <li class="sideBarli  adminWithdrawsSideA">
                                        <a href="{{ route('adminWithdraws') }}" class="nav-link"><i class="fas fa-money-bill"></i><span> {{ __('Admin Withdrawal') }} </span></a>
                                    </li>
                                @endif
                                @if(has_permission(session()->get('user_type'), 'view_withdrawal'))
                                    <li class="sideBarli  userWithdrawsSideA">
                                        <a href="{{ route('userWithdraws') }}" class="nav-link"><i class="fas fa-money-bill"></i><span> {{ __('Customer Withdrawal') }} </span></a>
                                    </li>
                                @endif
                                
                                @if(has_permission(session()->get('user_type'), 'view_balance'))
                                    <li class="sideBarli  userWalletRechargeSideA">
                                        <a href="{{ route('deposits') }}" class="nav-link"><i class="fas fa-wallet"></i><span> {{ __('IBP Account Deposits') }} </span></a>
                                    </li>
                                @endif
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_languages') || has_permission(session()->get('user_type'), 'view_content'))
                                <li class="menu-header">{{ __('Config') }}</li>
                                @if(has_permission(session()->get('user_type'), 'view_languages'))
                                    <li class="sideBarli  languageWithSideA">
                                        <a href="{{ route('language')}}" class="nav-link"><i class="fas fa-money-bill"></i><span> {{ __('Languages') }} </span></a>
                                    </li>
                                @endif
                                
                                @if(has_permission(session()->get('user_type'), 'view_content'))
                                    <li class="sideBarli  contentSideA">
                                        <a href="{{ route('contents') }}" class="nav-link"><i class="fas fa-money-bill"></i><span> {{ __('Language Contents')}} </span></a>
                                    </li>
                                @endif
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_emails') || has_permission(session()->get('user_type'), 'view_notification') || has_permission(session()->get('user_type'), 'view_admin_emails') )
                                <li class="menu-header">{{ __('Template') }}</li>
                              
                                
                                @if(has_permission(session()->get('user_type'), 'view_emails'))
                                    <li class="sideBarli  emailSideA">
                                        <a href="{{ route('email.templates') }}" class="nav-link"><i class="fas fa-money-bill"></i><span> {{ __('Customer Email Templates')}} </span></a>
                                    </li>
                                @endif
                                
                                  @if(has_permission(session()->get('user_type'), 'view_admin_emails'))
                                    <li class="sideBarli  emailAdminSideA">
                                        <a href="{{ route('admin.email.templates') }}" class="nav-link"><i class="fas fa-money-bill"></i><span> {{ __('Admin Email Templates')}} </span></a>
                                    </li>
                                @endif
                                
                                @if(has_permission(session()->get('user_type'), 'view_notification'))
                                     <li class="sideBarli  notificationSideA">
                                        <a href="{{ route('notification.templates') }}" class="nav-link"><i class="fas fa-money-bill"></i><span> {{ __('Customer Notification Templates')}} </span></a>
                                    </li>
                                @endif
                                 @if(has_permission(session()->get('user_type'), 'view_notification'))
                                     <li class="sideBarli  adminNotificationSideA">
                                        <a href="{{ route('admin.notification.templates') }}" class="nav-link"><i class="fas fa-money-bill"></i><span> {{ __('Admin Notification Templates')}} </span></a>
                                    </li>
                                @endif
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_settings') || has_permission(session()->get('user_type'), 'view_email_settings') || has_permission(session()->get('user_type'), 'view_revenue_setting') || has_permission(session()->get('user_type'), 'view_taxes') || has_permission(session()->get('user_type'), 'view_fees') || has_permission(session()->get('user_type'), 'view_payment') || has_permission(session()->get('user_type'), 'view_loyality_points') || has_permission(session()->get('user_type'), 'view_assign_card_fees') || has_permission(session()->get('user_type'), 'view_app_settings') || has_permission(session()->get('user_type'), 'view_country'))
                                <li class="menu-header">{{ __('Settings') }}</li>
                                @if(has_permission(session()->get('user_type'), 'view_settings'))
                                    <li class="sideBarli settingsSideA">
                                        <a href="{{ route('general') }}" class="nav-link"><i class="fas fa-cog"></i><span> {{ __('General Settings') }} </span></a>
                                    </li>
                                @endif
                                
                                @if(has_permission(session()->get('user_type'), 'view_email_settings'))
                                    <li class="sideBarli emailsettSideA">
                                        <a href="{{ route('email.settings') }}" class="nav-link"><i class="fas fa-cog"></i><span> {{ __('Email Settings') }} </span></a>
                                    </li>
                                @endif
                                
                                @if(has_permission(session()->get('user_type'), 'view_revenue_setting'))
                                    <li class="sideBarli revenueSettingSideA">
                                        <a href="{{ route('revenue.setting') }}" class="nav-link"><i class="fas fa-cog"></i><span> {{ __('Revenue Settings') }} </span></a>
                                    </li>
                                @endif
                                
                                @if(has_permission(session()->get('user_type'), 'view_taxes'))
                                    <li class="sideBarli taxesSideA">
                                        <a href="{{ route('taxes') }}" class="nav-link"><i class="fas fa-cog"></i><span> {{ __('Tax Settings') }} </span></a>
                                    </li>
                                @endif
                                
                                @if(has_permission(session()->get('user_type'), 'view_fees'))
                                    <li class="sideBarli feesSideA">
                                        <a href="{{ route('fees') }}" class="nav-link"><i class="fas fa-cog"></i><span> {{ __('Fee Settings') }} </span></a>
                                    </li>
                                @endif
                                
                                @if(has_permission(session()->get('user_type'), 'view_assign_card_fees'))
                                    <li class="sideBarli assigncardfeeSideA">
                                        <a href="{{ route('assign.card.fees') }}" class="nav-link"><i class="fas fa-cog"></i><span> {{ __('Card Assign Fee') }} </span></a>
                                    </li>
                                @endif
                                
                                @if(has_permission(session()->get('user_type'), 'view_card_membership_fee'))
                                    <li class="sideBarli maintenancefeeSideA">
                                        <a href="{{ route('maintenance.card.fees') }}" class="nav-link"><i class="fas fa-cog"></i><span> {{ __('Card Membership Fee') }} </span></a>
                                    </li>
                                @endif
                                
                                @if(has_permission(session()->get('user_type'), 'view_app_settings'))
                                    <li class="sideBarli appsettingsSideA">
                                        <a href="{{ route('app.settings') }}" class="nav-link"><i class="fas fa-cog"></i><span> {{ __('App Settings') }} </span></a>
                                    </li>
                                @endif
                                
                                @if(has_permission(session()->get('user_type'), 'view_payment'))
                                    <li class="sideBarli gatewaysSideA">
                                        <a href="{{ route('gateways') }}" class="nav-link"><i class="fas fa-cog"></i><span> {{ __('Payment Gateways') }} </span></a>
                                    </li>
                                @endif
                                
                                @if(has_permission(session()->get('user_type'), 'view_loyality_points'))
                                    <li class="sideBarli loyalitySideA">
                                        <a href="{{ route('loyality.points') }}" class="nav-link"><i class="fas fa-cog"></i><span> {{ __('Loyality Points') }} </span></a>
                                    </li>
                                @endif
                                
                                @if(has_permission(session()->get('user_type'), 'view_country'))
                                    <li class="sideBarli countrySideA">
                                        <a href="{{ route('countries') }}" class="nav-link"><i class="fas fa-cog"></i><span> {{ __('Country Settings') }} </span></a>
                                    </li>
                                @endif
                                
                                @if(session()->get('user_type') == '1')
                                    <li class="sideBarli rolesSideA">
                                        <a href="{{ route('roles') }}" class="nav-link"><i class="fa fa-key"></i><span> {{ __('Roles & Permissions') }} </span></a>
                                    </li>
                                @endif
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_company'))
                                <li class="menu-header">{{ __('Other Data') }}</li>
                                @if(has_permission(session()->get('user_type'), 'view_company'))
                                    <li class="sideBarli  salonsSideA">
                                        <a href="{{ route('platforms') }}" class="nav-link"><i class="fas fa-store"></i><span> {{ __('Company Profile') }} </span></a>
                                    </li>
                                @endif
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_pages'))
                                <li class="menu-header">{{ __('Pages') }}</li>
                                <li class="sideBarli pageSideA">
                                    <a href="{{ route('pages') }}" class="nav-link"><i class="fas fa-info"></i><span>{{ __('All Pages') }}</span></a>
                                </li>
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_blogs'))
                                <li class="menu-header">{{ __('Blogs') }}</li>
                                <li class="sideBarli blogSideA">
                                    <a href="{{ route('blogs') }}" class="nav-link"><i class="fas fa-info"></i><span>{{ __('All Blogs') }}</span></a>
                                </li>
                            @endif
                            
                            @if(has_permission(session()->get('user_type'), 'view_maintenance'))
                                <li class="menu-header">{{ __('Maintenance') }}</li>
                                <li class="sideBarli maintenanceSideA">
                                    <a href="{{ route('maintainance') }}" class="nav-link"><i class="fas fa-info"></i><span>{{ __('Maintenance Settings') }}</span></a>
                                </li>
                            @endif
                        </ul>
                    </aside>
                </div>
            @endif

            <div class="main-content">

                @yield('content')
                <form action="">
                    <input type="hidden" id="user_type" value="{{ session('user_type') }}">
                </form>

            </div>

        </div>
    </div>
    
    <script src="{{ asset('asset/js/app.min.js ') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    
    <script src="https://unpkg.com/sweetalert2@7.8.2/dist/sweetalert2.all.js"></script>
    <script src="{{ asset('asset/bundles/datatables/datatables.min.js ') }}"></script>
    <script src="{{ asset('asset/bundles/jquery-ui/jquery-ui.min.js ') }}"></script>
    <script src=" {{ asset('asset/js/page/datatables.js') }}"></script>
    <script src="{{ asset('asset/js/scripts.js') }}"></script>
    <script src="{{ asset('asset/script/app.js') }}"></script>
    <script src="{{ asset('asset/bundles/summernote/summernote-bs4.js ') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.js"
        integrity="sha512-Fd3EQng6gZYBGzHbKd52pV76dXZZravPY7lxfg01nPx5mdekqS8kX4o1NfTtWiHqQyKhEGaReSf4BrtfKc+D5w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        
    <div class="modal fade" id="scancard" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Scan Card / Enter Card Number') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-right">
                    <input type="number" class="form-control" id="barcode" min="12" max="12">
                    @if(session()->get('user_type') == '20')
                        <button type="submit" id="barcodesubmit" class="btn btn-primary">Submit</button>
                        <p class="text-warning text-center mt-4"><strong>Please scan barcode at the center and hold for 5 seconds</strong></p>
                        <div id="qr-reader" style="width:100%;"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="assignnewcard" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Assign New Card') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('assign.cards.store') }}" method="post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                        @csrf

                        <div class="form-group">
                            <label>{{ __('Select User') }}</label>
                            <select name="user_id" class="form-control form-control-sm" placeholder="Select User" id="avail_select_user">
                                <option value="">Select User</option>
                                @if(!empty($availusers))
                                    @foreach($availusers as $val)
                                        <option value="{{$val->id}}">{{$val->first_name}} {{$val->last_name}} ({{$val->email}})</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label> {{ __('Select Cards') }}</label>
                            <select name="card_id" class="form-control form-control-sm" placeholder="Select Card" id="avail_select_card">
                                <option value="">Select Card</option>
                                @if(!empty($availcards))
                                    @foreach($availcards as $val)
                                        <option value="{{$val->id}}">{{chunk_split($val->card_number, 4, ' ')}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Assign') }}" id='submitformbtn'>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <div class="modal fade" id="cardtopupsHeader" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Card Topup') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('cards.topups.store') }}" method="post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                        @csrf

                        <div class="form-group">
                            <label> {{ __('Select Card') }}</label>
                            <select name="card_id" class="form-control form-control-sm" placeholder="Select Card" id="topup_select_card">
                                <option value="">Select Card</option>
                                @if(!empty($cards))
                                    @foreach($cards as $val)
                                    <?php 
                                        $usersdetal = DB::table('users')->where('id', $val->assigned_to)->first();
                                    ?>
                                        <option value="{{$val->id}}">{{chunk_split($val->card_number, 4, ' ')}} ({{$usersdetal->email??''}})</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>{{ __('Enter Amount') }}</label>
                            <input class="form-control" type="number" id="icon" name="amount" required>
                        </div>

                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}" id='submitformbtn'>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    
    <script type="text/javascript">
    	$(document).ready(function () {
          $('#avail_select_user').selectize({
              sortField: 'text'
          });
      });
    </script>
     <script type="text/javascript">
    	$(document).ready(function () {
          $('#select_user').selectize({
              sortField: 'text'
          });
      });
    </script>
    
    <script type="text/javascript">
    	$(document).ready(function () {
          $('#avail_select_card').selectize({
              sortField: 'text'
          });
      });
    </script>
    
    <script type="text/javascript">
    	$(document).ready(function () {
          $('#topup_select_card').selectize({
              sortField: 'text'
          });
      });
    </script>
    
    @if(session()->has('card_assign'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('card_assign') }}",
                    type: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<a href='{{route('cards.edit', session()->get('card_id'))}}'>Card Details</a>",
                    cancelButtonText: "<a href='{{route('assign.invoice', session()->get('card_id'))}}' target='_blank'>Print</a>",
                    closeOnConfirm: false, 
                    customClass: "Custom_Cancel"
                })
            });
        </script>
    @endif
    
    <script src="https://raw.githubusercontent.com/mebjas/html5-qrcode/master/minified/html5-qrcode.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
    
    <script>
        function docReady(fn) {
            if (document.readyState === "complete"
                || document.readyState === "interactive") {
                setTimeout(fn, 1);
            } else {
                document.addEventListener("DOMContentLoaded", fn);
            }
        }
    
        docReady(function () {
            var lastResult, countResults = 0;
            function onScanSuccess(decodedText, decodedResult) {
                if (decodedText !== lastResult) {
                    ++countResults;
                    lastResult = decodedText;
                    console.log(`Scan result ${decodedText}`, decodedResult);
                    
                    var qrreaderresults = decodedText;
                    document.getElementById("barcode").value = qrreaderresults;
                }
            }
    
            var html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", { fps: 10, qrbox: 250 });
            html5QrcodeScanner.render(onScanSuccess);
        });
    </script>
    
    <script>
        $(document).on('input', '#barcode', function(){
            var card_id = $('#barcode').val();
            $.ajax({
                type:"POST",
                url: "{{ route('cards.details') }}",
                dataType: "json",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "card_id": card_id
                },
                success:function(result){
                    if(result.status == true){
                       window.location.href = result.redirect_url;
                    }else if(result.status == false){
                        $(function () {
                            swal({
                                title: "Warning",
                                text: result.message,
                                type: "warning",
                                confirmButtonColor: "#000",
                                confirmButtonText: "Close",
                                closeOnConfirm: false, 
                            })
                        });
                    }
                }
            });
        });
    
        $(document).on('click', '#barcodesubmit', function(){
            var card_id = $('#barcode').val();
            $.ajax({
                type:"POST",
                url: "{{ route('cards.details') }}",
                dataType: "json",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "card_id": card_id
                },
                success:function(result){
                    if(result.status == true){
                       window.location.href = result.redirect_url;
                    }else if(result.status == false){
                        $(function () {
                            swal({
                                title: "Warning",
                                text: result.message,
                                type: "warning",
                                confirmButtonColor: "#000",
                                confirmButtonText: "Close",
                                closeOnConfirm: false, 
                            })
                        });
                    }
                }
            });
        });
    </script>
    
    <script>
        function disableformButton() {
            var btn = document.getElementById('submitformbtn');
            btn.disabled = true;
            btn.innerText = 'Submiting...'
        }
    </script>

</body>


</html>
