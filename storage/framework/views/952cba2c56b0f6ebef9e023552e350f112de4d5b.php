<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/userWithdraws.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .bank-details span {
            display: block;
            line-height: 18px;
        }
    </style>
    <div class="card mt-3">
        <div class="card-header">
            <h4><?php echo e(__('Customer Withdrawal Requests')); ?></h4>
        </div>
        <div class="card-body">
            <ul class="nav nav-pills border-b mb-3  ml-0">

                <li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#Section1" aria-controls="home"
                        role="tab" data-toggle="tab"><?php echo e(__('Pending')); ?><span
                            class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section2" role="tab"
                        data-toggle="tab"><?php echo e(__('Completed')); ?>

                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section3" role="tab"
                        data-toggle="tab"><?php echo e(__('Rejected')); ?>

                        <span class="badge badge-transparent "></span></a>
                </li>
            </ul>

            <div class="tab-content tabs" id="home">
                
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="pendingTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Request Number')); ?></th>
                                    <th><?php echo e(__('Customer ID')); ?></th>
                                     <th><?php echo e(__('Customer Name')); ?></th>
                                    <th><?php echo e(__('Amount & Status')); ?></th>
                                    <th><?php echo e(__('Fee')); ?></th>
                                    <th><?php echo e(__('Amount to Transfer')); ?></th>
                                    <th><?php echo e(__('Bank Details')); ?></th>
                                    <th><?php echo e(__('Placed On')); ?></th>
                                    <th><?php echo e(__('Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div role="tabpanel" class="row tab-pane" id="Section2">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="completedTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Request Number')); ?></th>
                                     <th><?php echo e(__('Customer ID')); ?></th>
                                      <th><?php echo e(__('Customer Name')); ?></th>
                                    <th><?php echo e(__('Amount & Status')); ?></th>
                                    <th><?php echo e(__('Fee')); ?></th>
                                    <th><?php echo e(__('Amount to Transfer')); ?></th>
                                    <th><?php echo e(__('Bank Details')); ?></th>
                                    <th><?php echo e(__('Summary')); ?></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div role="tabpanel" class="row tab-pane" id="Section3">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="rejectedTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Request Number')); ?></th>
                                    <th><?php echo e(__('Customer ID')); ?></th>
                                    <th><?php echo e(__('Customer Name')); ?></th>
                                    <th><?php echo e(__('Amount & Status')); ?></th>
                                    <th><?php echo e(__('Fee')); ?></th>
                                    <th><?php echo e(__('Amount to Transfer')); ?></th>
                                    <th><?php echo e(__('Bank Details')); ?></th>
                                    <th><?php echo e(__('Summary')); ?></th>
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
    
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><?php echo e(__('Reject Withdrawal')); ?></h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="rejectForm" autocomplete="off">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="rejectId" name="id">
                        <div class="form-group">
                            <label> <?php echo e(__('Summary')); ?></label>
                            <textarea rows="10" style="height:200px !important;" type="text" name="summary" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <div class="modal fade" id="completeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><?php echo e(__('Complete Withdrawal')); ?></h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="completeForm" autocomplete="off">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="completeId" name="id">
                        <div class="form-group">
                            <label> <?php echo e(__('Summary')); ?></label>
                            <textarea rows="10" style="height:200px !important;" type="text" name="summary" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/withdrawals/user.blade.php ENDPATH**/ ?>