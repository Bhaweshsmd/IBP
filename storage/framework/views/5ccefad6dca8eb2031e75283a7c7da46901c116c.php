<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/roles.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(asset('asset/style/viewService.css')); ?>">
    <style>
        #map {
            height:500px;
        }
        #modaldialog {
            max-width: 100% !important;
        }
        
        .card .card-header .form-control{
            height: auto !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h4><?php echo e(__('Role Details')); ?> :</h4>
            <a href="<?php echo e(route('roles')); ?>" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> <?php echo e(__('Back to Roles List')); ?></a>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('roles.update', $result->id)); ?>" method="Post">
                <?php echo csrf_field(); ?>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Name</label>
                        <input type="text" value="<?php echo e($result->name); ?>" class="form-control" name="name"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Display Name</label>
                        <input type="text" value="<?php echo e($result->display_name); ?>" class="form-control" name="display_name"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Description</label>
                        <input type="text" value="<?php echo e($result->description); ?>" class="form-control" name="description"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" <?php if($result->status == '1'): ?> selected <?php endif; ?>>Active</option>
                            <option value="0" <?php if($result->tatus == '0'): ?> selected <?php endif; ?>>Inctive</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Permissions</th>
                                        <th>View</th>
                                        <th>Add</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                        $i=0;
                                    ?>

                                    <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <tr data-rel="<?php echo e($i); ?>">
    
                                            <td><?php echo e($key); ?></td>
    
                                            <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input type="hidden" value="<?php echo e($v['user_type']); ?>" name="user_type" id="user_type">
                                                <input type="hidden" value="<?php echo e($result->id); ?>" name="id" id="id">
    
                                                <?php if(isset($v['display_name'])): ?>
                                                    <td>
                                                        <label class="checkbox-container">
                                                            <input type="checkbox" name="permission[]" id="<?php echo e('view_'.$i); ?>" value="<?php echo e($v['id']); ?>"  <?php echo e(in_array( $v['id'], $stored_permissions) ? 'checked' : ''); ?> class="<?php echo e(($i % 4 == 0) ? 'view_checkbox' :'other_checkbox'); ?>">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </td>
                                                <?php else: ?>
                                                    <td></td>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tr>

                                        <?php
                                            $i++;
                                        ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div id="error-message"></div>
                        </div>
                    </div>
                    <div class="form-group col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/roles/edit.blade.php ENDPATH**/ ?>