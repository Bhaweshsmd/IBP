<?php $page = 'user-wallet'; ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
        <?php echo e(__('string.notification')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?>
            <?php echo e(__('string.home')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_2'); ?>
        <?php echo e(__('string.notification')); ?>

        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
 
	<div class="content court-bg">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="court-tab-content">
						<div class="card card-tableset">
							<div class="card-body">
								<div class="coache-head-blk">
									<div class="row align-items-center">
										<div class="col-lg-5">
											<div class="court-table-head">
												<h4><?php echo e(__('string.notification')); ?>s</h4>
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
											   <th>Sr. No.</th>
											   <th><?php echo e(__('string.title')); ?></th>
											   <th><?php echo e(__('string.content')); ?></th>
											   <th><?php echo e(__('string.date_time')); ?> </th>
											</tr>
										</thead>
										
										<tbody>
										    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										        <?php
										            $language = session()->get('locale');
                                                    if(empty($language)){
                                                        $cont_lang = 'en';
                                                    }else{
                                                        $cont_lang = $language;
                                                    }
                                                    
                                                    $template = DB::table('notification_template')->where('id', $notification->temp_id)->first();
                                                    if(!empty($template)){
                                                        if($cont_lang == 'en'){
                                                            $subject = $template->notification_subjects;
                                                            $content = $template->notification_content;
                                                        }elseif($cont_lang == 'pap'){
                                                            $subject = $template->notification_subject_pap;
                                                            $content = $template->notification_content_pap;
                                                        }elseif($cont_lang == 'nl'){
                                                            $subject = $template->notification_subject_nl;
                                                            $content = $template->notification_content_nl;
                                                        }
                                                        
                                                        if($notification->temp_id == '1'){
                                                            $content = str_replace(["{amount}"],[$notification->amount], $content);
                                                        }elseif($notification->temp_id == '2'){
                                                            $subject = str_replace(["{booking_id}"],[$notification->booking_id], $subject);
                                                            $content = str_replace(["{item_name}","{booking_id}"],[$notification->item_name,$notification->booking_id], $content);
                                                        }elseif($notification->temp_id == '4'){
                                                            $content = str_replace(["{booking_id}"],[$notification->booking_id], $content);
                                                        }elseif($notification->temp_id == '5'){
                                                            $content = str_replace(["{booking_id}"],[$notification->booking_id], $content);
                                                        }elseif($notification->temp_id == '6'){
                                                            $content = str_replace(["{total}"],[$notification->total], $content);
                                                        }elseif($notification->temp_id == '7'){
                                                            $content = str_replace(["{total}"],[$notification->total], $content);
                                                        }elseif($notification->temp_id == '8'){
                                                            $content = str_replace(["{amount}"],[$notification->amount], $content);
                                                        }elseif($notification->temp_id == '9'){
                                                            $content = str_replace(["{card_number}"],[$notification->card_number], $content);
                                                        }elseif($notification->temp_id == '10'){
                                                            $content = str_replace(["{amount}","{card_number}"],[$notification->amount,$notification->card_number], $content);
                                                        }elseif($notification->temp_id == '11'){
                                                            $content = str_replace(["{ticket_id}"],[$notification->ticket_id], $content);
                                                        }elseif($notification->temp_id == '12'){
                                                            $content = str_replace(["{date_time}"],[$notification->date_time], $content);
                                                        }elseif($notification->temp_id == '14'){
                                                            $content = str_replace(["{ticket_id}"],[$notification->ticket_id], $content);
                                                        }elseif($notification->temp_id == '15'){
                                                            $subject = str_replace(["{ticket_id}"],[$notification->ticket_id], $subject);
                                                            $content = str_replace(["{ticket_id}"],[$notification->ticket_id], $content);
                                                        }
                                                    }else{
                                                        $subject = $notification->title;
                                                        $content = $notification->description;
                                                    }
                                                ?>
												<tr> 
												    <td><?php echo e($loop->iteration); ?></td>
													<td><a href="javascript:void(0)" class="text-primary"><?php echo e($subject); ?></a></td>
													<td><?php echo $content; ?></td>
													<td class="table-date-time">
														<h4><?php echo e(date('d M,Y',strtotime($notification->created_at))); ?><span><?php echo e(date('D H:i A',strtotime($notification->created_at))); ?></span></h4>
													</td>
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

    <?php $__env->startComponent('components.modalpopup',['userDetails'=>$userDetails,'settings'=>$settings,'fee'=>$fee]); ?>
    <?php echo $__env->renderComponent(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/web/users/notification.blade.php ENDPATH**/ ?>