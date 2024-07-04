<?php $page = 'booking-order-confirm'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
            {{__('string.booking_review')}}
        @endslot
        @slot('li_1')
            {{__('string.home')}}
        @endslot
        @slot('li_2')
            {{__('string.booking_review')}}
        @endslot
    @endcomponent

    <section class="booking-steps py-30">
        <div class="container">
            <ul class="d-lg-flex justify-content-center align-items-center">
                <li>
                    <h5><a href="{{route('booking-details',$services->slug) }}"><span>1</span>{{__('string.book_item')}}</a></h5>
                </li>
                <li class="active">
                    <h5><a href="{{ url('booking-order-confirm') }}"><span>2</span>{{__('string.booking_review')}}</a></h5>
                </li>
                <li>
                    <h5><a href="{{ url('booking-checkout') }}"><span>3</span>{{__('string.payment')}}</a></h5>
                </li>
            </ul>
        </div>
    </section>

    <div class="content book-cage">
        <div class="container">
            <section class="card mb-40">
                <div class="text-center mb-10">
                    <h3 class="">{{__('string.booking_review')}}</h3>
                    <p class="sub-title mb-0">{{__('string.review_your_order')}}</p>
                </div>
            </section>
            <section class="card booking-order-confirmation">
                <h5 class="mb-3">{{__('string.booking_details')}}</h5>
                <ul class="booking-info d-lg-flex justify-content-between align-items-center">
                    <li>
                        <h6>{{__('string.item_name')}}</h6>
                        <p>{{$services->title}}</p>
                    </li>
                    <li>
                        <h6>{{__('string.quantity')}}</h6>
                        <p>{{$quantity}}</p>
                    </li>
                    <li>
                        <h6>{{__('string.appointment_date')}}</h6>
                        <p>{{date('D, d F Y',strtotime($date))}}</p>
                    </li>
                    <li>
                        <h6>{{__('string.appointment_time')}}</h6>
                        <p>{{date('h:i A', strtotime($booking_time))}}</p>
                    </li>
                    <li>
                        <h6>{{__('string.booking_hour')}}</h6>
                        <p>{{$booking_hours}}</p>
                    </li>
                    
                </ul>
                <h5 class="mb-3">{{__('string.contact_information')}}</h5>
                <ul class="contact-info d-lg-flex justify-content-start align-items-center">
                    <li>
                        <h6>{{__('string.name')}}</h6>
                        <p>{{$user_details->first_name??''}}&nbsp;&nbsp;{{$user_details->last_name??''}}</p>
                    </li>
                    <li>
                        <h6>{{__('string.contact_email_address')}}</h6>
                        <p>{{$user_details->email??''}}</p>
                    </li>
                    <li>
                        <h6>{{__('string.phone_number')}} </h6>
                        <p>{{$user_details->formated_number??''}}</p>
                    </li>
                </ul>
                <h5 class="mb-3">{{__('string.payment_information')}}</h5>
                <ul class="payment-info d-lg-flex justify-content-start align-items-center">
                    <li>
                        <h6>{{__('string.item_price')}}</h6>
                        <p class="primary-text">({{$settings->currency}}{{number_format($services->price-($services->price*$services->discount)/100,2)}} X {{Session::get('booking_hours')}} Hrs)</p>
                    </li>
                    <li>
                        <h6>Members</h6>
                        <p class="primary-text">{{$quantity}}</p>
                    </li>
                    <li>
                        <h6>{{__('string.total')}}</h6>
                        @if($services->service_type)
                            <p class="primary-text">{{$settings->currency}}{{number_format(($services->price*Session::get('booking_hours')-($services->price*Session::get('booking_hours')*$services->discount)/100),2)}}</p>
                        @else
                            <p class="primary-text">{{$settings->currency}}{{number_format(($services->price*Session::get('booking_hours')-($services->price*Session::get('booking_hours')*$services->discount)/100)*$quantity,2)}}</p>
                        @endif
                    </li>
                </ul>
            </section>
            <div class="text-center btn-row">
                <a class="btn btn-primary me-3 btn-icon" href="{{route('booking-details',$services->slug) }}"><i class="feather-arrow-left-circle me-1"></i> {{__('string.back')}}</a>
                <a class="btn btn-secondary btn-icon" href="{{ url('booking-checkout') }}">{{__('string.next')}} <i class="feather-arrow-right-circle ms-1"></i></a>
            </div>
        </div>
    </div>
@endsection
