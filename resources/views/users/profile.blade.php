@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/viewUserProfile.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/style/viewUserProfile.css') }}">
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
@endsection

@section('content')
    <input type="hidden" value="{{ $user->id }}" id="userId">

    <div class="card">
        <div class="card-header">
            @if($user->profile_image)
                <img class="rounded-circle owner-img-border mr-2" width="40" height="40" src="{{ url('public/storage/'.$user->profile_image) }}" alt="">
            @else
                <img class="rounded-circle owner-img-border mr-2" width="40" height="40" src=" https://placehold.jp/150x150.png" alt="">
            @endif
            
            <h4 class="d-inline">
                <span>{{ $user->first_name }}</span>&nbsp;&nbsp;<span>{{ $user->last_name }}</span>
            </h4>
            <span>- {{ $user->profile_id }}</span>&nbsp;&nbsp;
            
            @if($user->user_type)
                <span>( Non Resident- {{ $user->identity }})</span>
            @else
                <span>(Resident- {{ $user->identity }})</span>
            @endif
            
            @if(has_permission(session()->get('user_type'), 'add_bookings'))
                <a href="{{ route('admin.service.booking', $user->id) }}" class="ml-auto btn btn-primary text-white">{{ __('Book Service') }}</a>
            @endif
           
            @if(has_permission(session()->get('user_type'), 'add_balance'))
                <a href="" id="rechargeWallet" class="ml-2 btn btn-primary text-white">{{ __('Recharge IBP Account') }}</a>
            @endif

            @if(has_permission(session()->get('user_type'), 'delete_user'))
                @if ($user->is_block == 1)
                    <a href="" id="unblockUser" class="ml-2 btn btn-success text-white">{{ __('Unblock') }}</a>
                @else
                    <a href="" id="blockUser" class="ml-2 btn btn-danger text-white">{{ __('Block') }}</a>
                @endif
            @endif
        </div>
        
        <div class="card-body">
            <div class="form-row">
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('IBP Account Balance') }}</label>
                    <p class="mt-0 p-data">{{ $settings->currency }}{{ $user->wallet }}</p>
                </div>
                
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('IBP Card Balance') }}</label>
                    <p class="mt-0 p-data">{{ $settings->currency }}{{ !empty($ibpcards->balance) ? number_format($ibpcards->balance, 2, '.', ',') : '0.00' }}</p>
                </div>
                
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Phone') }}</label>
                    @if ($user->formated_number != null)
                        <p class="mt-0 p-data">{{ $user->formated_number }}</p>
                    @else
                        <p class="mt-0 p-data">---</p>
                    @endif
                </div>
                
                <div class="col-md-4">
                    <label class="mb-0 text-grey" for="">{{ __('Email') }}</label>
                    @if ($user->email != null)
                        <p class="mt-0 p-data">{{ $user->email }}</p>
                    @else
                        <p class="mt-0 p-data">---</p>
                    @endif
                </div>
                
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Total Bookings') }}</label>
                    <p class="mt-0 p-data">{{ $totalBookings }}</p>
                </div>
            </div>


        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills border-b  ml-0">
                <li role="presentation" class="nav-item ">
                    <a class="nav-link pointer active" href="#tabCards" role="tab" aria-controls="tabCards" data-toggle="tab">{{ __('IBP Card') }}
                        <span class="badge badge-transparent "></span>
                    </a>
                </li>
                
                <li role="presentation" class="nav-item ">
                    <a class="nav-link pointer" href="#tabBookings" role="tab" aria-controls="tabBookings" data-toggle="tab">{{ __('Bookings') }}
                        <span class="badge badge-transparent "></span>
                    </a>
                </li>

                <li role="presentation" class="nav-item">
                    <a class="nav-link pointer" href="#tabWallet" role="tab" data-toggle="tab">{{ __('IBP Account Transactions') }}
                        <span class="badge badge-transparent "></span>
                    </a>
                </li>
               
                <li role="presentation" class="nav-item">
                    <a class="nav-link pointer" href="#tabWalletRechargeLogs" role="tab" data-toggle="tab">{{ __('IBP Account Recharges') }}
                        <span class="badge badge-transparent "></span>
                    </a>
                </li>
                
                <li role="presentation" class="nav-item">
                    <a class="nav-link pointer" href="#tabWithdrawRequests" role="tab" data-toggle="tab">{{ __('Withdrawals') }}
                        <span class="badge badge-transparent "></span>
                    </a>
                </li>
                
                <li role="presentation" class="nav-item">
                    <a class="nav-link pointer" href="#tabCard" role="tab" data-toggle="tab">{{ __('IBP Card Transactions') }}
                        <span class="badge badge-transparent "></span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content tabs" id="home">
                @if(!empty($ibpcards))
                
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
                
                    @if($ibpcards->language == '3')
                        <style>
                            .card-code{
                                padding-top: 15px;
                                padding-left: 20px;
                            }
                        </style>
                    @else
                        <style>
                            .card-code{
                                position: relative;
                                bottom: 215px;
                                padding-left: 11px;
                            }
                        </style>
                    @endif
                    
                    <style>
                        .wallet-wrap-back{
                            background-image: url("{{$card_back}}");
                            background-size: cover;
                            width: 400px;
                            height: 250px;
                            box-shadow: none !important;
                            border-radius: 15px;
                        }
                    
                        .wallet-wrap-front{
                            background-image: url("{{$card_front}}");
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
                                    <p class="card-number">{{chunk_split($ibpcards->card_number, 4, ' ')}}</p>
                                    <svg id="cardfront" class="wallet-wrap-front"></svg>
                                  </div>
                                  <div class="back">
                                    <svg id="cardback" class="wallet-wrap-back">
                                        <p class="card-code"><img src="data:image/png;base64,{{DNS1D::getBarcodePNG($ibpcards->card_number, 'I25')}}" alt="barcode" /></p>
                                        <p class="card-back-number">{{chunk_split($ibpcards->card_number, 4, ' ')}}</p>
                                    </svg>
                                  </div>
                                </div>
                                
                                <div class="form-group col-md-12" style="position: relative; bottom: 340px; left: 30px;">
                                    <h5 class="mb-3">
                                        <strong>Card Number :</strong> 
                                        <span>{{chunk_split($ibpcards->card_number, 4, ' ')}} <a onclick="myFunction()" onmouseout="outFunc()"><i class="fa fa-copy" id="myTooltip"></i></a></span>
                                    </h5>
                                    <h5 class="mb-3">
                                        <strong>Balance :</strong> 
                                        <span>USD {{number_format($ibpcards->balance, 2, '.', ',')}}</span>
                                    </h5>
                                    <h5 class="mb-3">
                                        <strong>Loyality Points :</strong> 
                                        <span>{{$ibpcards->loyality_points}}</span>
                                    </h5>
                                    
                                    <div class="mt-5">
                                        <a data-toggle="modal" data-target="#cardtopups" href="#">
                                            <button class="btn btn-primary" style="font-size: 12px; padding: 5px 11px;"><i class="fa fa-arrow-up"></i> New Topup</button>
                                        </a>
                                        <a href="{{ route('card.transaction', $ibpcards->id) }}">
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
                                            @if(!empty($ibpcards->assigned_to))
                                                @if(!empty($user))
                                                    {{$user->first_name}} {{$user->last_name}}
                                                @else
                                                    N/A
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Email :</strong> 
                                        <span style="float: right;">
                                            @if(!empty($ibpcards->assigned_to))
                                                @if(!empty($user))
                                                    {{$user->email}}
                                                @else
                                                    N/A
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Phone :</strong> 
                                        <span style="float: right;">
                                            @if(!empty($ibpcards->assigned_to))
                                                @if(!empty($user))
                                                    {{$user->formated_number}}
                                                @else
                                                    N/A
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </p>
                                    
                                    <p>
                                        <strong>Created On :</strong> 
                                        <span style="float: right;">
                                            @if(!empty($ibpcards->assigned_to))
                                                @if(!empty($user))
                                                    {{ !empty($user->created_at) ? Carbon\Carbon::parse($user->created_at)->format('d-M-Y') : 'N/A'}}
                                                @else
                                                    N/A
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </p>
                                    
                                    <h4 class="mt-5" style="color: #009544;">Card Details</h4>
                                    <p>
                                        <strong>Assigned By :</strong> 
                                        <span style="float: right;">
                                            @if(!empty($ibpcards->assigned_by))
                                                @if(!empty($cashier))
                                                    {{$cashier->first_name}} {{$cashier->last_name}}
                                                @else
                                                    N/A
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Assigned On :</strong> 
                                        <span style="float: right;">{{ !empty($ibpcards->assigned_on) ? Carbon\Carbon::parse($ibpcards->assigned_on)->format('d-M-Y') : 'N/A'}}</span>
                                    </p>
                                    <p>
                                        <strong>Card Status : </strong> 
                                        <span style="float: right;">
                                            @if($ibpcards->status == '1')
                                                Active
                                            @elseif($ibpcards->status == '2')
                                                Inactive
                                            @else
                                                Block
                                            @endif 
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Language : </strong> 
                                        <span style="float: right;">{{$language->name}}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div role="tabpanel" class="tab-pane active" id="tabCards">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <p class="text-center">Card Not Assigned</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div role="tabpanel" class="tab-pane" id="tabBookings">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="bookingsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Booking Number') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Service Amount') }}</th>
                                    <th>{{ __('Coupon Discount') }}</th>
                                    <th>{{ __('Subtotal') }}</th>
                                    <th>{{ __('Tax') }}</th>
                                    <th>{{ __('Payable Amount') }}</th>
                                    <th>{{ __('Action') }}</th>
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
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Transaction ID') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Credit/Debit') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
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
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Request Number') }}</th>
                                    <th>{{ __('Bank Details') }}</th>
                                    <th>{{ __('Amount & Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Summary') }}</th>
                                    <th>{{ __('Actions') }}</th>
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
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Gateway') }}</th>
                                    <th>{{ __('Transaction ID') }}</th>
                                    <th>{{ __('Date') }}</th>
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
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Transaction ID') }}</th>
                                    <th>{{ __('Card Number') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
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
                    <h5>{{ __('Reject Withdrawal') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="rejectForm"
                        autocomplete="off">
                        @csrf
                        <input type="hidden" id="rejectId" name="id">
                        <div class="form-group">
                            <label> {{ __('Summary') }}</label>
                            <textarea rows="10" style="height:200px !important;" type="text" name="summary" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
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
                    <h5>{{ __('Complete Withdrawal') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="completeForm"
                        autocomplete="off">
                        @csrf
                        <input type="hidden" id="completeId" name="id">
                        <div class="form-group">
                            <label> {{ __('Summary') }}</label>
                            <textarea rows="10" style="height:200px !important;" type="text" name="summary" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
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
                    <h5>{{ __('Book Service') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="BookingServiceForm"
                        autocomplete="off">
                        @csrf

                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <input type="hidden" name="order_by" value="{{ __('ADDED_BY_ADMIN') }}">
                        <input type="hidden" name="gateway" value="2">
                        <div class="form-group">
                            <label> {{ __('Select Service') }}</label>
                           <select class="selectpicker form-control" name="service_id" id="service_id" data-live-search="true">
                                  @foreach($services as $service)
                                     <option data-tokens="{{$service->id}}" value="{{$service->id}}">{{$service->title}}</option>
                                  @endforeach
                           </select>
                        </div>
                        <div class="form-group">
                            <label for="date"> {{ __('Date') }}</label>
                            <input type="date" name="date" id="date" min="{{date('d-m-Y')}}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Select booking Hours') }}</label>
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
                            @if($ibpcards)
                                <div class="form-check">
                                    <input class="form-check-input default-check me-2" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="card" checked="">
                                    <label class="form-check-label" for="inlineRadio3">IBP Card {{ $settings->currency }}{{number_format($ibpcards->balance,2)}}</label>
                                </div>
                            @endif
                      
                            <div class="form-check form-check-inline @if(empty($ibpcards)) active @endif ">
                                <input class="form-check-input default-check me-2" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="wallet" @if(empty($ibpcards)) checked="" @endif >
                                <label class="form-check-label" for="inlineRadio2">IBP Account {{ $settings->currency }}{{number_format($user->wallet,2)}}</label>
                            </div>
                        </div>    
                            
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
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
                    <h5>{{ __('Recharge Wallet') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('user.wallet.topup') }}" method="post" enctype="multipart/form-data" autocomplete="off" onsubmit='disableformButton()'>
                        @csrf

                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="transaction_id" value="{{ __('ADDED_BY_ADMIN') }}">
                        <input type="hidden" name="gateway" value="2">


                        <div class="form-group">
                            <label> {{ __('Amount') }}</label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Transaction Summary') }}</label>
                            <textarea name="transaction_summary" class="form-control" required></textarea>
                        </div>

                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}" id='submitformbtn'>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    
    @if(!empty($ibpcards))
        <div class="modal fade" id="generatecards" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>{{ __('Change Card Status') }}</h5>
    
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
    
                        <form action="{{ route('cards.status.update', $ibpcards->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
    
                            <div class="form-group">
                                <label> {{ __('Change Status') }}</label>
                                <select name="status" class="form-control form-control-sm" aria-label="Default select example">
                                    <option value="1" @if($ibpcards->status == '1') selected @endif>Active</option>
                                    <option value="2" @if($ibpcards->status == '2') selected @endif>Inactive</option>
                                    <option value="3" @if($ibpcards->status == '3') selected @endif>Block</option>
                                </select>
                            </div>
    
                            <div class="form-group text-right">
                                <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
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
                        <h5>{{ __('Card Topup') }}</h5>
    
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
    
                        <form action="{{ route('cards.topups.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
    
                            <div class="form-group">
                                <label> {{ __('Card Number') }}</label>
                                <input class="form-control" type="hidden" id="card_id" name="card_id" value="{{$ibpcards->id}}">
                                <input class="form-control" type="text" id="card_number" value="{{chunk_split($ibpcards->card_number, 4, ' ')}}" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label>{{ __('Enter Amount') }}</label>
                                <input class="form-control" type="number" id="icon" name="amount" required>
                            </div>
    
                            <div class="form-group text-right">
                                <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
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
    @endif
    
    @if(session()->has('booking_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('booking_message') }}",
                    type: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<a href='{{ route('bookings.view', session()->get('booking_id')) }}'>Booking Details</a>",
                    cancelButtonText: "<a href='{{ route('booking.invoice', session()->get('booking_id')) }}' id='bookinginvoice'>Print</a>",
                    closeOnConfirm: false, 
                    customClass: "Custom_Cancel"
                })
            });
        </script>
    @endif
    
    @if(session()->has('card_topup'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('card_topup') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
    
    <script>
        document.getElementById('bookinginvoice').addEventListener('click', function() {
            window.ReactNativeWebView.postMessage('{{ url("booking-invoice/338") }}');
        });
    </script>
@endsection
