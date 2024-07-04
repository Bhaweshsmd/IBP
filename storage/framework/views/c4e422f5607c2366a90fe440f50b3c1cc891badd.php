<?php $page = 'user-wallet'; ?>


<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
        <?php echo e(__('string.ibp_account')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?>
            <?php echo e(__('string.home')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?>
        <?php echo e(__('string.ibp_account')); ?>

        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <?php $__env->startComponent('components.user-dashboard'); ?>
	<?php echo $__env->renderComponent(); ?>

	<div class="content court-bg">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-lg-5 d-flex">
					<div class="wallet-wrap flex-fill">
						<div class="wallet-bal">
							<div class="wallet-img">
								<div class="wallet-amt">
									<h5><?php echo e(__('string.your_ibp_account_balance')); ?></h5>
									<h2><?php echo e($settings->currency); ?><?php echo e(number_format($userDetails->wallet,2)); ?></h2>
								</div>
							</div>
							<div class="payment-btn">
								<a href="#" class="btn balance-add" data-bs-toggle="modal" data-bs-target="#add-payment"><?php echo e(__('string.add_fund')); ?></a>
							</div>
						</div>
						<ul>
							<li>
								<h6><?php echo e(__('string.total_funds_added')); ?></h6>
								<h3><?php echo e($settings->currency); ?><?php echo e(number_format($total_fund_added,2)); ?></h3>
							</li>
							<li>
								<h6><?php echo e(__('string.total_bookings')); ?></h6>
								<h3><?php echo e($settings->currency); ?><?php echo e(number_format($total_bookings,2)); ?></h3>
							</li>
							<li>
								<h6><?php echo e(__('string.total_refund')); ?></h6>
								<h3><?php echo e($settings->currency); ?><?php echo e(number_format($total_refund,2)); ?></h3>
							</li>
						</ul>
					</div>									
				</div>
			
				<div class="col-md-12 col-lg-5 d-flex">
					<div class="wallet-wrap flex-fill" id="wallet-wrap">
						<div class="wallet-bal">
							<div class="wallet-img">
								<div class="wallet-amt">
									<h5><?php echo e(__('string.available_to_withdraw')); ?></h5>
									<h2><?php echo e($settings->currency); ?><?php echo e(number_format($userDetails->wallet,2)); ?></h2>
								</div>
							</div>
							<div class="payment-btn">
								<a href="#" class="btn balance-add" data-bs-toggle="modal" data-bs-target="#request-payment"><?php echo e(__('string.request_withdraw')); ?></a>
							</div>
						</div>
						<ul>
							<li>
								<h6><?php echo e(__('string.pending_withdraw')); ?></h6>
								<h3><?php echo e($settings->currency); ?><?php echo e(number_format($total_withdraw_pending,2)); ?></h3>
							</li>
							<li>
								<h6><?php echo e(__('string.success_withdraw')); ?></h6>
								<h3><?php echo e($settings->currency); ?><?php echo e(number_format($total_withdraw_success,2)); ?></h3>
							</li>
							<li>
								<h6><?php echo e(__('string.total_withdraw')); ?></h6>
								<h3><?php echo e($settings->currency); ?><?php echo e(number_format($total_withdraw,2)); ?></h3>
							</li>
						</ul>
					</div>									
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12">
				     <?php $__env->startComponent('components.user-wallet',['type'=>$type]); ?><?php echo $__env->renderComponent(); ?>
					<div class="court-tab-content">
						<div class="card card-tableset">
							<div class="card-body">
								<div class="coache-head-blk">
									<div class="row align-items-center">
										<div class="col-lg-5">
											<div class="court-table-head">
												<h4><?php echo e(__('string.transaction')); ?></h4>
											</div>
										</div>
										<div class="col-lg-7">
											<div class="table-search-top invoice-search-top">
												<div id="tablefilter"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="table-responsive table-datatble">
									<table class="table datatable">
										<thead class="thead-light">
                                            <tr>
                                                <th>Sr.No</th>
                                                <th><?php echo e(__('string.ref_id')); ?></th>
                                                <th><?php echo e(__('string.transaction_for')); ?></th>
                                                <?php if($type=='purchase'): ?>
                                                    <th><?php echo e(__('string.booking_id')); ?></th>
                                                <?php endif; ?>
                                                <th><?php echo e(__('string.date_time')); ?>  </th>
                                                <?php if($type=='withdraw'): ?>
                                                    <th><?php echo e(__('string.request_amount')); ?>  </th>
                                                    <th><?php echo e(__('string.fee')); ?>  </th>
                                                <?php endif; ?>
                                                <?php if($type=='withdraw'): ?> 
                                                    <th><?php echo e(__('string.amount_received')); ?></th>
                                                <?php elseif($type=='deposit'): ?> 
                                                    <th><?php echo e(__('string.deposit_amount')); ?></th>
                                                <?php elseif($type=='refund'): ?> 
                                                    <th><?php echo e(__('string.refund_amount')); ?></th>
                                                <?php else: ?> 
                                                    <th><?php echo e(__('string.payment')); ?></th>
                                                <?php endif; ?>
                                                <?php if($type=='purchase'): ?>
                                                    <th><?php echo e(__('string.print_invoice')); ?></th>
                                                <?php endif; ?>
                                                <?php if($type=='deposit'): ?>
                                                    <th><?php echo e(__('string.fee')); ?></th>
                                                    <th><?php echo e(__('string.total_amount')); ?></th>
                                                <?php endif; ?>
                                                <?php if($type=='refund'): ?>    
                                                    <th><?php echo e(__('string.booking_id')); ?></th>
                                                    <th><?php echo e(__('string.total_booking_amount')); ?></th>
                                                    <th><?php echo e(__('string.cancellation_charges')); ?></th>
                                                <?php endif; ?>
                                            </tr>
										</thead>
										<tbody>
										    <?php $__currentLoopData = $statement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<tr> 
												    <td><?php echo e(++$k); ?></td>
													<td><a href="javascript::void(0)" class="text-primary"><?php echo e($value->transaction_id); ?></a></td>
													<td>
														<h2 class="table-avatar">
															<span class="table-head-name flex-grow-1">
																<a href="#">
                                                                    <?php if($value->type==1): ?>
                                                                       purchase
                                                                    <?php elseif($value->type==2): ?>
                                                                        withdraw
                                                                    <?php elseif($value->type==3): ?>
                                                                      refund
                                                                    <?php else: ?>
                                                                      deposit
                                                                    <?php endif; ?>
																</a>
															</span>
														</h2>
													</td>
													<?php if($type=='purchase'): ?>
												        <td><a href="javascript::void(0)" class="text-primary"><?php echo e($value->booking_id); ?></a></td>
												    <?php endif; ?> 
													<td class="table-date-time">
														<h4><?php echo e(date('d M,Y',strtotime($value->created_at))); ?><span><?php echo e(date('D H:i A',strtotime($value->created_at))); ?></span></h4>
													</td>
													<?php if($type=='withdraw'): ?>
												   	     <td><span class="pay-dark fs-16"><?php echo e($settings->currency); ?><?php echo e(number_format($value->amount,2)); ?></span></td>
												         <td><span class="text-danger">-<?php echo e($settings->currency); ?><?php echo e(number_format($value->charge_amount,2)); ?></span></td>
												    <?php endif; ?>
												        
												    <?php if($type=='deposit'): ?>
												         <td><span class="pay-dark fs-16"><?php echo e($settings->currency); ?><?php echo e(number_format($value->amount,2)); ?></span></td>
												         <td><span class="text-danger">+<?php echo e($settings->currency); ?><?php echo e(number_format($value->charge_amount,2)); ?></span></td>
												    <?php endif; ?>
												    
													<?php if($type=='deposit'): ?>
													    <td><span class="pay-dark fs-16"><?php echo e($settings->currency); ?><?php echo e(number_format($value->total_amount,2)); ?></span></td>
													<?php else: ?>
    													<?php if($value->booking_id): ?>
    													    <td><span class="pay-dark fs-16"><?php echo e($settings->currency); ?><?php echo e(number_format($value->amount,2)); ?></span></td>
    													<?php else: ?>
    												    	<td><span class="pay-dark fs-16"><?php echo e($settings->currency); ?><?php echo e(number_format($value->total_amount,2)); ?></span></td>
    													<?php endif; ?>
													<?php endif; ?>
													
													<?php if($type=='purchase'): ?>
    													<?php
    													    $bookings = DB::table('bookings')->where('booking_id', $value->booking_id)->first();
    													?>
												        <td><a href="<?php echo e(route('booking.invoice', $bookings->id)); ?>" class="text-primary" target="_blank">Download</a></td>
												    <?php endif; ?> 
													
													<?php if($type=='refund'): ?>
												        <td><a href="javascript::void(0)" class="text-primary"><?php echo e($value->booking_id); ?></a></td>
												     	<td><span class="pay-dark fs-16"><?php echo e($settings->currency); ?><?php echo e(number_format($value->total_amount,2)); ?></span></td>
												     	<td><span class="text-danger">-<?php echo e($settings->currency); ?><?php echo e(number_format($value->charge_amount,2)); ?></span></td>
                        							<?php endif; ?>
												</tr>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</tbody>
									</table>
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

    <?php $__env->startComponent('components.modalpopup',['userDetails'=>$userDetails,'settings'=>$settings,'fee'=>$fee,'withdraw_fee'=>$withdraw_fee]); ?>
    <?php echo $__env->renderComponent(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/web/users/wallet.blade.php ENDPATH**/ ?>