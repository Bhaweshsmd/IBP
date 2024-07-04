<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/email.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h4><?php echo e(__('General Settings')); ?></h4>
        </div>
        <div class="card-body">

            <form Autocomplete="off" class="form-group form-border" action="<?php echo e(route('email.settings.update')); ?>" method="post">
                <?php echo csrf_field(); ?>

                <div class="form-row ">
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Mail Driver')); ?></label>
                        <input value="<?php echo e($email->mail_driver); ?>" type="text" class="form-control" name="mail_driver" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Mail Mailer')); ?></label>
                        <input value="<?php echo e($email->mail_mailer); ?>" type="text" class="form-control" name="mail_mailer" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Mail Host')); ?></label>
                        <input value="<?php echo e($email->mail_host); ?>" type="text" class="form-control" name="mail_host" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Mail Port')); ?></label>
                        <input value="<?php echo e($email->mail_port); ?>" type="text" class="form-control" name="mail_port" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Mail Username')); ?></label>
                        <input value="<?php echo e($email->mail_username); ?>" type="text" class="form-control" name="mail_username" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Mail Password')); ?></label>
                        <input value="<?php echo e($email->mail_password); ?>" type="text" class="form-control" name="mail_password" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Mail Encryption')); ?></label>
                        <input value="<?php echo e($email->mail_encryption); ?>" type="text" class="form-control" name="mail_encryption">
                    </div>
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Mail From Address')); ?></label>
                        <input value="<?php echo e($email->mail_from_address); ?>" type="text" class="form-control" name="mail_from_address" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Mail From Name')); ?></label>
                        <input value="<?php echo e($email->mail_from_name); ?>" type="text" class="form-control" name="mail_from_name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Status')); ?></label>
                        <select class="form-control" name="status" required>
                            <option value="1" <?php if($email->status == '1'): ?> selected <?php endif; ?>>Active</option>
                            <option value="2" <?php if($email->status == '2'): ?> selected <?php endif; ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                
                <?php if(has_permission(session()->get('user_type'), 'edit_email_settings')): ?>
                    <div class="form-group-submit text-right">
                        <button class="btn btn-primary " type="submit"><?php echo e(__('Update')); ?></button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <?php if(session()->has('settings_message_success')): ?>
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "<?php echo e(session()->get('settings_message_success')); ?>",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    <?php endif; ?>
    
    <?php if(session()->has('settings_message_error')): ?>
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Warning",
                    text: "<?php echo e(session()->get('settings_message_error')); ?>",
                    type: "warning",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/settings/email.blade.php ENDPATH**/ ?>