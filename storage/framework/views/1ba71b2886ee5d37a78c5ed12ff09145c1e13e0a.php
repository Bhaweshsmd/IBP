<?php $page = 'contact-us'; ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
            <?php echo e(__('string.contact_us')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?>
            <?php echo e(__('string.home')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?>
            <?php echo e(__('string.contact_us')); ?>

        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    
    <script async src="https://www.google.com/recaptcha/api.js"></script>
    
    <style>
        .swal2-popup .swal2-styled.swal2-cancel {
            background-color: #1E425E !important;
            border-color: #1E425E;
            color: #000;
            border-radius: 5rem !important;
            font-weight: bold !important;
        }
        
        .swal2-popup .swal2-styled.swal2-cancel a {
            color: #fff !important;
        }
        
        .swal2-popup .swal2-styled.swal2-confirm {
            background-color: #23d9a6 !important;
            border-color: #23d9a6;
            color: #000;
            border-radius: 5rem !important;
            font-weight: bold !important;
        }
        
        .swal2-popup .swal2-styled.swal2-confirm a {
            color: #000 !important;
        }
        
        .swal2-popup .swal2-styled:focus {
            outline: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
        }
        
        .swal2-confirm{
            border-radius: 5rem !important;
            background: black !important;
            color: #fff !important;
        }
        
        .swal2-popup{
            border-radius: 25px !important;
        }
        .contact-us-page .details{padding:24px 0px !important;}
        .displaycenter{margin:auto;}
    </style>

    <div class="content blog-details contact-group">
        <div class="container">
            <h2 class="text-center mb-40"><?php echo e(__('string.welcome_to_isidel')); ?></h2>
            <p class="text-center"><?php echo e(__('string.we_thrilled_you')); ?></p>
        </div>
    </div>

    <div class="content blog-details contact-group">
        <div class="container">
            <h4 class="text-center mb-40"><?php echo e(__('string.contact_information')); ?></h4>
            <div class="row mb-40">
                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="details">
                        <i class="feather-phone-call d-flex justify-content-center align-items-center displaycenter"></i>
                        <div class="info text-center mb-5 mt-4">
                            <h4><?php echo e(__('string.phone_number')); ?></h4>
                            <p class="mt-4"><?php echo e($company_details->salon_phone); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="details">
                        <i class="feather-map-pin d-flex justify-content-center align-items-center displaycenter"></i>
                        <div class="info text-center mt-4">
                            <h4><?php echo e(__('string.location')); ?></h4>
                            <p>Isidel Beach Park</p>
                            <p>Kaya Gobernador N.Debrot 75B</p>
                            <p>Kralendijk, Bonaire</p>
                            <p>Caribbean Netherlands </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="details">
                        <i class="feather-map-pin d-flex justify-content-center align-items-center displaycenter"></i>
                        <div class="info text-center mt-4">
                            <h4><?php echo e(__('string.location')); ?></h4>
                            <p>Bonaire Overheidsgebouwen N.V.</p>
                            <p>Kaya Grandi 2</p>
                            <p>Kralendijk, Bonaire</p>
                            <p>Caribbean Netherlands</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row mb-40 mt-5">
                        <h4 class="text-center mb-40"><?php echo e(__('string.email_addresses')); ?></h4>
                        
                        <div class="col-sm-3">
                            <div class="details">
                                <i class="feather-mail d-flex justify-content-center align-items-center displaycenter"></i>
                                <div class="info mt-3 text-center">
                                    <p><a href="mailto:info@isidelbeachpark.com">info@isidelbeachpark.com</a></p>
                                    <h4><?php echo e(__('string.general')); ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="details">
                                <i class="feather-mail d-flex justify-content-center align-items-center displaycenter"></i>
                                <div class="info mt-3 text-center">
                                    <p><a href="mailto:a.jansen@isidelbeachpark.com">a.jansen@isidelbeachpark.com</a></p>
                                    <h4><?php echo e(__('string.management')); ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="details">
                                <i class="feather-mail d-flex justify-content-center align-items-center displaycenter"></i>
                                <div class="info mt-3 text-center">
                                    <p><a href="mailto:finance@isidelbeachpark.com">finance@isidelbeachpark.com</a></p>
                                    <h4><?php echo e(__('string.finance')); ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="details">
                                <i class="feather-mail d-flex justify-content-center align-items-center displaycenter"></i>
                                <div class="info mt-3 text-center">
                                    <p><a href="mailto:info@example.com">pr@isidelbeachpark.com </a></p>
                                    <h4><?php echo e(__('string.press')); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="section dull-bg">
            <div class="container">
                <h2 class="text-center mb-40"><?php echo e(__('string.reach_out_to_us')); ?></h2>
                <p class="text-center"><?php echo e(__('string.feelfree_to_get')); ?></p>
                <form class="contact-us" method="Post" action="<?php echo e(route('submit.contact.us')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 mb-3">
                            <label for="first-name" class="form-label"><?php echo e(__('string.first_name')); ?></label>
                            <input type="text" class="form-control" name="firstname" placeholder="<?php echo e(__('string.enter_first_name')); ?>">
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 mb-3">
                            <label for="last-name" class="form-label"><?php echo e(__('string.last_name')); ?></label>
                            <input type="text" class="form-control" name="lastname" placeholder="<?php echo e(__('string.enter_last_name')); ?>">
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 mb-3">
                            <label for="e-mail" class="form-label"><?php echo e(__('string.email')); ?></label>
                            <input type="text" class="form-control" name="email" placeholder="<?php echo e(__('string.enter_email')); ?>">
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 mb-3">
                            <label for="phone" class="form-label"><?php echo e(__('string.phone_number')); ?></label>
                            <input type="text" class="form-control" name="phone" placeholder="<?php echo e(__('string.enter_phone_number')); ?>">
                        </div>
                    </div>
                    <div>
                        <label for="comments" class="form-label"><?php echo e(__('string.message')); ?></label>
                        <textarea class="form-control" name="comments" rows="3" placeholder="<?php echo e(__('string.enter_message')); ?>"></textarea>
                    </div>
                    <div class="g-recaptcha mt-4" data-sitekey=<?php echo e($settings->recaptcha_key); ?>></div>
                    <button type="submit" class="btn btn-secondary d-flex align-items-center"><?php echo e(__('string.submit')); ?><i class="feather-arrow-right-circle ms-2"></i></button>
                </form>
            </div>
        </section>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="<?php echo e(asset('asset/cdnjs/sweetalert.min.js')); ?>"></script>
    <script src="https://unpkg.com/sweetalert2@7.8.2/dist/sweetalert2.all.js"></script>
    
    <?php if(session()->has('contact_success_message')): ?>
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "<?php echo e(session()->get('contact_success_message')); ?>",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    <?php endif; ?>
    
    <?php if(session()->has('contact_fail_message')): ?>
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Warning",
                    text: "<?php echo e(session()->get('contact_fail_message')); ?>",
                    type: "warning",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/web/home/contact-us.blade.php ENDPATH**/ ?>