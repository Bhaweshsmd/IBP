@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/viewBookingDetails.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/style/viewBookingDetails.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
@php
    use App\Models\Constants as Constants;
    use App\Models\GlobalFunction as GlobalFunction;
@endphp
@section('content')
    <style>
        .coupon-text {
            padding: 0px 5px;
            border-radius: 5px;
        }
        
        .swal2-cancel{
          color:#fff !important;   
        }
    </style>

    <input type="hidden" value="{{ $booking->id }}" id="bookingId">
    <input type="hidden" value="{{ $booking->completion_otp }}" id="completionOtp">
    <input type="hidden" value="{{ $booking->booking_id }}" id="bookingIdBig">
    
    @if(has_permission(session()->get('user_type'), 'edit_Bookings'))
        <div class="row flex-column flex-xl-row mt-2">
            <div class="card col-12 mr-2">
                <div class="card-header">
                    <h4 class="d-inline">
                        {{__('Change Booking Status')}}
                    </h4>
                    <a  href="{{route('bookings')}}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> Back to Booking List</a>
                </div>
                <div class="card-body">
                    <select class="form-control" name="weekday" value="" id="changeStatus" required="">
                        <option value="">{{__('Change Booking Status')}}</option>
                        <option value="2"  @if ($booking->status == Constants::orderCompleted) selected  @endif>{{ __('Completed') }}</option>
                        <option value="4" @if ($booking->status == Constants::orderCancelled) selected  @endif>{{__('Cancelled')}}</option>
                    </select>
                </div>
            </div>
        </div>
    @endif
    
    <div class="row flex-column flex-xl-row mt-2">

        <div class="card col mr-2">
            <div class="card-header">
                <h4 class="d-inline">
                    {{ $booking->booking_id }}
                </h4>

                @if ($booking->status == Constants::orderPlacedPending)
                    <span class="badge bg-warning text-white ">{{ __('Waiting For Confirmation') }} </span>
                @elseif($booking->status == Constants::orderAccepted)
                    <span class="badge bg-info text-white ">{{ __('Accepted') }} </span>
                @elseif($booking->status == Constants::orderCompleted)
                    <span class="badge bg-success text-white ">{{ __('Completed') }} </span>
                @elseif($booking->status == Constants::orderDeclined)
                    <span class="badge bg-danger text-white ">{{ __('Declined') }} </span>
                @elseif($booking->status == Constants::orderCancelled)
                    <span class="badge bg-danger text-white ">{{ __('Cancelled') }} </span>
                @endif

            </div>
            <div class="card-body">
                <div class="">
                    <div class="mt-3">
                        <label class="mb-1 text-grey d-block" for="">{{ __('Customer') }}</label>
                        <div class="d-flex align-items-center card-profile">
                            @if ($booking->user->profile_image != null)
                                <img class="rounded owner-img-border mr-2" width="80" height="80" src="{{ url('public/storage/'.$booking->user->profile_image) }}" alt="">
                            @else
                                <img class="rounded owner-img-border mr-2" width="80" height="80" src="http://placehold.jp/150x150.png" alt="">
                            @endif

                            <div>
                                <p class="mt-0 mb-0 p-data">{{ $booking->user->first_name }} {{ $booking->user->last_name }}</p>
                                <span class="mt-0 mb-0">{{ $booking->user->email != null ? $booking->user->email : '' }}</span> <br>
                                <span class="mt-0 mb-0">{{ $booking->user->formated_number != null ? $booking->user->formated_number : '' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="mb-1 text-grey d-block" for="">{{ __('Feedback') }}</label>
                        <div class="card-profile align-items-center">
                            <div>
                                @if ($booking->rating != null)
                                    {!! $ratingBar !!}
                                    <br>
                                    <span class="mt-0 mb-0">{{ $booking->rating->comment }}</span><br>
                                @else
                                    <p class="mt-0 mb-0 p-data">{{ __('No Feedback') }}</p><br>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card col ml-2">
            <div class="card-header">
                <h4 class="d-inline">
                    {{ __('Details') }}
                </h4>
                
                <a class="ml-auto" href="{{ route('booking.invoice', $booking->id) }}">
                    <span class="badge bg-warning text-white ">{{ __('Print') }} </span>
                </a>
            </div>
            <div class="card-body" id="details-body">

                <div class="d-flex">
                    <div class="col p-0">
                        <label class="text-grey d-block mb-0" for="">{{ __('Booking Number') }}</label>
                        <div class="card-profile align-items-center">
                            <div>
                                <p class="mt-0 mb-0 p-data">{{ $booking->booking_id }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col p-0 text-right">
                        <label class="text-grey d-block mb-0" for="">{{ __('Booking Date') }}</label>
                        <div class="card-profile align-items-center">
                            <div>
                                <p class="mt-0 mb-0 p-data">{{ $booking->created_at }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="text-grey d-block mb-0" for="">{{ __('Appointment Schedule') }}</label>
                    <div class="card-profile align-items-center">
                        <div>
                            <p class="p-data"><span class="mt-0 mb-0">{{ __('Date') }}: {{ $booking->date }}</span> |
                                <span class="mt-0 mb-0">{{ __('Time') }}: {{date('h:i A',strtotime($booking->time)) }}</span> |
                                <span class="mt-0 mb-0">{{ __('Duration') }}: {{ $booking->booking_hours}} Hrs</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 p-0 mt-3">
                    <label class="text-grey d-block mb-0" for="">{{ __('Customer') }}</label>
                    <div class="card-profile align-items-center">
                        <div>
                            <p class="mt-0 mb-0 p-data">{{ $booking->user->first_name }} {{ $booking->user->last_name }}</p>
                            <p class="p-data">
                             <span class="mt-0 mb-0">{{ $booking->user->email != null ? $booking->user->email : '' }}</span><br>
                             <span class="mt-0 mb-0"> {{ $booking->user->formated_number != null ? $booking->user->formated_number : '' }}</span>
                            </p>
                            
                        </div>
                    </div>
                </div>

                <div id="payment-details-body " class="mt-3">

                    @foreach ($bookingSummary['services'] as $item)
                        <div class="invoice-item">
                            <div class="d-flex">
                                <p>{{ $item['title'] }}</p>
                            </div>
                            <p>{{ $settings->currency }}{{number_format( $item['price'],2) }}/hr</p>
                        </div>
                    @endforeach
                        <div class="invoice-item ">
                        <div class="d-flex">
                            <p>{{ __('Discounted Price') }}</p>
                        </div>
                        <p>{{ $settings->currency }}{{ number_format($booking->service_amount,2) }}/hr</p>
                    </div>
                    <div class="invoice-item ">
                        <div class="d-flex">
                            <p>{{ __('Duration') }}</p>
                        </div>
                        <p>{{ $booking->booking_hours}} Hrs</p>
                    </div>
                        <div class="invoice-item ">
                        <div class="d-flex">
                            <p>{{ __('Members') }}</p>
                        </div>
                        <p>{{$booking->quantity}}</p>
                    </div>

                    @if(!empty($bookingSummary['coupon_apply']) && $bookingSummary['coupon_apply'] == 1)
                        <div class="invoice-item ">
                            <div class="d-flex">
                                <p>{{ __('Coupon Discount') }}</p>
                                <p class="ml-2 bg-dark text-white coupon-text">{{ $bookingSummary['coupon']['coupon'] }}
                                </p>
                            </div>
                            <p>{{ $settings->currency }}{{number_format($bookingSummary['discount_amount'],2) }}</p>
                        </div>
                    @endif
                    
                    <div class="invoice-item ">
                        <div class="d-flex">
                            <p>{{ __('Subtotal') }}</p>
                        </div>
                        <p>{{ $settings->currency }}{{number_format($booking->subtotal,2) }}</p>
                    </div>

                    @foreach ($bookingSummary['taxes'] as $item)
                        <div class="invoice-item">
                            <div class="d-flex">
                                @if ($item['type'] == Constants::taxPercent)
                                    <p>{{ $item['tax_title'] }}({{__('Included')}}) </p>
                                @else
                                    <p>{{ $item['tax_title'] }}</p>
                                @endif
                            </div>
                            <p> {{$item['value']}}% </p>
                        </div>
                    @endforeach

                    <div class="invoice-item ">
                        <div class="d-flex">
                            <p class="text-white">{{ __('Payable Amount') }}</p>
                        </div>
                        <p class="text-white">{{ $settings->currency }}{{$booking->payable_amount }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if(session()->has('booking_cancel'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Warning",
                    text: "{{ session()->get('booking_cancel') }}",
                    type: "warning",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
