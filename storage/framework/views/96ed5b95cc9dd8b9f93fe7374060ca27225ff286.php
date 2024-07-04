<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/settings.js')); ?>"></script>
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
            <h4><?php echo e(__('General Settings')); ?></h4>
        </div>
        <div class="card-body">

            <form Autocomplete="off" class="form-group form-border" id="globalSettingsForm" action="" method="post">
                <?php echo csrf_field(); ?>

                <div class="form-row ">
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Currency')); ?></label>
                        <input value="<?php echo e($data->currency); ?>" type="text" class="form-control" name="currency" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Number of bookings users can have at a time')); ?></label>
                        <input value="<?php echo e($data->max_order_at_once); ?>" type="text" class="form-control" name="max_order_at_once" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for=""><?php echo e(__('Support Email')); ?></label>
                        <input value="<?php echo e($data->support_email); ?>" type="text" class="form-control" name="support_email" required>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for=""><?php echo e(__('Admin Email')); ?></label>
                        <input value="<?php echo e($data->admin_email); ?>" type="text" class="form-control" name="admin_email" required>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for=""><?php echo e(__('Contact Email')); ?></label>
                        <input value="<?php echo e($data->contact_email); ?>" type="text" class="form-control" name="contact_email" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Recaptcha Key')); ?></label>
                        <input value="<?php echo e($data->recaptcha_key); ?>" type="text" class="form-control" name="recaptcha_key" required>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Recaptcha Secret')); ?></label>
                        <input value="<?php echo e($data->recaptcha_secret); ?>" type="text" class="form-control" name="recaptcha_secret" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for=""><?php echo e(__('Twilio Id')); ?></label>
                        <input value="<?php echo e($data->twilio_sid); ?>" type="text" class="form-control" name="twilio_sid" required>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for=""><?php echo e(__('Twilio Auth Token')); ?></label>
                        <input value="<?php echo e($data->twilio_auth_token); ?>" type="text" class="form-control" name="twilio_auth_token" required>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for=""><?php echo e(__('Twilio Phone Number')); ?></label>
                        <input value="<?php echo e($data->twilio_phone_number); ?>" type="text" class="form-control" name="twilio_phone_number" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Pusher Id')); ?></label>
                        <input value="<?php echo e($data->pusher_id); ?>" type="text" class="form-control" name="pusher_id" required>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Pusher Key')); ?></label>
                        <input value="<?php echo e($data->pusher_key); ?>" type="text" class="form-control" name="pusher_key" required>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Pusher Secret')); ?></label>
                        <input value="<?php echo e($data->pusher_secret); ?>" type="text" class="form-control" name="pusher_secret" required>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Pusher Secret')); ?></label>
                        <input value="<?php echo e($data->pusher_cluster); ?>" type="text" class="form-control" name="pusher_cluster" required>
                    </div>
                </div>
                
                <?php if(has_permission(session()->get('user_type'), 'edit_settings')): ?>
                    <div class="form-group-submit text-right">
                        <button class="btn btn-primary " type="submit"><?php echo e(__('Update')); ?></button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/settings/general.blade.php ENDPATH**/ ?>