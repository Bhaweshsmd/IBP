
<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/index.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
    <?php if(has_permission(session()->get('user_type'), 'view_bookings') && session()->get('user_type') != '20'): ?>
        <div class="card" style="background-color:white;">
            <div class="ml-4 my-3">
                <h5 class="d-inline" style="color: rgb(59, 58, 58);"><?php echo e(__('Bookings')); ?></h5>
                <a href="<?php echo e(route('bookings')); ?>"><span class="badge bg-primary text-white"><?php echo e(__('View All')); ?></span></a>
            </div>
            <div class="row col-12">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: green;">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <h4 class="font-18 mt-1 text-theme-color"><?php echo e(__('Today')); ?></h4>
                                            <div class="records-tab-div">
                                                <p class="text-grey">
                                                    <?php echo e(__('Total : ')); ?><strong><?php echo e($todayTotalBookings); ?></strong>
                                                </p>
                                                <p class="text-grey">
                                                    <?php echo e(__('Accepted : ')); ?><strong><?php echo e($todayTotalAcceptedBookings); ?></strong>
                                                </p>
                                                <p class="text-grey">
                                                    <?php echo e(__('Completed : ')); ?><strong><?php echo e($todayTotalCompletedBookings); ?></strong>
                                                </p>
                                                <p class="text-grey">
                                                    <?php echo e(__('Cancelled : ')); ?><strong><?php echo e($todayTotalCancelledBookings); ?></strong>
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
                                            <h4 class="font-18 mt-1 text-theme-color"><?php echo e(__('Last 7 Days')); ?></h4>
                                            <div class="records-tab-div">
                                                <p class="text-grey">
                                                    <?php echo e(__('Total : ')); ?><strong><?php echo e($last7daysTotalBookings); ?></strong>
                                                </p>
                                                <p class="text-grey">
                                                    <?php echo e(__('Accepted : ')); ?><strong><?php echo e($last7daysTotalAcceptedBookings); ?></strong>
                                                </p>
                                                <p class="text-grey">
                                                    <?php echo e(__('Completed : ')); ?><strong><?php echo e($last7daysTotalCompletedBookings); ?></strong>
                                                </p>
                                                <p class="text-grey">
                                                    <?php echo e(__('Cancelled : ')); ?><strong><?php echo e($last7daysTotalCancelledBookings); ?></strong>
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
                                            <h4 class="font-18 mt-1 text-theme-color"><?php echo e(__('Last 30 Days')); ?></h4>
                                            <div class="records-tab-div">
                                                <p class="text-grey">
                                                    <?php echo e(__('Total : ')); ?><strong><?php echo e($last30daysTotalBookings); ?></strong>
                                                </p>
                                                <p class="text-grey">
                                                    <?php echo e(__('Accepted : ')); ?><strong><?php echo e($last30daysTotalAcceptedBookings); ?></strong>
                                                </p>
                                                <p class="text-grey">
                                                    <?php echo e(__('Completed : ')); ?><strong><?php echo e($last30daysTotalCompletedBookings); ?></strong>
                                                </p>
                                                <p class="text-grey">
                                                    <?php echo e(__('Cancelled : ')); ?><strong><?php echo e($last30daysTotalCancelledBookings); ?></strong>
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
                                            <h4 class="font-18 mt-1 text-theme-color"><?php echo e(__('All Time')); ?></h4>
                                            <div class="records-tab-div">
                                                <p class="text-grey">
                                                    <?php echo e(__('Total : ')); ?><strong><?php echo e($allTimeTotalBookings); ?></strong>
                                                </p>
                                                <p class="text-grey">
                                                    <?php echo e(__('Accepted : ')); ?><strong><?php echo e($allTimeTotalAcceptedBookings); ?></strong>
                                                </p>
                                                <p class="text-grey">
                                                    <?php echo e(__('Completed : ')); ?><strong><?php echo e($allTimeTotalCompletedBookings); ?></strong>
                                                </p>
                                                <p class="text-grey">
                                                    <?php echo e(__('Cancelled : ')); ?><strong><?php echo e($allTimeTotalCancelledBookings); ?></strong>
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
    <?php endif; ?>
    
    <?php if(has_permission(session()->get('user_type'), 'view_earnings') && session()->get('user_type') != '20'): ?>
        <div class="card mt-4" style="background-color:white;">
            <div class="ml-4 my-3">
                <h5 class="d-inline" style="color: rgb(59, 58, 58);"><?php echo e(__('Platform Earnings')); ?></h5>
                <a href="<?php echo e(route('platform.earnings')); ?>"><span class="badge bg-primary text-white"><?php echo e(__('View All')); ?></span></a>
            </div>
            <div class="row col-12">
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0 0.5019 0.0053);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($todayEarnings, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Today')); ?></h5>
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
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($last7DaysEarnings, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Last 7 Days')); ?></h5>
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
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($last30DaysEarnings, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Last 30 Days')); ?></h5>
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
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($last90DaysEarnings, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Last 90 Days')); ?></h5>
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
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($last180DaysEarnings, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Last 180 Days')); ?></h5>
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
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($allTimeDaysEarnings, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('All Time')); ?></h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if(has_permission(session()->get('user_type'), 'view_balance') && session()->get('user_type') != '20'): ?>
        <div class="card mt-4" style="background-color:white;">
            <div class="ml-4 my-3">
                <h5 class="d-inline" style="color: rgb(59, 58, 58);"><?php echo e(__('Customer IBP Account Recharge')); ?></h5>
                <a href="<?php echo e(route('deposits')); ?>"><span class="badge bg-primary text-white"><?php echo e(__('View All')); ?></span></a>
            </div>
            <div class="row col-12">
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0 0.5019 0.0053);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($todayRecharges, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Today')); ?></h5>
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
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($last7DaysRecharges, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Last 7 Days')); ?></h5>
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
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($last30DaysRecharges, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Last 30 Days')); ?></h5>
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
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($last90DaysRecharges, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Last 90 Days')); ?></h5>
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
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($last180DaysRecharges, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Last 180 Days')); ?></h5>
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
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($allTimeRecharges, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('All Time')); ?></h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if(has_permission(session()->get('user_type'), 'view_card_topup') && session()->get('user_type') != '20'): ?>
        <div class="card mt-4" style="background-color:white;">
            <div class="ml-4 my-3">
                <h5 class="d-inline" style="color: rgb(59, 58, 58);"><?php echo e(__('Customer IBP Card Topups')); ?></h5>
                <a href="<?php echo e(route('cards.topups')); ?>"><span class="badge bg-primary text-white"><?php echo e(__('View All')); ?></span></a>
            </div>
            <div class="row col-12">

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0 0.5019 0.0053);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($todayTopups, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Today')); ?></h5>
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
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($last7DaysTopups, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Last 7 Days')); ?></h5>
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
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($last30DaysTopups, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Last 30 Days')); ?></h5>
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
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($last90DaysTopups, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Last 90 Days')); ?></h5>
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
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($last180DaysTopups, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Last 180 Days')); ?></h5>
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
                                            <h4 class="mb-2 "><?php echo e($settings->currency); ?><?php echo e(number_format($allTimeTopups, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('All Time')); ?></h5>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if(has_permission(session()->get('user_type'), 'view_withdrawal') && session()->get('user_type') != '20'): ?>
        <div class="card mt-4" style="background-color:white;">
            <div class="ml-4 my-3">
                <h5 class="d-inline" style="color: rgb(59, 58, 58);"><?php echo e(__('Customer Withdrawals')); ?></h5>
                <a href="<?php echo e(route('userWithdraws')); ?>"><span class="badge bg-primary text-white"><?php echo e(__('View All')); ?></span></a>
            </div>
            <div class="row col-12">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="card" style="background-color: color(srgb 0 0.5019 0.0053);">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 ">
                                        <div>
                                            <h4 class=""><?php echo e($settings->currency); ?><?php echo e(number_format($pendingUserPayouts, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Pending Withdrawals ')); ?></h5>
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
                                            <h4 class=""><?php echo e($settings->currency); ?><?php echo e(number_format($completedUserPayouts, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Completed Withdrawals ')); ?></h5>
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
                                            <h4 class=""><?php echo e($settings->currency); ?><?php echo e(number_format($rejectedUserPayouts, 2, '.', ',')); ?></h4>
                                            <h5 class="font-15 mt-1 text-grey"><?php echo e(__('Rejected Withdrawals ')); ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php if(session()->get('user_type') == '20'): ?>
    <style>
        .main-content{
            padding-left: 0px;
            padding-right: 0px;
        }
    </style>
    <div class="container-fluid card text-center pt-4">
        <div class="row">
            <div class="col-11 mb-3">
                <?php if(has_permission(session()->get('user_type'), 'view_scan_card')): ?>
                    <a data-toggle="modal" data-target="#scancard" href="" class="btn btn-primary" style="width: 100%; margin-left: 15px; font-size: 25px; padding: 15px 17px; font-weight: bold;"><?php echo e(__('Scan Card')); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="container-fluid card text-center pt-4 mt-4">
        <div class="row">
            <div class="col-6 mb-3">
                <?php if(has_permission(session()->get('user_type'), 'add_assign_card')): ?>
                    <a data-toggle="modal" data-target="#poscardassign" href="" class="btn btn-success" style="width: 200px; font-size: 18px; padding: 15px 17px;"><?php echo e(__('Assign Card')); ?></a>
                <?php endif; ?>
            </div>
            <div class="col-6 mb-3">
                <?php if(has_permission(session()->get('user_type'), 'add_card_topup')): ?>
                    <a data-toggle="modal" data-target="#poscardtopups" href="" class="btn btn-success" style="width: 200px; font-size: 18px; padding: 15px 17px;"><?php echo e(__('Topup Card')); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="container-fluid card text-center pt-4 mt-4">
        <div class="row">
            <div class="col-6 mb-3">
                <?php if(has_permission(session()->get('user_type'), 'view_bookings')): ?>
                    <a href="<?php echo e(route('bookings')); ?>" class="btn btn-info" style="width: 200px; font-size: 18px; padding: 15px 5px;">Booking <br> List</a>
                <?php endif; ?>
            </div>
            <div class="col-6 mb-3">
                <?php if(has_permission(session()->get('user_type'), 'view_card_topup')): ?>
                    <a href="<?php echo e(route('cards.assign')); ?>" class="btn btn-info" style="width: 200px; font-size: 18px; padding: 15px 5px;">Assigned Card <br> List</a>
                <?php endif; ?>
            </div>
            <div class="col-6 mb-3">
                <?php if(has_permission(session()->get('user_type'), 'view_assign_card')): ?>
                    <a href="<?php echo e(route('cards.topups')); ?>" class="btn btn-info" style="width: 200px; font-size: 18px; padding: 15px 5px;">Card Topups <br> List</a>
                <?php endif; ?>
            </div>
            <div class="col-6 mb-3">
                <?php if(has_permission(session()->get('user_type'), 'view_card_transactions')): ?>
                    <a href="<?php echo e(route('card.transactions')); ?>" class="btn btn-info" style="width: 200px; font-size: 18px; padding: 15px 5px;">Card <br> Transactions</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="modal fade" id="poscardassign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5><?php echo e(__('Assign New Card')); ?></h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="<?php echo e(route('assign.cards.store')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="form-group">
                        <label><?php echo e(__('Select User')); ?></label>
                        <select name="user_id" class="form-control form-control-sm" placeholder="Select User">
                            <option value="">Select User</option>
                            <?php if(!empty($users)): ?>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($val->id); ?>"><?php echo e($val->first_name); ?> <?php echo e($val->last_name); ?> (<?php echo e($val->email); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label> <?php echo e(__('Select Cards')); ?></label>
                        <select name="card_id" class="form-control form-control-sm" placeholder="Select Card">
                            <option value="">Select Card</option>
                            <?php if(!empty($cards)): ?>
                                <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($val->id); ?>"><?php echo e(chunk_split($val->card_number, 4, ' ')); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group text-right">
                        <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Assign')); ?>">
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
                <h5><?php echo e(__('New Card Topup')); ?></h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="<?php echo e(route('cards.topups.store')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="form-group">
                        <label> <?php echo e(__('Select Card')); ?></label>
                        <select name="card_id" class="form-control form-control-sm" placeholder="Select Card">
                            <option value="">Select Card</option>
                            <?php if(!empty($assigncards)): ?>
                                <?php $__currentLoopData = $assigncards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php 
                                    $user = DB::table('users')->where('id', $val->assigned_to)->first();
                                ?>
                                    <option value="<?php echo e($val->id); ?>"><?php echo e(chunk_split($val->card_number, 4, ' ')); ?> (<?php echo e($user->email); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label><?php echo e(__('Enter Amount')); ?></label>
                        <input class="form-control" type="number" id="icon" name="amount" required>
                    </div>

                    <div class="form-group text-right">
                        <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>">
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

<?php if(session()->has('card_assign')): ?>
    <script type="text/javascript">
        $(function () {
            swal({
                title: "Success",
                text: "<?php echo e(session()->get('card_assign')); ?>",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<a href='<?php echo e(route('cards.edit', session()->get('card_id'))); ?>'>Card Details</a>",
                cancelButtonText: "<a href='<?php echo e(route('assign.invoice', session()->get('card_id'))); ?>' id='assigninvoice'>Print</a>",
                closeOnConfirm: false, 
                customClass: "Custom_Cancel"
            })
        });
    </script>
    
    <script>
        document.getElementById('assigninvoice').addEventListener('click', function() {
            window.ReactNativeWebView.postMessage('<?php echo e(url("booking-invoice/338")); ?>');
        });
    </script>
<?php endif; ?>

<?php if(session()->has('card_topup')): ?>
    <script type="text/javascript">
        $(function () {
            swal({
                title: "Success",
                text: "<?php echo e(session()->get('card_topup')); ?>",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<a href='<?php echo e(route('cards.topup.edit', session()->get('card_id'))); ?>'>Topup Details</a>",
                cancelButtonText: "<a href='<?php echo e(route('topup.invoice', session()->get('card_id'))); ?>' id='topupinvoice'>Print</a>",
                closeOnConfirm: false, 
                customClass: "Custom_Cancel"
            })
        });
    </script>
    
    <script>
        document.getElementById('topupinvoice').addEventListener('click', function() {
            window.ReactNativeWebView.postMessage('<?php echo e(url("booking-invoice/338")); ?>');
        });
    </script>
<?php endif; ?>

<?php if(session()->has('booking_message')): ?>
    <script type="text/javascript">
        $(function () {
            swal({
                title: "Success",
                text: "<?php echo e(session()->get('booking_message')); ?>",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<a href='<?php echo e(route('bookings.view', session()->get('booking_id'))); ?>'>Booking Details</a>",
                cancelButtonText: "<a href='<?php echo e(route('booking.invoice', session()->get('booking_id'))); ?>'>Print</a>",
                closeOnConfirm: false, 
                customClass: "Custom_Cancel"
            })
        });
    </script>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/dashboard/index.blade.php ENDPATH**/ ?>