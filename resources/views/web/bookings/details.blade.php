<?php $page = 'booking-details'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
            {{__('string.book_an_item')}}
        @endslot
        @slot('li_1')
            {{__('string.home')}}
        @endslot
        @slot('li_2')
            {{__('string.book_an_item')}}
        @endslot
         @slot('li_3')
            {{$services->title}}
        @endslot
    @endcomponent
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
    </style>
    <input type="hidden" id="service_type" value="{{$service_type??0}}">
    <input type="hidden" id="maximum_quantity" value="{{$maximum_quantity}}">

    <section class="booking-steps py-30">
        <div class="container">
            <ul class="d-lg-flex justify-content-center align-items-center">
                <li class="active">
                    <h5><a href="{{ route('booking-details',$services->slug) }}"><span>1</span>{{__('string.book_item')}}</a></h5>
                </li>
                <li>
                    <h5><a href="{{ url('booking-order-confirm') }}"><span>2</span>{{__('string.confirm_booking')}}</a></h5>
                </li>
                <li>
                    <h5><a href="{{ url('booking-checkout') }}"><span>3</span>{{__('string.payment')}}</a></h5>
                </li>
            </ul>
        </div>
    </section>
    <div class="content book-cage">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                    <section class="card booking-form">
                        <h3 class="border-bottom">{{__('string.booking_form')}}</h3>
                        <form>
                            <div class="mb-3">
                                <label for="date" class="form-label">{{__('string.select_date')}}</label>
                                <div class="form-icon">
                                    <input type="text" class="form-control datetimepicker"  placeholder="{{__('string.select_date')}}" id="date">
                                    <span class="cus-icon">
                                        <i class="feather-calendar icon-date"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="start-time" class="form-label">{{__('string.booking_hour')}}</label>
                                <div class='form-icon'>
                                    <select class="form-control select" name="slots" id="selectSlots" >
                                          <option value="">{{__('string.no_hours')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="end-time" class="form-label d-flex align-items-center col-sm-8 justifyContent">{{__('string.select_time')}}<span class="ml-1" id="bookedColor"></span><span class="text-black">Not Available</span><span class="ml-1" id="availableColor"></span><span class="text-black">Available</span></label>
                               <div class="token-slot mt-2" id="slotslist">{{__('string.no_time_available')}}</div>
                            </div>
                            <div class="select-guest">
                                <h5>{{__('string.select_member')}}</h5><span class="primary-text"></span>
                                <label class="w-100 text-danger" id="slotsNotSelected"></label>
                                <div class="d-md-flex justify-content-between align-items-center">
                                    <div class="qty-item text-center">
                                        <a href="javascript:void(0);"
                                            class="dec d-flex justify-content-center align-items-center"><i
                                                class="feather-minus-circle"></i></a>
                                        <input type="number" class="form-control text-center" name="qty" id="adults"
                                            value="1" readonly>
                                        <a href="javascript:void(0);"
                                            class="inc d-flex justify-content-center align-items-center"><i
                                                class="feather-plus-circle"></i></a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
                
                <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                    <aside class="card booking-details">
                        <h3 class="border-bottom">{{__('string.booking_details')}} {{$settings->currency??''}}<span>{{number_format($services->price-($services->price*$services->discount)/100,2)}}</span>/{{__('string.hrs')}}</h3>
                        <ul>
                            <li><i class="fa-regular fa-building me-2"></i>{{$services->title}}<span
                                    class="x-circle"></span></li>
                          
                        </ul>
                        <div class="d-grid btn-block">
                            <button type="button" class="btn btn-primary">Subtotal : {{$settings->currency??''}}<span id="subtotal">{{number_format($services->price-($services->price*$services->discount)/100,2)}}</span></button>
                        </div>
                    </aside>
                </div>
            </div>
            <div class="text-center btn-row">
                <a class="btn btn-primary me-3 btn-icon" href="javascript:void(0);"><i class="feather-arrow-left-circle me-1"></i> {{__('string.back')}}</a>
                <a class="btn btn-secondary btn-icon" href="javascript:void(0)"  id="nextToConfirm">{{__('string.next')}} <i class="feather-arrow-right-circle ms-1"></i></a>
            </div>
        </div>
    </div>
@endsection
