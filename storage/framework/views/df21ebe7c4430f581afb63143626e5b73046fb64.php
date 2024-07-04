<?php $page = 'booking-order-confirm'; ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
            <?php echo e(__('string.booking_review')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?>
            <?php echo e(__('string.home')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?>
            <?php echo e(__('string.booking_review')); ?>

        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <section class="booking-steps py-30">
        <div class="container">
            <ul class="d-lg-flex justify-content-center align-items-center">
                <li>
                    <h5><a href="<?php echo e(route('booking-details',$services->slug)); ?>"><span>1</span><?php echo e(__('string.book_item')); ?></a></h5>
                </li>
                <li class="active">
                    <h5><a href="<?php echo e(url('booking-order-confirm')); ?>"><span>2</span><?php echo e(__('string.booking_review')); ?></a></h5>
                </li>
                <li>
                    <h5><a href="<?php echo e(url('booking-checkout')); ?>"><span>3</span><?php echo e(__('string.payment')); ?></a></h5>
                </li>
            </ul>
        </div>
    </section>

    <div class="content book-cage">
        <div class="container">
            <section class="card mb-40">
                <div class="text-center mb-10">
                    <h3 class=""><?php echo e(__('string.booking_review')); ?></h3>
                    <p class="sub-title mb-0"><?php echo e(__('string.review_your_order')); ?></p>
                </div>
            </section>
            <section class="card booking-order-confirmation">
                <h5 class="mb-3"><?php echo e(__('string.booking_details')); ?></h5>
                <ul class="booking-info d-lg-flex justify-content-between align-items-center">
                    <li>
                        <h6><?php echo e(__('string.item_name')); ?></h6>
                        <p><?php echo e($services->title); ?></p>
                    </li>
                    <li>
                        <h6><?php echo e(__('string.quantity')); ?></h6>
                        <p><?php echo e($quantity); ?></p>
                    </li>
                    <li>
                        <h6><?php echo e(__('string.appointment_date')); ?></h6>
                        <p><?php echo e(date('D, d F Y',strtotime($date))); ?></p>
                    </li>
                    <li>
                        <h6><?php echo e(__('string.appointment_time')); ?></h6>
                        <p><?php echo e(date('h:i A', strtotime($booking_time))); ?></p>
                    </li>
                    <li>
                        <h6><?php echo e(__('string.booking_hour')); ?></h6>
                        <p><?php echo e($booking_hours); ?></p>
                    </li>
                    
                </ul>
                <h5 class="mb-3"><?php echo e(__('string.contact_information')); ?></h5>
                <ul class="contact-info d-lg-flex justify-content-start align-items-center">
                    <li>
                        <h6><?php echo e(__('string.name')); ?></h6>
                        <p><?php echo e($user_details->first_name??''); ?>&nbsp;&nbsp;<?php echo e($user_details->last_name??''); ?></p>
                    </li>
                    <li>
                        <h6><?php echo e(__('string.contact_email_address')); ?></h6>
                        <p><?php echo e($user_details->email??''); ?></p>
                    </li>
                    <li>
                        <h6><?php echo e(__('string.phone_number')); ?> </h6>
                        <p><?php echo e($user_details->formated_number??''); ?></p>
                    </li>
                </ul>
                <h5 class="mb-3"><?php echo e(__('string.payment_information')); ?></h5>
                <ul class="payment-info d-lg-flex justify-content-start align-items-center">
                    <li>
                        <h6><?php echo e(__('string.item_price')); ?></h6>
                        <p class="primary-text">(<?php echo e($settings->currency); ?><?php echo e(number_format($services->price-($services->price*$services->discount)/100,2)); ?> X <?php echo e(Session::get('booking_hours')); ?> Hrs)</p>
                    </li>
                    <li>
                        <h6>Members</h6>
                        <p class="primary-text"><?php echo e($quantity); ?></p>
                    </li>
                    <li>
                        <h6><?php echo e(__('string.total')); ?></h6>
                        <?php if($services->service_type): ?>
                            <p class="primary-text"><?php echo e($settings->currency); ?><?php echo e(number_format(($services->price*Session::get('booking_hours')-($services->price*Session::get('booking_hours')*$services->discount)/100),2)); ?></p>
                        <?php else: ?>
                            <p class="primary-text"><?php echo e($settings->currency); ?><?php echo e(number_format(($services->price*Session::get('booking_hours')-($services->price*Session::get('booking_hours')*$services->discount)/100)*$quantity,2)); ?></p>
                        <?php endif; ?>
                    </li>
                </ul>
            </section>
            <div class="text-center btn-row">
                <a class="btn btn-primary me-3 btn-icon" href="<?php echo e(route('booking-details',$services->slug)); ?>"><i class="feather-arrow-left-circle me-1"></i> <?php echo e(__('string.back')); ?></a>
                <a class="btn btn-secondary btn-icon" href="<?php echo e(url('booking-checkout')); ?>"><?php echo e(__('string.next')); ?> <i class="feather-arrow-right-circle ms-1"></i></a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/web/bookings/order-confirm.blade.php ENDPATH**/ ?>