<div class="sortby-section court-sortby-section">
							<div class="sorting-info">
								<div class="row d-flex align-items-center">
									<div class="col-xl-12 col-lg-12 col-sm-12 col-12">
										<div class="coach-court-list">
											<ul class="nav">
											   <li><a  class="<?php if($type==null): ?> active <?php endif; ?>" href="<?php echo e(route('user-wallet')); ?>">All Transactions</a></li>
											    <li><a class="<?php if($type=='deposit'): ?> active <?php endif; ?>" href="<?php echo e(route('user-wallet','deposit')); ?>">Deposit</a></li>
												<li><a class="<?php if($type=='withdraw'): ?> active <?php endif; ?>" href="<?php echo e(route('user-wallet','withdraw')); ?>">Withdraw</a></li>
												<li><a class="<?php if($type=='refund'): ?> active <?php endif; ?>" href="<?php echo e(route('user-wallet','refund')); ?>">Refund</a></li>
												<li><a class="<?php if($type=='purchase'): ?> active <?php endif; ?>" href="<?php echo e(route('user-wallet','purchase')); ?>">Booking Payments</a></li>
											</ul>
										</div>
									</div>
						
								</div>
								
							</div>
						</div><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/components/user-wallet.blade.php ENDPATH**/ ?>