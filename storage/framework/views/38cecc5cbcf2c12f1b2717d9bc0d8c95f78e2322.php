<?php $page = 'venue-details'; ?>

<style>
    #reviewSection{
       border: 1px solid #EAEDF0;
       border-radius: 6px;
       padding: 10px;
       margin-right: 10px;
    }
    
    .accordion-item ul li {
        list-style-type: inherit !important;
    }
</style>
<?php $__env->startSection('content'); ?>

    <?php
        $count_reviews = count($services->reviews);
        if($count_reviews > 0){
            $sum_reviews = 0;
            foreach($services->reviews as $review){
                $sum_reviews += $review->rating;
            }
            $total_reviews = $sum_reviews/$count_reviews;
        }else{
            $total_reviews = 0;
        }
    ?>
    
    <?php if(!empty($services->images)): ?>
        <div class="bannergallery-section">
            <div class="main-gallery-slider owl-carousel owl-theme">
                <?php $__currentLoopData = $services->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="gallery-widget-item">
                        <a href="<?php echo e(url('public/storage/'.$gallery->image)); ?>" data-fancybox="gallery1">
                            <img class="img-fluid" alt="Image"
                                src="<?php echo e(url('public/storage/'.$gallery->image)); ?>">
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="showphotos corner-radius-10">
                <a href="<?php echo e(url('public/storage/'.$services->thumbnail)); ?>" data-fancybox="gallery1"><i class="fa-regular fa-images"></i><?php echo e(__('string.more_photos')); ?></a>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="venue-info white-bg d-block">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                    <h1 class="d-flex align-items-center justify-content-start"><?php echo e($services->title); ?><span class="d-flex justify-content-center align-items-center"><i class="fas fa-check-double"></i></span></h1>
                    <ul class="d-sm-flex justify-content-start align-items-center">
                        <li><i class="feather-info"></i><?php echo e($category->title??''); ?></li>
                        <li><i class="feather-phone-call"></i><?php echo e($company_details->salon_phone??''); ?></li>
                        <li class="d-flex"><i class="feather-mail"></i><a href="mailto:help@isidelbeachpark.com"><?php echo e($company_details->email??''); ?></a></li>
                    </ul>
                    <ul class="d-sm-flex justify-content-start align-items-center mt-1">
                        <li><i class="feather-map-pin"></i><?php echo e($company_details->salon_address??''); ?></li>
                    </ul>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-6 text-right">
                    <ul class="social-options float-lg-end d-sm-flex justify-content-start align-items-center">
                        <li class="d-flex">
                            <i class="feather-heart  <?php if(favouriteList($services->id)): ?> text-danger <?php endif; ?>" id="selectedHeart" rel="<?php echo e($services->id); ?>"></i>
                            <a href="javascript:vaid(0)" class="favour-adds fav-icon" id="addRemoveContent" rel="<?php echo e($services->id); ?>">
                                <?php if(favouriteList($services->id)): ?>
                                    <?php echo e(__('string.remove_from_favourite')); ?>

                                <?php else: ?>
                                   <?php echo e(__('string.add_to_favourite')); ?>

                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="d-flex" >
                            <?php if(Auth::check()): ?>
                                <a href="<?php echo e(route('user-chat')); ?>"> <i class="fa-regular fa-comments"></i> Chat Now</a>
                            <?php else: ?>
                                <i class="fa-regular fa-comments"></i> Chat Now</i>
                            <?php endif; ?>
                        </li>
                        <li class="venue-review-info d-flex justify-content-start align-items-center" id="reviewSection">
                            <span class="d-flex justify-content-center align-items-center"><?php echo e(number_format($total_reviews,1)); ?></span>
                            <div class="review">
                                <div class="rating">
                                    <?php for($i=0;$i< (int)$total_reviews;$i++): ?>
                                        <i class="fas fa-star filled"></i>
                                    <?php endfor; ?>
                                    <?php for($i=0;$i < 5-(int)$total_reviews;$i++): ?>
                                        <i class="fa fa-star" aria-hidden="true" style="color:grey"></i>
                                    <?php endfor; ?>
                                </div>
                                <p class="mb-0"><?php echo e(count($services->reviews)); ?> <?php echo e(__('string.reviews')); ?></p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                    <div class="venue-options white-bg mb-4">
                        <ul class="clearfix">
                            <li class="active"><a href="#overview"><?php echo e(__('string.overview')); ?></a></li>
                            <?php if(!empty($services->included_items)): ?>
                                <li><a href="#includeditems"><?php echo e(__('string.includeditems')); ?></a></li>
                            <?php endif; ?>
                            <?php if(!empty($services->history)): ?>
                            <li><a href="#history"><?php echo e(__('string.history')); ?></a></li>
                            <?php endif; ?>
                            <li><a href="#rules"><?php echo e(__('string.rules')); ?></a></li>
                            <li><a href="#gallery"><?php echo e(__('string.gallery')); ?></a></li>
                            <?php if(!empty($services->map_images)): ?>
                            <li><a href="#area_map"><?php echo e(__('string.area_map')); ?></a></li>
                            <?php endif; ?>
                            <li><a href="#reviews"><?php echo e(__('string.reviews')); ?></a></li>
                        </ul>
                    </div>

                    <div class="accordion" id="accordionPanel">
                        <div class="accordion-item mb-4" id="overview">
                            <h4 class="accordion-header" id="panelsStayOpen-overview">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                    aria-controls="panelsStayOpen-collapseOne">
                                    <?php echo e(__('string.overview')); ?>

                                </button>
                            </h4>
                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
                                aria-labelledby="panelsStayOpen-overview">
                                <div class="accordion-body">
                                    <div class="text show-more-height">
                                       <?php echo $services->about; ?>

                                    </div>
                                    <div class="show-more d align-items-center primary-text"><i class="feather-plus-circle"></i><?php echo e(__('string.show_more')); ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if(!empty($services->included_items)): ?>
                            <div class="accordion-item mb-4" id="includeditems">
                                <h4 class="accordion-header" id="panelsStayOpen-includeditems">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                        <?php echo e(__('string.includeditems')); ?>

                                    </button>
                                </h4>
                                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-includeditems">
                                    <div class="accordion-body">
                                        <div class="text show-more-height">
                                           <?php echo $services->included_items; ?>

                                        </div>
                                        <div class="show-more d align-items-center primary-text"><i class="feather-plus-circle"></i><?php echo e(__('string.show_more')); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($services->history)): ?>
                            <div class="accordion-item mb-4" id="history">
                                <h4 class="accordion-header" id="panelsStayOpen-history">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                        <?php echo e(__('string.history')); ?>

                                    </button>
                                </h4>
                                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-history">
                                    <div class="accordion-body">
                                        <div class="text show-more-height">
                                           <?php echo $services->history; ?>

                                        </div>
                                        <div class="show-more d align-items-center primary-text"><i class="feather-plus-circle"></i><?php echo e(__('string.show_more')); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                     
                        <div class="accordion-item mb-4" id="rules">
                            <h4 class="accordion-header" id="panelsStayOpen-rules">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                    <?php echo e(__('string.rules')); ?>

                                </button>
                            </h4>
                            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-rules">
                                <div class="accordion-body">
                                   <?php echo $services->rules; ?>

                                </div>
                            </div>
                        </div>
                       
                        <div class="accordion-item mb-4" id="gallery">
                            <h4 class="accordion-header" id="panelsStayOpen-gallery">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
                                    <?php echo e(__('string.gallery')); ?>

                                </button>
                            </h4>
                            <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-gallery">
                                <div class="accordion-body">
                                    <div class="owl-carousel gallery-slider owl-theme">
                                        <?php if(!empty($services->images)): ?>
                                            <?php $__currentLoopData = $services->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a class="corner-radius-10" href="<?php echo e(url('public/storage/'.$gallery->image)); ?>" data-fancybox="gallery3">
                                                    <img class="img-fluid corner-radius-10" alt="Image" src="<?php echo e(url('public/storage/'.$gallery->image)); ?>">
                                                </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if(!empty($services->map_images)): ?>
                            <div class="accordion-item mb-4" id="area_map">
                                <h4 class="accordion-header" id="panelsStayOpen-area_map">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseNine" aria-expanded="false" aria-controls="panelsStayOpen-collapseNine">
                                        <?php echo e(__('string.area_map')); ?>

                                    </button>
                                </h4>
                                <div id="panelsStayOpen-collapseNine" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-area_map">
                                    <div class="accordion-body">
                                        <div class="owl-carousel map-images-slider owl-theme">
                                            <?php $__currentLoopData = $services->map_images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a class="corner-radius-10" href="<?php echo e(url('public/storage/'.$gallery->image)); ?>" data-fancybox="gallery3">
                                                    <img class="img-fluid corner-radius-10" alt="Image" src="<?php echo e(url('public/storage/'.$gallery->image)); ?>">
                                                </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="accordion-item mb-4" id="reviews">
                            <div class="accordion-header" id="panelsStayOpen-reviews">
                                <div class="accordion-button d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSix" aria-controls="panelsStayOpen-collapseSix">
                                    <span class="w-75 mb-0">
                                        <?php echo e(__('string.reviews')); ?>

                                    </span>
                                </div>
                            </div>
                            <div id="panelsStayOpen-collapseSix" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-reviews">
                                <div class="accordion-body">
                                    <div class="row review-wrapper">
                                        <div class="col-lg-3">
                                            <div class="ratings-info corner-radius-10 text-center">
                                                <h3><?php echo e(number_format($total_reviews,1)); ?></h3>
                                                <span>out of 5.0</span>
                                                <div class="rating">
                                                    <?php for($i=0;$i< (int)$total_reviews;$i++): ?>
                                                        <i class="fas fa-star filled"></i>
                                                    <?php endfor; ?>
                                                    <?php for($i=0;$i < 5-(int)$total_reviews;$i++): ?>
                                                        <i class="fa fa-star" aria-hidden="true" style="color:grey"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if(count($services->reviews)): ?>
                                            <div class="col-lg-9">
                                                <div class="recommended">
                                                    <h5>Recommended by <?php echo e(count($services->reviews)); ?>+ Visitors</h5>
                                                    <div class="row">
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>    
                                    </div>

                                    <?php $__currentLoopData = $services->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="review-box d-md-flex">
                                            <div class="review-profile">
                                                <?php if(!empty($review->user->profile_image)): ?>
                                                    <img src="<?php echo e(URL::asset('/public/storage/'.$review->user->profile_image)); ?>" alt="User">
                                                <?php else: ?>
                                                    <img src="<?php echo e(URL::asset('/public/profile.jpg')); ?>" alt="User">
                                                <?php endif; ?>
                                            </div>
                                            <div class="review-info">
                                                <div class="rating">
                                                    <?php for($i=0;$i<$review->rating;$i++): ?>
                                                        <i class="fas fa-star filled"></i>
                                                    <?php endfor; ?>
                                                    <?php for($i=0;$i < 5-$review->rating;$i++): ?>
                                                        <i class="fa fa-star" aria-hidden="true" style="color:grey"></i>
                                                    <?php endfor; ?> 
                                                  
                                                    <span class=""><?php echo e(number_format($review->rating,1)); ?></span>
                                                    <h6><?php echo e(ucfirst($review->user->first_name)); ?>&nbsp;&nbsp;<?php echo e($review->user->last_name); ?></h6>
                                                </div>
                                                <span class="success-text"><i class="feather-check"></i><?php echo e($review->comment); ?></span>
                                                <br>
                                                <span class="post-date"><?php echo e($review->created_at); ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <aside class="col-12 col-sm-12 col-md-12 col-lg-4 theiaStickySidebar">
                    <div class="white-bg book-court">
                        <ul class="d-sm-flex align-items-center justify-content-evenly">
                            <li>
                                <?php if($services->discount): ?>
                                    <h4 class="d-inline-block primary-text text-decoration-line-through text-danger"><?php echo e($settings->currency); ?><?php echo e(number_format($services->price,2)); ?></h4><span>/hr</span><br>
                                <?php endif; ?> 
                                <h3 class="d-inline-block primary-text"><?php echo e($settings->currency); ?><?php echo e(number_format($services->price-($services->price*$services->discount)/100,2)); ?></h3><span>/hr</span>
                            </li>
                        </ul>
                        <div class="d-grid btn-block mt-3">
                            <?php if(Auth::check()): ?>
                                <a href="<?php echo e(route('booking-details',$services->slug)); ?>" class="btn btn-secondary d-inline-flex justify-content-center align-items-center"><i class="feather-calendar"></i><?php echo e(__('string.book_now')); ?></a>
                            <?php else: ?>
                                <a href="javascript:void(0)" class="btn btn-secondary d-inline-flex justify-content-center align-items-center checkLogin"><i class="feather-calendar"></i><?php echo e(__('string.book_now')); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="white-bg cage-owner-info">
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="profile-pic">
                                <a href="<?php echo e(route('company-details')); ?>"><img class="img-fluid" alt="User" src="<?php echo e(URL::asset('/public/storage/'.$company_details->owner_photo)); ?>"></a>
                            </div>
                            <div class="">
                                <h5><?php echo e($company_details->salon_name); ?></h5>
                                <div class="rating">
                                    <?php for($i=0;$i< (int)$company_details->rating;$i++): ?>
                                        <i class="fas fa-star filled"></i>
                                    <?php endfor; ?>
                                    <?php for($i=0;$i < 5-(int)$company_details->rating;$i++): ?>
                                        <i class="fa fa-star" aria-hidden="true" style="color:grey"></i>
                                    <?php endfor; ?>
                                    <span class=""><?php echo e(number_format($company_details->rating,1)); ?></span>
                                    <span>(<?php echo e($total_company_review); ?>)</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid btn-block text-center mt-3">
                            <a href="<?php echo e(url('contact-us')); ?>" class="btn btn-secondary d-inline-flex justify-content-center align-items-center"><i class="feather-phone-call"></i><?php echo e(__('string.contact_us')); ?></a>
                        </div>
                    </div>
                    <div class="white-bg listing-owner">
                        <h4 class="border-bottom"><?php echo e(__('string.other_items')); ?></h4>
                        <ul>
                            <?php $__currentLoopData = $similar_service; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $similars): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="d-flex justify-content-start align-items-center">
                                    <div class="">
                                        <a href="<?php echo e(url('item-details/'.$similars->slug)); ?>"><img class="img-fluid" alt="Venue" src="<?php echo e(url('public/storage/'.$similars->thumbnail)); ?>" style="width:110px; height: 110px;"></a>
                                    </div>
                                    <div class="owner-info">
                                        <h5><a href="<?php echo e(url('item-details/'.$similars->slug)); ?>"><?php echo e($similars->title); ?></a></h5>
                                        <p><i class="feather-map-pin"></i><span><?php echo e($similars->category->title); ?></span></p>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>

        <section class="section innerpagebg">
            <div class="container">
                <div class="featured-slider-group">
                    <h3 class="mb-40"><?php echo e(__('string.similar_items')); ?></h3>
                    <div class="owl-carousel featured-venues-slider owl-theme">
                        <?php $__currentLoopData = $similar_service; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $similars): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="featured-venues-item">
                                <div class="listing-item mb-0">
                                    <div class="listing-img">
                                        <a href="<?php echo e(url('item-details/'.$similars->slug)); ?>">
                                            <img src="<?php echo e(url('public/storage/'.$similars->thumbnail)); ?>" alt="Venue">
                                        </a>
                                        <div class="fav-item-venues">
                                            <span class="tag tag-blue"><?php echo e($similars->category->title); ?></span>
                                        </div>
                                        <div class="hour-list dis-price">
                                            <?php if($similars->discount > 0): ?>
                                                <h5 class="tag tag-primary mx-2 original-price "><?php echo e($settings->currency); ?><?php echo e(number_format($similars->price,2)); ?> <span>/hr</span></h5>
                                            <?php endif; ?>
                                            <h5 class="tag tag-primary display-price"><?php echo e($settings->currency); ?><?php echo e(number_format($similars->price-($similars->price*$similars->discount)/100,2)); ?> <span>/hr</span></h5>
                                            
                                        </div>
                                    </div>
                                    <div class="listing-content">
                                        <div class="list-reviews">
                                            <div class="d-flex align-items-center">
                                                <span class="rating-bg"><?php echo e(number_format($similars->rating,1)); ?></span><span><?php echo e(count($similars->reviews)); ?> <?php echo e(__('string.reviews')); ?></span>
                                            </div>
                                            <a href="javascript:void(0)" class="fav-icon <?php if(favouriteList($similars->id)): ?> selected <?php endif; ?>" rel="<?php echo e($similars->id); ?>">
                                                <i class="feather-heart"></i>
                                            </a>
                                        </div>
                                        <h3 class="listing-title">
                                            <a href="<?php echo e(url('item-details/'.$similars->slug)); ?>"><?php echo e($similars->title); ?></a>
                                        </h3>
                                        <div class="listing-details-group">
                                            <p class = "para"><?php echo e($similars->about); ?></p>
                                            <ul>
                                                <li>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="listing-button">
                                            <div class="listing-venue-owner">
                                            </div>
                                            <a href="<?php echo e(url('item-details/'.$similars->slug)); ?>" class="user-book-now"><span><i class="feather-calendar me-2"></i></span><?php echo e(__('string.book_now')); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php $__env->startComponent('components.modalpopup'); ?>
    <?php echo $__env->renderComponent(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/web/home/venue-details.blade.php ENDPATH**/ ?>