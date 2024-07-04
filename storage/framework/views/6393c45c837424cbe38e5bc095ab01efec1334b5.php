<?php $page = 'booking-checkout';
  
    $userDetails= getUserDetails(Session::get('user_id'));
    $globalsetting=fetchGlobalSettings();
    
    $taxes=$globalsetting->taxes

?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
            <?php echo e(__('string.appointment_details')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?>
            <?php echo e(__('string.home')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?>
            <?php echo e(__('string.appointment_details')); ?>

        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
  
    <div class="content">
        <div class="container">
            <section class="">
                <div class="text-center mb-4">
                    <h3 class=""><?php echo e(__('string.appointment_booked')); ?></h3>
                </div>
                <div class="row checkout text-center  ">
                   
                    <div class="card-design">
                        <aside class="card payment-modes payment-type ">
                            <h6 class="text-center"><?php echo e(__('string.appointment_id')); ?></h6>
                            <span class="text-center mb-5">IBPBYKX759980MNU</span>
                       
                            <ul class="order-sub-total">
                                  <li>
                                    <p><?php echo e(__('string.item')); ?></p>
                                    <h6><?php echo e($booking_details->service->title); ?></h6>
                                </li>
                                    <li>
                                    <p><?php echo e(__('string.date')); ?></p>
                                    <h6><?php echo e(date('D, d F Y',strtotime($booking_details->date))); ?></h6>
                                </li>
                                <li>
                                    <p><?php echo e(__('string.time')); ?></p>
                                    <h6><?php echo e(date('h:i A', strtotime($booking_details->time))); ?></h6>
                                </li>
                                <li>
                                    <p><?php echo e(__('string.booking_hour')); ?></p>
                                    <h6>
                                        <?php if($booking_details->booking_hours==16): ?>
                                          <?php echo e(__('string.whole_day')); ?>

                                        <?php else: ?>
                                        <?php echo e($booking_details->booking_hours); ?>

                                        <?php endif; ?>
                                    </h6>
                                </li>
                                <li>
                                    <p><?php echo e(__('string.quantity')); ?></p>
                                    <h6><?php echo e($booking_details->quantity); ?></h6>
                                </li>
                                <li>
                                    <p><?php echo e(__('string.item_price')); ?></p>
                                    <h6><?php echo e($settings->currency); ?><?php echo e(number_format($booking_details->service_amount,2)); ?>/<?php echo e(__('string.hrs')); ?></h6>
                                </li>
                              
                                <li>
                                    <p><?php echo e(__('string.total_amount')); ?></p>
                                    <h6><?php echo e($settings->currency); ?><?php echo e(number_format($booking_details->payable_amount,2)); ?></h6>
                                </li>
                            </ul>
                           
                             <div class="form-check d-flex justify-content-center align-items-center mt-5">
                              
                                <h6><?php echo e(__('string.your_appointment_has')); ?></h6>
                                
                            </div>
                            <div class="form-check d-flex justify-content-start align-items-center mb-4">
                                <label class="form-check-label" for="policy"><?php echo e(__('string.check_appointments_tab')); ?></label>
                            </div>
                            <div class="d-grid btn-block">
                                <a href="<?php echo e(route('user-bookings')); ?>"  type="button" class="btn btn-primary btn-md"  ><?php echo e(__('string.my_bookings')); ?> </a>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <?php $__env->startComponent('components.modalpopup'); ?>
    <?php echo $__env->renderComponent(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/web/bookings/success.blade.php ENDPATH**/ ?>