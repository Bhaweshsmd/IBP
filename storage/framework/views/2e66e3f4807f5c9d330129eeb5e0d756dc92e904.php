<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/admins.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card mt-3">
        <div class="card-header">
            <h4><?php echo e(__('Admins')); ?></h4>
            <?php if(has_permission(session()->get('user_type'), 'add_admin')): ?>
                <a href="<?php echo e(route('admins.create')); ?>" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> Add New Admin</a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="activeAdmins">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Profile')); ?></th>
                                    <th><?php echo e(__('Name')); ?></th>
                                    <th><?php echo e(__('User Name')); ?></th>
                                    <th><?php echo e(__('Email')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Group')); ?></th>
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
    
    <?php if(session()->has('admin_message')): ?>
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "<?php echo e(session()->get('admin_message')); ?>",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/admins/index.blade.php ENDPATH**/ ?>