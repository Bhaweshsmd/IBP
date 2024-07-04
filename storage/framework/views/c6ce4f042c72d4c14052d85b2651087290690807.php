<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/viewService.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(asset('asset/style/viewService.css')); ?>">
    <style>
        #map {
            height:500px;
        }
        #modaldialog {
            max-width: 100% !important;
        }
       
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <input type="hidden" value="<?php echo e($service->id); ?>" id="serviceId">
    
    <div class="card">
        <div class="card-header">
            <h4> <?php echo e(__('Service Details')); ?> : </h4>
            <span><?php echo e($service->service_number); ?></span>
            <span class="mr-2 ml-2">:</span>
            <span> <?php echo e($service->title); ?></span>
            
            <?php if(has_permission(session()->get('user_type'), 'edit_services')): ?>
                <label class="switch mb-0 ml-auto">
                    <input id="serviceStatus" rel="<?php echo e($service->id); ?>" type="checkbox" class="onoff"
                        <?php echo e($service->status == 1 ? 'checked' : ''); ?>>
                    <span class="slider round"></span>
                </label>
            <?php endif; ?>
            
            <?php if(has_permission(session()->get('user_type'), 'delete_services')): ?>
                <a href="" id="deleteService" class="ml-2 btn btn-danger text-white"><?php echo e(__('Delete Service')); ?></a>
            <?php endif; ?>
            
            <a href="<?php echo e(route('services')); ?>" class="ml-2 btn btn-primary text-white"><i class="fa fa-chevron-left"></i> Back To Service List</a>
        </div>
        
        <div class="card-body">
            <div class="form-group">
                <div class="row"> 
                    <div class="col-md-12">
                        <ul class="nav nav-pills border-b  ml-0">
                            <li role="presentation" class="nav-item"> 
                                <a class="nav-link pointer badge bg-primary text-white" href="#tabSlots" role="tab" aria-controls="tabSlots" data-toggle="tab"><?php echo e(__('View Schedules and Stocks')); ?>

                                    <span class="badge badge-transparent "></span>
                                </a>
                                <a data-toggle="modal" data-target="#addBannerModal" href="" class="ml-auto nav-link badge bg-primary text-white text-white"><?php echo e(__('+ Add Schedule and Stock')); ?></a>
                            </li>
                        </ul>
                        <div class="tab-content tabs" id="home">     
                            <div role="tabpanel" class="tab-pane" id="tabSlots">
                                <div class="slote-table table-responsive col-12">
                                    <div  id="slottable">
                                    <div class="mt-2  ">
                                        <label class="mb-0 text-grey" for=""><?php echo e(__('Monday')); ?></label>
                                        <div class="slot-time-block">
                                            <?php $__currentLoopData = $slots['mondaySlots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <!--<span class="ml-2 btn btn-danger deleteSlots mt-2 " data-id=<?php echo e($item['id']); ?>>×</span>-->
                                                <div class="slot-time-inner">
                                                    <i class="fas fa-trash deleteSlots text-danger" data-id=<?php echo e($item['id']); ?>></i>
                                                    <span class="slot-time"><?php echo e(date('h:i A',strtotime($item['time']))); ?>-<?php echo e(date('h:i A',strtotime('+'.$item['booking_hours'].'hour',strtotime($item['time'])))); ?> (<?php echo e($item['booking_hours']); ?> Hr)</span>
                                                    <span class="total-slot"> <?php echo e($item['booking_limit']); ?> <?php echo e(__('Slots')); ?></span>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <div class="mt-2  ">
                                        <label class="mb-0 text-grey" for=""><?php echo e(__('Tuesday')); ?></label>
                                        <div class="slot-time-block">
                                            <?php $__currentLoopData = $slots['tuesdaySlots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="slot-time-inner">
                                                     <i class="fas fa-trash  deleteSlots text-danger" data-id=<?php echo e($item['id']); ?>></i>
                                                    <span class="slot-time"><?php echo e(date('h:i A',strtotime($item['time']))); ?>-<?php echo e(date('h:i A',strtotime('+'.$item['booking_hours'].'hour',strtotime($item['time'])))); ?> (<?php echo e($item['booking_hours']); ?> Hr)</span>
                                                    <span class="total-slot"> <?php echo e($item['booking_limit']); ?> <?php echo e(__('Slots')); ?></span>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <div class="mt-2  ">
                                        <label class="mb-0 text-grey" for=""><?php echo e(__('Wednesday')); ?></label>
                                        <div class="slot-time-block">
                                            <?php $__currentLoopData = $slots['wednesdaySlots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="slot-time-inner">
                                                     <i class="fas fa-trash deleteSlots text-danger" data-id=<?php echo e($item['id']); ?>></i>
                                                    <span class="slot-time"><?php echo e(date('h:i A',strtotime($item['time']))); ?>-<?php echo e(date('h:i A',strtotime('+'.$item['booking_hours'].'hour',strtotime($item['time'])))); ?> (<?php echo e($item['booking_hours']); ?> Hr)</span>
                                                    <span class="total-slot"> <?php echo e($item['booking_limit']); ?> <?php echo e(__('Slots')); ?></span>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <div class="mt-2  ">
                                        <label class="mb-0 text-grey" for=""><?php echo e(__('Thursday')); ?></label>
                                        <div class="slot-time-block">
                                            <?php $__currentLoopData = $slots['thursdaySlots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="slot-time-inner">
                                                     <i class="fas fa-trash  deleteSlots text-danger" data-id=<?php echo e($item['id']); ?>></i>
                                                    <span class="slot-time"><?php echo e(date('h:i A',strtotime($item['time']))); ?>-<?php echo e(date('h:i A',strtotime('+'.$item['booking_hours'].'hour',strtotime($item['time'])))); ?> (<?php echo e($item['booking_hours']); ?> Hr)</span>
                                                    <span class="total-slot"> <?php echo e($item['booking_limit']); ?> <?php echo e(__('Slots')); ?></span>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <div class="mt-2  ">
                                        <label class="mb-0 text-grey" for=""><?php echo e(__('Friday')); ?></label>
                                        <div class="slot-time-block">
                                            <?php $__currentLoopData = $slots['fridaySlots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="slot-time-inner">
                                                     <i class="fas fa-trash  deleteSlots text-danger" data-id=<?php echo e($item['id']); ?>></i>
                                                    <span class="slot-time"><?php echo e(date('h:i A',strtotime($item['time']))); ?>-<?php echo e(date('h:i A',strtotime('+'.$item['booking_hours'].'hour',strtotime($item['time'])))); ?> (<?php echo e($item['booking_hours']); ?> Hr)</span>
                                                    <span class="total-slot"> <?php echo e($item['booking_limit']); ?> <?php echo e(__('Slots')); ?></span>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <label class="mb-0 text-grey" for=""><?php echo e(__('Saturday')); ?></label>
                                        <div class="slot-time-block">
                                            <?php $__currentLoopData = $slots['saturdaySlots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                <div class="slot-time-inner">
                                                     <i class="fas fa-trash  deleteSlots text-danger" data-id=<?php echo e($item['id']); ?>></i>
                                                    <span class="slot-time"><?php echo e(date('h:i A',strtotime($item['time']))); ?>-<?php echo e(date('h:i A',strtotime('+'.$item['booking_hours'].'hour',strtotime($item['time'])))); ?> (<?php echo e($item['booking_hours']); ?> Hr)</span>
                                                    <span class="total-slot"> <?php echo e($item['booking_limit']); ?> <?php echo e(__('Slots')); ?></span>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <label class="mb-0 text-grey" for=""><?php echo e(__('Sunday')); ?></label>
                                        <div class="slot-time-block">
                                            <?php $__currentLoopData = $slots['sundaySlots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="slot-time-inner">
                                                     <i class="fas fa-trash  deleteSlots text-danger" data-id=<?php echo e($item['id']); ?>></i>
                                                    <span class="slot-time"><?php echo e(date('h:i A',strtotime($item['time']))); ?>-<?php echo e(date('h:i A',strtotime('+'.$item['booking_hours'].'hour',strtotime($item['time'])))); ?> (<?php echo e($item['booking_hours']); ?> Hr)</span>
                                                   <span class="total-slot"> <?php echo e($item['booking_limit']); ?> <?php echo e(__('Slots')); ?></span>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <form action="<?php echo e(route('updateService_Admin')); ?>" method="post" enctype="multipart/form-data" class="" autocomplete="off">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?php echo e($service->id); ?>">
                <ul class="nav nav-pills border-b  ml-0">
                    <li role="presentation" class="nav-item active">
                        <a class="nav-link pointer active " href="#tabSlotstitle" role="tab" aria-controls="tabSlots" data-toggle="tab"><?php echo e(__('English')); ?>

                            <span class="badge badge-transparent "></span>
                        </a>
                    </li>
                    <li role="presentation" class="nav-item ">
                        <a class="nav-link pointer  " href="#tabPapiamentutitle" role="tab" aria-controls="tabSlots" data-toggle="tab"><?php echo e(__('Papiamentu')); ?>

                            <span class="badge badge-transparent "></span>
                        </a>
                    </li>
                    <li role="presentation" class="nav-item ">
                        <a class="nav-link pointer " href="#tabDutchtitle" role="tab" aria-controls="tabSlots" data-toggle="tab"><?php echo e(__('Dutch')); ?>

                            <span class="badge badge-transparent "></span>
                        </a>
                    </li>
                </ul>  
                
                <div class="form-row">
                    <div class="tab-content tabs col-sm-12" id="home">     
                        <div role="tabpanel" class="tab-pane active" id="tabSlotstitle">
                            <div class="form-group ">
                                <label for=""><?php echo e(__('Title In English')); ?></label>
                                <input type="text" class="form-control" name="title"   value="<?php echo e(old('title',$service->title)); ?>" required>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="tabPapiamentutitle">
                            <div class="form-group ">
                                <label for=""><?php echo e(__('Title In Papiamentu ')); ?></label>
                                <input type="text" class="form-control" name="title_in_papiamentu"   value="<?php echo e(old('title_in_papiamentu',$service->title_in_papiamentu)); ?>" >
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="tabDutchtitle">
                            <div class="form-group ">
                                <label for=""><?php echo e(__('Title In Dutch ')); ?></label>
                                <input type="text" class="form-control" name="title_in_dutch"   value="<?php echo e(old('title_in_dutch',$service->title_in_dutch)); ?>" >
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-row ">
                    <div class="form-group col-md-4">
                        <label for=""><?php echo e(__('Category')); ?></label>
                        <select name="category_id" id="category_id" class="form-control" aria-label="Default select example">
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php echo e($cat->id == $service->category_id ? 'selected' : ''); ?> value="<?php echo e($cat->id); ?>"><?php echo e($cat->title); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for=""><?php echo e(__('Service Type')); ?></label>
                        <div class="input-group">
                             <select class="form-control" name="service_type" value="<?php echo e(old('service_type')); ?>" required>
                                <option value="0" <?php if($service->service_type==0): ?> selected <?php endif; ?> ><?php echo e(__('Quantity Wise')); ?></option>
                                <option value="1"  <?php if($service->service_type==1): ?> selected <?php endif; ?>><?php echo e(__('Member Wise')); ?></option>
                                </select>
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for=""><?php echo e(__('Quantity')); ?></label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="qauntity" min="1"  value="<?php echo e(old('qauntity',$service->qauntity)); ?>" required>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="service_time"  value="60"  required>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <a data-toggle="modal" data-target="#addMapModal" href="" class="ml-auto badge bg-primary text-white text-white"><?php echo e(__('Select co-ordinates ')); ?></a>
                        <label for=""><?php echo e(__('Service Latitude')); ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="latitude" id="latitude" value="<?php echo e(old('latitude',$service->latitude)); ?>" >
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for=""><?php echo e(__('Service Longitude')); ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="logintude" id="logintude" value="<?php echo e(old('logintude',$service->logintude)); ?>"   >
                        </div>
                    </div>
                </div>
                
                <div class="form-row ">
                    <div class="form-group col-md-4">
                        <label for=""><?php echo e(__('Resident Price Per Hours')); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <?php echo e($settings->currency); ?>

                                </div>
                            </div>
                            <input type="number" class="form-control" name="price" step="any" value="<?php echo e(old('price',$service->price)); ?>" required>
                        </div>
                    </div>
    
                    <div class="form-group col-md-4">
                        <label for=""><?php echo e(__('Resident Discount')); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    %
                                </div>
                            </div>
                            <input type="number" class="form-control" name="discount" step="any" value="<?php echo e(old('discount',$service->discount)); ?>" >
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for=""><?php echo e(__('Resident Cancellation Charges')); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    %
                                </div>
                            </div>
                            <input type="number" class="form-control" name="local_cancellation_charges" step="any" value="<?php echo e(old('local_cancellation_charges',$service->local_cancellation_charges)); ?>" >
                        </div>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for=""><?php echo e(__('Non-Resident Price Per Hours')); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <?php echo e($settings->currency); ?>

                                </div>
                            </div>
                            <input type="number" class="form-control" name="foreiner_price"  step="any" value="<?php echo e(old('foreiner_price',$service->foreiner_price)); ?>" required>
                        </div>
                    </div>
    
                    <div class="form-group col-md-4">
                        <label for=""><?php echo e(__('Non-Resident Discount')); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    %
                                </div>
                            </div>
                            <input type="number" class="form-control" name="foreiner_discount" min=0 step="any" value="<?php echo e(old('foreiner_discount',$service->foreiner_discount)); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for=""><?php echo e(__('Non-Resident Cancellation Charges')); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    %
                                </div>
                            </div>
                            <input type="number" class="form-control" name="foreiner_cancellation_charges" step="any" value="<?php echo e(old('foreiner_cancellation_charges',$service->foreiner_cancellation_charges)); ?>" >
                        </div>
                    </div>
                </div>
                
                <div class="form-row ">
                    <div class="form-group col-md-3">
                        <div class="form-group mt-0">
                            <label for=""><?php echo e(__('Thumbnail')); ?></label>
                            <div class="d-flex mb-2">
                                <div class="service_image">
                                    <img width="100" class="rounded" height="100" src="<?php echo e(url('public/storage/'.$service->thumbnail)); ?>" alt="">
                                </div>
                            </div>
                        </div>
                        <label for=""><?php echo e(__('Service Thumbnail')); ?></label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="thumbnail" value="<?php echo e(old('thumbnail')); ?>" accept=".png,.jpeg,.jpg" >
                        </div>
                    </div>
                    <div class="form-group col-md-9">
                        <div class="form-group mt-0">
                            <label for=""><?php echo e(__('Gallery')); ?></label>
                            <div class="d-flex mb-2">
                                <?php $__currentLoopData = $service->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="service_image">
                                        <img width="100" class="rounded" height="100" src="<?php echo e(url('public/storage/'.$image->image)); ?>" alt="">
                                        <i rel="<?php echo e($image->id); ?>" class="fas fa-trash img-delete"></i>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <label for=""><?php echo e(__('Gallery')); ?></label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="images[]" value="<?php echo e(old('images[]')); ?>" multiple  accept=".png,.jpeg,.jpg">
                        </div>
                    </div>
                </div>
                
                <div class="form-row ">
                    <div class="form-group col-md-12">
                        <div class="form-group mt-0">
                            <label for=""><?php echo e(__('Service Map images')); ?></label>
                            <div class="d-flex mb-2">
                                <?php $__currentLoopData = $service->map_images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="service_image">
                                        <img width="100" class="rounded" height="100" src="<?php echo e(url('public/storage/'.$image->image)); ?>" alt="">
                                        <i rel="<?php echo e($image->id); ?>" class="fas fa-trash map-img-delete"></i>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                     </div>    
                        <div class="form-group col-md-4">
                        <label for=""><?php echo e(__('Service Map images')); ?></label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="map_images[]" value="<?php echo e(old('map_images[]')); ?>" multiple  accept=".png,.jpeg,.jpg">
                        </div>
                    </div>
                </div>
              
                <ul class="nav nav-pills border-b  ml-0">
                    <li role="presentation" class="nav-item active">
                        <a class="nav-link pointer active " href="#tabEnglish" role="tab" aria-controls="tabSlots" data-toggle="tab"><?php echo e(__('English')); ?>

                            <span class="badge badge-transparent "></span>
                        </a>
                    </li>
                    <li role="presentation" class="nav-item ">
                        <a class="nav-link pointer  " href="#tabPapiamentu" role="tab" aria-controls="tabSlots" data-toggle="tab"><?php echo e(__('Papiamentu')); ?>

                            <span class="badge badge-transparent "></span>
                        </a>
                    </li>
                    <li role="presentation" class="nav-item ">
                        <a class="nav-link pointer " href="#tabDutch" role="tab" aria-controls="tabSlots" data-toggle="tab"><?php echo e(__('Dutch')); ?>

                            <span class="badge badge-transparent "></span>
                        </a>
                    </li>
                </ul>
                
                <div class="form-row ">
                    <div class="tab-content tabs col-sm-12" id="home">     
                        <div role="tabpanel" class="tab-pane active" id="tabEnglish">
                            <div class="form-group">
                                <label for=""><?php echo e(__('About In English')); ?></label>
                                <textarea type="text" class="form-control" name="about"><?php echo e(old('about',$service->about)); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for=""><?php echo e(__('Rules In English')); ?></label>
                                <textarea  class="summernote-simple" id="summernote" name="rules"><?php echo e(old('rules',$service->rules)); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for=""><?php echo e(__('History In English')); ?></label>
                                <textarea  class="summernote-simple" id="summernote" name="history"><?php echo e(old('history',$service->history)); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="included_items"><?php echo e(__('Included Items In English')); ?></label>
                                <textarea  class="summernote-simple" id="summernote" name="included_items"><?php echo e(old('included_items',$service->included_items)); ?></textarea>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="tabPapiamentu">
                            <div class="form-group ">
                                <label for=""><?php echo e(__('About In Papiamentu')); ?></label>
                                <textarea type="text" class="form-control" name="about_in_papiamentu"><?php echo e(old('about_in_papiamentu',$service->about_in_papiamentu)); ?></textarea>
                            </div>
                            <div class="form-group ">
                                <label for=""><?php echo e(__('Rules In Papiamentu')); ?></label>
                                <textarea  class="summernote-simple" id="summernote" name="rules_in_papiamentu"><?php echo e(old('rules_in_papiamentu',$service->rules_in_papiamentu)); ?></textarea>
                            </div>
                            <div class="form-group ">
                                <label for="included_items_in_papiamentu"><?php echo e(__('History In Papiamentu')); ?></label>
                                <textarea  class="summernote-simple" id="summernote" name="history_in_papiamentu"><?php echo e(old('history_in_papiamentu',$service->history_in_papiamentu)); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for=""><?php echo e(__('Included Items In Papiamentu')); ?></label>
                                <textarea  class="summernote-simple" id="summernote" name="included_items_in_papiamentu"><?php echo e(old('included_items_in_papiamentu',$service->included_items_in_papiamentu)); ?></textarea>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="tabDutch">
                            <div class="form-group ">
                                <label for=""><?php echo e(__('About In Dutch')); ?></label>
                                <textarea type="text" class="form-control" name="about_in_dutch"><?php echo e(old('about_in_dutch',$service->about_in_dutch)); ?></textarea>
                            </div>
                            <div class="form-group ">
                                <label for=""><?php echo e(__('Rules In Dutch')); ?></label>
                                <textarea  class="summernote-simple" id="summernote" name="rules_in_dutch"><?php echo e(old('rules_in_dutch',$service->rules_in_dutch)); ?></textarea>
                            </div>
                            <div class="form-group ">
                                <label for=""><?php echo e(__('History In Dutch')); ?></label>
                                <textarea  class="summernote-simple" id="summernote" name="history_in_dutch"><?php echo e(old('history_in_dutch',$service->history_in_dutch)); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="included_items_in_dutch"><?php echo e(__('Included Items In Dutch')); ?></label>
                                <textarea  class="summernote-simple" id="summernote" name="included_items_in_dutch"><?php echo e(old('included_items_in_dutch',$service->included_items_in_dutch)); ?></textarea>
                            </div>
                       </div>
                    </div>
                </div>
                
                <?php if(has_permission(session()->get('user_type'), 'edit_services')): ?>
                    <div class="form-group text-right">
                        <input class="btn btn-primary mr-1" type="submit" value="<?php echo e(__('Update')); ?>">
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <div class="modal fade" id="addBannerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><?php echo e(__('Add Times')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('add.booking.slots')); ?>" method="post" enctype="multipart/form-data" id="addBannerForm" autocomplete="off">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="service_id" value="<?php echo e($service->id); ?>">
                        
                        <div class="form-group">
                            <label><?php echo e(__('weekday')); ?></label>
                            <select class="form-control" name="weekday" value="<?php echo e(old('weekday')); ?>" required>
                                <option value="" ><?php echo e(__('Select Weekday')); ?></option>
                                <option value="1" ><?php echo e(__('Monday')); ?></option>
                                <option value="2" ><?php echo e(__('Tuesday')); ?></option>
                                <option value="3" ><?php echo e(__('Wednesday')); ?></option>
                                <option value="4" ><?php echo e(__('Thursday')); ?></option>
                                <option value="5" ><?php echo e(__('Friday')); ?></option>
                                <option value="6" ><?php echo e(__('Saturday')); ?></option>
                                <option value="7" ><?php echo e(__('Sunday')); ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo e(__('Select Booking Hours')); ?></label>
                            <select class="form-control" name="booking_hours" value="<?php echo e(old('booking_hours')); ?>" required>
                                <option value="" ><?php echo e(__('Select Booking Hours')); ?></option>
                                <?php for($i=1;$i<=24;$i++): ?> 
                                    <option value="<?php echo e($i); ?>" ><?php echo e($i); ?><?php echo e(__(' Hours')); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo e(__('Time')); ?></label>
                            <input class="form-control" type="time" name="time" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo e(__('Slots')); ?></label>
                            <input class="form-control" type="number" min='1' name="booking_limit" required>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="addMapModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" id="modaldialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><?php echo e(__('Select co-ordinates to click on image')); ?></h5>&nbsp; &nbsp;
                    <spna>Latitude:</spna>&nbsp;<span id="let"><?php echo e($service->latitude); ?></span> &nbsp; &nbsp;
                    <spna>Longitude:</spna>&nbsp;<span id="long"><?php echo e($service->logintude); ?></span>
                    <button type="button" class="close btn btn-success" data-dismiss="modal" aria-label="Close" style="padding: 7px 12px; background-color: green;">Save</button>
                </div>
                <div class="modal-body">
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
                    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
                    <div class="content map-content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xl-12 map-right">
                                    <div id="map" class="maplisting"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        var map = L.map('map', {
                            crs: L.CRS.Simple
                        });
                    
                        var imageUrl = "<?php echo e(asset('assets/map.jpg')); ?>";
                        var  latitude = "<?php echo e($service->latitude); ?>";
                        var  logintude = "<?php echo e($service->logintude); ?>";
                        var imageBounds = [[0, 0], [500,1400]];
                    
                        var imageOverlay   = L.imageOverlay(imageUrl, imageBounds).addTo(map);
                        map.fitBounds(imageBounds);
                        var bounds = imageOverlay.getBounds();
                        var centerLat = (bounds.getSouth() + bounds.getNorth()) / 2;
                        var centerLng = (bounds.getWest() + bounds.getEast()) / 2;
                        var center = L.latLng(centerLat, centerLng);
                        var marker = L.marker([latitude,logintude]).addTo(map);
                        marker.bindPopup("<b><?php echo e($service->title); ?></b>").openPopup();
                    
                        map.on('click', function(e) {
                            var lat = e.latlng.lat;
                            var lng = e.latlng.lng;
                            $("#latitude").val(lat);
                            $("#logintude").val(lng);
                            $("#let").text(lat);
                            $("#long").text(lng);
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
    
    <?php if(session()->has('slot_message')): ?>
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Warning",
                    text: "<?php echo e(session()->get('slot_message')); ?>",
                    type: "warning",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/services/edit.blade.php ENDPATH**/ ?>