<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/viewBookingDetails.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(asset('asset/style/viewBookingDetails.css')); ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php $__env->stopSection(); ?>
<?php
    use App\Models\Constants as Constants;
    use App\Models\GlobalFunction as GlobalFunction;
?>
<?php $__env->startSection('content'); ?>
    <style>
        .coupon-text {
            padding: 0px 5px;
            border-radius: 5px;
        }
        
        .swal2-cancel{
          color:#fff !important;   
        }
    </style>

    <input type="hidden" value="<?php echo e($booking->id); ?>" id="bookingId">
    <input type="hidden" value="<?php echo e($booking->completion_otp); ?>" id="completionOtp">
    <input type="hidden" value="<?php echo e($booking->booking_id); ?>" id="bookingIdBig">
    
    <?php if(has_permission(session()->get('user_type'), 'edit_Bookings')): ?>
        <div class="row flex-column flex-xl-row mt-2">
            <div class="card col-12 mr-2">
                <div class="card-header">
                    <h4 class="d-inline">
                        <?php echo e(__('Change Booking Status')); ?>

                    </h4>
                    <a  href="<?php echo e(route('bookings')); ?>" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> Back to Booking List</a>
                </div>
                <div class="card-body">
                    <select class="form-control" name="weekday" value="" id="changeStatus" required="">
                        <option value=""><?php echo e(__('Change Booking Status')); ?></option>
                        <option value="2"  <?php if($booking->status == Constants::orderCompleted): ?> selected  <?php endif; ?>><?php echo e(__('Completed')); ?></option>
                        <option value="4" <?php if($booking->status == Constants::orderCancelled): ?> selected  <?php endif; ?>><?php echo e(__('Cancelled')); ?></option>
                    </select>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="row flex-column flex-xl-row mt-2">

        <div class="card col mr-2">
            <div class="card-header">
                <h4 class="d-inline">
                    <?php echo e($booking->booking_id); ?>

                </h4>

                <?php if($booking->status == Constants::orderPlacedPending): ?>
                    <span class="badge bg-warning text-white "><?php echo e(__('Waiting For Confirmation')); ?> </span>
                <?php elseif($booking->status == Constants::orderAccepted): ?>
                    <span class="badge bg-info text-white "><?php echo e(__('Accepted')); ?> </span>
                <?php elseif($booking->status == Constants::orderCompleted): ?>
                    <span class="badge bg-success text-white "><?php echo e(__('Completed')); ?> </span>
                <?php elseif($booking->status == Constants::orderDeclined): ?>
                    <span class="badge bg-danger text-white "><?php echo e(__('Declined')); ?> </span>
                <?php elseif($booking->status == Constants::orderCancelled): ?>
                    <span class="badge bg-danger text-white "><?php echo e(__('Cancelled')); ?> </span>
                <?php endif; ?>

            </div>
            <div class="card-body">
                <div class="">
                    <div class="mt-3">
                        <label class="mb-1 text-grey d-block" for=""><?php echo e(__('Customer')); ?></label>
                        <div class="d-flex align-items-center card-profile">
                            <?php if($booking->user->profile_image != null): ?>
                                <img class="rounded owner-img-border mr-2" width="80" height="80" src="<?php echo e(url('public/storage/'.$booking->user->profile_image)); ?>" alt="">
                            <?php else: ?>
                                <img class="rounded owner-img-border mr-2" width="80" height="80" src="http://placehold.jp/150x150.png" alt="">
                            <?php endif; ?>

                            <div>
                                <p class="mt-0 mb-0 p-data"><?php echo e($booking->user->first_name); ?> <?php echo e($booking->user->last_name); ?></p>
                                <span class="mt-0 mb-0"><?php echo e($booking->user->email != null ? $booking->user->email : ''); ?></span> <br>
                                <span class="mt-0 mb-0"><?php echo e($booking->user->formated_number != null ? $booking->user->formated_number : ''); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="mb-1 text-grey d-block" for=""><?php echo e(__('Feedback')); ?></label>
                        <div class="card-profile align-items-center">
                            <div>
                                <?php if($booking->rating != null): ?>
                                    <?php echo $ratingBar; ?>

                                    <br>
                                    <span class="mt-0 mb-0"><?php echo e($booking->rating->comment); ?></span><br>
                                <?php else: ?>
                                    <p class="mt-0 mb-0 p-data"><?php echo e(__('No Feedback')); ?></p><br>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card col ml-2">
            <div class="card-header">
                <h4 class="d-inline">
                    <?php echo e(__('Details')); ?>

                </h4>
                
                <a class="ml-auto" href="<?php echo e(route('booking.invoice', $booking->id)); ?>">
                    <span class="badge bg-warning text-white "><?php echo e(__('Print')); ?> </span>
                </a>
            </div>
            <div class="card-body" id="details-body">

                <div class="d-flex">
                    <div class="col p-0">
                        <label class="text-grey d-block mb-0" for=""><?php echo e(__('Booking Number')); ?></label>
                        <div class="card-profile align-items-center">
                            <div>
                                <p class="mt-0 mb-0 p-data"><?php echo e($booking->booking_id); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col p-0 text-right">
                        <label class="text-grey d-block mb-0" for=""><?php echo e(__('Booking Date')); ?></label>
                        <div class="card-profile align-items-center">
                            <div>
                                <p class="mt-0 mb-0 p-data"><?php echo e($booking->created_at); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="text-grey d-block mb-0" for=""><?php echo e(__('Appointment Schedule')); ?></label>
                    <div class="card-profile align-items-center">
                        <div>
                            <p class="p-data"><span class="mt-0 mb-0"><?php echo e(__('Date')); ?>: <?php echo e($booking->date); ?></span> |
                                <span class="mt-0 mb-0"><?php echo e(__('Time')); ?>: <?php echo e(date('h:i A',strtotime($booking->time))); ?></span> |
                                <span class="mt-0 mb-0"><?php echo e(__('Duration')); ?>: <?php echo e($booking->booking_hours); ?> Hrs</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 p-0 mt-3">
                    <label class="text-grey d-block mb-0" for=""><?php echo e(__('Customer')); ?></label>
                    <div class="card-profile align-items-center">
                        <div>
                            <p class="mt-0 mb-0 p-data"><?php echo e($booking->user->first_name); ?> <?php echo e($booking->user->last_name); ?></p>
                            <p class="p-data">
                             <span class="mt-0 mb-0"><?php echo e($booking->user->email != null ? $booking->user->email : ''); ?></span><br>
                             <span class="mt-0 mb-0"> <?php echo e($booking->user->formated_number != null ? $booking->user->formated_number : ''); ?></span>
                            </p>
                            
                        </div>
                    </div>
                </div>

                <div id="payment-details-body " class="mt-3">

                    <?php $__currentLoopData = $bookingSummary['services']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="invoice-item">
                            <div class="d-flex">
                                <p><?php echo e($item['title']); ?></p>
                            </div>
                            <p><?php echo e($settings->currency); ?><?php echo e(number_format( $item['price'],2)); ?>/hr</p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="invoice-item ">
                        <div class="d-flex">
                            <p><?php echo e(__('Discounted Price')); ?></p>
                        </div>
                        <p><?php echo e($settings->currency); ?><?php echo e(number_format($booking->service_amount,2)); ?>/hr</p>
                    </div>
                    <div class="invoice-item ">
                        <div class="d-flex">
                            <p><?php echo e(__('Duration')); ?></p>
                        </div>
                        <p><?php echo e($booking->booking_hours); ?> Hrs</p>
                    </div>
                        <div class="invoice-item ">
                        <div class="d-flex">
                            <p><?php echo e(__('Members')); ?></p>
                        </div>
                        <p><?php echo e($booking->quantity); ?></p>
                    </div>

                    <?php if(!empty($bookingSummary['coupon_apply']) && $bookingSummary['coupon_apply'] == 1): ?>
                        <div class="invoice-item ">
                            <div class="d-flex">
                                <p><?php echo e(__('Coupon Discount')); ?></p>
                                <p class="ml-2 bg-dark text-white coupon-text"><?php echo e($bookingSummary['coupon']['coupon']); ?>

                                </p>
                            </div>
                            <p><?php echo e($settings->currency); ?><?php echo e(number_format($bookingSummary['discount_amount'],2)); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="invoice-item ">
                        <div class="d-flex">
                            <p><?php echo e(__('Subtotal')); ?></p>
                        </div>
                        <p><?php echo e($settings->currency); ?><?php echo e(number_format($booking->subtotal,2)); ?></p>
                    </div>

                    <?php $__currentLoopData = $bookingSummary['taxes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="invoice-item">
                            <div class="d-flex">
                                <?php if($item['type'] == Constants::taxPercent): ?>
                                    <p><?php echo e($item['tax_title']); ?>(<?php echo e(__('Included')); ?>) </p>
                                <?php else: ?>
                                    <p><?php echo e($item['tax_title']); ?></p>
                                <?php endif; ?>
                            </div>
                            <p> <?php echo e($item['value']); ?>% </p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="invoice-item ">
                        <div class="d-flex">
                            <p class="text-white"><?php echo e(__('Payable Amount')); ?></p>
                        </div>
                        <p class="text-white"><?php echo e($settings->currency); ?><?php echo e($booking->payable_amount); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if(session()->has('booking_cancel')): ?>
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Warning",
                    text: "<?php echo e(session()->get('booking_cancel')); ?>",
                    type: "warning",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/bookings/view.blade.php ENDPATH**/ ?>