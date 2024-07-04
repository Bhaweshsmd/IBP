<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/services.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card mt-3">
        <div class="card-header">
            <h4><?php echo e(__('Services')); ?></h4>
            <?php if(has_permission(session()->get('user_type'), 'add_services')): ?>
                <a href="<?php echo e(route('addService')); ?>" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> Add New Service</a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="servicesTable">
                    <thead>
                        <tr>
                            <th><?php echo e(__('Sr. No.')); ?></th>
                            <th><?php echo e(__('Item Code')); ?></th>
                            <th><?php echo e(__('Image')); ?></th>
                            <th><?php echo e(__('Name')); ?></th>
                            <th><?php echo e(__('Category')); ?></th>
                            <th><?php echo e(__('Time')); ?><br><?php echo e(__('(Minutes)')); ?></th>
                            <th><?php echo e(__('Price')); ?><br><?php echo e(__('(Bonaire Resident)')); ?></th>
                            <th><?php echo e(__('Discount')); ?> <br><?php echo e(__('(Bonaire Resident)')); ?></th>
                             <th><?php echo e(__('Price')); ?><br><?php echo e(__('(Non-Resident)')); ?></th>
                            <th><?php echo e(__('Discount')); ?> <br><?php echo e(__('(Non-Resident)')); ?></th>
                            <th><?php echo e(__('Status ')); ?> <br><?php echo e(__('(On/Off)')); ?></th>
                            <th><?php echo e(__('Action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    
    <?php if(session()->has('service_message')): ?>
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "<?php echo e(session()->get('service_message')); ?>",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/services/index.blade.php ENDPATH**/ ?>