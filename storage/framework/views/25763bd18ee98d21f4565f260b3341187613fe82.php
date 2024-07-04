<?php $page = 'coaches-map-sidebar'; ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
           <?php echo e(__('string.items_facilities')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?>
           <?php echo e(__('string.home')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?>
           <?php echo e(__('string.items_facilities')); ?>

        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    
    <style>
        .coach-btn {
            border-bottom: 0px;
            margin: 0 0 5px;
            padding: 0 0 5px;
        }
        .sortby-section .sorting-info .sortby-filter-group .grid-listview {
            margin: 0;
            padding: 0;
            border-right: unset;
        }
    </style>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sortby-section">
                        <div class="sorting-info">
                            <div class="row d-flex align-items-center">
                                <div class="col-xl-4 col-lg-3 col-sm-12 col-12">
                                    <div class="count-search">
                                        <p><span><?php echo e(count($services)); ?>+</span> <?php echo e(__('string.items_facilities_listed')); ?></p>
                                    </div>
                                </div>
                                <div class="col-xl-8 col-lg-9 col-sm-12 col-12">
                                    <div class="sortby-filter-group">
                                        <div class="grid-listview">
                                            <ul class="nav">
                                                <li>
                                                    <a href="<?php echo e(url('items-facilities?category=')); ?><?php echo e(Request::get('category')); ?>&keyword=<?php echo e(Request::get('keyword')); ?>" class="active">
                                                        <img src="<?php echo e(URL::asset('/assets/img/icons/sort-01.svg')); ?>" alt="Icon">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo e(url('items-facilities-list?category=')); ?><?php echo e(Request::get('category')); ?>&keyword=<?php echo e(Request::get('keyword')); ?>">
                                                        <img src="<?php echo e(URL::asset('/assets/img/icons/sort-02.svg')); ?>" alt="Icon">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                <ul class="nav nav-pills mb-3 d-flex  navpills">
                  <li class="nav-item grid-nav-item <?php if(!Request::get('category')): ?> active-item <?php endif; ?>"><a href="<?php echo e(url('items-facilities?category=')); ?><?php echo e(0); ?>&keyword=<?php echo e(Request::get('keyword')); ?>">All</a></li>
                  <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li for="<?php echo e($category->id); ?>" class="nav-item grid-nav-item <?php if(Request::get('category')==$category->id): ?> active-item <?php endif; ?> "><a id="<?php echo e($category->id); ?>" href="<?php echo e(url('items-facilities?category=')); ?><?php echo e($category->id); ?>&keyword=<?php echo e(Request::get('keyword')); ?>"><?php echo e($category->title); ?></a></li>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               </ul>
                  </div>
            </div>

            <div class="row justify-content-center">
                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $serv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="featured-venues-item">
                            <div class="listing-item listing-item-grid">
                                <div class="listing-img">
                                    <a href="<?php echo e(url('item-details/'.$serv->slug)); ?>">
                                        <img src="<?php echo e(url('public/storage/'.$serv->thumbnail)); ?>" alt="Venue">
                                    </a>
                                    <div class="fav-item-venues">
                                        <span class="tag tag-blue"><?php echo e($serv->category->title); ?></span>
                                        <div class="list-reviews coche-star">
                                            <a href="javascript:void(0)" class="fav-icon <?php if(favouriteList($serv->id)): ?> selected <?php endif; ?>" rel="<?php echo e($serv->id); ?>">
                                                <i class="feather-heart" rel="<?php echo e($serv->id); ?>"></i>
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <div class="hour-list dis-price">
                                        <?php if($serv->discount > 0): ?>
                                        <h5 class="tag tag-primary mx-3 original-price "><?php echo e($settings->currency); ?><?php echo e(number_format($serv->price, 2)); ?> <span>/hr</span></h5>
                                        <?php endif; ?>
                                        <h5 class="tag tag-primary display-price"><?php echo e($settings->currency); ?><?php echo e(number_format($serv->price-($serv->price*$serv->discount)/100,2)); ?> <span>/hr</span></h5>
                                        
                                    </div>
                                </div>
                         
                                <div class="listing-content">
                                    <div class="avalbity-review">
                                        <ul>
                                            <li>
                                                <div class="list-reviews mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <span class="rating-bg"><?php echo e(number_format($serv->rating,1)); ?></span><span><?php echo e(count($serv->reviews)); ?> <?php echo e(__('string.reviews')); ?></span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <h3 class="listing-title">
                                        <a href="<?php echo e(url('item-details/'.$serv->slug)); ?>"><?php echo e($serv->title); ?></a>
                                    </h3>
                                    <ul class="mb-2">
                                        <li>
                                            <span>
                                                <i class="feather-map-pin me-2"></i>Port Alsworth, AK
                                            </span>
                                        </li>
                                    </ul>
                                    <div class="listing-details-group">
                                        <?php
                                            $servabout = strip_tags($serv->about);
                                            if (strlen($servabout) > 50) {
                                                $stringCut = substr($servabout, 0, 50);
                                                $endPoint = strrpos($stringCut, ' ');
                                                $servabout = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                            }
                                        ?>
                                        <p><?php echo e($servabout); ?>... <a href="<?php echo e(url('item-details/'.$serv->slug)); ?>" class="text-primary">Read More</a></p>
                                    </div>
                                    <div class="coach-btn">
                                        <ul>
                                            <li>
                                                <a href="<?php echo e(url('item-details/'.$serv->slug)); ?>" class="btn btn-primary w-100"><i class="feather-eye me-2"></i><?php echo e(__('string.view_items')); ?></a>
                                            </li>
                                            <li>
                                                <?php if(Auth::check()): ?>
                                                    <a href="<?php echo e(route('booking-details',$serv->slug)); ?>" class="btn btn-secondary w-100"><i class="feather-calendar me-2"></i><?php echo e(__('string.book_now')); ?></a>
                                                <?php else: ?>
                                                    <a href="javascript:void(0)" class="btn btn-secondary w-100 checkLogin"><i class="feather-calendar me-2"></i><?php echo e(__('string.book_now')); ?></a>
                                                <?php endif; ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/web/home/items.blade.php ENDPATH**/ ?>