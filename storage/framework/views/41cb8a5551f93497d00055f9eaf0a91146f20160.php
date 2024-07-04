<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/salons.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card mt-3">
        <div class="card-header">
            <h4><?php echo e(__('Company Profile')); ?></h4>
        </div>
        <div class="card-body">
            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="activeSalonTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Company ID')); ?></th>
                                    <th><?php echo e(__('Company Name')); ?></th>
                                    <th><?php echo e(__('Lifetime Earnings')); ?></th>
                                    <th><?php echo e(__('Contact')); ?></th>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/platforms/index.blade.php ENDPATH**/ ?>