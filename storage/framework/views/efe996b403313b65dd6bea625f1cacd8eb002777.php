<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/viewSalon.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(asset('asset/style/viewSalon.css')); ?>">
<?php $__env->stopSection(); ?>

<style>
    .bank-details span {
        display: block;
        line-height: 18px;
    }
</style>

<?php $__env->startSection('content'); ?>
    <input type="hidden" value="<?php echo e($salon->id); ?>" id="salonId">

    <div class="card">
        <div class="card-header">
              <div class="">
                    <div class="d-flex mt-1 align-items-center">
                        <img class="rounded-circle owner-img-border" width="40" height="40" src="<?php echo e(url('public/storage/'.$salon->owner_photo)); ?>" alt="">
                        <p class="mt-0 p-data mb-0 ml-2"><?php echo e($salon->owner_name); ?></p>
                    </div>
                </div>
            <h4>
                <?php echo e($salon->salon_name); ?> / <?php echo e($salon->salon_number); ?>

            </h4>

            <?php if($salon->status == $salonStatus['statusSalonPending']): ?>
                <span class="badge bg-warning text-white "><?php echo e(__('Pending Review')); ?> </span>
            <?php elseif($salon->status == $salonStatus['statusSalonActive']): ?>
                <span class="badge bg-success text-white "><?php echo e(__('Active')); ?> </span>
            <?php elseif($salon->status == $salonStatus['statusSalonBanned']): ?>
                <span class="badge bg-danger text-white "><?php echo e(__('Banned')); ?> </span>
            <?php endif; ?>

            <?php if($salon->top_rated == 1): ?>
                <span class="ml-2 badge bg-topRated bg-primary text-white "><?php echo e(__('Top Rated')); ?> </span>
            <?php endif; ?>
        </div>
        <div class="card-body">

            <div class="form-row">
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for=""><?php echo e(__('Accepted Bookings')); ?></label>
                    <p class="mt-0 p-data"><?php echo e($total_accepted); ?></p>
                </div>
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for=""><?php echo e(__('Completed Bookings')); ?></label>
                    <p class="mt-0 p-data"><?php echo e($total_completed); ?></p>
                </div>
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for=""><?php echo e(__('Cancelled Bookings')); ?></label>
                    <p class="mt-0 p-data"><?php echo e($total_cancelled); ?></p>
                </div>
            </div>

            <div class="form-row mt-3">
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for=""><?php echo e(__('Overall Rating')); ?></label>
                    <p class="mt-0 p-data"><?php echo e(number_format($salon->rating,2)); ?></p>
                </div>

                <div class="col-md-2">
                    <label class="mb-0 text-grey" for=""><?php echo e(__('Mon-Fri Time')); ?></label>
                    <p class="mt-0 p-data"><?php echo e($salon->mon_fri_from); ?> - <?php echo e($salon->mon_fri_to); ?></p>
                </div>

                <div class="col-md-2">
                    <label class="mb-0 text-grey" for=""><?php echo e(__('Sat-Sun Time')); ?></label>
                    <p class="mt-0 p-data"><?php echo e($salon->sat_sun_from); ?> - <?php echo e($salon->sat_sun_to); ?></p>
                </div>

                <div class="col-md-2">
                    <label class="mb-0 text-grey d-block" for=""><?php echo e(__('Company Location')); ?></label>
                    <a target="_blank" class="badge bg-primary text-white mt-1"
                        href="https://www.google.com/maps/?q=<?php echo e($salon->salon_lat); ?>,<?php echo e($salon->salon_long); ?>"><?php echo e(__('Click To Locate')); ?></a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills border-b  ml-0">

                <li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#tabDetails"
                        aria-controls="home" role="tab" data-toggle="tab"><?php echo e(__('Details')); ?><span
                            class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabServices" role="tab"
                        data-toggle="tab"><?php echo e(__('Services')); ?>

                        <span class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabBookings" role="tab"
                        aria-controls="tabBookings" data-toggle="tab"><?php echo e(__('Bookings')); ?>

                        <span class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabGallery" role="tab"
                        aria-controls="tabGallery" data-toggle="tab"><?php echo e(__('Gallery')); ?>

                        <span class="badge badge-transparent "></span></a>
                </li>
                
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabMap" role="tab"
                        aria-controls="tabMap" data-toggle="tab"><?php echo e(__('Area Map')); ?>

                        <span class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabReviews" role="tab"
                        aria-controls="tabReviews" data-toggle="tab"><?php echo e(__('Reviews')); ?>

                        <span class="badge badge-transparent "></span></a>
                </li>

            </ul>

            
           
        </div>
         
        <div class="card-body">
            <div class="tab-content tabs" id="home">
          
                <div role="tabpanel" class="tab-pane active" id="tabDetails">
     
                    <?php if(has_permission(session()->get('user_type'), 'add_company')): ?>
                        <a data-toggle="modal" data-target="#addSalonImagesModal" href="" id="addSalonImages" class="ml-auto btn btn-primary text-white float-right mb-3"><i class="fa fa-plus"></i> <?php echo e(__('Add New Company Images')); ?></a>
                    <?php endif; ?>
                    <div class="form-group mt-0">
                        <label for=""><?php echo e(__('Images')); ?></label>
                        <div class="d-flex mb-2">
                            <?php $__currentLoopData = $salon->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="salon_image">
                                    <img width="100" class="rounded" height="100" src="<?php echo e(url('public/storage/'.$image->image)); ?>" alt="">
                                    <i rel="<?php echo e($image->id); ?>" class="fas fa-trash img-delete"></i>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <form action="" method="post" enctype="multipart/form-data" class=""
                        id="salonDetailsForm" autocomplete="off">
                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="id" value="<?php echo e($salon->id); ?>">

                        <div class="form-row ">
                            <div class="form-group col-md-3">
                                <label for=""><?php echo e(__('Email')); ?></label>
                                <input type="text" class="form-control" name="email" disabled
                                    value="<?php echo e($salon->email); ?>">
                            </div>

                            <div class="form-group col-md-3">
                                <label for=""><?php echo e(__('Company Name')); ?></label>
                                <input type="text" class="form-control" name="salon_name"
                                    value="<?php echo e($salon->salon_name); ?>">
                            </div>


                            <div class="form-group col-md-3">
                                <label for=""><?php echo e(__('Company Phone')); ?></label>
                                <input type="text" class="form-control" name="salon_phone"
                                    value="<?php echo e($salon->salon_phone); ?>">
                            </div>
          
                            <div class="form-group col-md-3">
                                <label for=""><?php echo e(__('Company Latitude')); ?></label>
                                <input type="text" class="form-control" name="salon_lat"
                                    value="<?php echo e($salon->salon_lat); ?>">
                            </div>
                            
                            <div class="form-group col-md-4">
                                <label for=""><?php echo e(__('Company Longitude')); ?></label>
                                <input type="text" class="form-control" name="salon_long"
                                    value="<?php echo e($salon->salon_long); ?>">
                            </div>
                            
                             <div class="form-group col-md-4">
                                <label for=""><?php echo e(__('Company Profile')); ?></label>
                                <input type="file" class="form-control" accept=".png,.jpg,.jpeg" name="owner_photo" value="<?php echo e($salon->owner_photo); ?>">
                                <a target="_blank" class="badge bg-primary text-white mt-1" href="<?php echo e(url('public/storage/'.$salon->owner_photo)); ?>" target="_black"><?php echo e(__('View')); ?></a>  
                            </div>
                            
                            <div class="form-group col-md-4">
                                <label for=""><?php echo e(__('Company banner')); ?></label>
                                <input type="file" class="form-control" accept=".png,.jpg,.jpeg" name="banner" value="<?php echo e($salon->banner); ?>">
                                <a target="_blank" class="badge bg-primary text-white mt-1" href="<?php echo e(url('public/storage/'.$salon->banner)); ?>" target="_black"><?php echo e(__('View')); ?></a>  
                            </div>
                        </div>

                        <div class="form-row ">
                            <div class="form-group col-md-6">
                                <label for=""><?php echo e(__('About Company')); ?></label>
                                <textarea type="text" class="form-control" name="salon_about" style="height: 300px !important;"><?php echo e($salon->salon_about); ?></textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for=""><?php echo e(__('Company Address')); ?></label>
                                <textarea type="text" class="form-control" name="salon_address" style="height: 300px !important;"><?php echo e($salon->salon_address); ?></textarea>
                            </div>
                        </div>
                        
                        <?php if(has_permission(session()->get('user_type'), 'edit_company')): ?>
                            <div class="form-group text-right">
                                <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Update')); ?>">
                            </div>
                        <?php endif; ?>
                    </form>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabBookings">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="bookingsTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Booking Number')); ?></th>
                                    <th><?php echo e(__('User')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Date & Time')); ?></th>
                                    <th><?php echo e(__('Service Amount')); ?></th>
                                    <th><?php echo e(__('Discount Amount')); ?></th>
                                    <th><?php echo e(__('Subtotal')); ?></th>
                                    <th><?php echo e(__('Total Tax Amount')); ?></th>
                                    <th><?php echo e(__('Payable Amount')); ?></th>
                                    <th><?php echo e(__('Placed On')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabSlots">
                    <div class="slote-table table-responsive col-12">
                        <div class="mt-2 d-flex">
                            <label class="mb-0 text-grey" for=""><?php echo e(__('Monday')); ?></label>
                            <div class="slot-time-block">
                                <?php $__currentLoopData = $slots['mondaySlots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="slot-time-inner">
                                        <span class="slot-time"><?php echo e($item['time']); ?></span>
                                        <span class="total-slot"> <?php echo e($item['booking_limit']); ?> <?php echo e(__('Slots')); ?>

                                        </span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="mt-2 d-flex">
                            <label class="mb-0 text-grey" for=""><?php echo e(__('Tuesday')); ?></label>
                            <div class="slot-time-block">
                                <?php $__currentLoopData = $slots['tuesdaySlots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="slot-time-inner">
                                        <span class="slot-time"><?php echo e($item['time']); ?></span>
                                       <span class="total-slot"> <?php echo e($item['booking_limit']); ?> <?php echo e(__('Slots')); ?>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="mt-2 d-flex">
                            <label class="mb-0 text-grey" for=""><?php echo e(__('Wednesday')); ?></label>
                            <div class="slot-time-block">
                                <?php $__currentLoopData = $slots['wednesdaySlots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="slot-time-inner">
                                        <span class="slot-time"><?php echo e($item['time']); ?></span>
                                       <span class="total-slot"> <?php echo e($item['booking_limit']); ?> <?php echo e(__('Slots')); ?>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="mt-2 d-flex">
                            <label class="mb-0 text-grey" for=""><?php echo e(__('Thursday')); ?></label>
                            <div class="slot-time-block">
                                <?php $__currentLoopData = $slots['thursdaySlots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="slot-time-inner">
                                        <span class="slot-time"><?php echo e($item['time']); ?></span>
                                       <span class="total-slot"> <?php echo e($item['booking_limit']); ?> <?php echo e(__('Slots')); ?>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="mt-2 d-flex">
                            <label class="mb-0 text-grey" for=""><?php echo e(__('Friday')); ?></label>
                            <div class="slot-time-block">
                                <?php $__currentLoopData = $slots['thursdaySlots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="slot-time-inner">
                                        <span class="slot-time"><?php echo e($item['time']); ?></span>
                                       <span class="total-slot"> <?php echo e($item['booking_limit']); ?> <?php echo e(__('Slots')); ?>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="mt-2 d-flex">
                            <label class="mb-0 text-grey" for=""><?php echo e(__('Saturday')); ?></label>
                            <div class="slot-time-block">
                                <?php $__currentLoopData = $slots['saturdaySlots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="slot-time-inner">
                                        <span class="slot-time"><?php echo e($item['time']); ?></span>
                                       <span class="total-slot"> <?php echo e($item['booking_limit']); ?> <?php echo e(__('Slots')); ?>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="mt-2 d-flex">
                            <label class="mb-0 text-grey" for=""><?php echo e(__('Sunday')); ?></label>
                            <div class="slot-time-block">
                                <?php $__currentLoopData = $slots['sundaySlots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="slot-time-inner">
                                        <span class="slot-time"><?php echo e($item['time']); ?></span>
                                       <span class="total-slot"> <?php echo e($item['booking_limit']); ?> <?php echo e(__('Slots')); ?>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabServices">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="servicesTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Number')); ?></th>
                                    <th><?php echo e(__('Image')); ?></th>
                                    <th><?php echo e(__('Title')); ?></th>
                                    <th><?php echo e(__('Category')); ?></th>
                                    <th><?php echo e(__('Time (Minutes)')); ?></th>
                                    <th><?php echo e(__('Price')); ?></th>
                                    <th><?php echo e(__('Discount')); ?></th>
                                    <th><?php echo e(__('Gender')); ?></th>
                                    <th><?php echo e(__('Status (On/Off)')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabGallery">
                     <?php if(has_permission(session()->get('user_type'), 'add_gallery_images')): ?>
                         <a data-toggle="modal" data-target="#addSalonImagesModalGallery" href="" id="addSalonImagesGallery" class="ml-auto btn btn-primary btn-lg text-white float-right mb-3"><i class="fa fa-plus"></i>  <?php echo e(__('Add New Gallery Images')); ?></a>
                     <?php endif; ?>
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="galleryTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Image')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabMap">
                    <?php if(has_permission(session()->get('user_type'), 'add_gallery_images')): ?>
                      <a data-toggle="modal" data-target="#addSalonImagesModalMap" href="" id="addSalonImagesMap" class="ml-auto btn btn-primary btn-lg text-white float-right mb-3"><i class="fa fa-plus"></i> <?php echo e(__('Add New Area Map Images')); ?></a>
                    <?php endif; ?>
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="mapTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Image')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabReviews">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="reviewsTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Rating')); ?></th>
                                    <th><?php echo e(__('Service name')); ?></th>
                                    <th><?php echo e(__('User ID')); ?></th>
                                    <th><?php echo e(__('Comment')); ?></th>
                                    <th><?php echo e(__('Booking')); ?></th>
                                    <th><?php echo e(__('Date&Time')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabAwards">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="awardsTable">
                            <thead>
                                <tr>
                                    
                                    <th><?php echo e(__('Award')); ?></th>
                                    <th><?php echo e(__('By')); ?></th>
                                    <th class="w-70"><?php echo e(__('Description')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabWalletMoney">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="walletStatementTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Transaction ID')); ?></th>
                                    <th><?php echo e(__('Summary')); ?></th>
                                    <th><?php echo e(__('Amount')); ?></th>
                                    <th><?php echo e(__('Credit/Debit')); ?></th>
                                    <th><?php echo e(__('Date & Time')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabPayOuts">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="salonPayOutsTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Request Number')); ?></th>
                                    <th><?php echo e(__('Bank Details')); ?></th>
                                    <th><?php echo e(__('Amount & Status')); ?></th>
                                    <th><?php echo e(__('Date & Time')); ?></th>
                                    <th><?php echo e(__('Salon')); ?></th>
                                    <th><?php echo e(__('Summary')); ?></th>
                                    <th><?php echo e(__('Placed On')); ?></th>
                                    <th><?php echo e(__('Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabEarningHistory">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="earningsTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Earning Number')); ?></th>
                                    <th><?php echo e(__('Booking Number')); ?></th>
                                    <th><?php echo e(__('Amount')); ?></th>
                                    <th><?php echo e(__('Date')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addSalonImagesModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5><?php echo e(__('Add New Company Images')); ?></h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form action="" method="post" enctype="multipart/form-data" id="addSalonImagesForm"
                                autocomplete="off">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="id" value="<?php echo e($salon->id); ?>">

                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="image" class="form-label"><?php echo e(__('Select Images')); ?></label>
                                        <input class="form-control" type="file" name="images[]"
                                            accept="image/png, image/gif, image/jpeg" required multiple>
                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>">
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="addSalonImagesModalGallery" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5><?php echo e(__('Add New GalleryImages')); ?></h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form action="" method="post" enctype="multipart/form-data" id="addSalonImagesFormGallery"
                                autocomplete="off">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="id" value="<?php echo e($salon->id); ?>">

                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="image" class="form-label"><?php echo e(__('Select Images')); ?></label>
                                        <input class="form-control" type="file" name="images[]"
                                            accept="image/png, image/gif, image/jpeg" required multiple>
                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>">
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="addSalonImagesModalMap" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5><?php echo e(__('Add New Area Map Image')); ?></h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form action="" method="post" enctype="multipart/form-data" id="addSalonImagesFormMap"
                                autocomplete="off">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="id" value="<?php echo e($salon->id); ?>">

                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="image" class="form-label"><?php echo e(__('Select Images')); ?></label>
                                        <input class="form-control" type="file" name="images[]"
                                            accept="image/png, image/gif, image/jpeg" required multiple>
                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>">
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="previewGalleryModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5><?php echo e(__('Preview Gallery Post')); ?></h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p id="descGalleryPreview"></p>
                            <img class="rounded" width="100%" id="imggalleryPreview" src="" alt="">
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5><?php echo e(__('Reject Withdrawal')); ?></h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form action="" method="post" enctype="multipart/form-data" id="rejectForm"
                                autocomplete="off">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" id="rejectId" name="id">
                                <div class="form-group">
                                    <label> <?php echo e(__('Summary')); ?></label>
                                    <textarea rows="10" style="height:200px !important;" type="text" name="summary" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>">
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="completeModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5><?php echo e(__('Complete Withdrawal')); ?></h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form action="" method="post" enctype="multipart/form-data" id="completeForm"
                                autocomplete="off">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" id="completeId" name="id">
                                <div class="form-group">
                                    <label> <?php echo e(__('Summary')); ?></label>
                                    <textarea rows="10" style="height:200px !important;" type="text" name="summary" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>">
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
            
            <?php if(session()->has('company_message')): ?>
                <script type="text/javascript">
                    $(function () {
                        swal({
                            title: "Success",
                            text: "<?php echo e(session()->get('company_message')); ?>",
                            type: "success",
                            confirmButtonColor: "#000",
                            confirmButtonText: "Close",
                            closeOnConfirm: false, 
                        })
                    });
                </script>
            <?php endif; ?>
            
            <?php if(session()->has('review_message')): ?>
                <script type="text/javascript">
                    $(function () {
                        swal({
                            title: "Success",
                            text: "<?php echo e(session()->get('review_message')); ?>",
                            type: "success",
                            confirmButtonColor: "#000",
                            confirmButtonText: "Close",
                            closeOnConfirm: false, 
                        })
                    });
                </script>
            <?php endif; ?>
        <?php $__env->stopSection(); ?>

<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/platforms/view.blade.php ENDPATH**/ ?>