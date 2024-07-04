<?php $__env->startSection('header'); ?>
    <script src="<?php echo e(asset('asset/script/viewUserProfile.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(asset('asset/style/viewUserProfile.css')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        #map {
            height:500px;
        }
        #modaldialog {
            max-width: 100% !important;
        }
        
        .card .card-header .form-control{
            height: auto !important;
        }
        
        .card-number{
            color: #bde1e2 !important;
            position: relative;
            top: 255px;
            font-size: 20px;
            margin-left: 40px;
        }
        
        .card-back-number{
            color: #000 !important;
            position: relative;
            bottom: 203px;
            font-size: 22px;
            margin-left: 20px;
        }

        .creditdebitcard {
          width: 100%;
          max-width: 400px;
          -webkit-transform-style: preserve-3d;
          transform-style: preserve-3d;
          transition: -webkit-transform 0.6s;
          -webkit-transition: -webkit-transform 0.6s;
          transition: transform 0.6s;
          transition: transform 0.6s, -webkit-transform 0.6s;
          cursor: pointer;
        }
    
        .creditdebitcard .front{
          position: relative;
          width: 100%;
          max-width: 400px;
          -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
          -webkit-font-smoothing: antialiased;
          color: #47525d;
          bottom: 50px;
        }
        
        .creditdebitcard .back {
          position: relative;
          width: 100%;
          max-width: 400px;
          -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
          -webkit-font-smoothing: antialiased;
          color: #47525d;
          bottom: 300px;
        }
    
        .creditdebitcard .back {
          -webkit-transform: rotateY(180deg);
          transform: rotateY(180deg);
        }
    
        .creditdebitcard.flipped {
          -webkit-transform: rotateY(180deg);
          transform: rotateY(180deg);
        }
        
        .tooltip {
          position: relative;
          display: inline-block;
        }
        
        .tooltip .tooltiptext {
          visibility: hidden;
          width: 140px;
          background-color: #555;
          color: #fff;
          text-align: center;
          border-radius: 6px;
          padding: 5px;
          position: absolute;
          z-index: 1;
          bottom: 150%;
          left: 50%;
          margin-left: -75px;
          opacity: 0;
          transition: opacity 0.3s;
        }
        
        .tooltip .tooltiptext::after {
          content: "";
          position: absolute;
          top: 100%;
          left: 50%;
          margin-left: -5px;
          border-width: 5px;
          border-style: solid;
          border-color: #555 transparent transparent transparent;
        }
        
        .tooltip:hover .tooltiptext {
          visibility: visible;
          opacity: 1;
        }

        .quantity {
          display: flex;
          align-items: center;
          padding: 0;
        }
        .quantity__minus,
        .quantity__plus {
        display: block;
            width: 100px;
            height: 42px;
            margin: 0;
            background: #dee0ee;
            text-decoration: none;
            text-align: center;
            line-height: 44px;
        }
        .quantity__minus:hover,
        .quantity__plus:hover {
          background: #575b71;
          color: #fff;
        } 
        .quantity__minus {
          border-radius: 3px 0 0 3px;
        }
        .quantity__plus {
          border-radius: 0 3px 3px 0;
        }
        .quantity__input {
          margin: 0;
          padding: 0;
          text-align: center;
          border-top: 2px solid #dee0ee;
          border-bottom: 2px solid #dee0ee;
          border-left: 1px solid #dee0ee;
          border-right: 2px solid #dee0ee;
          background: #fff;
          color: #8184a1;
        }
        .quantity__minus:link,
        .quantity__plus:link {
          color: #8184a1;
        } 
        .quantity__minus:visited,
        .quantity__plus:visited {
          color: #000;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <input type="hidden" value="<?php echo e($user->id); ?>" id="userId">

    <div class="card">
        <div class="card-header">
            <?php if($user->profile_image): ?>
                <img class="rounded-circle owner-img-border mr-2" width="40" height="40" src="<?php echo e(url('public/storage/'.$user->profile_image)); ?>" alt="">
            <?php else: ?>
                <img class="rounded-circle owner-img-border mr-2" width="40" height="40" src=" https://placehold.jp/150x150.png" alt="">
            <?php endif; ?>
            
            <h4 class="d-inline">
                <span><?php echo e($user->first_name); ?></span>&nbsp;&nbsp;<span><?php echo e($user->last_name); ?></span>
            </h4>
            <span>- <?php echo e($user->profile_id); ?></span>&nbsp;&nbsp;
            
            <?php if($user->user_type): ?>
                <span>( Non Resident- <?php echo e($user->identity); ?>)</span>
            <?php else: ?>
                <span>(Resident- <?php echo e($user->identity); ?>)</span>
            <?php endif; ?>
            
            <?php if(has_permission(session()->get('user_type'), 'add_bookings')): ?>
                <a href="<?php echo e(route('admin.service.booking', $user->id)); ?>" class="ml-auto btn btn-primary text-white"><?php echo e(__('Book Service')); ?></a>
            <?php endif; ?>
           
            <?php if(has_permission(session()->get('user_type'), 'add_balance')): ?>
                <a href="" id="rechargeWallet" class="ml-2 btn btn-primary text-white"><?php echo e(__('Recharge IBP Account')); ?></a>
            <?php endif; ?>

            <?php if(has_permission(session()->get('user_type'), 'delete_user')): ?>
                <?php if($user->is_block == 1): ?>
                    <a href="" id="unblockUser" class="ml-2 btn btn-success text-white"><?php echo e(__('Unblock')); ?></a>
                <?php else: ?>
                    <a href="" id="blockUser" class="ml-2 btn btn-danger text-white"><?php echo e(__('Block')); ?></a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <div class="card-body">
            <div class="form-row">
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for=""><?php echo e(__('IBP Account Balance')); ?></label>
                    <p class="mt-0 p-data"><?php echo e($settings->currency); ?><?php echo e($user->wallet); ?></p>
                </div>
                
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for=""><?php echo e(__('IBP Card Balance')); ?></label>
                    <p class="mt-0 p-data"><?php echo e($settings->currency); ?><?php echo e(!empty($ibpcards->balance) ? number_format($ibpcards->balance, 2, '.', ',') : '0.00'); ?></p>
                </div>
                
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for=""><?php echo e(__('Phone')); ?></label>
                    <?php if($user->formated_number != null): ?>
                        <p class="mt-0 p-data"><?php echo e($user->formated_number); ?></p>
                    <?php else: ?>
                        <p class="mt-0 p-data">---</p>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-4">
                    <label class="mb-0 text-grey" for=""><?php echo e(__('Email')); ?></label>
                    <?php if($user->email != null): ?>
                        <p class="mt-0 p-data"><?php echo e($user->email); ?></p>
                    <?php else: ?>
                        <p class="mt-0 p-data">---</p>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for=""><?php echo e(__('Total Bookings')); ?></label>
                    <p class="mt-0 p-data"><?php echo e($totalBookings); ?></p>
                </div>
            </div>


        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills border-b  ml-0">
                <li role="presentation" class="nav-item ">
                    <a class="nav-link pointer active" href="#tabCards" role="tab" aria-controls="tabCards" data-toggle="tab"><?php echo e(__('IBP Card')); ?>

                        <span class="badge badge-transparent "></span>
                    </a>
                </li>
                
                <li role="presentation" class="nav-item ">
                    <a class="nav-link pointer" href="#tabBookings" role="tab" aria-controls="tabBookings" data-toggle="tab"><?php echo e(__('Bookings')); ?>

                        <span class="badge badge-transparent "></span>
                    </a>
                </li>

                <li role="presentation" class="nav-item">
                    <a class="nav-link pointer" href="#tabWallet" role="tab" data-toggle="tab"><?php echo e(__('IBP Account Transactions')); ?>

                        <span class="badge badge-transparent "></span>
                    </a>
                </li>
               
                <li role="presentation" class="nav-item">
                    <a class="nav-link pointer" href="#tabWalletRechargeLogs" role="tab" data-toggle="tab"><?php echo e(__('IBP Account Recharges')); ?>

                        <span class="badge badge-transparent "></span>
                    </a>
                </li>
                
                <li role="presentation" class="nav-item">
                    <a class="nav-link pointer" href="#tabWithdrawRequests" role="tab" data-toggle="tab"><?php echo e(__('Withdrawals')); ?>

                        <span class="badge badge-transparent "></span>
                    </a>
                </li>
                
                <li role="presentation" class="nav-item">
                    <a class="nav-link pointer" href="#tabCard" role="tab" data-toggle="tab"><?php echo e(__('IBP Card Transactions')); ?>

                        <span class="badge badge-transparent "></span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content tabs" id="home">
                <?php if(!empty($ibpcards)): ?>
                
                    <?php
                        $cashier = DB::table('admin_user')->where('user_id', $ibpcards->assigned_by)->first();
                        $language = DB::table('languages')->where('id', $ibpcards->language)->first();
                        
                        if($ibpcards->language == '1'){
                            $card_front = asset('public/cards/englishfront.PNG');
                            $card_back = asset('public/cards/englishback.PNG');
                        }elseif($ibpcards->language == '2'){
                            $card_front = asset('public/cards/papiamentufront.PNG');
                            $card_back = asset('public/cards/papiamentuback.PNG');
                        }elseif($ibpcards->language == '3'){
                            $card_front = asset('public/cards/dutchfront.PNG');
                            $card_back = asset('public/cards/dutchback.PNG');
                        }
                    ?>
                
                    <?php if($ibpcards->language == '3'): ?>
                        <style>
                            .card-code{
                                padding-top: 15px;
                                padding-left: 20px;
                            }
                        </style>
                    <?php else: ?>
                        <style>
                            .card-code{
                                position: relative;
                                bottom: 215px;
                                padding-left: 11px;
                            }
                        </style>
                    <?php endif; ?>
                    
                    <style>
                        .wallet-wrap-back{
                            background-image: url("<?php echo e($card_back); ?>");
                            background-size: cover;
                            width: 400px;
                            height: 250px;
                            box-shadow: none !important;
                            border-radius: 15px;
                        }
                    
                        .wallet-wrap-front{
                            background-image: url("<?php echo e($card_front); ?>");
                            background-size: cover;
                            width: 400px;
                            height: 250px;
                            box-shadow: none !important;
                            border-radius: 15px;
                        }
                    </style>
                    
                    <div role="tabpanel" class="tab-pane active" id="tabCards">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="creditdebitcard preload">
                                  <div class="front">
                                    <p class="card-number"><?php echo e(chunk_split($ibpcards->card_number, 4, ' ')); ?></p>
                                    <svg id="cardfront" class="wallet-wrap-front"></svg>
                                  </div>
                                  <div class="back">
                                    <svg id="cardback" class="wallet-wrap-back">
                                        <p class="card-code"><img src="data:image/png;base64,<?php echo e(DNS1D::getBarcodePNG($ibpcards->card_number, 'I25')); ?>" alt="barcode" /></p>
                                        <p class="card-back-number"><?php echo e(chunk_split($ibpcards->card_number, 4, ' ')); ?></p>
                                    </svg>
                                  </div>
                                </div>
                                
                                <div class="form-group col-md-12" style="position: relative; bottom: 340px; left: 30px;">
                                    <h5 class="mb-3">
                                        <strong>Card Number :</strong> 
                                        <span><?php echo e(chunk_split($ibpcards->card_number, 4, ' ')); ?> <a onclick="myFunction()" onmouseout="outFunc()"><i class="fa fa-copy" id="myTooltip"></i></a></span>
                                    </h5>
                                    <h5 class="mb-3">
                                        <strong>Balance :</strong> 
                                        <span>USD <?php echo e(number_format($ibpcards->balance, 2, '.', ',')); ?></span>
                                    </h5>
                                    <h5 class="mb-3">
                                        <strong>Loyality Points :</strong> 
                                        <span><?php echo e($ibpcards->loyality_points); ?></span>
                                    </h5>
                                    
                                    <div class="mt-5">
                                        <a data-toggle="modal" data-target="#cardtopups" href="#">
                                            <button class="btn btn-primary" style="font-size: 12px; padding: 5px 11px;"><i class="fa fa-arrow-up"></i> New Topup</button>
                                        </a>
                                        <a href="<?php echo e(route('card.transaction', $ibpcards->id)); ?>">
                                            <button class="btn btn-primary" style="font-size: 12px; padding: 5px 11px;"><i class="fa fa-exchange"></i> All Transactions</button>
                                        </a>
                                        <a href="#" data-toggle="modal" data-target="#generatecards">
                                            <button class="btn btn-primary" style="font-size: 12px; padding: 5px 11px;"><i class="fa fa-edit"></i> Change Status</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div style="border: 1px solid #8080804f; border-radius: 20px; padding: 15px;">
                                    <h4 style="color: #009544;">Customer Details</h4>
                                    <p>
                                        <strong>Customer :</strong> 
                                        <span style="float: right;">
                                            <?php if(!empty($ibpcards->assigned_to)): ?>
                                                <?php if(!empty($user)): ?>
                                                    <?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>

                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Email :</strong> 
                                        <span style="float: right;">
                                            <?php if(!empty($ibpcards->assigned_to)): ?>
                                                <?php if(!empty($user)): ?>
                                                    <?php echo e($user->email); ?>

                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Phone :</strong> 
                                        <span style="float: right;">
                                            <?php if(!empty($ibpcards->assigned_to)): ?>
                                                <?php if(!empty($user)): ?>
                                                    <?php echo e($user->formated_number); ?>

                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </span>
                                    </p>
                                    
                                    <p>
                                        <strong>Created On :</strong> 
                                        <span style="float: right;">
                                            <?php if(!empty($ibpcards->assigned_to)): ?>
                                                <?php if(!empty($user)): ?>
                                                    <?php echo e(!empty($user->created_at) ? Carbon\Carbon::parse($user->created_at)->format('d-M-Y') : 'N/A'); ?>

                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </span>
                                    </p>
                                    
                                    <h4 class="mt-5" style="color: #009544;">Card Details</h4>
                                    <p>
                                        <strong>Assigned By :</strong> 
                                        <span style="float: right;">
                                            <?php if(!empty($ibpcards->assigned_by)): ?>
                                                <?php if(!empty($cashier)): ?>
                                                    <?php echo e($cashier->first_name); ?> <?php echo e($cashier->last_name); ?>

                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Assigned On :</strong> 
                                        <span style="float: right;"><?php echo e(!empty($ibpcards->assigned_on) ? Carbon\Carbon::parse($ibpcards->assigned_on)->format('d-M-Y') : 'N/A'); ?></span>
                                    </p>
                                    <p>
                                        <strong>Card Status : </strong> 
                                        <span style="float: right;">
                                            <?php if($ibpcards->status == '1'): ?>
                                                Active
                                            <?php elseif($ibpcards->status == '2'): ?>
                                                Inactive
                                            <?php else: ?>
                                                Block
                                            <?php endif; ?> 
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Language : </strong> 
                                        <span style="float: right;"><?php echo e($language->name); ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div role="tabpanel" class="tab-pane active" id="tabCards">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <p class="text-center">Card Not Assigned</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div role="tabpanel" class="tab-pane" id="tabBookings">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="bookingsTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Booking Number')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Date & Time')); ?></th>
                                    <th><?php echo e(__('Service Amount')); ?></th>
                                    <th><?php echo e(__('Coupon Discount')); ?></th>
                                    <th><?php echo e(__('Subtotal')); ?></th>
                                    <th><?php echo e(__('Tax')); ?></th>
                                    <th><?php echo e(__('Payable Amount')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabWallet">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="walletStatementTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Transaction ID')); ?></th>
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

                <div role="tabpanel" class="tab-pane" id="tabWithdrawRequests">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="tabWithdrawRequestsTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Request Number')); ?></th>
                                    <th><?php echo e(__('Bank Details')); ?></th>
                                    <th><?php echo e(__('Amount & Status')); ?></th>
                                    <th><?php echo e(__('Date & Time')); ?></th>
                                    <th><?php echo e(__('Summary')); ?></th>
                                    <th><?php echo e(__('Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabWalletRechargeLogs">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="walletRechargeLogsTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Amount')); ?></th>
                                    <th><?php echo e(__('Gateway')); ?></th>
                                    <th><?php echo e(__('Transaction ID')); ?></th>
                                    <th><?php echo e(__('Date')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabCard">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="cardStatementTable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sr. No.')); ?></th>
                                    <th><?php echo e(__('Transaction ID')); ?></th>
                                    <th><?php echo e(__('Card Number')); ?></th>
                                    <th><?php echo e(__('Amount')); ?></th>
                                    <th><?php echo e(__('Type')); ?></th>
                                    <th><?php echo e(__('Date & Time')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
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

    <div class="modal fade" id="completeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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

    <div class="modal fade" id="BookingServiceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><?php echo e(__('Book Service')); ?></h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="BookingServiceForm"
                        autocomplete="off">
                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="user_id" value="<?php echo e($user->id); ?>">
                        <input type="hidden" name="order_by" value="<?php echo e(__('ADDED_BY_ADMIN')); ?>">
                        <input type="hidden" name="gateway" value="2">
                        <div class="form-group">
                            <label> <?php echo e(__('Select Service')); ?></label>
                           <select class="selectpicker form-control" name="service_id" id="service_id" data-live-search="true">
                                  <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <option data-tokens="<?php echo e($service->id); ?>" value="<?php echo e($service->id); ?>"><?php echo e($service->title); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </select>
                        </div>
                        <div class="form-group">
                            <label for="date"> <?php echo e(__('Date')); ?></label>
                            <input type="date" name="date" id="date" min="<?php echo e(date('d-m-Y')); ?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> <?php echo e(__('Select booking Hours')); ?></label>
                           <select class="selectpicker form-control" name="booking_hours" id="selectSlots" data-live-search="true" required>
                           </select>
                        </div>
                        <div class="form-group">
                            <label for="end-time" class="form-label">Slect Time</label>
                            <div class="token-slot mt-2" id="slotslist">No Time Available</div>
                        </div>
                        <span  class="text-danger" id="slotsNotSelected"></span>
                        <div class="form-group">
                            <label for="end-time" class="form-label">Select Quantity</label>
                            <div class="quantity">
                                <a href="javascript:void(0)" class="quantity__minus"><span>-</span></a>
                                <input name="quantity" id="booking_numbers" type="text" class="quantity__input form-control" value="1">
                                <a href="javascript:void(0)" class="quantity__plus"><span>+</span></a>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="end-time" class="form-label">Payment Method</label>
                            <?php if($ibpcards): ?>
                                <div class="form-check">
                                    <input class="form-check-input default-check me-2" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="card" checked="">
                                    <label class="form-check-label" for="inlineRadio3">IBP Card <?php echo e($settings->currency); ?><?php echo e(number_format($ibpcards->balance,2)); ?></label>
                                </div>
                            <?php endif; ?>
                      
                            <div class="form-check form-check-inline <?php if(empty($ibpcards)): ?> active <?php endif; ?> ">
                                <input class="form-check-input default-check me-2" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="wallet" <?php if(empty($ibpcards)): ?> checked="" <?php endif; ?> >
                                <label class="form-check-label" for="inlineRadio2">IBP Account <?php echo e($settings->currency); ?><?php echo e(number_format($user->wallet,2)); ?></label>
                            </div>
                        </div>    
                            
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="rechargeWalletModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><?php echo e(__('Recharge Wallet')); ?></h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="<?php echo e(route('user.wallet.topup')); ?>" method="post" enctype="multipart/form-data" autocomplete="off" onsubmit='disableformButton()'>
                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="user_id" value="<?php echo e($user->id); ?>">
                        <input type="hidden" name="transaction_id" value="<?php echo e(__('ADDED_BY_ADMIN')); ?>">
                        <input type="hidden" name="gateway" value="2">


                        <div class="form-group">
                            <label> <?php echo e(__('Amount')); ?></label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> <?php echo e(__('Transaction Summary')); ?></label>
                            <textarea name="transaction_summary" class="form-control" required></textarea>
                        </div>

                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>" id='submitformbtn'>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <?php if(!empty($ibpcards)): ?>
        <div class="modal fade" id="generatecards" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5><?php echo e(__('Change Card Status')); ?></h5>
    
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
    
                        <form action="<?php echo e(route('cards.status.update', $ibpcards->id)); ?>" method="post" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
    
                            <div class="form-group">
                                <label> <?php echo e(__('Change Status')); ?></label>
                                <select name="status" class="form-control form-control-sm" aria-label="Default select example">
                                    <option value="1" <?php if($ibpcards->status == '1'): ?> selected <?php endif; ?>>Active</option>
                                    <option value="2" <?php if($ibpcards->status == '2'): ?> selected <?php endif; ?>>Inactive</option>
                                    <option value="3" <?php if($ibpcards->status == '3'): ?> selected <?php endif; ?>>Block</option>
                                </select>
                            </div>
    
                            <div class="form-group text-right">
                                <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>">
                            </div>
    
                        </form>
                    </div>
    
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="cardtopups" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5><?php echo e(__('Card Topup')); ?></h5>
    
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
    
                        <form action="<?php echo e(route('cards.topups.store')); ?>" method="post" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
    
                            <div class="form-group">
                                <label> <?php echo e(__('Card Number')); ?></label>
                                <input class="form-control" type="hidden" id="card_id" name="card_id" value="<?php echo e($ibpcards->id); ?>">
                                <input class="form-control" type="text" id="card_number" value="<?php echo e(chunk_split($ibpcards->card_number, 4, ' ')); ?>" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label><?php echo e(__('Enter Amount')); ?></label>
                                <input class="form-control" type="number" id="icon" name="amount" required>
                            </div>
    
                            <div class="form-group text-right">
                                <input class="btn btn-primary mr-1" type="submit" value=" <?php echo e(__('Submit')); ?>">
                            </div>
    
                        </form>
                    </div>
    
                </div>
            </div>
        </div>
        
        <script src='https://cdnjs.cloudflare.com/ajax/libs/imask/3.4.0/imask.min.js'></script>
    
        <script type="text/javascript">
            window.onload = function () {
                document.querySelector(".preload").classList.remove("preload");
                document.querySelector(".creditdebitcard").addEventListener("click", function () {
                    if (this.classList.contains("flipped")) {
                      this.classList.remove("flipped");
                    } else {
                      this.classList.add("flipped");
                    }
                });
            };
        </script>
        
        <script>
            function myFunction() {
              var copyText = document.getElementById("card_number");
              copyText.select();
              copyText.setSelectionRange(0, 99999);
              navigator.clipboard.writeText(copyText.value);
              
              var tooltip = document.getElementById("myTooltip");
              tooltip.innerHTML = "<i class='fa fa-check text-success'><i>";
            }
            
            function outFunc() {
              var tooltip = document.getElementById("myTooltip");
            }
        </script>
    <?php endif; ?>
    
    <?php if(session()->has('booking_message')): ?>
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "<?php echo e(session()->get('booking_message')); ?>",
                    type: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<a href='<?php echo e(route('bookings.view', session()->get('booking_id'))); ?>'>Booking Details</a>",
                    cancelButtonText: "<a href='<?php echo e(route('booking.invoice', session()->get('booking_id'))); ?>' id='bookinginvoice'>Print</a>",
                    closeOnConfirm: false, 
                    customClass: "Custom_Cancel"
                })
            });
        </script>
    <?php endif; ?>
    
    <?php if(session()->has('card_topup')): ?>
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "<?php echo e(session()->get('card_topup')); ?>",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    <?php endif; ?>
    
    <script>
        document.getElementById('bookinginvoice').addEventListener('click', function() {
            window.ReactNativeWebView.postMessage('<?php echo e(url("booking-invoice/338")); ?>');
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/users/profile.blade.php ENDPATH**/ ?>