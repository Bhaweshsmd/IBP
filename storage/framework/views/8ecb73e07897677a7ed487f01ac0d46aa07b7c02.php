<?php $page = 'booking-checkout';
  
    $userDetails= getUserDetails(Session::get('user_id'));
    $globalsetting=fetchGlobalSettings();
    $taxes=$globalsetting->taxes;
?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
            <?php echo e(__('string.booking_payment')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?>
            <?php echo e(__('string.home')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?>
            <?php echo e(__('string.booking_payment')); ?>

        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <style>
        .couponLists{
            display: flex;
    justify-content: space-between;
    align-items: center;
        }
      .couponCard{
            border: 1px solid;
           padding: 15px;
           border-radius: 10px;
           border-color: #eee;
        }
    </style>
    <section class="booking-steps py-30">
        <div class="container">
            <ul class="d-lg-flex justify-content-center align-items-center list-unstyled">
                <li>
                    <h5><a href="<?php echo e(route('booking-details',$services->slug)); ?>"><span>1</span><?php echo e(__('string.book_item')); ?></a></h5>
                </li>
                <li>
                    <h5><a href="<?php echo e(url('booking-order-confirm')); ?>"><span>2</span><?php echo e(__('string.booking_review')); ?></a></h5>
                </li>
                <li class="active">
                    <h5><a href="<?php echo e(url('booking-checkout')); ?>"><span>3</span><?php echo e(__('string.payment')); ?></a></h5>
                </li>
            </ul>
        </div>
    </section>

    <!-- Page Content -->
    <div class="content">
        <div class="container">
          <section class=" ">
                <div class="card text-center mb-40">
                    <h3 class=""><?php echo e(__('string.payment')); ?></h3>
                    <p class="sub-title mb-0"><?php echo e(__('string.complete_payment')); ?> </p>
                </div>
            
                <div class="row checkout">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-7">
                        <div class="card booking-details">
                            <h3 class="border-bottom"><?php echo e(__('string.order_summary')); ?></h3>
                            <ul class="list-unstyled">
                                <li><i class="fa-regular fa-building me-2"></i><?php echo e($services->title); ?><span
                                        class="x-circle"></span></li>
                                <li><i class="feather-calendar me-2"></i><?php echo e(date('D, d F Y',strtotime($date))); ?></li>
                                <li><i class="feather-clock me-2"></i><?php echo e(date('h:i A',strtotime($booking_time))); ?></li>
                                <li><i class="feather-users me-2"></i><?php echo e(Session::get('quantity')); ?> Members</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-5">
                        <aside class="card payment-modes">
                            <h3 class="border-bottom"><?php echo e(__('string.checkout')); ?></h3>
                            <div class="radio">
                             
                                <div class="form-check form-check-inline active">
                                    <input class="form-check-input default-check me-2" type="radio"
                                        name="inlineRadioOptions" id="inlineRadio3" value="Wallet" checked>
                                    <label class="form-check-label" for="inlineRadio3">IBP Account <b> <?php echo e($settings->currency); ?><?php echo e(number_format($userDetails->wallet,2)); ?></b></label>
                                </div>
                            </div>
                            <hr>
                            <ul class="order-sub-total">
                                  <li>
                                    <p><?php echo e(__('string.item_price')); ?></p>
                                    <h6><?php echo e($settings->currency); ?><?php echo e(number_format(($services->price-($services->price*$services->discount)/100),2)); ?>/<?php echo e(__('string.hrs')); ?></h6>
                                </li>
                                <li>
                                    <p><?php echo e(__('string.quantity')); ?></p>
                                    <h6><?php echo e($quantity); ?></h6>
                                </li>
                                 <li>
                                    <p><?php echo e(__('string.booking_hours')); ?></p>
                                    <h6><?php echo e($booking_hours); ?></h6>
                                </li>
                                <li>
                                    <p><?php echo e(__('string.sub_total')); ?></p>
                                   <?php if($services->service_type): ?>
                                        <h5><?php echo e($settings->currency); ?><?php echo e(number_format(($services->price*Session::get('booking_hours')-($services->price*Session::get('booking_hours')*$services->discount)/100),2)); ?></h5>
                                 
                                   <?php else: ?>
                                       <h5><?php echo e($settings->currency); ?><?php echo e(number_format(($services->price*Session::get('booking_hours')-($services->price*Session::get('booking_hours')*$services->discount)/100)*$quantity,2)); ?></h5>
                                      <?php endif; ?>                                </li>
                           
                                <li>
                                    <p><?php echo e(__('string.tax_included')); ?></p>
                                    <h6><?php echo e($taxes->value); ?>%</h6>
                                </li>
                            </ul>
                            <div class="order-total d-flex justify-content-between align-items-center">
                                <h5><?php echo e(__('string.order_total')); ?></h5>
                                 <?php if($services->service_type): ?>
                                <h5><?php echo e($settings->currency); ?><?php echo e(number_format(($services->price*Session::get('booking_hours')-($services->price*Session::get('booking_hours')*$services->discount)/100),2)); ?></h5>
                                 
                                 <?php else: ?>
                                <h5><?php echo e($settings->currency); ?><?php echo e(number_format(($services->price*Session::get('booking_hours')-($services->price*Session::get('booking_hours')*$services->discount)/100)*$quantity,2)); ?></h5>
                                <?php endif; ?>
                            </div>
                            <?php if(count($allcoupons)): ?>
                            <ul class="order-sub-total couponCard">
                                  <li>
                                    <p><?php echo e(__('Coupons')); ?></p>
                                    <a href="#" class="btn btn-primary balance-add applyBtn" data-bs-toggle="modal" data-bs-target="#add-payment">View All</a>
                                 </li>
                                 <?php $__currentLoopData = $allcoupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                   <div class="couponList">
                                   <li >
                                     <p class="couponCode"><?php echo e(__($coupon->coupon)); ?></p>
                                     <p><?php echo e(__($coupon->percentage)); ?>% OFF</p>
                                     <a href="javascript:void(0)" class="btn applyBtn btn-primary applyCoupon"  data-id="<?php echo e($coupon->id); ?>"><?php echo e(__('Apply Coupon')); ?></a>
                                 </li>
                                 <span class="couponHeading"><?php echo e(__($coupon->heading)); ?></span>
                                 </div>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>    
                            <?php endif; ?>
                            <div class="form-check d-flex justify-content-start align-items-center policy">
                                <div class="d-inline-block">
                                    <input class="form-check-input" type="checkbox"  id="policy">
                                </div>
                                <label class="form-check-label" for="policy"><?php echo e(__('string.by_clicking')); ?> <a href="<?php echo e(route('web.pages', 'privacy-policy')); ?>"><u><?php echo e(__('string.privacy_policy')); ?></u></a> and <a
                                        href="<?php echo e(route('web.pages', 'terms-of-use')); ?>"><u><?php echo e(__('string.terms_of_use')); ?></u></a></label>
                            </div>
                            <div class="d-grid btn-block">
                                <a href="javascript:void(0)"  type="button" class="btn btn-primary"  id="WalletCheckout" ><?php echo e(__('string.proceed')); ?> <?php echo e($settings->currency); ?><span class="amount_after_discount"><?php echo e(number_format($totalBookValue,2)); ?></span></a>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>
        </div>
        <!-- /Container -->
    </div>
    <!-- /Page Content -->
    <div class="modal custom-modal fade payment-modal " id="add-payment"  aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">All Coupon</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                <?php if(count($allcoupons)): ?>
                            <ul class="order-sub-total">
                                 <?php $__currentLoopData = $allcoupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                   <div class="couponList">
                                   <li class="couponLists" >
                                     <p class="couponCode"><?php echo e(__($coupon->coupon)); ?></p>
                                     <p><?php echo e(__($coupon->percentage)); ?>% OFF</p>
                                     <a href="javascript:void(0)" class="btn applyBtn btn-primary applyCoupon"  data-id="<?php echo e($coupon->id); ?>"><?php echo e(__('Apply Coupon')); ?></a>
                                 </li>
                                 <span class="couponHeading"><?php echo e(__($coupon->heading)); ?></span>
                                 </div>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>    
                            <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                            <a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</a>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/web/bookings/checkout.blade.php ENDPATH**/ ?>