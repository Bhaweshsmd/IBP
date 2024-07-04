<?php $page = 'user-bookings'; ?>


<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
        <?php echo e(__('string.user_bookings')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?>
            <?php echo e(__('string.home')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?>
        <?php echo e(__('string.user_bookings')); ?>

        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <?php $__env->startComponent('components.user-dashboard'); ?>
	<?php echo $__env->renderComponent(); ?>

    <style>
        .visit-btns{
            cursor:pointer;   
        }
        
        .availabe{
            border: 1px solid;
            border-color: green;
            padding: 7px 9px 3px 7px;
            border-radius: 10px;
            margin: 10px;
            background: green;
            color: #fff;
        }
        
        .notavailabe{
            border: 1px solid;
            border-color: red;
            padding: 7px 9px 3px 7px;
            border-radius: 10px;
            margin: 10px;
            background: red;
            color: #fff;
            cursor: not-allowed;
        }
        
        form label span {
            color: #FFF;
        }
        
        .visit-rsn{
            margin-bottom: 2px;
        }
        
        #bookedColor{
            width: 20px;
            height: 20px;
            background: red;
            display: flex;
            border-radius: 50%;
        }
        
        #availableColor{
            width: 20px;
            height: 20px;
            background: green;
            display: flex;
            border-radius: 50%;
        }
        
        .justifyContent{
            justify-content: space-between;
        }
        
        .datetimepicker{
            border: 2px solid;
        }
   </style>
   
	<div class="content court-bg">
		<div class="container">

			<div class="row">
				<div class="col-lg-12">
					<div class="sortby-section court-sortby-section">
						<div class="sorting-info">
							<div class="row d-flex align-items-center">
								<div class="col-xl-7 col-lg-7 col-sm-12 col-12">
									<div class="coach-court-list">
										<ul class="nav">
											<li><a class="active" href="<?php echo e(url('user-bookings')); ?>"><?php echo e(__('string.all_booking')); ?></a></li>
											<li><a  href="<?php echo e(url('user-ongoing')); ?>"><?php echo e(__('string.confirmed')); ?></a></li>
											<li><a  href="<?php echo e(url('user-complete')); ?>"><?php echo e(__('string.completed')); ?></a></li>
											<li><a href="<?php echo e(url('user-cancelled')); ?>"><?php echo e(__('string.cancelled')); ?></a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="court-tab-content">
						<div class="card card-tableset">
							<div class="card-body">
								<div class="coache-head-blk">
									<div class="row align-items-center">
										<div class="col-md-5">
											<div class="court-table-head">
												<h4><?php echo e(__('string.my_bookings')); ?></h4>
											</div>
										</div>
										<div class="col-md-7">
											<div class="table-search-top">
												<div id="tablefilter"></div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="tab-content">
									<div class="tab-pane fade show active" id="nav-Recent" role="tabpanel" aria-labelledby="nav-Recent-tab">
										<div class="table-responsive table-datatble">
											<table class="table  datatable">
												<thead class="thead-light">
													<tr>
                                                        <th><?php echo e(__('string.sr_no')); ?></th>
                                                        <th><?php echo e(__('string.booking_id')); ?></th>
                                                        <th><?php echo e(__('string.items_name')); ?></th>
                                                        <th><?php echo e(__('string.schedule_date_time')); ?></th>
                                                        <th><?php echo e(__('string.booking_date')); ?></th>
                                                        <th><?php echo e(__('string.payment')); ?></th>
                                                        <th><?php echo e(__('string.status')); ?></th>
                                                        <th><?php echo e(__('string.details')); ?></th>
                                                        <th></th>
													</tr>
												</thead>
												<tbody>
												    <?php $__currentLoopData = $allbookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$bookings): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<tr>
													    <td><?php echo e(++$k); ?></td>
													    <td><?php echo e($bookings->booking_id); ?></td>
														<td>
															<h2 class="table-avatar">
																<a href="<?php echo e(url('item-details/'.$bookings->service->slug)); ?>"  target="_blank" class="avatar avatar-sm flex-shrink-0"><img class="avatar-img" src="<?php echo e(url('/public/storage/'.$bookings->service->thumbnail)); ?>" alt="title"></a>
																<span class="table-head-name flex-grow-1">
																	<a href="<?php echo e(url('item-details/'.$bookings->service->slug)); ?>" target="_blank"><?php echo e($bookings->service->title); ?></a>
																	<span><?php echo e($bookings->service->category->title); ?></span>
																</span>
															</h2>
														</td>
														<td class="table-date-time">
															<h4><?php echo e(date('D, d M Y',strtotime($bookings->date))); ?>

															<span><?php echo e(date('h:i A', strtotime($bookings->time))); ?></span>

															</h4>
														</td>	
														<td><?php echo e(date('D, d M Y',strtotime($bookings->created_at))); ?></td>
														<td><span class="pay-dark fs-16"><?php echo e($settings->currency); ?><?php echo e($bookings->payable_amount); ?></span></td>
														
														<td>
														<?php if($bookings->status==1): ?>
														    <span class="badge bg-success"><i class="feather-check-square me-1"></i> <?php echo e(__('string.confirmed')); ?></span>
														<?php elseif($bookings->status==2): ?>
													      <span class="badge bg-success"><i class="feather-check-square me-1"></i>	<?php echo e(__('string.completed')); ?></span>
														<?php elseif($bookings->status==3): ?>
															Declined												
														<?php elseif($bookings->status==4): ?>
												           	<span class="badge bg-danger"><img src="<?php echo e(URL::asset('/assets/img/icons/delete.svg')); ?>" alt="Icon" class="me-1"><?php echo e(__('string.cancelled')); ?></span>
														<?php else: ?>
														    Pending
														<?php endif; ?>
														</td>
														
														<td class="text-pink view-detail-pink"><a href="javascript:;" data-bs-toggle="modal" data-bs-target="#upcoming-court<?php echo e($bookings->booking_id); ?>"><i class="feather-eye"></i><?php echo e(__('string.view_details')); ?></a></td>
														<td class="text-end">
															<div class="dropdown dropdown-action table-drop-action">
																<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>
																<div class="dropdown-menu dropdown-menu-end">
																    <?php if($bookings->status==1): ?>																	    
																	    <a class="dropdown-item" href="javascript:void(0);"  data-bs-toggle="modal" data-bs-target="#cancel-court<?php echo e($bookings->booking_id); ?>"><i class="feather-corner-down-left"></i><?php echo e(__('string.cancel_booking')); ?></a>
																	    <a class="dropdown-item" href="javascript:void(0);"  data-bs-toggle="modal" data-bs-target="#reschedule-booking<?php echo e($bookings->booking_id); ?>"><i class="feather-edit"></i><?php echo e(__('string.reschedule')); ?></a>
																	    <a class="dropdown-item" href="<?php echo e(route('booking.invoice', $bookings->id)); ?>" target="_blank"><i class="fa fa-print"></i><?php echo e(__('string.print_invoice')); ?></a>
																    <?php elseif($bookings->status==2 && $bookings->is_rated==0): ?>
																        <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add-review<?php echo e($bookings->booking_id); ?>"><i class="feather-star"></i><?php echo e(__('string.write_review')); ?></a>
																    <?php endif; ?>
																</div>
															</div>
														</td>
													</tr>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div> 
						<div class="tab-footer">
							<div class="row">
								<div class="col-md-6">
									<div id="tablelength"></div>
								</div>
								<div class="col-md-6">
									<div id="tablepage"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <?php $__env->startComponent('components.modalpopup',['allbookings'=>$allbookings,'settings'=>$settings  ]); ?><?php echo $__env->renderComponent(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/web/bookings/bookings.blade.php ENDPATH**/ ?>