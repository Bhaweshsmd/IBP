<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/users.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
    </style>
    <div class="card mt-3">
        <div class="card-header">
            <h4><?php echo e(__('All Customers')); ?></h4>
            <?php if(has_permission(session()->get('user_type'), 'add_user')): ?>
                <a href="<?php echo e(route('users.create')); ?>" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> Add New Customer</a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100" id="usersTable">
                    <thead>
                        <tr>
                            <th><?php echo e(__('Sr. No.')); ?></th>
                            <th><?php echo e(__('Customer ID')); ?></th>
                            <th><?php echo e(__('Profile Image')); ?></th>
                            <th><?php echo e(__('Name')); ?></th>
                            <th><?php echo e(__('Email')); ?></th>
                            <th><?php echo e(__('Phone')); ?></th>
                            <th><?php echo e(__('Customer Type')); ?></th>
                            <th><?php echo e(__('ID/Passport')); ?></th>
                            <th><?php echo e(__('Total Bookings')); ?></th>
                            <th><?php echo e(__('Register By')); ?></th>
                            <th><?php echo e(__('Action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUserNotiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><?php echo e(__('Notify Customers')); ?></h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="editUserNotiForm"
                        autocomplete="off">
                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="id" id="editUserNotiId">

                        <div class="form-group">
                            <label> <?php echo e(__('Title')); ?></label>
                            <input type="text" id="editUserNotiTitle" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> <?php echo e(__('Description')); ?></label>
                            <textarea id="editUserNotiDesc" rows="10" style="height:200px !important;" type="text" name="description"
                                class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="addUserNotiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><?php echo e(__('Notify Customers')); ?></h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="addUserNotiForm"
                        autocomplete="off">
                        <?php echo csrf_field(); ?>

                        <div class="form-group">
                            <label> <?php echo e(__('Title')); ?></label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> <?php echo e(__('Description')); ?></label>
                            <textarea rows="10" style="height:200px !important;" type="text" name="description" class="form-control"
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="addSalonNotiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><?php echo e(__('Notify Salons')); ?></h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="addSalonNotiForm"
                        autocomplete="off">
                        <?php echo csrf_field(); ?>

                        <div class="form-group">
                            <label> <?php echo e(__('Title')); ?></label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> <?php echo e(__('Description')); ?></label>
                            <textarea rows="10" style="height:200px !important;" type="text" name="description" class="form-control"
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="editSalonNotiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><?php echo e(__('Notify Customers')); ?></h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="editSalonNotiForm"
                        autocomplete="off">
                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="id" id="editSalonNotiId">

                        <div class="form-group">
                            <label> <?php echo e(__('Title')); ?></label>
                            <input type="text" id="editSalonNotiTitle" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> <?php echo e(__('Description')); ?></label>
                            <textarea id="editSalonNotiDesc" rows="10" style="height:200px !important;" type="text" name="description"
                                class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <?php if(session()->has('user_message')): ?>
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "<?php echo e(session()->get('user_message')); ?>",
                    type: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Close",
                    cancelButtonText: "<a href='<?php echo e(route('cards.assign')); ?>'>Assign Card</a>",
                    closeOnConfirm: false, 
                    customClass: "Custom_Cancel"
                })
            });
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/users/index.blade.php ENDPATH**/ ?>