<?php if(!Route::is(['coach-details','lesson-personalinfo','lesson-timedate','lesson-order-confirm','lesson-type','lesson-payment','coach-order-confirm','coach-payment','coach-personalinfo','coach-timedate'])): ?>
    <section class="breadcrumb breadcrumb-list mb-0">
<?php endif; ?>
<?php if(Route::is(['coach-details','lesson-personalinfo','lesson-timedate','lesson-order-confirm','lesson-type','lesson-payment','coach-order-confirm','coach-payment','coach-personalinfo','coach-timedate','coach-order-confirm','coach-payment','coach-personalinfo','coach-timedate'])): ?>
    <div class="breadcrumb mb-0">
<?php endif; ?>
<span class="primary-right-round"></span>
<div class="container">
    <h1 class="text-white"><?php echo e($title); ?></h1>
    <ul>
        <li><a href="<?php echo e(url('/')); ?>"><?php echo e($li_1); ?></a></li>
        <li><?php echo e($li_2); ?></li>
        <?php if(Route::is(['booking-details'])): ?>
        <li><?php echo e($li_3??''); ?></li>
        <?php endif; ?>
    </ul>
</div>
<?php if(!Route::is(['coach-details','lesson-personalinfo','lesson-timedate','lesson-order-confirm','lesson-type','lesson-payment','coach-order-confirm','coach-payment','coach-personalinfo','coach-timedate','coach-order-confirm','coach-payment','coach-personalinfo','coach-timedate'])): ?>
    </section>
<?php endif; ?>
<?php if(Route::is(['coach-details','lesson-personalinfo','lesson-timedate','lesson-order-confirm','lesson-type','lesson-payment','coach-order-confirm','coach-payment','coach-personalinfo','coach-timedate','coach-order-confirm','coach-payment','coach-personalinfo','coach-timedate'])): ?>
    </div>
<?php endif; ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/components/breadcrumb.blade.php ENDPATH**/ ?>