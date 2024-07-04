<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/appsettings.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<style>
    .payment-gateway-card {
        background-color: rgb(245, 245, 245);
        border-radius: 10px;
    }
</style>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h4><?php echo e(__('App Settings')); ?></h4>
        </div>
        <div class="card-body">

            <form Autocomplete="off" class="form-group form-border"  action="<?php echo e(route('app.settings.update')); ?>" method="post">
                <?php echo csrf_field(); ?>

                <div class="form-row ">
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Android Version')); ?></label>
                        <input value="<?php echo e($data->android_version); ?>" type="text" class="form-control" name="android_version" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Android URL')); ?></label>
                        <input value="<?php echo e($data->android_url); ?>" type="text" class="form-control" name="android_url" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('IOS Version')); ?></label>
                        <input value="<?php echo e($data->ios_version); ?>" type="text" class="form-control" name="ios_version" required>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('IOS URL')); ?></label>
                        <input value="<?php echo e($data->ios_url); ?>" type="text" class="form-control" name="ios_url" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for=""><?php echo e(__('Firebase Key')); ?></label>
                        <input value="<?php echo e($data->firebase_key); ?>" type="text" class="form-control" name="firebase_key" required>
                    </div>
                </div>
                
                <?php if(has_permission(session()->get('user_type'), 'edit_app_settings')): ?>
                    <div class="form-group-submit text-right">
                        <button class="btn btn-primary " type="submit"><?php echo e(__('Update')); ?></button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <?php if(session()->has('settings_message')): ?>
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "<?php echo e(session()->get('settings_message')); ?>",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/settings/app.blade.php ENDPATH**/ ?>