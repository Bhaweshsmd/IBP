<?php use App\Models\GlobalFunction; ?>


<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/maintenance.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h4><?php echo e(__('Maintenance Settings Details')); ?></h4>
            <a  href="<?php echo e(route('maintainance')); ?>" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> <?php echo e(__('Back to Maintenance Settings List')); ?></a>
        </div>
        <div class="card-body">
            
            <form action="<?php echo e(route('update.maintainance', $maintainance->id)); ?>" method="Post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                <?php echo csrf_field(); ?>
                
                <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php 
                        $lang_title = 'subject_'.$language->short_name;
                        $lang_desc = 'message_'.$language->short_name;
                    ?>
                    
                    <div class="form-group">
                        <label><?php echo e($language->name); ?> Subject</label>
                        <input type="text" class="form-control" name="<?php echo e($lang_title); ?>" value="<?php echo $maintainance->$lang_title; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label><?php echo e($language->name); ?> Message</label>
                        <textarea class="form-control" name="<?php echo e($lang_desc); ?>"><?php echo $maintainance->$lang_desc; ?></textarea>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" class="form-control" name="date" value="<?php echo e($maintainance->date); ?>">
                </div>
                
                <div class="form-group">
                    <label>From Time</label>
                    <input type="time" class="form-control" name="from_time" value="<?php echo e($maintainance->from_time); ?>">
                </div>
                
                <div class="form-group">
                    <label>To Time</label>
                    <input type="time" class="form-control" name="to_time" value="<?php echo e($maintainance->to_time); ?>">
                </div>
                
                <div class="form-group">
                    <label>Platform</label>
                    <select name="type" class="form-control">
                        <option value="1" <?php if($maintainance->type == '1'): ?> selected <?php endif; ?>>Web</option>
                        <option value="2" <?php if($maintainance->type == '2'): ?> selected <?php endif; ?>>App</option>
                        <option value="3" <?php if($maintainance->type == '3'): ?> selected <?php endif; ?>>Both</option>
                    </select>
                </div>
                
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" id='submitformbtn'>Submit</button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/maintainance/edit.blade.php ENDPATH**/ ?>