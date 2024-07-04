<?php $page = 'event-details'; ?>
@extends('layout.mainlayout')
@section('content')

    <div class="content">
        <section class=" event-booking">
            <div class="container">
                <div class="row">
                    <div class="col-12 offset-sm-12 offset-md-2 col-md-8 col-lg-8">
                        <div class="text-center mb-5">
                            <h3>{{__('string.book_an_event')}}</h3>
                            <p>{{__('string.hi_we_are')}} <br> {{__('string.contactus_in_one')}}</p>
                        </div>
                        <form method="post" id="eventInquiries">
                            <div class="card">
                                <div class="mb-10">
                                    <label for="name" class="form-label">{{__('string.event_type')}}</label>
                                    <select class="form-control select" name="event_type" id="event_type" required>
                                        <option value="">Select Event </option>
                                        @if($event_type)
                                            @foreach($event_type as $type)
                                                <option value="{{$type->title}}">{{$type->title}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-10">
                                    <label for="email" class="form-label">{{__('string.number_of_people')}}</label>
                                    <input type="number" min="1"  class="form-control" name="no_of_people" id="no_of_people" placeholder="{{__('string.number_of_people')}}" required>
                                </div>
                                <div class="mb-10">
                                    <label for="date" class="form-label">{{__('string.select_date')}}</label>
                                    <div class="form-icon">
                                        <input type="text" class="form-control datetimepicker" name="event_date" placeholder="{{__('string.select_date')}}"
                                            id="date">
                                        <span class="cus-icon">
                                            <i class="feather-calendar icon-date"></i>
                                        </span>
                                    </div>
                               </div>
                               <div class="mb-10">
                                    <label for="date" class="form-label">{{__('string.select_time')}}</label>
                                    <div class="form-icon">
                                       	<input type="text" class="form-control datetimepicker1" name="event_time" placeholder="{{__('string.select_time')}}">
                                    </div>
                                </div>
                            
                                <div>
                                    <label for="comments" class="form-label">{{__('string.tell_us_about')}}</label>
                                    <textarea class="form-control" id="message" name="message" rows="3"  placeholder="{{__('string.enter_message')}}" required></textarea>
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="submit" class="btn btn-secondary d-flex align-items-center mt-3">{{__('string.submit')}}<i class="feather-arrow-right-circle ms-2"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
