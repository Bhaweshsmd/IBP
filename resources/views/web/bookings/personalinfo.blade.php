<?php $page = 'booking-personalinfo'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
            Book A Court
        @endslot
        @slot('li_1')
            Home
        @endslot
        @slot('li_2')
            Book A Court
        @endslot
    @endcomponent
    
    <section class="booking-steps py-30">
        <span class="primary-right-round"></span>
        <div class="container">
            <ul class="d-lg-flex justify-content-center align-items-center">
                <li>
                    <h5><a href="{{ url('booking-details') }}"><span>1</span>Book a Court</a></h5>
                </li>
                <li>
                    <h5><a href="{{ url('booking-order-confirm') }}"><span>2</span>Order Confirmation</a></h5>
                </li>
                <li class="active">
                    <h5><a href="{{ url('booking-personalinfo') }}"><span>3</span>Personal Information</a></h5>
                </li>
                <li>
                    <h5><a href="{{ url('booking-checkout') }}"><span>4</span>Payment</a></h5>
                </li>
            </ul>
        </div>
    </section>

    <div class="content book-cage">
        <div class="container">
            <section class="mb-40">
                <div class="text-center mb-40">
                    <h3 class="mb-1">Personal Information</h3>
                    <p class="sub-title mb-0">Share your info and embark on a sporting journey.</p>
                </div>
                <div class="card">
                    <h3 class="border-bottom">Enter Details</h3>
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter Name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter Email Address">
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phonenumber" placeholder="Enter Phone Number">
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Address</label>
                            <input type="text" class="form-control" id="address" placeholder="Enter Address">
                        </div>
                        <div>
                            <label for="comments" class="form-label">Details</label>
                            <textarea class="form-control" id="comments" rows="3" placeholder="Enter Comments"></textarea>
                        </div>
                    </form>
                </div>
            </section>
            <div class="text-center btn-row">
                <a class="btn btn-primary me-3 btn-icon" href="{{ url('booking-order-confirm') }}"><i class="feather-arrow-left-circle me-1"></i> Back</a>
                <a class="btn btn-secondary btn-icon" href="{{ url('booking-checkout') }}">Next <i class="feather-arrow-right-circle ms-1"></i></a>
            </div>
        </div>
    </div>
@endsection
