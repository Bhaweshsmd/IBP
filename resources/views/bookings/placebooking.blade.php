@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/viewUserProfile.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/style/viewUserProfile.css') }}">
    <style>
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
        
        .bookingSummery {
            background-color: whitesmoke;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 20px;
            border-bottom: 1px solid #c2c2c2;
        }
        
        .payable-amount {
            color:#fff;
            background-color:#000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 20px;
        }
        
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
    </style>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Book Service') }}</h4>
            @if(session()->get('user_type') == '20')
                <a href="{{ route('dashboard') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> Back To Dashboard</a>
            @else
                <a href="{{ route('users.profile', $user->id) }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> Back To Profile Page</a>
            @endif
        </div>
        <div class="card-body">
            <form action="{{route('place.booking') }}" method="post" enctype="multipart/form-data" autocomplete="off" onsubmit='disableformButton()'>
                @csrf
                <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">
                <input type="hidden" name="order_by" value="{{ __('ADDED_BY_ADMIN') }}">
                <input type="hidden" name="gateway" value="2">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('Select Categories') }}</label>
                            <select name="category_id" id="category_id" class="form-control form-control-sm" placeholder="Select Categories">
                                <option value="">Select Categories</option>
                                @foreach($categories as $category)
                                    <option data-tokens="{{$category->id}}" value="{{$category->id}}">{{$category->title}}</option>
                                @endforeach
                           </select>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Select Services') }}</label>
                            <select class="selectpicker form-control" name="service_id" id="service_id" placeholder="Select Services">
                                <option value="">Select Services</option>
                           </select>
                        </div>
                        <div class="form-group">
                            <label for="date"> {{ __('Date') }}</label>
                            <input type="date" name="date" id="date" min="{{date('d-m-Y')}}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Select booking Hours') }}</label>
                            <select class="selectpicker form-control" name="booking_hours" id="selectSlots" data-live-search="true" required>
                                <option value="">Select Booking Hours</option>
                           </select>
                        </div>
                        <div class="form-group">
                            <label for="end-time" class="form-label">Select Time</label>
                            <div class="token-slot mt-2" id="slotslist">No Time Available</div>
                            <span  class="text-danger" id="slotsNotSelected"></span>
                        </div>
                        <div class="form-group">
                            <label for="end-time" class="form-label">Select Quantity</label>
                            <div class="quantity">
                                <a href="javascript:void(0)" class="quantity__minus" id="minus_quant"><span>-</span></a>
                                <input name="quantity" id="booking_numbers" type="text" class="quantity__input form-control" value="1" readonly>
                                <a href="javascript:void(0)" class="quantity__plus" id="plus_quant"><span>+</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 style="color: #009544;" class="mb-3">Order Details</h4>
                        <div class="form-group mb-0 bookingSummery">
                            <label for="end-time" class="form-label">User: </label> {{ $user->first_name }} {{ $user->last_name }}
                        </div> 
                        
                        <div class="form-group mb-0 bookingSummery">
                            <label for="end-time" class="form-label">Email: </label> {{ $user->email }}
                        </div> 
                        
                        <div class="form-group mb-0 bookingSummery">
                            <label for="end-time" class="form-label">Item Name: </label> <span id="item_name">N/A</span></span>
                        </div> 
                        
                        <div class="form-group mb-0 bookingSummery">
                            <label for="end-time" class="form-label">Item Price: </label> <span>{{ $settings->currency }}<span id="booking_price">0.00</span>/hr</span>
                        </div>
                        
                        <div class="form-group mb-0 bookingSummery">
                            <label for="end-time" class="form-label">Discounted Price: </label> <span>{{ $settings->currency }}<span id="discounted_price">0.00</span>/hr</span>
                        </div>
                        
                        <div class="form-group mb-0 bookingSummery">
                            <label for="end-time" class="form-label">Quantity: </label> <span id="quantity">0</span></span>
                        </div>
                        
                        <div class="form-group mb-0 bookingSummery">
                            <label for="end-time" class="form-label">Total Amount: </label> <span>{{ $settings->currency }}<span id="total_amount">0.00</span></span>
                        </div>
                        
                        <div class="form-group mb-0 bookingSummery">
                            <label for="end-time" class="form-label">Discount: </label> <span><span id="booking_discount">0</span>%</span>
                        </div>
                        
                        <div class="form-group mb-0 bookingSummery">
                            <label for="end-time" class="form-label">Tax (Included): </label> <span><span id="booking_tax">0</span>%</span>
                        </div>
                        
                        <div class="form-group mb-0 payable-amount">
                            <label for="end-time" class="form-label" style="color:#fff; font-size: 20px; margin-bottom: 0px;">Payable Amount: </label> <span style="font-size: 23px;">{{ $settings->currency }}<span id="payable_amount">0.00</span></span>
                        </div>
                        
                        <div class="form-group mt-5">
                            <h4 style="color: #009544;" class="mb-3">Payment Methods</h4>
                            <select class="form-control" name="inlineRadioOptions" required>
                                @if($ibpcards)
                                    <option value="card">IBP Card (Balance : {{ $settings->currency }}{{number_format($ibpcards->balance,2)}})</option>
                                @endif
                                <option value="wallet">IBP Account (Balance : {{ $settings->currency }}{{number_format($user->wallet,2)}})</option>
                            </select>
                        </div> 
                    </div>
                    
                    <div class="form-group col-md-12 text-right">
                        <button class="btn btn-primary" id='submitformbtn' type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script type="text/javascript">
    	$(document).ready(function () {
          $('#category_id').selectize({
              sortField: 'text'
          });
      });
    </script>
    
    @if(session()->has('booking_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Warning",
                    text: "{{ session()->get('booking_message') }}",
                    type: "warning",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
