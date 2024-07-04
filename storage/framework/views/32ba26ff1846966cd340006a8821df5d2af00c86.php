<style>
	@media  screen and (max-width: 780px) {
	    .statcontent{
	        padding: 0px 25px;
	    }
	    
	    .statistics-grid {
    	    width:320px;
            padding: 20px 22px !important;
        }
	}
	
	.fa-trash-alt{
        text-align: center;
        padding: 10;
        font-size: 12px;
        width: 34px;
        height: 34px;
        right: 3px;
        top: 3px;
        color: #FFFFFF;
        border-radius: 50px;
        background: #CA0D0D;
    }
    
    .statistics-grid .statistics-icon {
        background: unset !important;
        box-shadow: 0px 4px 44px rgba(211, 211, 211, 0.25);
        min-width: fit-content !important;
        height: unset !important;
        border-radius: 5px;
    }
</style>

<?php $page = 'user-dashboard'; ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
        <?php echo e(__('string.user_dashboard')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?>
            <?php echo e(__('string.home')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?>
        <?php echo e(__('string.user_dashboard')); ?>

        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <?php $__env->startComponent('components.user-dashboard'); ?>
	<?php echo $__env->renderComponent(); ?>

	<div class="content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="card dashboard-card statistics-card">
						<div class="card-header">
							<h4><?php echo e(__('string.dashboard')); ?></h4>
							<p><?php echo e(__('string.get_your_bookings payments_here')); ?>.</p>
						</div>
						<div class="row">
							<div class="col-xl-6 col-lg-6 col-md-6 ">
							     <a href="<?php echo e(route('user-bookings')); ?>">
								<div class="statistics-grid flex-fill">
									<div class="statistics-content statcontent">
										<h3><?php echo e($allbookingsCount); ?></h3>
										<p><?php echo e(__('string.total_items_booked')); ?></p>
									</div>
									<div class="statistics-icon">
										<img src="<?php echo e(URL::asset('/assets/img/icons/list.png')); ?>" alt="Icon" width="60">
									</div>
								</div>
								  </a>	
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6">
							    <a href="<?php echo e(route('user-bookings')); ?>">
								<div class="statistics-grid flex-fill">
									<div class="statistics-content statcontent">
										<h3><?php echo e($settings->currency); ?><?php echo e(number_format($total_bookings,2)); ?></h3>
										<p><?php echo e(__('string.booking_payments')); ?></p>
									</div>
									<div class="statistics-icon">
										<img src="<?php echo e(URL::asset('/assets/img/icons/correct.png')); ?>" alt="Icon" width="60">
									</div>
								</div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xl-7 col-lg-12 d-flex">
					<div class="card dashboard-card flex-fill">
						<div class="card-header card-header-info">
							<div class="card-header-inner">
								<h4><?php echo e(__('string.my_bookings')); ?></h4>
								<p>(Latest 5 Bookings)</p>
							</div>
							<div class="card-header-btns">
								<nav>
									<div class="nav nav-tabs" role="tablist">
									  <a class="nav-link active" id="nav-Court-tab" href="<?php echo e(route('user-bookings')); ?>" aria-selected="true">View All</a>
									</div>
								</nav>
							</div>
						</div>
						<?php if(count($allbookings)): ?>
							<div class="tab-content">
								<div class="tab-pane fade show active" id="nav-Court" role="tabpanel" aria-labelledby="nav-Court-tab"
									tabindex="0">
									<div class="table-responsive dashboard-table-responsive">
										<table class="table dashboard-card-table">
											<tbody>
											    <?php $__currentLoopData = $allbookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bookings): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<tr>
													<td>
														<div class="academy-info">
															<a href="<?php echo e(url('item-details/'.$bookings->service->slug)); ?>" class="academy-img  col-sm-3">
																<img src="<?php echo e(url('/public/storage/'.$bookings->service->thumbnail)); ?>" alt="<?php echo e($bookings->service->title); ?>">
															</a>
															<div class="academy-content">
															
																<h6><a href="<?php echo e(url('item-details/'.$bookings->service->slug)); ?>" ><?php echo e($bookings->service->title); ?></a></h6>
																<ul>
																	<li><?php echo e(__('string.quantity')); ?> : <?php echo e($bookings->quantity); ?></li>
																	<li>
																	    <?php if($bookings->booking_hours==16): ?>
																	        <?php echo e(__('string.whole_day')); ?></li>
																	    <?php else: ?>
																	        <?php echo e($bookings->booking_hours??'1'); ?> <?php echo e(__('string.hrs')); ?>

																	    <?php endif; ?>
																	</li>
																</ul>
    															<?php if($bookings->status==1): ?>
    															    <span class="badge bg-success"><i class="feather-check-square me-1"></i> Confirmed</span>
    															<?php elseif($bookings->status==2): ?>
    														      <span class="badge bg-success"><i class="feather-check-square me-1"></i>	Completed</span>
    															<?php elseif($bookings->status==3): ?>
    																Declined												
    															<?php elseif($bookings->status==4): ?>
    													           	<span class="badge bg-danger"><img src="<?php echo e(URL::asset('/assets/img/icons/delete.svg')); ?>" alt="Icon" class="me-1">Cancelled</span>
    															<?php else: ?>
    															    Pending
    															<?php endif; ?>
															</div>
														</div>
													</td>
													<td>
														<h6><?php echo e(__('string.date_time')); ?></h6>
														<p><?php echo e(date(' D, d M Y',strtotime($bookings->date))); ?></p>
														<p><?php echo e(date('h:i A', strtotime($bookings->time))); ?></p>
													</td>
													<td>
														<h4><?php echo e($settings->currency); ?><?php echo e(number_format($bookings->payable_amount,2)); ?></h4>
													</td>
												</tr>
											   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>	
											</tbody>
										</table>
									</div>
								</div>
							</div>
						<?php else: ?>
						    <div class="noBooking mt-5 text-center">
                           	    <p><?php echo e(__('string.no_booking_currently')); ?></p>
                           	</div>
                        <?php endif; ?>
					</div>
				</div>
				<div class="col-xl-5 col-lg-12 d-flex flex-column">
					<div class="card payment-card ">
						<div class="payment-info ">
							<div class="payment-content">
								<p><?php echo e(__('string.your_ibp_account_balance')); ?></p>
								<h2><?php echo e($settings->currency); ?><?php echo e(number_format($userDetails->wallet,2)); ?></h2>
							</div>
						</div>
					</div>
					<div class="card dashboard-card academy-card">
						<div class="card-header card-header-info">
							<div class="card-header-inner">
								<h4><?php echo e(__('string.my_favourite_items')); ?></h4>
							</div>
							<div class="card-header-btns">
								<nav>
									<div class="nav nav-tabs" role="tablist">
									    <a class="nav-link active" id="nav-Court-tab" href="<?php echo e(route('favourite-services')); ?>" aria-selected="true">View All</a>
									</div>
								</nav>
							</div>
						</div>
						<div class="tab-content">
							<div class="tab-pane fade show active" id="nav-Favourites" role="tabpanel" aria-labelledby="nav-Favourites-tab" tabindex="0">
							    <?php if(count($wishlist)): ?>
									<div class="table-responsive dashboard-table-responsive">
										<table class="table dashboard-card-table">
											<tbody>
											    <?php $__currentLoopData = $wishlist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bookings): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<tr>
													<td>
														<div class="academy-info academy-info"> 
															<a href="<?php echo e(url('item-details/'.$bookings->slug)); ?>" class="academy-img  col-sm-3">
																<img src="<?php echo e(url('/public/storage/'.$bookings->thumbnail)); ?>" alt="Booking">
															</a>
															<div class="academy-content">
																<h6><a href="<?php echo e(url('item-details/'.$bookings->slug)); ?>"><?php echo e($bookings->title); ?></a></h6>
																<p><?php echo e($settings->currency); ?><?php echo e(number_format($bookings->price-($bookings->price*$bookings->discount)/100,2)); ?> - 1 Hour</p>
															</div>
														</div>
													</td>
													<td>
														<div class="academy-icon">
															<a href="javascript:void(0)" class="fav-icon addtofavroute btn btn-icon logo-hide-btn btn-sm"  rel="<?php echo e($bookings->id); ?>"><i class="far fa-trash-alt"></i></a>
														</div>
													</td>
												</tr>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>	
											</tbody>
										</table>
									</div>
								<?php else: ?>
    							    <div class="noBooking mt-2  text-center">
    	                           	    <p><?php echo e(__('string.no_favourite_items_currently')); ?></p>
    	                           	</div>
                                <?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <?php $__env->startComponent('components.modalpopup'); ?>
    <?php echo $__env->renderComponent(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/web/users/dashboard.blade.php ENDPATH**/ ?>