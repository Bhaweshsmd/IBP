<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/bookings.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card mt-3">
        <div class="card-header">
            <h4><?php echo e(__('Bookings')); ?></h4>
        </div>
        <div class="card-body">

            <ul class="nav nav-pills border-b mb-3  ml-0">

                <li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#allBookingSection"
                        aria-controls="home" role="tab" data-toggle="tab"><?php echo e(__('All Bookings')); ?><span
                            class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#acceptedBookingSection"
                        role="tab" data-toggle="tab"><?php echo e(__('Accepted')); ?>

                        <span class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#completedBookingSection"
                        role="tab" data-toggle="tab"><?php echo e(__('Completed')); ?>

                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#cancelledBookingSection"
                        role="tab" data-toggle="tab"><?php echo e(__('Cancelled')); ?>

                        <span class="badge badge-transparent "></span></a>
                </li>
            </ul>

            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="allBookingSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="allBookingsTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Booking Number')); ?></th>
                                    <th><?php echo e(__('Service Name')); ?></th>
                                    <th><?php echo e(__('Customer ID')); ?></th>
                                    <th><?php echo e(__('Full Name')); ?></th>
                                    <th><?php echo e(__('Contact')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Date & Time')); ?></th>
                                    <th><?php echo e(__('Service Amount')); ?></th>
                                    <th><?php echo e(__('Counpon Discount')); ?></th>
                                    <th><?php echo e(__('Subtotal')); ?></th>
                                    <th><?php echo e(__('Payable Amount')); ?></th>
                                    <th><?php echo e(__('Order Date')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="row tab-pane" id="pendingBookingSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="pendingBookingsTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Booking Number')); ?></th>
                                    <th><?php echo e(__('Customer ID')); ?></th>
                                    <th><?php echo e(__('Full Name')); ?></th>
                                    <th><?php echo e(__('Contact')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Date & Time')); ?></th>
                                    <th><?php echo e(__('Service Amount')); ?></th>
                                    <th><?php echo e(__('Counpon Discount')); ?></th>
                                    <th><?php echo e(__('Subtotal')); ?></th>
                                    <th><?php echo e(__('Payable Amount')); ?></th>
                                    <th><?php echo e(__('Order Date')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="row tab-pane" id="acceptedBookingSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="acceptedBookingsTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Booking Number')); ?></th>
                                    <th><?php echo e(__('Customer ID')); ?></th>
                                    <th><?php echo e(__('Full Name')); ?></th>
                                    <th><?php echo e(__('Contact')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Date & Time')); ?></th>
                                    <th><?php echo e(__('Service Amount')); ?></th>
                                    <th><?php echo e(__('Counpon Discount')); ?></th>
                                    <th><?php echo e(__('Subtotal')); ?></th>
                                    <th><?php echo e(__('Payable Amount')); ?></th>
                                    <th><?php echo e(__('Order Date')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="row tab-pane" id="completedBookingSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="completedBookingsTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Booking Number')); ?></th>
                                    <th><?php echo e(__('Customer ID')); ?></th>
                                    <th><?php echo e(__('Full Name')); ?></th>
                                    <th><?php echo e(__('Contact')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Date & Time')); ?></th>
                                    <th><?php echo e(__('Service Amount')); ?></th>
                                    <th><?php echo e(__('Counpon Discount')); ?></th>
                                    <th><?php echo e(__('Subtotal')); ?></th>
                                    <th><?php echo e(__('Payable Amount')); ?></th>
                                    <th><?php echo e(__('Order Date')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="row tab-pane" id="cancelledBookingSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="cancelledBookingsTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Booking Number')); ?></th>
                                    <th><?php echo e(__('Customer ID')); ?></th>
                                    <th><?php echo e(__('Full Name')); ?></th>
                                   <th><?php echo e(__('Contact')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Date & Time')); ?></th>
                                    <th><?php echo e(__('Service Amount')); ?></th>
                                    <th><?php echo e(__('Counpon Discount')); ?></th>
                                    <th><?php echo e(__('Subtotal')); ?></th>
                                    <th><?php echo e(__('Payable Amount')); ?></th>
                                    <th><?php echo e(__('Order Date')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="row tab-pane" id="declinedBookingSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="declinedBookingsTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Booking Number')); ?></th>
                                    <th><?php echo e(__('Customer ID')); ?></th>
                                    <th><?php echo e(__('Full Name')); ?></th>
                                    <th><?php echo e(__('Contact')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Date & Time')); ?></th>
                                    <th><?php echo e(__('Service Amount')); ?></th>
                                    <th><?php echo e(__('Counpon Discount')); ?></th>
                                    <th><?php echo e(__('Subtotal')); ?></th>
                                    <th><?php echo e(__('Payable Amount')); ?></th>
                                    <th><?php echo e(__('Order Date')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/bookings/index.blade.php ENDPATH**/ ?>