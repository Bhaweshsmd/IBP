<?php
    $page = 'faq';
    
    if(!empty($lang)){
        $title_lang = 'question_'.$lang;
        $desc_lang = 'answer_'.$lang;
    }else{
        $title_lang = 'question_en';
        $desc_lang = 'answer_en';
    }
?>


<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
            Faq
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?>
            Home
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?>
            Faq
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12 offset-sm-12 offset-md-1 col-md-10 col-lg-10">
                    <div class="ask-questions">
                        <div class="faq-info">
                            <div class="accordion" id="accordionExample">
                                
                                <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading<?php echo e($k); ?>">
                                            <a href="javascript:;" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo e($k); ?>" aria-expanded="true" aria-controls="collapse<?php echo e($k); ?>">
                                                <?php echo e($faq->$title_lang); ?>

                                            </a>
                                        </h2>
                                        <div id="collapse<?php echo e($k); ?>" class="accordion-collapse collapse <?php if($k == '0'): ?> show <?php endif; ?>" aria-labelledby="heading<?php echo e($k); ?>" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="accordion-content">
                                                    <p><?php echo e($faq->$desc_lang); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/web/home/faq.blade.php ENDPATH**/ ?>