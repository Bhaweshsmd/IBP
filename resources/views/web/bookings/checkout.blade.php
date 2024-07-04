<?php $page = 'booking-checkout';
  
    $userDetails= getUserDetails(Session::get('user_id'));
    $globalsetting=fetchGlobalSettings();
    $taxes=$globalsetting->taxes;
?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
            {{__('string.booking_payment')}}
        @endslot
        @slot('li_1')
            {{__('string.home')}}
        @endslot
        @slot('li_2')
            {{__('string.booking_payment')}}
        @endslot
    @endcomponent
    <style>
        .couponLists{
            display: flex;
    justify-content: space-between;
    align-items: center;
        }
      .couponCard{
            border: 1px solid;
           padding: 15px;
           border-radius: 10px;
           border-color: #eee;
        }
    </style>
    <section class="booking-steps py-30">
        <div class="container">
            <ul class="d-lg-flex justify-content-center align-items-center list-unstyled">
                <li>
                    <h5><a href="{{ route('booking-details',$services->slug) }}"><span>1</span>{{__('string.book_item')}}</a></h5>
                </li>
                <li>
                    <h5><a href="{{ url('booking-order-confirm') }}"><span>2</span>{{__('string.booking_review')}}</a></h5>
                </li>
                <li class="active">
                    <h5><a href="{{ url('booking-checkout') }}"><span>3</span>{{__('string.payment')}}</a></h5>
                </li>
            </ul>
        </div>
    </section>

    <!-- Page Content -->
    <div class="content">
        <div class="container">
          <section class=" ">
                <div class="card text-center mb-40">
                    <h3 class="">{{__('string.payment')}}</h3>
                    <p class="sub-title mb-0">{{__('string.complete_payment')}} </p>
                </div>
            
                <div class="row checkout">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-7">
                        <div class="card booking-details">
                            <h3 class="border-bottom">{{__('string.order_summary')}}</h3>
                            <ul class="list-unstyled">
                                <li><i class="fa-regular fa-building me-2"></i>{{$services->title}}<span
                                        class="x-circle"></span></li>
                                <li><i class="feather-calendar me-2"></i>{{date('D, d F Y',strtotime($date))}}</li>
                                <li><i class="feather-clock me-2"></i>{{date('h:i A',strtotime($booking_time))}}</li>
                                <li><i class="feather-users me-2"></i>{{Session::get('quantity')}} Members</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-5">
                        <aside class="card payment-modes">
                            <h3 class="border-bottom">{{__('string.checkout')}}</h3>
                            <div class="radio">
                             
                                <div class="form-check form-check-inline active">
                                    <input class="form-check-input default-check me-2" type="radio"
                                        name="inlineRadioOptions" id="inlineRadio3" value="Wallet" checked>
                                    <label class="form-check-label" for="inlineRadio3">IBP Account <b> {{$settings->currency}}{{number_format($userDetails->wallet,2)}}</b></label>
                                </div>
                            </div>
                            <hr>
                            <ul class="order-sub-total">
                                  <li>
                                    <p>{{__('string.item_price')}}</p>
                                    <h6>{{$settings->currency}}{{number_format(($services->price-($services->price*$services->discount)/100),2)}}/{{__('string.hrs')}}</h6>
                                </li>
                                <li>
                                    <p>{{__('string.quantity')}}</p>
                                    <h6>{{$quantity}}</h6>
                                </li>
                                 <li>
                                    <p>{{__('string.booking_hours')}}</p>
                                    <h6>{{$booking_hours}}</h6>
                                </li>
                                <li>
                                    <p>{{__('string.sub_total')}}</p>
                                   @if($services->service_type)
                                        <h5>{{$settings->currency}}{{number_format(($services->price*Session::get('booking_hours')-($services->price*Session::get('booking_hours')*$services->discount)/100),2)}}</h5>
                                 
                                   @else
                                       <h5>{{$settings->currency}}{{number_format(($services->price*Session::get('booking_hours')-($services->price*Session::get('booking_hours')*$services->discount)/100)*$quantity,2)}}</h5>
                                      @endif                                </li>
                           
                                <li>
                                    <p>{{__('string.tax_included')}}</p>
                                    <h6>{{$taxes->value}}%</h6>
                                </li>
                            </ul>
                            <div class="order-total d-flex justify-content-between align-items-center">
                                <h5>{{__('string.order_total')}}</h5>
                                 @if($services->service_type)
                                <h5>{{$settings->currency}}{{number_format(($services->price*Session::get('booking_hours')-($services->price*Session::get('booking_hours')*$services->discount)/100),2)}}</h5>
                                 
                                 @else
                                <h5>{{$settings->currency}}{{number_format(($services->price*Session::get('booking_hours')-($services->price*Session::get('booking_hours')*$services->discount)/100)*$quantity,2)}}</h5>
                                @endif
                            </div>
                            @if(count($allcoupons))
                            <ul class="order-sub-total couponCard">
                                  <li>
                                    <p>{{__('Coupons')}}</p>
                                    <a href="#" class="btn btn-primary balance-add applyBtn" data-bs-toggle="modal" data-bs-target="#add-payment">View All</a>
                                 </li>
                                 @foreach($allcoupons as $coupon)
                                   <div class="couponList">
                                   <li >
                                     <p class="couponCode">{{__($coupon->coupon)}}</p>
                                     <p>{{__($coupon->percentage)}}% OFF</p>
                                     <a href="javascript:void(0)" class="btn applyBtn btn-primary applyCoupon"  data-id="{{$coupon->id}}">{{__('Apply Coupon')}}</a>
                                 </li>
                                 <span class="couponHeading">{{__($coupon->heading)}}</span>
                                 </div>
                                 @endforeach
                            </ul>    
                            @endif
                            <div class="form-check d-flex justify-content-start align-items-center policy">
                                <div class="d-inline-block">
                                    <input class="form-check-input" type="checkbox"  id="policy">
                                </div>
                                <label class="form-check-label" for="policy">{{__('string.by_clicking')}} <a href="{{ route('web.pages', 'privacy-policy') }}"><u>{{__('string.privacy_policy')}}</u></a> and <a
                                        href="{{ route('web.pages', 'terms-of-use') }}"><u>{{__('string.terms_of_use')}}</u></a></label>
                            </div>
                            <div class="d-grid btn-block">
                                <a href="javascript:void(0)"  type="button" class="btn btn-primary"  id="WalletCheckout" >{{__('string.proceed')}} {{$settings->currency}}<span class="amount_after_discount">{{number_format($totalBookValue,2)}}</span></a>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>
        </div>
        <!-- /Container -->
    </div>
    <!-- /Page Content -->
    <div class="modal custom-modal fade payment-modal " id="add-payment"  aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">All Coupon</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                @if(count($allcoupons))
                            <ul class="order-sub-total">
                                 @foreach($allcoupons as $coupon)
                                   <div class="couponList">
                                   <li class="couponLists" >
                                     <p class="couponCode">{{__($coupon->coupon)}}</p>
                                     <p>{{__($coupon->percentage)}}% OFF</p>
                                     <a href="javascript:void(0)" class="btn applyBtn btn-primary applyCoupon"  data-id="{{$coupon->id}}">{{__('Apply Coupon')}}</a>
                                 </li>
                                 <span class="couponHeading">{{__($coupon->heading)}}</span>
                                 </div>
                                 @endforeach
                            </ul>    
                            @endif
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                            <a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</a>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
