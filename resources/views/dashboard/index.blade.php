@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/index.js') }}"></script>
@endsection

@section('content')
    <style>
        .chartjs-render-monitor {
            -webkit-animation: chartjs-render-animation 0.001s;
            animation: chartjs-render-animation 0.001s;
        }

        *,
        ::after,
        ::before {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        .mainbg {
            background-color: #156779 !important;
        }

        .card-icon2 {
            width: 50px;
            height: 50px;
            line-height: 50px;
            font-size: 22px;
            margin: 5px 0px;
            box-shadow: 2px 2px 10px 0 #97979794;
            border-radius: 10px;
            background: #6777ef;
            text-align: center;
        }
        .card .card-statistic-4{
            color: #ffffff;
        }

        .maincolor {
            color: white !important;
        }

        .text-theme-color {
            color: white !important;
        }

        .text-grey {
            color: #fff !important;
        }

        .records-tab-div p {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 7px !important;
            margin-bottom: 0 !important;
            padding: 0 10px;
            border-radius: 5px;
        }
        
        .h4, h4 {
            font-size: 1.25rem;
        }
        
    </style>

<section class="section">
    @if(has_permission(session()->get('user_type'), 'view_bookings') && session()->get('user_type') != '20')
        <div class="card" style="background-color:white;">
            <div class="ml-4 my-3">
                <h5 class="d-inline" style="color: rgb(59, 58, 58);">{{ __('Bookings') }}</h5>
                <a href="{{ route('bookings') }}"><span class="badge bg-primary text-white">{{ __('View All') }}</span></a>
            </div>
            <div class="row col-12">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: green;">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <h4 class="font-18 mt-1 text-theme-color">{{ __('Today') }}</h4>
                                            <div class="records-tab-div">
                                                <p class="text-grey">
                                                    {{ __('Total : ') }}<strong>{{ $todayTotalBookings }}</strong>
                                                </p>
                                                <p class="text-grey">
                                                    {{ __('Accepted : ') }}<strong>{{ $todayTotalAcceptedBookings }}</strong>
                                                </p>
                                                <p class="text-grey">
                                                    {{ __('Completed : ') }}<strong>{{ $todayTotalCompletedBookings }}</strong>
                                                </p>
                                                <p class="text-grey">
                                                    {{ __('Cancelled : ') }}<strong>{{ $todayTotalCancelledBookings }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: orange;">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <h4 class="font-18 mt-1 text-theme-color">{{ __('Last 7 Days') }}</h4>
                                            <div class="records-tab-div">
                                                <p class="text-grey">
                                                    {{ __('Total : ') }}<strong>{{ $last7daysTotalBookings }}</strong>
                                                </p>
                                                <p class="text-grey">
                                                    {{ __('Accepted : ') }}<strong>{{ $last7daysTotalAcceptedBookings }}</strong>
                                                </p>
                                                <p class="text-grey">
                                                    {{ __('Completed : ') }}<strong>{{ $last7daysTotalCompletedBookings }}</strong>
                                                </p>
                                                <p class="text-grey">
                                                    {{ __('Cancelled : ') }}<strong>{{ $last7daysTotalCancelledBookings }}</strong>
                                                </p>
                                            </div>
    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="card" style ="background-color: rgb(25, 110, 190);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <h4 class="font-18 mt-1 text-theme-color">{{ __('Last 30 Days') }}</h4>
                                            <div class="records-tab-div">
                                                <p class="text-grey">
                                                    {{ __('Total : ') }}<strong>{{ $last30daysTotalBookings }}</strong>
                                                </p>
                                                <p class="text-grey">
                                                    {{ __('Accepted : ') }}<strong>{{ $last30daysTotalAcceptedBookings }}</strong>
                                                </p>
                                                <p class="text-grey">
                                                    {{ __('Completed : ') }}<strong>{{ $last30daysTotalCompletedBookings }}</strong>
                                                </p>
                                                <p class="text-grey">
                                                    {{ __('Cancelled : ') }}<strong>{{ $last30daysTotalCancelledBookings }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
          
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: rgb(222, 109, 68);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <h4 class="font-18 mt-1 text-theme-color">{{ __('All Time') }}</h4>
                                            <div class="records-tab-div">
                                                <p class="text-grey">
                                                    {{ __('Total : ') }}<strong>{{ $allTimeTotalBookings }}</strong>
                                                </p>
                                                <p class="text-grey">
                                                    {{ __('Accepted : ') }}<strong>{{ $allTimeTotalAcceptedBookings }}</strong>
                                                </p>
                                                <p class="text-grey">
                                                    {{ __('Completed : ') }}<strong>{{ $allTimeTotalCompletedBookings }}</strong>
                                                </p>
                                                <p class="text-grey">
                                                    {{ __('Cancelled : ') }}<strong>{{ $allTimeTotalCancelledBookings }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
        </div>
    @endif
    
    @if(has_permission(session()->get('user_type'), 'view_earnings') && session()->get('user_type') != '20')
        <div class="card mt-4" style="background-color:white;">
            <div class="ml-4 my-3">
                <h5 class="d-inline" style="color: rgb(59, 58, 58);">{{ __('Platform Earnings') }}</h5>
                <a href="{{ route('platform.earnings') }}"><span class="badge bg-primary text-white">{{ __('View All') }}</span></a>
            </div>
            <div class="row col-12">
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0 0.5019 0.0053);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($todayEarnings, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Today') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: orange;">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($last7DaysEarnings, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Last 7 Days') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0.099 0.4299 0.7445);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($last30DaysEarnings, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Last 30 Days') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0.8724 0.4264 0.2667);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($last90DaysEarnings, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Last 90 Days') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: rgb(184, 97, 250);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($last180DaysEarnings, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Last 180 Days') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: yellowgreen;">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($allTimeDaysEarnings, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('All Time') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if(has_permission(session()->get('user_type'), 'view_balance') && session()->get('user_type') != '20')
        <div class="card mt-4" style="background-color:white;">
            <div class="ml-4 my-3">
                <h5 class="d-inline" style="color: rgb(59, 58, 58);">{{ __('Customer IBP Account Recharge') }}</h5>
                <a href="{{ route('deposits') }}"><span class="badge bg-primary text-white">{{ __('View All') }}</span></a>
            </div>
            <div class="row col-12">
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0 0.5019 0.0053);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($todayRecharges, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Today') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(display-p3 0.949 0.6627 0.2314);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($last7DaysRecharges, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Last 7 Days') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0.099 0.4299 0.7445);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($last30DaysRecharges, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Last 30 Days') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0.8724 0.4264 0.2667);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($last90DaysRecharges, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Last 90 Days') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0.7237 0.3781 0.9808);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($last180DaysRecharges, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Last 180 Days') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0.6055 0.8056 0.1931);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($allTimeRecharges, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('All Time') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if(has_permission(session()->get('user_type'), 'view_card_topup') && session()->get('user_type') != '20')
        <div class="card mt-4" style="background-color:white;">
            <div class="ml-4 my-3">
                <h5 class="d-inline" style="color: rgb(59, 58, 58);">{{ __('Customer IBP Card Topups') }}</h5>
                <a href="{{ route('cards.topups') }}"><span class="badge bg-primary text-white">{{ __('View All') }}</span></a>
            </div>
            <div class="row col-12">

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0 0.5019 0.0053);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($todayTopups, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Today') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(display-p3 0.949 0.6627 0.2314);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($last7DaysTopups, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Last 7 Days') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0.099 0.4299 0.7445);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($last30DaysTopups, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Last 30 Days') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0.8724 0.4264 0.2667);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($last90DaysTopups, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Last 90 Days') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0.7237 0.3781 0.9808);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($last180DaysTopups, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Last 180 Days') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0.6055 0.8056 0.1931);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 ">{{ $settings->currency }}{{ number_format($allTimeTopups, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('All Time') }}</h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if(has_permission(session()->get('user_type'), 'view_withdrawal') && session()->get('user_type') != '20')
        <div class="card mt-4" style="background-color:white;">
            <div class="ml-4 my-3">
                <h5 class="d-inline" style="color: rgb(59, 58, 58);">{{ __('Customer Withdrawals') }}</h5>
                <a href="{{ route('userWithdraws') }}"><span class="badge bg-primary text-white">{{ __('View All') }}</span></a>
            </div>
            <div class="row col-12">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0 0.5019 0.0053);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="">{{ $settings->currency }}{{ number_format($pendingUserPayouts, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Pending Withdrawals ') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(display-p3 0.949 0.6627 0.2314);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="">{{ $settings->currency }}{{ number_format($completedUserPayouts, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Completed Withdrawals ') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0.099 0.4299 0.7445);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="">{{ $settings->currency }}{{ number_format($rejectedUserPayouts, 2, '.', ',') }}</h4>
                                            <h5 class="font-15 mt-1 text-grey">{{ __('Rejected Withdrawals ') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>

@if(session()->get('user_type') == '20')
    <style>
        .main-content{
            padding-left: 0px;
            padding-right: 0px;
        }
    </style>
    <div class="container-fluid card text-center pt-4">
        <div class="row">
            <div class="col-11 mb-3">
                @if(has_permission(session()->get('user_type'), 'view_scan_card'))
                    <a data-toggle="modal" data-target="#scancard" href="" class="btn btn-primary" style="width: 100%; margin-left: 15px; font-size: 25px; padding: 15px 17px; font-weight: bold;">{{ __('Scan Card') }}</a>
                @endif
            </div>
        </div>
    </div>
    
    <div class="container-fluid card text-center pt-4 mt-4">
        <div class="row">
            <div class="col-6 mb-3">
                @if(has_permission(session()->get('user_type'), 'add_assign_card'))
                    <a data-toggle="modal" data-target="#poscardassign" href="" class="btn btn-success" style="width: 200px; font-size: 18px; padding: 15px 17px;">{{ __('Assign Card') }}</a>
                @endif
            </div>
            <div class="col-6 mb-3">
                @if(has_permission(session()->get('user_type'), 'add_card_topup'))
                    <a data-toggle="modal" data-target="#poscardtopups" href="" class="btn btn-success" style="width: 200px; font-size: 18px; padding: 15px 17px;">{{ __('Topup Card') }}</a>
                @endif
            </div>
        </div>
    </div>
    
    <div class="container-fluid card text-center pt-4 mt-4">
        <div class="row">
            <div class="col-6 mb-3">
                @if(has_permission(session()->get('user_type'), 'view_bookings'))
                    <a href="{{ route('bookings') }}" class="btn btn-info" style="width: 200px; font-size: 18px; padding: 15px 5px;">Booking <br> List</a>
                @endif
            </div>
            <div class="col-6 mb-3">
                @if(has_permission(session()->get('user_type'), 'view_card_topup'))
                    <a href="{{ route('cards.assign') }}" class="btn btn-info" style="width: 200px; font-size: 18px; padding: 15px 5px;">Assigned Card <br> List</a>
                @endif
            </div>
            <div class="col-6 mb-3">
                @if(has_permission(session()->get('user_type'), 'view_assign_card'))
                    <a href="{{ route('cards.topups') }}" class="btn btn-info" style="width: 200px; font-size: 18px; padding: 15px 5px;">Card Topups <br> List</a>
                @endif
            </div>
            <div class="col-6 mb-3">
                @if(has_permission(session()->get('user_type'), 'view_card_transactions'))
                    <a href="{{ route('card.transactions') }}" class="btn btn-info" style="width: 200px; font-size: 18px; padding: 15px 5px;">Card <br> Transactions</a>
                @endif
            </div>
        </div>
    </div>
@endif

<div class="modal fade" id="poscardassign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>{{ __('Assign New Card') }}</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ route('assign.cards.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>{{ __('Select User') }}</label>
                        <select name="user_id" class="form-control form-control-sm" placeholder="Select User">
                            <option value="">Select User</option>
                            @if(!empty($users))
                                @foreach($users as $val)
                                    <option value="{{$val->id}}">{{$val->first_name}} {{$val->last_name}} ({{$val->email}})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label> {{ __('Select Cards') }}</label>
                        <select name="card_id" class="form-control form-control-sm" placeholder="Select Card">
                            <option value="">Select Card</option>
                            @if(!empty($cards))
                                @foreach($cards as $val)
                                    <option value="{{$val->id}}">{{chunk_split($val->card_number, 4, ' ')}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group text-right">
                        <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Assign') }}">
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="poscardtopups" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>{{ __('New Card Topup') }}</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ route('cards.topups.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label> {{ __('Select Card') }}</label>
                        <select name="card_id" class="form-control form-control-sm" placeholder="Select Card">
                            <option value="">Select Card</option>
                            @if(!empty($assigncards))
                                @foreach($assigncards as $val)
                                <?php 
                                    $user = DB::table('users')->where('id', $val->assigned_to)->first();
                                ?>
                                    <option value="{{$val->id}}">{{chunk_split($val->card_number, 4, ' ')}} ({{$user->email}})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>{{ __('Enter Amount') }}</label>
                        <input class="form-control" type="number" id="icon" name="amount" required>
                    </div>

                    <div class="form-group text-right">
                        <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
      $('select').selectize({
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
                cancelButtonText: "<a href='{{route('assign.invoice', session()->get('card_id'))}}' id='assigninvoice'>Print</a>",
                closeOnConfirm: false, 
                customClass: "Custom_Cancel"
            })
        });
    </script>
    
    <script>
        document.getElementById('assigninvoice').addEventListener('click', function() {
            window.ReactNativeWebView.postMessage('{{ url("booking-invoice/338") }}');
        });
    </script>
@endif

@if(session()->has('card_topup'))
    <script type="text/javascript">
        $(function () {
            swal({
                title: "Success",
                text: "{{ session()->get('card_topup') }}",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<a href='{{route('cards.topup.edit', session()->get('card_id'))}}'>Topup Details</a>",
                cancelButtonText: "<a href='{{route('topup.invoice', session()->get('card_id'))}}' id='topupinvoice'>Print</a>",
                closeOnConfirm: false, 
                customClass: "Custom_Cancel"
            })
        });
    </script>
    
    <script>
        document.getElementById('topupinvoice').addEventListener('click', function() {
            window.ReactNativeWebView.postMessage('{{ url("booking-invoice/338") }}');
        });
    </script>
@endif

@if(session()->has('booking_message'))
    <script type="text/javascript">
        $(function () {
            swal({
                title: "Success",
                text: "{{ session()->get('booking_message') }}",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<a href='{{ route('bookings.view', session()->get('booking_id')) }}'>Booking Details</a>",
                cancelButtonText: "<a href='{{ route('booking.invoice', session()->get('booking_id')) }}'>Print</a>",
                closeOnConfirm: false, 
                customClass: "Custom_Cancel"
            })
        });
    </script>
@endif

@endsection
