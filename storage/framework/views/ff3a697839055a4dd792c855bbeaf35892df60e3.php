
<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/maintenance.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card mt-3">
        <div class="card-header">
            <h4><?php echo e(__('Maintenance Settings')); ?></h4>
            
            <?php if(has_permission(session()->get('user_type'), 'add_maintenance')): ?>
                <a href="<?php echo e(route('create.maintainance')); ?>" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> <?php echo e(__('Add New Maintenance Setting')); ?></a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="activeMaintenance">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Platform</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
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
    
    <?php if(session()->has('maintenance_message')): ?>
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "<?php echo e(session()->get('maintenance_message')); ?>",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    <?php endif; ?>
    
    <?php if(session()->has('maintenance_delete')): ?>
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Warning",
                    text: "<?php echo e(session()->get('maintenance_delete')); ?>",
                    type: "warning",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/maintainance/index.blade.php ENDPATH**/ ?>