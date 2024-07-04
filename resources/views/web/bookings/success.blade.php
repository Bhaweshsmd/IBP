<?php $page = 'booking-checkout';
  
    $userDetails= getUserDetails(Session::get('user_id'));
    $globalsetting=fetchGlobalSettings();
    
    $taxes=$globalsetting->taxes

?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
            {{__('string.appointment_details')}}
        @endslot
        @slot('li_1')
            {{__('string.home')}}
        @endslot
        @slot('li_2')
            {{__('string.appointment_details')}}
        @endslot
    @endcomponent
  
    <div class="content">
        <div class="container">
            <section class="">
                <div class="text-center mb-4">
                    <h3 class="">{{__('string.appointment_booked')}}</h3>
                </div>
                <div class="row checkout text-center  ">
                   
                    <div class="card-design">
                        <aside class="card payment-modes payment-type ">
                            <h6 class="text-center">{{__('string.appointment_id')}}</h6>
                            <span class="text-center mb-5">IBPBYKX759980MNU</span>
                       
                            <ul class="order-sub-total">
                                  <li>
                                    <p>{{__('string.item')}}</p>
                                    <h6>{{$booking_details->service->title}}</h6>
                                </li>
                                    <li>
                                    <p>{{__('string.date')}}</p>
                                    <h6>{{date('D, d F Y',strtotime($booking_details->date))}}</h6>
                                </li>
                                <li>
                                    <p>{{__('string.time')}}</p>
                                    <h6>{{date('h:i A', strtotime($booking_details->time))}}</h6>
                                </li>
                                <li>
                                    <p>{{__('string.booking_hour')}}</p>
                                    <h6>
                                        @if($booking_details->booking_hours==16)
                                          {{__('string.whole_day')}}
                                        @else
                                        {{$booking_details->booking_hours}}
                                        @endif
                                    </h6>
                                </li>
                                <li>
                                    <p>{{__('string.quantity')}}</p>
                                    <h6>{{$booking_details->quantity}}</h6>
                                </li>
                                <li>
                                    <p>{{__('string.item_price')}}</p>
                                    <h6>{{$settings->currency}}{{number_format($booking_details->service_amount,2)}}/{{__('string.hrs')}}</h6>
                                </li>
                              
                                <li>
                                    <p>{{__('string.total_amount')}}</p>
                                    <h6>{{$settings->currency}}{{number_format($booking_details->payable_amount,2)}}</h6>
                                </li>
                            </ul>
                           
                             <div class="form-check d-flex justify-content-center align-items-center mt-5">
                              
                                <h6>{{__('string.your_appointment_has')}}</h6>
                                
                            </div>
                            <div class="form-check d-flex justify-content-start align-items-center mb-4">
                                <label class="form-check-label" for="policy">{{__('string.check_appointments_tab')}}</label>
                            </div>
                            <div class="d-grid btn-block">
                                <a href="{{route('user-bookings')}}"  type="button" class="btn btn-primary btn-md"  >{{__('string.my_bookings')}} </a>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @component('components.modalpopup')
    @endcomponent
@endsection
