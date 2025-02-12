@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/bookings.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Bookings') }}</h4>
        </div>
        <div class="card-body">

            <ul class="nav nav-pills border-b mb-3  ml-0">

                <li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#allBookingSection"
                        aria-controls="home" role="tab" data-toggle="tab">{{ __('All Bookings') }}<span
                            class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#acceptedBookingSection"
                        role="tab" data-toggle="tab">{{ __('Accepted') }}
                        <span class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#completedBookingSection"
                        role="tab" data-toggle="tab">{{ __('Completed') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#cancelledBookingSection"
                        role="tab" data-toggle="tab">{{ __('Cancelled') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
            </ul>

            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="allBookingSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="allBookingsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Booking Number') }}</th>
                                    <th>{{ __('Service Name') }}</th>
                                    <th>{{ __('Customer ID') }}</th>
                                    <th>{{ __('Full Name') }}</th>
                                    <th>{{ __('Contact') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Service Amount') }}</th>
                                    <th>{{ __('Counpon Discount') }}</th>
                                    <th>{{ __('Subtotal') }}</th>
                                    <th>{{ __('Payable Amount') }}</th>
                                    <th>{{ __('Order Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="row tab-pane" id="pendingBookingSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="pendingBookingsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Booking Number') }}</th>
                                    <th>{{ __('Customer ID') }}</th>
                                    <th>{{ __('Full Name') }}</th>
                                    <th>{{ __('Contact') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Service Amount') }}</th>
                                    <th>{{ __('Counpon Discount') }}</th>
                                    <th>{{ __('Subtotal') }}</th>
                                    <th>{{ __('Payable Amount') }}</th>
                                    <th>{{ __('Order Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="row tab-pane" id="acceptedBookingSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="acceptedBookingsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Booking Number') }}</th>
                                    <th>{{ __('Customer ID') }}</th>
                                    <th>{{ __('Full Name') }}</th>
                                    <th>{{ __('Contact') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Service Amount') }}</th>
                                    <th>{{ __('Counpon Discount') }}</th>
                                    <th>{{ __('Subtotal') }}</th>
                                    <th>{{ __('Payable Amount') }}</th>
                                    <th>{{ __('Order Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="row tab-pane" id="completedBookingSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="completedBookingsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Booking Number') }}</th>
                                    <th>{{ __('Customer ID') }}</th>
                                    <th>{{ __('Full Name') }}</th>
                                    <th>{{ __('Contact') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Service Amount') }}</th>
                                    <th>{{ __('Counpon Discount') }}</th>
                                    <th>{{ __('Subtotal') }}</th>
                                    <th>{{ __('Payable Amount') }}</th>
                                    <th>{{ __('Order Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="row tab-pane" id="cancelledBookingSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="cancelledBookingsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Booking Number') }}</th>
                                    <th>{{ __('Customer ID') }}</th>
                                    <th>{{ __('Full Name') }}</th>
                                   <th>{{ __('Contact') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Service Amount') }}</th>
                                    <th>{{ __('Counpon Discount') }}</th>
                                    <th>{{ __('Subtotal') }}</th>
                                    <th>{{ __('Payable Amount') }}</th>
                                    <th>{{ __('Order Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="row tab-pane" id="declinedBookingSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="declinedBookingsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Booking Number') }}</th>
                                    <th>{{ __('Customer ID') }}</th>
                                    <th>{{ __('Full Name') }}</th>
                                    <th>{{ __('Contact') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Service Amount') }}</th>
                                    <th>{{ __('Counpon Discount') }}</th>
                                    <th>{{ __('Subtotal') }}</th>
                                    <th>{{ __('Payable Amount') }}</th>
                                    <th>{{ __('Order Date') }}</th>
                                    <th>{{ __('Action') }}</th>
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
    </div>
@endsection
