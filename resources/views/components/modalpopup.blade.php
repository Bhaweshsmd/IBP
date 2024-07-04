<style>
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        font-size: 2.5em;
        justify-content: space-around;
        padding: 0 .2em;
        text-align: center;
        width: 7em;
    }
    
    .star-rating input {
      display:none;
    }
    
    .star-rating label {
      color:#ccc;
      cursor:pointer;
    }
    
    .star-rating :checked ~ label {
      color:#f90;
    }
    
    .star-rating label:hover,
    .star-rating label:hover ~ label {
      color:#fc0;
    }
    
    .modal-footer {
        justify-content: center;
    }
</style>

@if (Route::is(['user-bookings']) || Route::is(['user-complete']) || Route::is(['user-ongoing']) || Route::is(['user-cancelled']) )
    @if(!empty($allbookings))    
        @foreach($allbookings as $bookings)
            <div class="modal custom-modal fade request-modal" id="upcoming-court{{$bookings->booking_id}}" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="form-header modal-header-title">
                                <h4 class="mb-0">{{__('string.booking_details')}}  {{$bookings->booking_id}}
                            		@if($bookings->status==1)
        							 	<span class="badge bg-success"><i class="feather-check-square me-1"></i> {{__('string.confirmed')}}</span>
        							@elseif($bookings->status==2)
        						      <span class="badge bg-success"><i class="feather-check-square me-1"></i>	{{__('string.confirmed')}}</span>
        							@elseif($bookings->status==3)
        								{{__('string.declined')}}												
        							@elseif($bookings->status==4)
        					           	<span class="badge bg-danger"><img src="{{URL::asset('/assets/img/icons/delete.svg')}}" alt="Icon" class="me-1">{{__('string.cancelled')}}</span>
        							@else
        							    {{__('string.pending')}}
        							@endif
        							&nbsp;&nbsp;<span >{{__('string.completion_otp')}}&nbsp;&nbsp;</span>{{$bookings->completion_otp}}<span></span>
        						</h4>
                            </div>
                            <a class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                            </a>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card dashboard-card court-information">
                                        <div class="card-header">
                                            <h4>{{__('string.item_information')}}</h4>
                                        </div>
                                        <div class="appointment-info">
                                            <ul class="appointmentset">
                                                <li>
                                                    <div class="appointment-item">
                                                        <div class="appointment-img">
                                                            <img src="{{url('/public/storage/'.$bookings->service->thumbnail)}}" alt="Booking">
                                                        </div>
                                                        <div class="appointment-content">
                                                            <h6>{{$bookings->service->title}}</h6>
                                                            <p class="color-green">{{$bookings->service->category->title}}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.booked_on')}}</h6>
                                                    <p>{{date('D, d M Y',strtotime($bookings->created_at))}}</p>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.price_per_guest')}}</h6>
                                                    <p>{{$settings->currency}}{{number_format($bookings->service_amount,2)}}/{{__('string.hrs')}}</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card dashboard-card court-information">
                                        <div class="card-header">
                                            <h4>{{__('string.appointment_information')}}</h4>
                                        </div>
                                        <div class="appointment-info appoin-border">
                                            <ul class="appointmentset">
                                                <li>
                                                    <h6> {{__('string.scheduled_date_time')}}</h6>
                                                    <p>{{date('D, d M Y',strtotime($bookings->date))}}</p>
                                                    <p>{{date('h:i A', strtotime($bookings->time));}} </p>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.total_no_hours')}}</h6>
                                                    <p> 
                                                        @if($bookings['booking_hours']==16)
                                                            {{__('string.whole_day')}}</li>
                                                        @else
                                                            {{$bookings['booking_hours']}}
                                                        @endif
                                                    </p>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.no_of_guests')}}</h6>
                                                    <p>{{$bookings->quantity}}</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card dashboard-card court-information">
                                        <div class="card-header">
                                            <h4>{{__('string.payment_details')}}</h4>
                                        </div>
                                        <div class="appointment-info appoin-border ">
                                            <ul class="appointmentsetview">
                                                <li>
                                                    <h6>{{__('string.total_amount_paid')}}</h6>
                                                    <p class="color-green">{{$settings->currency}}{{$bookings->payable_amount}}</p>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.paid_on')}}</h6>
                                                    <p>{{date('D, d M Y',strtotime($bookings->created_at))}}</p>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.transaction_id')}}</h6>
                                                    @if($bookings->transaction)
                                                      <p>{{$bookings->transaction->transaction_id}}</p>
                                                    @endif
                                                    @if($bookings->card_transaction)
                                                      <p>{{$bookings->card_transaction->transaction_id}}</p>
                                                    @endif
                                                </li>
                                                <li>
                                                    <h6>{{__('string.payment_type')}}</h6>
                                                    @if($bookings->transaction)
                                                        <p>IBP Account</p>
                                                    @endif
                                                    @if($bookings->card_transaction)
                                                        <p>IBP Card</p>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    @if($bookings->status==2)
                                        <div class="card dashboard-card court-information">
                                            <div class="card-header">
                                                <h4>{{__('string.review_details')}}</h4>
                                            </div>
                                            @php 
                                                 $reviews=App\Models\SalonReviews::where('salon_id',$bookings->service_id)->where('booking_id',$bookings->id)->get();
                                            @endphp
                                            @foreach($reviews as $review)
                                                <div class="user-review-details">
                                                    <div class="user-review-content">
                                                        <div class="table-rating">
                                                            <div class="rating-point">
                                                                @for($x = 1; $x <= $review->rating; $x++)
                                                                    <i class="fas fa-star filled"></i>
                                                                @endfor 
                                                                @for($x = 1; $x <=5-$review->rating; $x++)
                                                                    <i class="fas fa-star"></i>
                                                                @endfor
                                                                <span>{{number_format($review->rating,1)}}</span>
                                                            </div>
                                                        </div>
                                                        <p>{{$review->comment}}</p>
                                                        <h5>Sent on {{date('d ,M Y',strtotime($review->created_at))}}</h5>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="table-accept-btn">
                                <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">{{__('string.close')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif 

    <div class="modal custom-modal fade request-modal" id="upcoming-coach" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">{{__('string.court_information')}}<span class="badge bg-info ms-2">Upcoming</span></h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Court Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/featured/featured-06.jpg') }}"
                                                        alt="Venue">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Angela Roudrigez</h6>
                                                    <div class="table-rating">
                                                        <div class="rating-point">
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <span>30 Reviews</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Location</h6>
                                            <p>Santa Monica, CA</p>
                                        </li>
                                        <li>
                                            <h6>Price Per Hour</h6>
                                            <p class="text-primary fw-semibold fs-16">$200.00 / hr</p>
                                        </li>
                                        <li>
                                            <h6>Rank</h6>
                                            <p>Expert</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>Booking Type</h6>
                                            <p>Single Lesson</p>
                                            <p>3 Days * 1 hour @ <span class="text-primary">$150.00</span></p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.total_no_hours')}}</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Booking Days</h4>
                                </div>
                                <div class="booking-days">
                                    <ul>
                                        <li class="active">
                                            <img src="{{ URL::asset('/assets/img/icons/reset.svg') }}" class="me-2"
                                                alt="Icon">
                                            <i class="feather-check-circle me-2"></i>
                                            14 May 2023 - 7:00 PM
                                            <i class="fa fa-check-circle ms-2"></i>
                                        </li>
                                        <li class="active">
                                            <img src="{{ URL::asset('/assets/img/icons/reset.svg') }}" class="me-2"
                                                alt="Icon">
                                            <i class="feather-check-circle me-2"></i>
                                            15 May 2023 - 7:00 PM
                                            <i class="fa fa-check-circle ms-2"></i>
                                        </li>
                                        <li>
                                            <img src="{{ URL::asset('/assets/img/icons/reset.svg') }}" class="me-2"
                                                alt="Icon">
                                            <i class="feather-check-circle me-2"></i>
                                            16 May 2023 - 7:00 PM
                                            <i class="fa fa-check-circle ms-2"></i>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Coaching Booking Amount</h6>
                                            <p>$200</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="text-primary fs-16">$420</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Visa Card</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade request-modal" id="profile-coach" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Coach Profile</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="profile-set">
                                    <div class="profile-set-image">
                                        <img src="{{ URL::asset('/assets/img/featured/featured-05.jpg') }}" alt="Venue">
                                    </div>
                                    <div class="profile-set-content">
                                        <h3>Kevin Anderson</h3>
                                        <div class="rating-city">
                                            <div class="profile-set-rating">
                                                <span>4.5</span>
                                                <h6>300 Reviews</h6>
                                            </div>
                                            <div class="profile-set-img">
                                                <img src="{{ URL::asset('/assets/img/flag/usa.png') }}"
                                                    alt="User">
                                                <h6>Santamanica, United states</h6>
                                            </div>
                                        </div>
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                            industry.industry's</p>
                                        <ul>
                                            <li>
                                                <img src="{{ URL::asset('/assets/img/icons/rank.svg') }}"
                                                    alt="Icon">
                                                <h6>Rank : Expert</h6>
                                            </li>
                                            <li>
                                                <img src="{{ URL::asset('/assets/img/icons/process.svg') }}"
                                                    alt="Icon">
                                                <h6>Sessions Completed : 25</h6>
                                            </li>
                                            <li>
                                                <img src="{{ URL::asset('/assets/img/icons/calendar-alt.svg') }}"
                                                    alt="Icon">
                                                <h6>With Dreamsport since<span> Apr 5, 2023</span></h6>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="price-set-price">
                                        <h6>Start’s From</h6>
                                        <h5>$250<span>/{{__('string.hrs')}}</span></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-tab">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab"
                                            data-bs-target="#profile" type="button" role="tab"
                                            aria-selected="true">Profile Info</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="appointment-tab" data-bs-toggle="tab"
                                            data-bs-target="#appointment" type="button" role="tab"
                                            aria-selected="false">Appointment Details</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab"
                                            data-bs-target="#reviews" type="button" role="tab"
                                            aria-selected="false">Reviews</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="previous-tab" data-bs-toggle="tab"
                                            data-bs-target="#previous" type="button" role="tab"
                                            aria-selected="false">Previous Booking</button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="profile" role="tabpanel">
                                        <div class="profile-card mb-0">
                                            <div class="profile-card-title">
                                                <h4>Contact Information</h4>
                                            </div>
                                            <div class="profile-contact-details">
                                                <ul>
                                                    <li>
                                                        <span>Email Address</span>
                                                        <h6>contact@example.com</h6>
                                                    </li>
                                                    <li>
                                                        <span>Phone Number</span>
                                                        <h6>+1 56565 556558</h6>
                                                    </li>
                                                    <li>
                                                        <span> Address</span>
                                                        <h6>1653 Davisson Street,Indianapolis, IN 46225</h6>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="profile-card-title">
                                                <h4>Short Bio</h4>
                                            </div>
                                            <div class="profile-card-content">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Varius
                                                    consectetur a at est diam ultricies. Egestas eros leo dapibus tellus
                                                    neque turpis. Nec in morbi adipiscing pretium accumsan urna ac,Lorem
                                                    ipsum dolor sit amet, consectetur adipiscing elit. Varius
                                                    consectetur a at est diam ultricies. Egestas eros leo dapibus tellus
                                                    neque turpis. Nec in morbi adipiscing pretium accumsan urna ac,Lorem
                                                    ipsum dolor sit amet, consectetur adipiscing elit. Varius
                                                    consectetur a at est diam ultricies. Egestas eros leo dapibus tellus
                                                    neque turpis. Nec in morbi adipiscing pretium accumsan urna ac,</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="appointment" role="tabpanel" aria-labelledby="appointment-tab">
                                        <div class="accordion">
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/short-bio.svg') }}"
                                                                alt="Icon"></span> Short Bio
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse">
                                                    <div class="accordion-body">
                                                        <div class="text show-more-height">
                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                                It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem Ipsum</p>

                                                            <ul>
                                                                <li>4 years of high school (3 years varsity)</li>
                                                                <li>3 years of college club badminton at Loyola
                                                                    Marymount</li>
                                                                <li>I grew up at North Venice Little League and
                                                                    represented</li>
                                                                <li>Southern California in 2017 for Senior State Champs.
                                                                </li>
                                                                <li>3 years on Varsity at Venice High School. Venice
                                                                    Varsity</li>
                                                                <li>badminton Western League Champs 2017.</li>
                                                                <li>2 years of Loyola Marymount University Club
                                                                    badminton.</li>
                                                            </ul>
                                                            <p>It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem Ipsum</p>
                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                                It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem Ipsum</p>
                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                            </p>
                                                            <p>It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem Ipsum</p>
                                                        </div>
                                                        <div class="show-more d align-items-center primary-text"><i
                                                                class="feather-plus-circle"></i>Show More</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/lesson-with-me.svg') }}"
                                                                alt="Icon"></span> Lesson With me
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse">
                                                    <div class="accordion-body">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and
                                                            typesetting industry. Lorem Ipsum has been the industry's
                                                            standard dummy text ever since the 1500s, when an unknown
                                                            printer took a galley of type and scrambled it to make a
                                                            type specimen book. It has survived not only five centuries,
                                                            but also the leap into electronic typesetting, remaining
                                                            essentially unchanged. It was popularised in the 1960s with
                                                            the release of Letraset sheets containing Lorem Ipsum
                                                            passages, and more recently with desktop publishing software
                                                            like Aldus PageMaker including versions of Lorem Ipsum</p>
                                                        <ul>
                                                            <li><i class="feather-check-square"></i>Single Lesson</li>
                                                            <li><i class="feather-check-square"></i>2 Player Lesson
                                                            </li>
                                                            <li><i class="feather-check-square"></i>Small group Lesson
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseThree"
                                                        aria-expanded="false"
                                                        aria-controls="panelsStayOpen-collapseThree">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/coaching.svg') }}"
                                                                alt="Icon"></span> Coaching
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-coaching">
                                                    <div class="accordion-body">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and
                                                            typesetting industry. Lorem Ipsum has been the industry's
                                                            standard dummy text ever since the 1500s, when an unknown
                                                            printer took a galley of type and scrambled it to make a
                                                            type specimen book. It has survived not only five centuries,
                                                            but also the leap into electronic typesetting, remaining
                                                            essentially unchanged. It was popularised in the 1960s with
                                                            the release of Letraset sheets containing Lorem Ipsum
                                                            passages, and more recently with desktop publishing software
                                                            like Aldus PageMaker including versions of Lorem Ipsum</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseFive"
                                                        aria-expanded="false">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/gallery.svg') }}"
                                                                alt="Icon"></span> Gallery
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse">
                                                    <div class="accordion-body">
                                                        <div class="owl-carousel gallery-slider owl-theme">
                                                            <div>
                                                                <img class="img-fluid" alt="Gallery"
                                                                    src="{{ URL::asset('/assets/img/gallery/gallery4/gallery-15.jpg') }}">
                                                            </div>
                                                            <div>
                                                                <img class="img-fluid" alt="Gallery"
                                                                    src="{{ URL::asset('/assets/img/gallery/gallery4/gallery-16.jpg') }}">
                                                            </div>
                                                            <div>
                                                                <img class="img-fluid" alt="Gallery"
                                                                    src="{{ URL::asset('/assets/img/gallery/gallery4/gallery-17.jpg') }}">
                                                            </div>
                                                            <div>
                                                                <img class="img-fluid" alt="Gallery"
                                                                    src="{{ URL::asset('/assets/img/gallery/gallery4/gallery-16.jpg') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item" id="location">
                                                <h4 class="accordion-header" id="panelsStayOpen-location">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseSeven"
                                                        aria-expanded="false"
                                                        aria-controls="panelsStayOpen-collapseSeven">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/location.svg') }}"
                                                                alt="Icon"></span> Location
                                                    </button>
                                                </h4>
                                                <div id="panelsStayOpen-collapseSeven"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-location">
                                                    <div class="accordion-body">
                                                        <div class="google-maps">
                                                            <iframe
                                                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2967.8862835683544!2d-73.98256668525309!3d41.93829486962529!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89dd0ee3286615b7%3A0x42bfa96cc2ce4381!2s132%20Kingston%20St%2C%20Kingston%2C%20NY%2012401%2C%20USA!5e0!3m2!1sen!2sin!4v1670922579281!5m2!1sen!2sin"
                                                                height="170" style="border:0;" allowfullscreen=""
                                                                loading="lazy"
                                                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                                                        </div>
                                                        <div
                                                            class="dull-bg d-flex justify-content-start align-items-center mb-3">
                                                            <div class="white-bg me-2">
                                                                <i class="fas fa-location-arrow"></i>
                                                            </div>
                                                            <div class="">
                                                                <h6>Our Venue Location</h6>
                                                                <p>70 Bright St New York, USA</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="reviews" role="tabpanel"
                                        aria-labelledby="reviews-tab">
                                        <div class="review-box review-box-user d-flex">
                                            <div class="review-profile">
                                                <img src="{{ URL::asset('/assets/img/profiles/avatar-01.jpg') }}"
                                                    class="img-fluid" alt="User">
                                            </div>
                                            <div class="review-info">
                                                <h6 class="mb-2 tittle">Amanda Booked on 06/04/2023</h6>
                                                <div class="rating">
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <span class="">5.0</span>
                                                </div>
                                                <span class="success-text"><i class="feather-check"></i>Yes, I would
                                                    book again.</span>
                                                <h6>Absolutely perfect</h6>
                                                <p>If you are looking for a perfect place for friendly matches with your
                                                    friends or a competitive match, It is the best place.</p>
                                                <ul class="review-gallery">
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-01.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-01.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-02.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-02.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-03.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-03.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-04.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-04.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-05.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-05.jpg') }}">
                                                        </a>
                                                    </li>
                                                </ul>
                                                <span class="post-date">Sent on 11/03/2023</span>
                                            </div>
                                        </div>
                                        <div class="review-box review-box-user d-flex">
                                            <div class="review-profile">
                                                <img src="{{ URL::asset('/assets/img/profiles/avatar-02.jpg') }}"
                                                    class="img-fluid" alt="img">
                                            </div>
                                            <div class="review-info">
                                                <h6 class="mb-2 tittle">Amanda Booked on 06/04/2023</h6>
                                                <div class="rating">
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <span class="">5.0</span>
                                                </div>
                                                <h6>Awesome. Its very convenient to play.</h6>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                    industry. Lorem Ipsum has been the industry's standard dummy text
                                                    ever since the 1500s, when an unknown printer took a galley of type
                                                    and scrambled it to make a type specimen book. It has survived not
                                                    only five centuries, but also the leap into electronic!!</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="previous" role="tabpanel"
                                        aria-labelledby="previous-tab">
                                        <div class="preview-tab">
                                            <ul>
                                                <li>
                                                    <div class="preview-tabcontent">
                                                        <div class="preview-tabimg">
                                                            <img src="{{ URL::asset('/assets/img/services/service-01.jpg') }}"
                                                                alt="Service">
                                                        </div>
                                                        <div class="preview-tabname">
                                                            <h4>Leap Sports Academy</h4>
                                                            <h5>Court 1</h5>
                                                            <ul>
                                                                <li><span>Guests : 4</span></li>
                                                                <li><span>2 Hrs</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <h6>Date & Time</h6>
                                                    <span class="d-block">Mon, Jul 11</span>
                                                    <span>06:00 PM - 08:00 PM</span>
                                                </li>
                                                <li>
                                                    <h6>$400</h6>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="preview-tab">
                                            <ul>
                                                <li>
                                                    <div class="preview-tabcontent">
                                                        <div class="preview-tabimg">
                                                            <img src="{{ URL::asset('/assets/img/services/service-02.jpg') }}"
                                                                alt="Service">
                                                        </div>
                                                        <div class="preview-tabname">
                                                            <h4>Marsh Academy</h4>
                                                            <h5>Court 1</h5>
                                                            <ul>
                                                                <li><span>Guests : 4</span></li>
                                                                <li><span>2 Hrs</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <h6>Date & Time</h6>
                                                    <span class="d-block">Mon, Jul 11</span>
                                                    <span>06:00 PM - 08:00 PM</span>
                                                </li>
                                                <li>
                                                    <h6>$300</h6>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade request-modal" id="profile-court" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Item Details</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="master-academy dull-whitesmoke-bg card master-academyview">
                                <div class="row d-flex align-items-center justify-content-center">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                                        <div class="d-sm-flex justify-content-start align-items-center">
                                            <a href="javascript:void(0);"><img class="corner-radius-10"
                                                    src="{{ URL::asset('/assets/img/master-academy.png') }}"
                                                    alt="Academy"></a>
                                            <div class="info">
                                                <h3 class="mb-2">Manchester Academy</h3>
                                                <div class="profile-set-content w-100 p-0">
                                                    <ul class="bg-transparent p-0">
                                                        <li>
                                                            <img src="{{ URL::asset('/assets/img/icons/location.svg') }}"
                                                                alt="Icon">
                                                            <h6> 70 Bright St New York, USA</h6>
                                                        </li>
                                                        <li>
                                                            <img src="{{ URL::asset('/assets/img/icons/call.svg') }}"
                                                                alt="Icon">
                                                            <h6>+3 80992 31212</h6>
                                                        </li>
                                                        <li>
                                                            <img src="{{ URL::asset('/assets/img/icons/mail.svg') }}"
                                                                alt="Icon">
                                                            <h6> yourmail@example.com</h6>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="coach-profile-set">
                                                    <ul class="bg-transparent p-0">
                                                        <li>
                                                            <div class="coach-profile-set-img">
                                                                <img src="{{ URL::asset('/assets/img/icons/indoor.svg') }}"
                                                                    alt="Icon">
                                                            </div>
                                                            <div class="coach-profile-set-contemt">
                                                                <h5>Venue </h5>
                                                                <span> Indoor</span>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="coach-profile-set-img ">
                                                                <img src="{{ URL::asset('/assets/img/profiles/avatar-01.jpg') }}"
                                                                    alt="Icon">
                                                            </div>
                                                            <div class="coach-profile-set-contemt">
                                                                <h5>Venue </h5>
                                                                <span> Indoor</span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                                        <ul class="d-sm-flex align-items-center justify-content-evenly">
                                            <li>
                                                <h3 class="d-inline-block">$150</h3><span>/{{__('string.hrs')}}</span>
                                                <p>up to 1 guests</p>
                                            </li>
                                            <li>
                                                <span><i class="feather-plus"></i></span>
                                            </li>
                                            <li class="text-center">
                                                <h3 class="d-inline-block">$5</h3><span>/{{__('string.hrs')}}</span>
                                                <p>each additional guest <br>up to 4 guests max</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-tab">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="profile-tab1" data-bs-toggle="tab"
                                            data-bs-target="#profile1" type="button" role="tab"
                                            aria-selected="true">Profile Info</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="appointment-tab1" data-bs-toggle="tab"
                                            data-bs-target="#appointment1" type="button" role="tab"
                                            aria-selected="false">Appointment Details</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="reviews-tab1" data-bs-toggle="tab"
                                            data-bs-target="#reviews1" type="button" role="tab"
                                            aria-selected="false">Reviews</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="previous-tab1" data-bs-toggle="tab"
                                            data-bs-target="#previous1" type="button" role="tab"
                                            aria-selected="false">Previous Booking</button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="profile1" role="tabpanel">
                                        <div class="profile-card mb-0">
                                            <div class="profile-card-title">
                                                <h4>Contact Information</h4>
                                            </div>
                                            <div class="profile-contact-details mb-0">
                                                <ul>
                                                    <li>
                                                        <span>Email Address</span>
                                                        <h6>contact@example.com</h6>
                                                    </li>
                                                    <li>
                                                        <span>Phone Number</span>
                                                        <h6>+1 56565 556558</h6>
                                                    </li>
                                                    <li>
                                                        <span> Address</span>
                                                        <h6>1653 Davisson Street,Indianapolis, IN 46225</h6>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="appointment1" role="tabpanel">
                                        <div class="accordion">
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/Black.svg') }}"
                                                                alt="Icon"></span>Overview
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse ">
                                                    <div class="accordion-body">
                                                        <div class="text">
                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                                It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem Ipsum</p>
                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                            </p>
                                                            <p>It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem IpsumLorem
                                                                Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/lesson-with-me.svg') }}"
                                                                alt="Icon"></span> Rules
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse ">
                                                    <div class="accordion-body">
                                                        <p><i class="feather-alert-octagon text-danger me-2"></i>Non
                                                            Marking Shoes are recommended not mandatory for Badminton.
                                                        </p>
                                                        <p><i class="feather-alert-octagon text-danger me-2"></i>A
                                                            maximum number of members per booking per badminton court is
                                                            admissible fixed by Venue Vendors</p>
                                                        <p><i class="feather-alert-octagon text-danger me-2"></i>No
                                                            pets, no seeds, no gum, no glass, no hitting or swinging
                                                            outside of the cage</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/lesson-with-me.svg') }}"
                                                                alt="Icon"></span> Amenities
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse">
                                                    <div class="accordion-body">
                                                        <ul class="amenities-set">
                                                            <li>
                                                                <span><i class="fa fa-check-circle text-success me-2"
                                                                        aria-hidden="true"></i>Parking</span>
                                                            </li>
                                                            <li>
                                                                <span><i class="fa fa-check-circle text-success me-2"
                                                                        aria-hidden="true"></i>Drinking Water</span>
                                                            </li>
                                                            <li>
                                                                <span><i class="fa fa-check-circle text-success me-2"
                                                                        aria-hidden="true"></i>First Aid</span>
                                                            </li>
                                                            <li>
                                                                <span><i class="fa fa-check-circle text-success me-2"
                                                                        aria-hidden="true"></i>Change Room</span>
                                                            </li>
                                                            <li>
                                                                <span><i class="fa fa-check-circle text-success me-2"
                                                                        aria-hidden="true"></i>Shower</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4" id="coaching">
                                                <h4 class="accordion-header" id="panelsStayOpen-coaching">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseThree"
                                                        aria-expanded="false"
                                                        aria-controls="panelsStayOpen-collapseThree">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/includes1.svg') }}"
                                                                alt="Icon"></span> Includes
                                                    </button>
                                                </h4>
                                                <div id="panelsStayOpen-collapseThree"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-coaching">
                                                    <div class="accordion-body includes-set">
                                                        <ul>
                                                            <li><i class="feather-check-square"></i>Badminton Racket
                                                                Unlimited</li>
                                                            <li><i class="feather-check-square"></i>Bats</li>
                                                            <li><i class="feather-check-square"></i>Hitting Machines
                                                            </li>
                                                            <li><i class="feather-check-square"></i>Multiple Courts
                                                            </li>
                                                        </ul>
                                                        <ul>
                                                            <li><i class="feather-check-square"></i>Spare Players</li>
                                                            <li><i class="feather-check-square"></i>Instant Racket</li>
                                                            <li><i class="feather-check-square"></i>Hitting Machines
                                                            </li>
                                                            <li><i class="feather-check-square"></i>Green Turfs</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/gallery.svg') }}"
                                                                alt="Image"></span> Gallery
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse ">
                                                    <div class="accordion-body">
                                                        <div class="owl-carousel gallery-slider owl-theme">
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('/assets/img/gallery/gallery4/gallery-18.jpg') }}">
                                                            </div>
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('/assets/img/gallery/gallery4/gallery-19.jpg') }}">
                                                            </div>
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('/assets/img/gallery/gallery4/gallery-20.jpg') }}">
                                                            </div>
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('/assets/img/gallery/gallery4/gallery-20.jpg') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="reviews1" role="tabpanel">
                                        <div class="review-box review-box-user d-flex">
                                            <div class="review-profile">
                                                <img src="{{ URL::asset('/assets/img/profiles/avatar-03.jpg') }}"
                                                    class="img-fluid" alt="User">
                                            </div>
                                            <div class="review-info">
                                                <h6 class="mb-2 tittle">Amanda Booked on 06/04/2023</h6>
                                                <div class="rating">
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <span class="">5.0</span>
                                                </div>
                                                <span class="success-text"><i class="feather-check"></i>Yes, I would
                                                    book again.</span>
                                                <h6>Absolutely perfect</h6>
                                                <p>If you are looking for a perfect place for friendly matches with your
                                                    friends or a competitive match, It is the best place.</p>
                                                <ul class="review-gallery">
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-01.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-01.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-02.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-02.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-03.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-03.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-04.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-04.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-05.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-05.jpg') }}">
                                                        </a>
                                                    </li>
                                                </ul>
                                                <span class="post-date">Sent on 11/03/2023</span>
                                            </div>
                                        </div>
                                        <div class="review-box review-box-user d-flex">
                                            <div class="review-profile">
                                                <img src="{{ URL::asset('/assets/img/profiles/avatar-02.jpg') }}"
                                                    class="img-fluid" alt="User">
                                            </div>
                                            <div class="review-info">
                                                <h6 class="mb-2 tittle">Amanda Booked on 06/04/2023</h6>
                                                <div class="rating">
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <span class="">5.0</span>
                                                </div>
                                                <h6>Awesome. Its very convenient to play.</h6>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                    industry. Lorem Ipsum has been the industry's standard dummy text
                                                    ever since the 1500s, when an unknown printer took a galley of type
                                                    and scrambled it to make a type specimen book. It has survived not
                                                    only five centuries, but also the leap into electronic!!</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="previous1" role="tabpanel">
                                        <div class="profile-card">
                                            <div class="preview-tab">
                                                <ul>
                                                    <li>
                                                        <div class="preview-tabcontent">
                                                            <div class="preview-tabimg">
                                                                <img src="{{ URL::asset('/assets/img/featured/featured-05.jpg') }}"
                                                                    alt="Venue">
                                                            </div>
                                                            <div class="preview-tabname">
                                                                <h4>Kevin Anderson</h4>
                                                                <h5>Onetime</h5>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <h6>Date & Time</h6>
                                                        <span class="d-block">Mon, Jul 11</span>
                                                        <span>06:00 PM - 08:00 PM</span>
                                                    </li>
                                                    <li>
                                                        <h6>$400</h6>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="preview-tab">
                                                <ul>
                                                    <li>
                                                        <div class="preview-tabcontent">
                                                            <div class="preview-tabimg">
                                                                <img src="{{ URL::asset('/assets/img/featured/featured-06.jpg') }}"
                                                                    alt="Venue">
                                                            </div>
                                                            <div class="preview-tabname">
                                                                <h4>Evon Raddick</h4>
                                                                <h5>Onetime</h5>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <h6>Date & Time</h6>
                                                        <span class="d-block">Mon, Jul 11</span>
                                                        <span>06:00 PM - 08:00 PM</span>
                                                    </li>
                                                    <li>
                                                        <h6>$300</h6>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($allbookings))    
        @foreach($allbookings as $bookings)
            <div class="modal custom-modal fade payment-modal" id="add-review{{$bookings->booking_id}}" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="form-header modal-header-title">
                                <h4 class="mb-0">Write a Review</h4>
                            </div>
                            <a class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                            </a>
                        </div>
                        <div class="modal-body">
                            <form  name="addRating" action="{{route('add-rating')}}" id="addRating" method="post">
                                @csrf
                                <input type="hidden" name="booking_id" value="{{$bookings->booking_id}}" class="form-control" id="reviewer-name">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-space">
                                            <label class="form-label">Your Review <span>*</span></label>
                                            <textarea class="form-control" name="comment" id="comment" rows="3" placeholder="Enter Your Review" required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-space review">
                                            <label class="form-label">Rating <span>*</span></label>
                                                <div class="star-rating">
                                                      <input type="radio" id="5-{{$bookings->booking_id}}" name="rating" value="5" />
                                                      <label for="5-{{$bookings->booking_id}}" class="star">&#9733;</label>
                                                      <input type="radio" id="4-{{$bookings->booking_id}}" name="rating" value="4" />
                                                      <label for="4-{{$bookings->booking_id}}" class="star">&#9733;</label>
                                                      <input type="radio" id="3-{{$bookings->booking_id}}" name="rating" value="3" />
                                                      <label for="3-{{$bookings->booking_id}}" class="star">&#9733;</label>
                                                      <input type="radio" id="2-{{$bookings->booking_id}}" name="rating" value="2" />
                                                      <label for="2-{{$bookings->booking_id}}" class="star">&#9733;</label>
                                                      <input type="radio" id="1-star" name="rating" value="1" />
                                                      <label for="1-star" class="star">&#9733;</label>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-accept-btn" style="float:right;">
                                <button type="submit" class="btn btn-primary "  >Submit</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @if(!empty($allbookings))    
        @foreach($allbookings as $bookings)
            <div class="modal custom-modal fade request-modal" id="cancel-court{{$bookings->booking_id}}" role="dialog">
                 <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="form-header modal-header-title">
                                <h4 class="mb-0">Booking Details  {{$bookings->booking_id}}
                            		@if($bookings->status==1)
        							 	<span class="badge bg-success"><i class="feather-check-square me-1"></i> Confirmed</span>
        							@elseif($bookings->status==2)
        						      <span class="badge bg-success"><i class="feather-check-square me-1"></i>	Completed</span>
        							@elseif($bookings->status==3)
        								Declined												
        							@elseif($bookings->status==4)
        					           	<span class="badge bg-danger"><img src="{{URL::asset('/assets/img/icons/delete.svg')}}" alt="Icon" class="me-1">Cancelled</span>
        							@else
        							    Pending
        							@endif
        						</h4>
                            </div>
                            <a class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                            </a>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card dashboard-card court-information">
                                        <div class="card-header">
                                            <h4>{{__('string.item_information')}}</h4>
                                        </div>
                                        <div class="appointment-info">
                                            <ul class="appointmentset">
                                                <li>
                                                    <div class="appointment-item">
                                                        <div class="appointment-img">
                                                            <img src="{{url('/public/storage/'.$bookings->service->thumbnail)}}"
                                                                alt="Booking">
                                                        </div>
                                                        <div class="appointment-content">
                                                            <h6>{{$bookings->service->title}}</h6>
                                                            <p class="color-green">{{$bookings->service->category->title}}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.booked_on')}}</h6>
                                                    <p>{{date('D, d M Y',strtotime($bookings->created_at))}}</p>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.price_per_guest')}}</h6>
                                                    <p>{{$settings->currency}}{{number_format($bookings->service_amount,2)}}/{{__('string.hrs')}}</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card dashboard-card court-information">
                                        <div class="card-header">
                                            <h4>{{__('string.appointment_information')}}</h4>
                                        </div>
                                        <div class="appointment-info appoin-border">
                                            <ul class="appointmentset">
                                                <li>
                                                    <h6> {{__('string.scheduled_date_time')}}</h6>
                                                    <p>{{date('D, d M Y',strtotime($bookings->date))}}</p>
                                                    <p>{{date('h:i A', strtotime($bookings->time));}} </p>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.total_no_hours')}}</h6>
                                                    <p> 
                                                        @if($bookings['booking_hours']==16)
                                                            {{__('string.whole_day')}}
                                                        @else
                                                            {{$bookings['booking_hours']}}
                                                        @endif
                                                    </p>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.no_of_guests')}}</h6>
                                                    <p>{{$bookings->quantity}}</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card dashboard-card court-information">
                                        <div class="card-header">
                                            <h4>{{__('string.payment_details')}}</h4>
                                        </div>
                                        <div class="appointment-info appoin-border ">
                                            <ul class="appointmentsetview">
                                                <li>
                                                    <h6>{{__('string.total_amount_paid')}}</h6>
                                                    <p class="color-green">{{$settings->currency}}{{$bookings->payable_amount}}</p>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.paid_on')}}</h6>
                                                    <p>{{date('D, d M Y',strtotime($bookings->created_at))}}</p>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.transaction_id')}}</h6>
                                                    @if($bookings->transaction)
                                                        <p>{{$bookings->transaction->transaction_id}}</p>
                                                    @endif
                                                    @if($bookings->card_transaction)
                                                        <p>{{$bookings->card_transaction->transaction_id}}</p>
                                                    @endif
                                                </li>
                                                <li>
                                                    <h6>{{__('string.payment_type')}}</h6>
                                                    @if($bookings->transaction)
                                                        <p>IBP Account</p>
                                                    @endif
                                                    @if($bookings->card_transaction)
                                                        <p>IBP Card</p>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="card dashboard-card court-information">
                                        <div class="card-header">
                                            <h4>{{__('string.cancellation_charges_refund')}}</h4>
                                        </div>
                                        <div class="appointment-info appoin-border ">
                                            <ul class="appointmentsetview">
                                                @php
                                                    if(Session::get('user_type')==1){
                                                        $charge = $bookings->payable_amount*$bookings->service->foreiner_cancellation_charges/100;
                                                        $refund = $bookings->payable_amount-$charge;
                                                    }else{
                                                        $charge = $bookings->payable_amount*$bookings->service->local_cancellation_charges/100;
                                                        $refund = $bookings->payable_amount-$charge;
                                                    }
                                                @endphp
                                                <li>
                                                    <h6>{{__('string.cancellation_charges')}}</h6>
                                                    <p class="color-green">{{$settings->currency}}{{number_format($charge,2)}}</p>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.refund')}}</h6>
                                                    <p>{{$settings->currency}}{{number_format($refund,2)}}</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                @if($bookings->status==2)
                                    <div class="card dashboard-card court-information">
                                        <div class="card-header">
                                            <h4>Review Details</h4>
                                        </div>
                                        @php 
                                            $reviews=App\Models\SalonReviews::where('salon_id',$bookings->service_id)->where('booking_id',$bookings->id)->get();
                                        @endphp
                                        @foreach($reviews as $review)
                                            <div class="user-review-details">
                                                <div class="user-review-content">
                                                    <div class="table-rating">
                                                        <div class="rating-point">
                                                            @for($x = 1; $x <= $review->rating; $x++)
                                                                                <i class="fas fa-star filled"></i>
                                                            @endfor 
                                                            @for($x = 1; $x <=5-$review->rating; $x++)
                                                                <i class="fas fa-star"></i>
                                                            @endfor
                                                            <span>{{number_format($review->rating,1)}}</span>
                                                        </div>
                                                    </div>
                                                    <p>{{$review->comment}}</p>
                                                    <h5>Sent on {{date('d ,M Y',strtotime($review->created_at))}}</h5>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="table-accept-btn">
                                <a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">Close</a>
                                <a href="javascript:;" class="btn btn-primary" id="cancelBooking"  data-bookingid="{{$bookings->booking_id}}"  >Confirm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @if(!empty($allbookings))    
        @foreach($allbookings as $bookings)
            <div class="modal custom-modal fade request-modal" id="reschedule-booking{{$bookings->booking_id}}" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="form-header modal-header-title">
                                <h4 class="mb-0">Booking Details  {{$bookings->booking_id}}
                            		@if($bookings->status==1)
        							 	<span class="badge bg-success"><i class="feather-check-square me-1"></i> Confirmed</span>
        							@elseif($bookings->status==2)
        						      <span class="badge bg-success"><i class="feather-check-square me-1"></i>	Completed</span>
        							@elseif($bookings->status==3)
        								Declined												
        							@elseif($bookings->status==4)
        					           	<span class="badge bg-danger"><img src="{{URL::asset('/assets/img/icons/delete.svg')}}" alt="Icon" class="me-1">Cancelled</span>
        							@else
        							    Pending
        							@endif
        						</h4>
                            </div>
                            <a class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                            </a>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card dashboard-card court-information">
                                        <div class="card-header">
                                            <h4>{{__('string.item_information')}}</h4>
                                        </div>
                                        <div class="appointment-info">
                                            <ul class="appointmentset">
                                                <li>
                                                    <div class="appointment-item">
                                                        <div class="appointment-img">
                                                            <img src="{{url('/public/storage/'.$bookings->service->thumbnail)}}"
                                                                alt="Booking">
                                                        </div>
                                                        <div class="appointment-content">
                                                            <h6>{{$bookings->service->title}}</h6>
                                                            <p class="color-green">{{$bookings->service->category->title}}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.booked_on')}}</h6>
                                                    <p>{{date('D, d M Y',strtotime($bookings->created_at))}}</p>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.price_per_guest')}}</h6>
                                                    <p>{{$settings->currency}}{{number_format($bookings->service_amount,2)}}/{{__('string.hrs')}}</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card dashboard-card court-information">
                                        <div class="card-header">
                                            <h4>{{__('string.appointment_information')}}</h4>
                                        </div>
                                        <div class="appointment-info appoin-border">
                                            <ul class="appointmentset">
                                                <li>
                                                    <h6> {{__('string.scheduled_date_time')}}</h6>
                                                    <p>{{date('D, d M Y',strtotime($bookings->date))}}</p>
                                                    <p>{{date('h:i A', strtotime($bookings->time));}} </p>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.total_no_hours')}}</h6>
                                                    <p>
                                                        @if($bookings['booking_hours']==16)
                                                            {{__('string.whole_day')}}</li>
                                                        @else
                                                            {{$bookings['booking_hours']}}
                                                        @endif
                                                    </p>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.no_of_guests')}}</h6>
                                                    <p>{{$bookings->quantity}}</p>
                                                </li>
                                                <li>
                                                    <h6>{{__('string.total_amount_paid')}}</h6>
                                                    <p class="color-green">{{$settings->currency}}{{$bookings->payable_amount}}</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                
                                    <div class="card dashboard-card court-information">
                                        <div class="card-header">
                                            <h4>{{__('string.reschedule')}}</h4>
                                        </div>
                                        <div class="appointment-info appoin-border ">
                                            <ul class="appointmentsetview">
                                                <li>
                                                     <div class="mb-3">
                                                        <label for="date" class="form-label">Select New Date</label>
                                                        <div class="form-icon">
                                                            <input type="text" class="form-control datetimepicker" data-maxQauntity="{{$bookings->quantity}}"  data-seviceType="{{$bookings->service->service_type}}"  data-rescheduleslotslist="{{$bookings->booking_id}}"  data-reschedulbookinghours="{{$bookings->booking_hours}}" placeholder="Select Date" id="date">
                                                        </div>
                                                    </div>
                                                </li>
                                                <div class="mb-3" style="width: -webkit-fill-available;">
                                                    <label for="end-time" class="form-label d-flex align-items-center col-sm-4 justifyContent">Select Time<span class="ml-1" id="bookedColor"></span><span class="text-black">Not Available</span><span class="ml-1" id="availableColor"></span><span class="text-black">Available</span></label>
                                                    <div class="token-slot mt-2" id="{{$bookings->booking_id}}">No Time Available</div>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="table-accept-btn">
                                <a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</a>
                                <a href="javascript:;" class="btn btn-primary rescheduleBooking"   data-bookingid="{{$bookings->booking_id}}"  >{{__('string.reschedule')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endif

@if (Route::is(['event-enquiry-list']))
    @if(!empty($event_inquiries))    
        @foreach($event_inquiries as $bookings)
            <div class="modal custom-modal fade event-details" id="event-details{{$bookings->id}}" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="form-header modal-header-title">
                                <h4 class="mb-0">Event Enquiry Details</h4>
                            </div>
                            <a class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                            </a>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-space">
                                        <textarea class="form-control" name="comment" id="comment" rows="3" placeholder="Enter Your Review" required>{{$bookings->message}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="table-accept-btn"> </div>
                        </div>
                        <div class="modal-footer">
                            <div class="table-accept-btn">
                                <a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endif  

@if (Route::is(['user-cancelled']))
    <div class="modal custom-modal fade request-modal" id="cancel-court" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Court Booking Details2<span class="badge bg-danger ms-2">Cancelled</span>
                        </h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Court Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/booking/booking-03.jpg') }}"
                                                        alt="Appointment">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Wing Sports Academy</h6>
                                                    <p class="color-green">Court 1</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>{{__('string.Reservá')}}</h6>
                                            <p>$150 Upto 2 guests</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.price_per_guest')}}</h6>{{__('string.price_per_guest')}}
                                            <p>$15</p>
                                        </li>
                                        <li>
                                            <h6>Maximum Number of Guests</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.date_time')}}</h6>
                                            <p>Mon, Jul 14<span class="d-block">05:00 PM - 08:00 PM</span></p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.total_no_hours')}}</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Court Booking Amount</h6>
                                            <p>$150</p>
                                        </li>
                                        <li>
                                            <h6>Additional Guests</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Amount Additional Guests</h6>
                                            <p>$30</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentsetview">
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="color-green">$180</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Wallet</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Reason for Cancellation</h4>
                                </div>
                                <div class="user-review-details">
                                    <div class="user-review-content">
                                        <h6 class="text-danger">Cancelled By Coach</h6>
                                        <p>If you are looking for a perfect place for friendly matches with your friends
                                            or a competitive match, It is the best place.</p>
                                        <h5>Sent on 11/03/2023</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn table-btn-split">
                        <a href="javascript:;" class="btn initiate-table-btn">Initiate Refund</a>
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade request-modal" id="cancel-coach" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Coach Booking Details<span class="badge bg-danger ms-2">Cancelled</span>
                        </h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Court Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/featured/featured-06.jpg') }}"
                                                        alt="Venue">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Angela Roudrigez</h6>
                                                    <div class="table-rating">
                                                        <div class="rating-point">
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <span>30 Reviews</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Location</h6>
                                            <p>Santa Monica, CA</p>
                                        </li>
                                        <li>
                                            <h6>Price Per Hour</h6>
                                            <p>$200.00 / hr</p>
                                        </li>
                                        <li>
                                            <h6>Rank</h6>
                                            <p>Expert</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>Booking Type</h6>
                                            <p>Onetime</p>
                                        </li>
                                        <li>
                                            <h6>Date & Time</h6>
                                            <p>Mon, Jul 14
                                                <span>05:00 PM - 08:00 PM</span>
                                            </p>

                                        </li>
                                        <li>
                                            <h6>{{__('string.total_no_hours')}}</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Coaching Booking Amount</h6>
                                            <p>$200</p>
                                        </li>
                                        <li>
                                            <h6>Number of Hours</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="color-green">$180</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Wallet</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Reason for Cancellation</h4>
                                </div>
                                <div class="user-review-details">
                                    <div class="user-review-content">
                                        <h6 class="text-danger">Cancelled By Coach</h6>
                                        <p>If you are looking for a perfect place for friendly matches with your friends
                                            or a competitive match, It is the best place.</p>
                                        <h5>Sent on 11/03/2023</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn table-btn-split">
                        <a href="javascript:;" class="btn initiate-table-btn">Initiate Refund</a>
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade cancel-modal" id="cancel-court-modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Court Reject Reason</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="card dashboard-card court-information">
                        <div class="card-header">
                            <h4>Court Information</h4>
                        </div>
                        <div class="appointment-info">
                            <ul class="appointmentset appointmentset-cancel">
                                <li>
                                    <div class="appointment-item">
                                        <div class="appointment-img">
                                            <img src="{{ URL::asset('/assets/img/booking/booking-03.jpg') }}"
                                                alt="Appointment">
                                        </div>
                                        <div class="appointment-content">
                                            <h6>Wing Sports Academy</h6>
                                            <p class="color-green">Court 1</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <h6>{{__('string.price_per_guest')}}</h6>
                                    <p>$15</p>
                                </li>
                                <li>
                                    <h6>Maximum Number of Guests</h6>
                                    <p>2</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card dashboard-card court-information">
                        <div class="card-header">
                            <h4>Court Information</h4>
                        </div>
                        <div class="appointment-info">
                            <ul class="appointmentset appointmentset-cancel">
                                <li>
                                    <div class="appointment-item">
                                        <div class="appointment-img">
                                            <img src="{{ URL::asset('/assets/img/featured/featured-06.jpg') }}"
                                                alt="Venue">
                                        </div>
                                        <div class="appointment-content">
                                            <h6>Angela Roudrigez</h6>
                                            <span>Since 05/05/2023</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <h6>Previosly Booked</h6>
                                    <p>22 Jan 2023</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card dashboard-card court-information">
                        <div class="card-header">
                            <h4>{{__('string.appointment_information')}}</h4>
                        </div>
                        <div class="appointment-info appoin-border">
                            <ul class="appointmentset">
                                <li>
                                    <h6>{{__('string.booked_on')}}</h6>
                                    <p>Mon, Jul 14</p>
                                </li>
                                <li>
                                    <h6>Booking Type</h6>
                                    <p>Single Lesson</p>
                                    <p>3 Days * 1 hour @ <span class="text-primary">$150.00</span></p>
                                </li>
                                <li>
                                    <h6>{{__('string.total_no_hours')}}</h6>
                                    <p>2</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <form>
                        <div class="info-about">
                            <label class="form-label">Reason</label>
                            <textarea class="form-control" rows="3" placeholder="Enter Reject Reson"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn btn-primary">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade cancel-modal" id="cancel-coach-modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Coaching Reject Reason</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="card dashboard-card court-information">
                        <div class="card-header">
                            <h4>Court Information</h4>
                        </div>
                        <div class="appointment-info">
                            <ul class="appointmentset appointmentset-cancel">
                                <li>
                                    <div class="appointment-item">
                                        <div class="appointment-img">
                                            <img src="{{ URL::asset('/assets/img/booking/booking-03.jpg') }}"
                                                alt="Appointment">
                                        </div>
                                        <div class="appointment-content">
                                            <h6>Wing Sports Academy</h6>
                                            <p class="color-green">Court 1</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <h6>{{__('string.price_per_guest')}}</h6>
                                    <p>$15</p>
                                </li>
                                <li>
                                    <h6>Maximum Number of Guests</h6>
                                    <p>2</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card dashboard-card court-information">
                        <div class="card-header">
                            <h4>Player Information</h4>
                        </div>
                        <div class="appointment-info">
                            <ul class="appointmentset appointmentset-cancel">
                                <li>
                                    <div class="appointment-item">
                                        <div class="appointment-img">
                                            <img src="{{ URL::asset('/assets/img/featured/featured-06.jpg') }}"
                                                alt="Venue">
                                        </div>
                                        <div class="appointment-content">
                                            <h6>Martina</h6>
                                            <span>Since 05/05/2023</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <h6>Previosly Booked</h6>
                                    <p>22 Jan 2023</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card dashboard-card court-information">
                        <div class="card-header">
                            <h4>{{__('string.appointment_information')}}</h4>
                        </div>
                        <div class="appointment-info appoin-border">
                            <ul class="appointmentset">
                                <li>
                                    <h6>{{__('string.booked_on')}}</h6>
                                    <p>Mon, Jul 14</p>
                                </li>
                                <li>
                                    <h6>Booking Type</h6>
                                    <p>Onetime</p>
                                </li>
                                <li>
                                    <h6>Number Of Hours</h6>
                                    <p>2</p>
                                </li>
                                <li>
                                    <h6>Booking Type</h6>
                                    <p>Date & Time</p>
                                    <p>05:00 PM - 08:00 PM</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <form>
                        <div class="info-about">
                            <label class="form-label">Reason</label>
                            <textarea class="form-control" placeholder="Enter Reject Reson"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn btn-primary">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['user-complete']))
    <div class="modal custom-modal fade request-modal" id="complete-court" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Court Booking Details<span class="badge bg-success ms-2">Completed</span>
                        </h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Court Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('assets/img/booking/booking-03.jpg') }}"
                                                        alt="Appointment">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Wing Sports Academy</h6>
                                                    <p class="color-green">Court 1</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>$150 Upto 2 guests</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.price_per_guest')}}</h6>
                                            <p>$15</p>
                                        </li>
                                        <li>
                                            <h6>Maximum Number of Guests</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>Date & Time</h6>
                                            <p>Mon, Jul 14<span class="d-block">05:00 PM - 08:00 PM</span></p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.total_no_hours')}}</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Court Booking Amount</h6>
                                            <p>$150</p>
                                        </li>
                                        <li>
                                            <h6>Additional Guests</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Amount Additional Guests</h6>
                                            <p>$30</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentsetview">
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="color-green">$180</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Wallet</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Review Details</h4>
                                </div>
                                <div class="user-review-details">
                                    <div class="user-review-img">
                                        <img src="{{ URL::asset('assets/img/profiles/avatar-01.jpg') }}"
                                            alt="User">
                                    </div>
                                    <div class="user-review-content">
                                        <div class="table-rating">
                                            <div class="rating-point">
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <span>5.0</span>
                                            </div>
                                        </div>
                                        <span><i class="fa fa-check me-2" aria-hidden="true"></i>Yes, I would book
                                            again.</span>
                                        <h6>Absolutely perfect</h6>
                                        <p>If you are looking for a perfect place for friendly matches with your friends
                                            or a competitive match, It is the best place.</p>
                                        <h5>Sent on 11/03/2023</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade request-modal" id="complete-coach" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Coach Booking Details<span class="badge bg-success ms-2">Complete</span>
                        </h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Court Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('assets/img/featured/featured-06.jpg') }}"
                                                        alt="Venue">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Angela Roudrigez</h6>
                                                    <div class="table-rating">
                                                        <div class="rating-point">
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <span>30 Reviews</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Location</h6>
                                            <p>Santa Monica, CA</p>
                                        </li>
                                        <li>
                                            <h6>Price Per Hour</h6>
                                            <p>$200.00 / hr</p>
                                        </li>
                                        <li>
                                            <h6>Rank</h6>
                                            <p>Expert</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>Booking Type</h6>
                                            <p>Onetime</p>
                                        </li>
                                        <li>
                                            <h6>Date & Time</h6>
                                            <p>Mon, Jul 14
                                                <span>05:00 PM - 08:00 PM</span>
                                            </p>

                                        </li>
                                        <li>
                                            <h6>{{__('string.total_no_hours')}}</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Booking Days</h4>
                                </div>
                                <div class="booking-days">
                                    <ul>
                                        <li class="active">
                                            <img src="{{ URL::asset('assets/img/icons/reset.svg') }}"
                                                class="me-2" alt="Icon">
                                            <i class="feather-check-circle me-2"></i>
                                            14 May 2023 - 7:00 PM
                                            <i class="fa fa-check-circle ms-2"></i>
                                        </li>
                                        <li class="active">
                                            <img src="{{ URL::asset('assets/img/icons/reset.svg') }}"
                                                class="me-2" alt="Icon">
                                            <i class="feather-check-circle me-2"></i>
                                            15 May 2023 - 7:00 PM
                                            <i class="fa fa-check-circle ms-2"></i>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Coaching Booking Amount</h6>
                                            <p>$200</p>
                                        </li>
                                        <li>
                                            <h6>Number of Hours</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="color-green">$180</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Wallet</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Review Details</h4>
                                </div>
                                <div class="user-review-details">
                                    <div class="user-review-img">
                                        <img src="{{ URL::asset('assets/img/profiles/avatar-01.jpg') }}"
                                            alt="User">
                                    </div>
                                    <div class="user-review-content">
                                        <div class="table-rating">
                                            <div class="rating-point">
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <span>5.0</span>
                                            </div>
                                        </div>
                                        <span><i class="fa fa-check me-2" aria-hidden="true"></i>Yes, I would book
                                            again.</span>
                                        <h6>Absolutely perfect</h6>
                                        <p>If you are looking for a perfect place for friendly matches with your friends
                                            or a competitive match, It is the best place.</p>
                                        <h5>Sent on 11/03/2023</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade request-modal" id="profile-coach" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Coach Profile</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="profile-set">
                                    <div class="profile-set-image">
                                        <img src="{{ URL::asset('assets/img/featured/featured-05.jpg') }}"
                                            alt="Venue">
                                    </div>
                                    <div class="profile-set-content">
                                        <h3>Kevin Anderson</h3>
                                        <div class="rating-city">
                                            <div class="profile-set-rating">
                                                <span>4.5</span>
                                                <h6>300 Reviews</h6>
                                            </div>
                                            <div class="profile-set-img">
                                                <img src="{{ URL::asset('assets/img/flag/usa.png') }}"
                                                    alt="User">
                                                <h6>Santamanica, United states</h6>
                                            </div>
                                        </div>
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                            industry.industry's</p>
                                        <ul>
                                            <li>
                                                <img src="{{ URL::asset('assets/img/icons/rank.svg') }}"
                                                    alt="Icon">
                                                <h6>Rank : Expert</h6>
                                            </li>
                                            <li>
                                                <img src="{{ URL::asset('assets/img/icons/process.svg') }}"
                                                    alt="Icon">
                                                <h6>Sessions Completed : 25</h6>
                                            </li>
                                            <li>
                                                <img src="{{ URL::asset('assets/img/icons/calendar-alt.svg') }}"
                                                    alt="Icon">
                                                <h6>With Dreamsport since<span> Apr 5, 2023</span></h6>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="price-set-price">
                                        <h6>Start’s From</h6>
                                        <h5>$250<span>/{{__('string.hrs')}}</span></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-tab">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab"
                                            data-bs-target="#profile" type="button" role="tab"
                                            aria-selected="true">Profile Info</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="appointment-tab" data-bs-toggle="tab"
                                            data-bs-target="#appointment" type="button" role="tab"
                                            aria-selected="false">Appointment Details</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab"
                                            data-bs-target="#reviews" type="button" role="tab"
                                            aria-selected="false">Reviews</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="previous-tab" data-bs-toggle="tab"
                                            data-bs-target="#previous" type="button" role="tab"
                                            aria-selected="false">Previous Booking</button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="profile" role="tabpanel"
                                        aria-labelledby="profile-tab">
                                        <div class="profile-card mb-0">
                                            <div class="profile-card-title">
                                                <h4>Contact Information</h4>
                                            </div>
                                            <div class="profile-contact-details">
                                                <ul>
                                                    <li>
                                                        <span>Email Address</span>
                                                        <h6>contact@example.com</h6>
                                                    </li>
                                                    <li>
                                                        <span>Phone Number</span>
                                                        <h6>+1 56565 556558</h6>
                                                    </li>
                                                    <li>
                                                        <span> Address</span>
                                                        <h6>1653 Davisson Street,Indianapolis, IN 46225</h6>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="profile-card-title">
                                                <h4>Short Bio</h4>
                                            </div>
                                            <div class="profile-card-content">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Varius
                                                    consectetur a at est diam ultricies. Egestas eros leo dapibus tellus
                                                    neque turpis. Nec in morbi adipiscing pretium accumsan urna ac,Lorem
                                                    ipsum dolor sit amet, consectetur adipiscing elit. Varius
                                                    consectetur a at est diam ultricies. Egestas eros leo dapibus tellus
                                                    neque turpis. Nec in morbi adipiscing pretium accumsan urna ac,Lorem
                                                    ipsum dolor sit amet, consectetur adipiscing elit. Varius
                                                    consectetur a at est diam ultricies. Egestas eros leo dapibus tellus
                                                    neque turpis. Nec in morbi adipiscing pretium accumsan urna ac,</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="appointment" role="tabpanel" aria-labelledby="appointment-tab">
                                        <div class="accordion">
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('assets/img/icons/short-bio.svg') }}"
                                                                alt="Icon"></span> Short Bio
                                                    </button>
                                                </h4>
                                                <div id="panelsStayOpen-collapseOne" class="accordion-collapse">
                                                    <div class="accordion-body">
                                                        <div class="text show-more-height">
                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                                It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem Ipsum</p>

                                                            <ul>
                                                                <li>4 years of high school (3 years varsity)</li>
                                                                <li>3 years of college club badminton at Loyola
                                                                    Marymount</li>
                                                                <li>I grew up at North Venice Little League and
                                                                    represented</li>
                                                                <li>Southern California in 2017 for Senior State Champs.
                                                                </li>
                                                                <li>3 years on Varsity at Venice High School. Venice
                                                                    Varsity</li>
                                                                <li>badminton Western League Champs 2017.</li>
                                                                <li>2 years of Loyola Marymount University Club
                                                                    badminton.</li>
                                                            </ul>

                                                            <p>It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem Ipsum</p>

                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                                It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem Ipsum</p>

                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                            </p>

                                                            <p>It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem Ipsum</p>
                                                        </div>
                                                        <div class="show-more d align-items-center primary-text"><i
                                                                class="feather-plus-circle"></i>Show More</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('assets/img/icons/lesson-with-me.svg') }}"
                                                                alt="Icon"></span> Lesson With me
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse">
                                                    <div class="accordion-body">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and
                                                            typesetting industry. Lorem Ipsum has been the industry's
                                                            standard dummy text ever since the 1500s, when an unknown
                                                            printer took a galley of type and scrambled it to make a
                                                            type specimen book. It has survived not only five centuries,
                                                            but also the leap into electronic typesetting, remaining
                                                            essentially unchanged. It was popularised in the 1960s with
                                                            the release of Letraset sheets containing Lorem Ipsum
                                                            passages, and more recently with desktop publishing software
                                                            like Aldus PageMaker including versions of Lorem Ipsum</p>
                                                        <ul>
                                                            <li><i class="feather-check-square"></i>Single Lesson</li>
                                                            <li><i class="feather-check-square"></i>2 Player Lesson
                                                            </li>
                                                            <li><i class="feather-check-square"></i>Small group Lesson
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('assets/img/icons/coaching.svg') }}"
                                                                alt="Icon"></span> Coaching
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse">
                                                    <div class="accordion-body">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and
                                                            typesetting industry. Lorem Ipsum has been the industry's
                                                            standard dummy text ever since the 1500s, when an unknown
                                                            printer took a galley of type and scrambled it to make a
                                                            type specimen book. It has survived not only five centuries,
                                                            but also the leap into electronic typesetting, remaining
                                                            essentially unchanged. It was popularised in the 1960s with
                                                            the release of Letraset sheets containing Lorem Ipsum
                                                            passages, and more recently with desktop publishing software
                                                            like Aldus PageMaker including versions of Lorem Ipsum</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('assets/img/icons/gallery.svg') }}"
                                                                alt="Icon"></span> Gallery
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse">
                                                    <div class="accordion-body">
                                                        <div class="owl-carousel gallery-slider owl-theme">
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('assets/img/gallery/gallery4/gallery-15.jpg') }}">
                                                            </div>
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('assets/img/gallery/gallery4/gallery-16.jpg') }}">
                                                            </div>
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('assets/img/gallery/gallery4/gallery-17.jpg') }}">
                                                            </div>
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('assets/img/gallery/gallery4/gallery-16.jpg') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('assets/img/icons/location.svg') }}"
                                                                alt="Icon"></span> Location
                                                    </button>
                                                </h4>
                                                <div id="panelsStayOpen-collapseSeven" class="accordion-collapse">
                                                    <div class="accordion-body">
                                                        <div class="google-maps">
                                                            <iframe
                                                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2967.8862835683544!2d-73.98256668525309!3d41.93829486962529!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89dd0ee3286615b7%3A0x42bfa96cc2ce4381!2s132%20Kingston%20St%2C%20Kingston%2C%20NY%2012401%2C%20USA!5e0!3m2!1sen!2sin!4v1670922579281!5m2!1sen!2sin"
                                                                height="170" style="border:0;"
                                                                allowfullscreen="" loading="lazy"
                                                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                                                        </div>
                                                        <div
                                                            class="dull-bg d-flex justify-content-start align-items-center mb-3">
                                                            <div class="white-bg me-2">
                                                                <i class="fas fa-location-arrow"></i>
                                                            </div>
                                                            <div class="">
                                                                <h6>Our Venue Location</h6>
                                                                <p>70 Bright St New York, USA</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="reviews" role="tabpanel"
                                        aria-labelledby="reviews-tab">
                                        <div class="review-box review-box-user d-flex">
                                            <div class="review-profile">
                                                <img src="{{ URL::asset('assets/img/profiles/avatar-01.jpg') }}"
                                                    class="img-fluid" alt="User">
                                            </div>
                                            <div class="review-info">
                                                <h6 class="mb-2 tittle">Amanda Booked on 06/04/2023</h6>
                                                <div class="rating">
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <span class="">5.0</span>
                                                </div>
                                                <span class="success-text"><i class="feather-check"></i>Yes, I would
                                                    book again.</span>
                                                <h6>Absolutely perfect</h6>
                                                <p>If you are looking for a perfect place for friendly matches with your
                                                    friends or a competitive match, It is the best place.</p>
                                                <ul class="review-gallery">
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-01.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('assets/img/gallery/gallery-01.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-02.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('assets/img/gallery/gallery-02.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-03.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('assets/img/gallery/gallery-03.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-04.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('assets/img/gallery/gallery-04.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-05.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('assets/img/gallery/gallery-05.jpg') }}">
                                                        </a>
                                                    </li>
                                                </ul>
                                                <span class="post-date">Sent on 11/03/2023</span>
                                            </div>
                                        </div>
                                        <div class="review-box review-box-user d-flex">
                                            <div class="review-profile">
                                                <img src="{{ URL::asset('assets/img/profiles/avatar-01.jpg') }}"
                                                    class="img-fluid" alt="User">
                                            </div>
                                            <div class="review-info">
                                                <h6 class="mb-2 tittle">Amanda Booked on 06/04/2023</h6>
                                                <div class="rating">
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <span class="">5.0</span>
                                                </div>
                                                <h6>Awesome. Its very convenient to play.</h6>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                    industry. Lorem Ipsum has been the industry's standard dummy text
                                                    ever since the 1500s, when an unknown printer took a galley of type
                                                    and scrambled it to make a type specimen book. It has survived not
                                                    only five centuries, but also the leap into electronic!!</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="previous" role="tabpanel"
                                        aria-labelledby="previous-tab">
                                        <div class="preview-tab">
                                            <ul>
                                                <li>
                                                    <div class="preview-tabcontent">
                                                        <div class="preview-tabimg">
                                                            <img src="{{ URL::asset('assets/img/services/service-01.jpg') }}"
                                                                alt="Service">
                                                        </div>
                                                        <div class="preview-tabname">
                                                            <h4>Leap Sports Academy</h4>
                                                            <h5>Court 1</h5>
                                                            <ul>
                                                                <li><span>Guests : 4</span></li>
                                                                <li><span>2 Hrs</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <h6>Date & Time</h6>
                                                    <span class="d-block">Mon, Jul 11</span>
                                                    <span>06:00 PM - 08:00 PM</span>
                                                </li>
                                                <li>
                                                    <h6>$400</h6>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="preview-tab">
                                            <ul>
                                                <li>
                                                    <div class="preview-tabcontent">
                                                        <div class="preview-tabimg">
                                                            <img src="{{ URL::asset('assets/img/services/service-02.jpg') }}"
                                                                alt="Service">
                                                        </div>
                                                        <div class="preview-tabname">
                                                            <h4>Marsh Academy</h4>
                                                            <h5>Court 1</h5>
                                                            <ul>
                                                                <li><span>Guests : 4</span></li>
                                                                <li><span>2 Hrs</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <h6>Date & Time</h6>
                                                    <span class="d-block">Mon, Jul 11</span>
                                                    <span>06:00 PM - 08:00 PM</span>
                                                </li>
                                                <li>
                                                    <h6>$300</h6>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade request-modal" id="profile-court" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Coach Profile</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="master-academy dull-whitesmoke-bg card master-academyview">
                                <div class="row d-flex align-items-center justify-content-center">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                                        <div class="d-sm-flex justify-content-start align-items-center">
                                            <a href="javascript:void(0);"><img class="corner-radius-10"
                                                    src="{{ URL::asset('assets/img/master-academy.png') }}"
                                                    alt="Academy"></a>
                                            <div class="info">
                                                <h3 class="mb-2">Manchester Academy</h3>
                                                <div class="profile-set-content w-100 p-0">
                                                    <ul class="bg-transparent p-0">
                                                        <li>
                                                            <img src="{{ URL::asset('assets/img/icons/location.svg') }}"
                                                                alt="Icon">
                                                            <h6> 70 Bright St New York, USA</h6>
                                                        </li>
                                                        <li>
                                                            <img src="{{ URL::asset('assets/img/icons/call.svg') }}"
                                                                alt="Icon">
                                                            <h6>+3 80992 31212</h6>
                                                        </li>
                                                        <li>
                                                            <img src="{{ URL::asset('assets/img/icons/mail.svg') }}"
                                                                alt="Icon">
                                                            <h6> yourmail@example.com</h6>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="coach-profile-set">
                                                    <ul class="bg-transparent p-0">
                                                        <li>
                                                            <div class="coach-profile-set-img">
                                                                <img src="{{ URL::asset('assets/img/icons/indoor.svg') }}"
                                                                    alt="Icon">
                                                            </div>
                                                            <div class="coach-profile-set-contemt">
                                                                <h5>Venue </h5>
                                                                <span> Indoor</span>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="coach-profile-set-img ">
                                                                <img src="{{ URL::asset('assets/img/profiles/avatar-01.jpg') }}"
                                                                    alt="Icon">
                                                            </div>
                                                            <div class="coach-profile-set-contemt">
                                                                <h5>Venue </h5>
                                                                <span> Indoor</span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                                        <ul class="d-sm-flex align-items-center justify-content-evenly">
                                            <li>
                                                <h3 class="d-inline-block">$150</h3><span>/{{__('string.hrs')}}</span>
                                                <p>up to 1 guests</p>
                                            </li>
                                            <li>
                                                <span><i class="feather-plus"></i></span>
                                            </li>
                                            <li class="text-center">
                                                <h3 class="d-inline-block">$5</h3><span>/{{__('string.hrs')}}</span>
                                                <p>each additional guest <br>up to 4 guests max</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-tab">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="profile-tab1" data-bs-toggle="tab"
                                            data-bs-target="#profile1" type="button" role="tab"
                                            aria-selected="true">Profile Info</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="appointment-tab1" data-bs-toggle="tab"
                                            data-bs-target="#appointment1" type="button" role="tab"
                                            aria-selected="false">Appointment Details</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="reviews-tab1" data-bs-toggle="tab"
                                            data-bs-target="#reviews1" type="button" role="tab"
                                            aria-selected="false">Reviews</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="previous-tab1" data-bs-toggle="tab"
                                            data-bs-target="#previous1" type="button" role="tab"
                                            aria-selected="false">Previous Booking</button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="profile1" role="tabpanel"
                                        aria-labelledby="profile-tab1">
                                        <div class="profile-card mb-0">
                                            <div class="profile-card-title">
                                                <h4>Contact Information</h4>
                                            </div>
                                            <div class="profile-contact-details mb-0">
                                                <ul>
                                                    <li>
                                                        <span>Email Address</span>
                                                        <h6>contact@example.com</h6>
                                                    </li>
                                                    <li>
                                                        <span>Phone Number</span>
                                                        <h6>+1 56565 556558</h6>
                                                    </li>
                                                    <li>
                                                        <span> Address</span>
                                                        <h6>1653 Davisson Street,Indianapolis, IN 46225</h6>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="appointment1" role="tabpanel" aria-labelledby="appointment-tab1">
                                        <div class="accordion">
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('assets/img/icons/Black.svg') }}"
                                                                alt="Icon"></span>Overview
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse">
                                                    <div class="accordion-body">
                                                        <div class="text">
                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                                It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem Ipsum</p>
                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                            </p>
                                                            <p>It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem IpsumLorem
                                                                Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('assets/img/icons/lesson-with-me.svg') }}"
                                                                alt="Icon"></span> Rules
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse">
                                                    <div class="accordion-body">
                                                        <p><i class="feather-alert-octagon text-danger me-2"></i>Non
                                                            Marking Shoes are recommended not mandatory for Badminton.
                                                        </p>
                                                        <p><i class="feather-alert-octagon text-danger me-2"></i>A
                                                            maximum number of members per booking per badminton court is
                                                            admissible fixed by Venue Vendors</p>
                                                        <p><i class="feather-alert-octagon text-danger me-2"></i>No
                                                            pets, no seeds, no gum, no glass, no hitting or swinging
                                                            outside of the cage</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('assets/img/icons/lesson-with-me.svg') }}"
                                                                alt="Icon"></span> Amenities
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse">
                                                    <div class="accordion-body">
                                                        <ul class="amenities-set">
                                                            <li>
                                                                <span><i class="fa fa-check-circle text-success me-2"
                                                                        aria-hidden="true"></i>Parking</span>
                                                            </li>
                                                            <li>
                                                                <span><i class="fa fa-check-circle text-success me-2"
                                                                        aria-hidden="true"></i>Drinking Water</span>
                                                            </li>
                                                            <li>
                                                                <span><i class="fa fa-check-circle text-success me-2"
                                                                        aria-hidden="true"></i>First Aid</span>
                                                            </li>
                                                            <li>
                                                                <span><i class="fa fa-check-circle text-success me-2"
                                                                        aria-hidden="true"></i>Change Room</span>
                                                            </li>
                                                            <li>
                                                                <span><i class="fa fa-check-circle text-success me-2"
                                                                        aria-hidden="true"></i>Shower</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('assets/img/icons/includes1.svg') }}"
                                                                alt="Icon"></span> Includes
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse">
                                                    <div class="accordion-body includes-set">
                                                        <ul>
                                                            <li><i class="feather-check-square"></i>Badminton Racket
                                                                Unlimited</li>
                                                            <li><i class="feather-check-square"></i>Bats</li>
                                                            <li><i class="feather-check-square"></i>Hitting Machines
                                                            </li>
                                                            <li><i class="feather-check-square"></i>Multiple Courts
                                                            </li>
                                                        </ul>
                                                        <ul>
                                                            <li><i class="feather-check-square"></i>Spare Players</li>
                                                            <li><i class="feather-check-square"></i>Instant Racket
                                                            </li>
                                                            <li><i class="feather-check-square"></i>Hitting Machines
                                                            </li>
                                                            <li><i class="feather-check-square"></i>Green Turfs</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('assets/img/icons/gallery.svg') }}"
                                                                alt="Icon"></span> Gallery
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse">
                                                    <div class="accordion-body">
                                                        <div class="owl-carousel gallery-slider owl-theme">
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('assets/img/gallery/gallery4/gallery-18.jpg') }}">
                                                            </div>
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('assets/img/gallery/gallery4/gallery-19.jpg') }}">
                                                            </div>
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('assets/img/gallery/gallery4/gallery-20.jpg') }}">
                                                            </div>
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('assets/img/gallery/gallery4/gallery-19.jpg') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="reviews1" role="tabpanel"
                                        aria-labelledby="reviews-tab1">
                                        <div class="review-box review-box-user d-flex">
                                            <div class="review-profile">
                                                <img src="{{ URL::asset('assets/img/profiles/avatar-01.jpg') }}"
                                                    class="img-fluid" alt="User">
                                            </div>
                                            <div class="review-info">
                                                <h6 class="mb-2 tittle">Amanda Booked on 06/04/2023</h6>
                                                <div class="rating">
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <span class="">5.0</span>
                                                </div>
                                                <span class="success-text"><i class="feather-check"></i>Yes, I would
                                                    book again.</span>
                                                <h6>Absolutely perfect</h6>
                                                <p>If you are looking for a perfect place for friendly matches with your
                                                    friends or a competitive match, It is the best place.</p>
                                                <ul class="review-gallery">
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-01.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('assets/img/gallery/gallery-01.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-02.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('assets/img/gallery/gallery-02.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-03.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('assets/img/gallery/gallery-03.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-04.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('assets/img/gallery/gallery-04.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-05.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('assets/img/gallery/gallery-05.jpg') }}">
                                                        </a>
                                                    </li>
                                                </ul>
                                                <span class="post-date">Sent on 11/03/2023</span>
                                            </div>
                                        </div>
                                        <div class="review-box review-box-user d-flex">
                                            <div class="review-profile">
                                                <img src="{{ URL::asset('assets/img/profiles/avatar-01.jpg') }}"
                                                    class="img-fluid" alt="User">
                                            </div>
                                            <div class="review-info">
                                                <h6 class="mb-2 tittle">Amanda Booked on 06/04/2023</h6>
                                                <div class="rating">
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <span class="">5.0</span>
                                                </div>
                                                <h6>Awesome. Its very convenient to play.</h6>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                    industry. Lorem Ipsum has been the industry's standard dummy text
                                                    ever since the 1500s, when an unknown printer took a galley of type
                                                    and scrambled it to make a type specimen book. It has survived not
                                                    only five centuries, but also the leap into electronic!!</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="previous1" role="tabpanel"
                                        aria-labelledby="previous-tab1">
                                        <div class="preview-tab">
                                            <ul>
                                                <li>
                                                    <div class="preview-tabcontent">
                                                        <div class="preview-tabimg">
                                                            <img src="{{ URL::asset('assets/img/featured/featured-05.jpg') }}"
                                                                alt="Venue">
                                                        </div>
                                                        <div class="preview-tabname">
                                                            <h4>Kevin Anderson</h4>
                                                            <h5>Onetime</h5>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <h6>Date & Time</h6>
                                                    <span class="d-block">Mon, Jul 11</span>
                                                    <span>06:00 PM - 08:00 PM</span>
                                                </li>
                                                <li>
                                                    <h6>$400</h6>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="preview-tab">
                                            <ul>
                                                <li>
                                                    <div class="preview-tabcontent">
                                                        <div class="preview-tabimg">
                                                            <img src="{{ URL::asset('assets/img/featured/featured-06.jpg') }}"
                                                                alt="Venue">
                                                        </div>
                                                        <div class="preview-tabname">
                                                            <h4>Evon Raddick</h4>
                                                            <h5>Onetime</h5>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <h6>Date & Time</h6>
                                                    <span class="d-block">Mon, Jul 11</span>
                                                    <span>06:00 PM - 08:00 PM</span>
                                                </li>
                                                <li>
                                                    <h6>$300</h6>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['user-dashboard']))
    <div class="modal custom-modal fade request-modal" id="upcoming-coach" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Coach Booking Details<span class="badge bg-info ms-2">Upcoming</span>
                        </h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Court Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('assets/img/featured/featured-06.jpg') }}"
                                                        alt="Venue">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Angela Roudrigez</h6>
                                                    <div class="table-rating">
                                                        <div class="rating-point">
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <span>30 Reviews</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Location</h6>
                                            <p>Santa Monica, CA</p>
                                        </li>
                                        <li>
                                            <h6>Price Per Hour</h6>
                                            <p>$200.00 / hr</p>
                                        </li>
                                        <li>
                                            <h6>Rank</h6>
                                            <p>Expert</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>Booking Type</h6>
                                            <p>Onetime</p>
                                        </li>
                                        <li>
                                            <h6>Date & Time</h6>
                                            <p>Mon, Jul 14
                                                <span>05:00 PM - 08:00 PM</span>
                                            </p>

                                        </li>
                                        <li>
                                            <h6>{{__('string.total_no_hours')}}</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Coaching Booking Amount</h6>
                                            <p>$200</p>
                                        </li>
                                        <li>
                                            <h6>Number of Hours</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="color-green">$180</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Wallet</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade request-modal" id="upcoming-court" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Court Booking Details<span class="badge bg-info ms-2">Upcoming</span>
                        </h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Court Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('assets/img/booking/booking-03.jpg') }}"
                                                        alt="Appointment">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Wing Sports Academy</h6>
                                                    <p class="color-green">Court 1</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>$150 Upto 2 guests</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.price_per_guest')}}</h6>
                                            <p>$15</p>
                                        </li>
                                        <li>
                                            <h6>Maximum Number of Guests</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>$150 Upto 2 guests</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.price_per_guest')}}</h6>
                                            <p>$15</p>
                                        </li>
                                        <li>
                                            <h6>Maximum Number of Guests</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Court Booking Amount</h6>
                                            <p>$150</p>
                                        </li>
                                        <li>
                                            <h6>Additional Guests</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Amount Additional Guests</h6>
                                            <p>$30</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentsetview">
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="color-green">$180</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Wallet</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade payment-modal" id="add-payment" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Add Payment to IBP Account</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="wallet-wrap wallet-modal">
                        <div class="wallet-amt">
                            <h5>Your IBP Account Balance</h5>
                            <h2>$4,544</h2>
                        </div>
                    </div>
                    <form>
                        <div class="input-space">
                            <label class="form-label">Amount</label>
                            <input type="text" class="form-control" placeholder="Enter Amount">
                        </div>
                        <div class="or-div">
                            <h6>OR</h6>
                        </div>
                        <div class="add-wallet-amount form-check">
                            <ul>
                                <li class="active">
                                    <div class="add-wallet-checkbox">
                                        <input type="checkbox" class="form-check-input" id="value" checked>
                                        <label for="value">Add Value 1</label>
                                    </div>
                                    <div class="add-wallet-price">
                                        <span>+ $80</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="add-wallet-checkbox">
                                        <input type="checkbox" class="form-check-input" id="value1">
                                        <label for="value1">Add Value 2</label>
                                    </div>
                                    <div class="add-wallet-price">
                                        <span>+ $60</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="add-wallet-checkbox">
                                        <input type="checkbox" class="form-check-input" id="value2">
                                        <label for="value2">Add Value 3</label>
                                    </div>
                                    <div class="add-wallet-price">
                                        <span>+ $120</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="add-wallet-checkbox">
                                        <input type="checkbox" class="form-check-input" id="value3">
                                        <label for="value3">Add Value 4</label>
                                    </div>
                                    <div class="add-wallet-price">
                                        <span>+ $120</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="radio-setview">
                            <h6>Select Payment Gateway</h6>
                            <div class="radio">
                                <div class="form-check form-check-inline mb-3">
                                    <input class="form-check-input default-check me-1" type="radio"
                                        name="inlineRadioOptions" id="inlineRadio3" value="Credit Card">
                                    <label class="form-check-label" for="inlineRadio3">Debit/Credit Card</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Reset</a>
                        <a href="javascript:;" class="btn btn-primary" data-bs-dismiss="modal"
                            aria-label="Close">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['user-ongoing']))
    <div class="modal custom-modal fade request-modal" id="complete-court" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Court Booking Details<span class="badge bg-warning ms-2">On Going</span>
                        </h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Court Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/booking/booking-03.jpg') }}"
                                                        alt="Appointment">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Wing Sports Academy</h6>
                                                    <p class="color-green">Court 1</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>$150 Upto 2 guests</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.price_per_guest')}}</h6>
                                            <p>$15</p>
                                        </li>
                                        <li>
                                            <h6>Maximum Number of Guests</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>Date & Time</h6>
                                            <p>Mon, Jul 14<span class="d-block">05:00 PM - 08:00 PM</span></p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.total_no_hours')}}</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Court Booking Amount</h6>
                                            <p>$150</p>
                                        </li>
                                        <li>
                                            <h6>Additional Guests</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Amount Additional Guests</h6>
                                            <p>$30</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentsetview">
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="color-green">$180</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Wallet</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Review Details</h4>
                                </div>
                                <div class="user-review-details">
                                    <div class="user-review-img">
                                        <img src="{{ URL::asset('/assets/img/profiles/avatar-01.jpg') }}"
                                            alt="User">
                                    </div>
                                    <div class="user-review-content">
                                        <div class="table-rating">
                                            <div class="rating-point">
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <span>5.0</span>
                                            </div>
                                        </div>
                                        <span><i class="fa fa-check me-2" aria-hidden="true"></i>Yes, I would book
                                            again.</span>
                                        <h6>Absolutely perfect</h6>
                                        <p>If you are looking for a perfect place for friendly matches with your friends
                                            or a competitive match, It is the best place.</p>
                                        <h5>Sent on 11/03/2023</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade request-modal" id="complete-coach" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Coach Booking Details<span class="badge bg-warning ms-2">On Going</span>
                        </h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Court Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/featured/featured-06.jpg') }}"
                                                        alt="Venue">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Angela Roudrigez</h6>
                                                    <div class="table-rating">
                                                        <div class="rating-point">
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <span>30 Reviews</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Location</h6>
                                            <p>Santa Monica, CA</p>
                                        </li>
                                        <li>
                                            <h6>Price Per Hour</h6>
                                            <p>$200.00 / hr</p>
                                        </li>
                                        <li>
                                            <h6>Rank</h6>
                                            <p>Expert</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>Booking Type</h6>
                                            <p>Onetime</p>
                                        </li>
                                        <li>
                                            <h6>Date & Time</h6>
                                            <p>Mon, Jul 14
                                                <span>05:00 PM - 08:00 PM</span>
                                            </p>

                                        </li>
                                        <li>
                                            <h6>{{__('string.total_no_hours')}}</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Booking Days</h4>
                                </div>
                                <div class="booking-days">
                                    <ul>
                                        <li class="active">
                                            <img src="{{ URL::asset('/assets/img/icons/reset.svg') }}"
                                                class="me-2" alt="Icon">
                                            <i class="feather-check-circle me-2"></i>
                                            14 May 2023 - 7:00 PM
                                            <i class="fa fa-check-circle ms-2"></i>
                                        </li>
                                        <li class="active">
                                            <img src="{{ URL::asset('/assets/img/icons/reset.svg') }}"
                                                class="me-2" alt="Icon">
                                            <i class="feather-check-circle me-2"></i>
                                            15 May 2023 - 7:00 PM
                                            <i class="fa fa-check-circle ms-2"></i>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Coaching Booking Amount</h6>
                                            <p>$200</p>
                                        </li>
                                        <li>
                                            <h6>Number of Hours</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="color-green">$180</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Wallet</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Review Details</h4>
                                </div>
                                <div class="user-review-details">
                                    <div class="user-review-img">
                                        <img src="{{ URL::asset('/assets/img/profiles/avatar-01.jpg') }}"
                                            alt="User">
                                    </div>
                                    <div class="user-review-content">
                                        <div class="table-rating">
                                            <div class="rating-point">
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <span>5.0</span>
                                            </div>
                                        </div>
                                        <span><i class="fa fa-check me-2" aria-hidden="true"></i>Yes, I would book
                                            again.</span>
                                        <h6>Absolutely perfect</h6>
                                        <p>If you are looking for a perfect place for friendly matches with your friends
                                            or a competitive match, It is the best place.</p>
                                        <h5>Sent on 11/03/2023</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade request-modal" id="profile-coach" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Coach Profile</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="profile-set">
                                    <div class="profile-set-image">
                                        <img src="{{ URL::asset('/assets/img/featured/featured-05.jpg') }}"
                                            alt="Venue">
                                    </div>
                                    <div class="profile-set-content">
                                        <h3>Kevin Anderson</h3>
                                        <div class="rating-city">
                                            <div class="profile-set-rating">
                                                <span>4.5</span>
                                                <h6>300 Reviews</h6>
                                            </div>
                                            <div class="profile-set-img">
                                                <img src="{{ URL::asset('/assets/img/flag/usa.png') }}"
                                                    alt="Profile">
                                                <h6>Santamanica, United states</h6>
                                            </div>
                                        </div>
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                            industry.industry's</p>
                                        <ul>
                                            <li>
                                                <img src="{{ URL::asset('/assets/img/icons/rank.svg') }}"
                                                    alt="Icon">
                                                <h6>Rank : Expert</h6>
                                            </li>
                                            <li>
                                                <img src="{{ URL::asset('/assets/img/icons/process.svg') }}"
                                                    alt="Icon">
                                                <h6>Sessions Completed : 25</h6>
                                            </li>
                                            <li>
                                                <img src="{{ URL::asset('/assets/img/icons/calendar-alt.svg') }}"
                                                    alt="Icon">
                                                <h6>With Dreamsport since<span> Apr 5, 2023</span></h6>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="price-set-price">
                                        <h6>Start’s From</h6>
                                        <h5>$250<span>/{{__('string.hrs')}}</span></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-tab">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab"
                                            data-bs-target="#profile" type="button" role="tab"
                                            aria-selected="true">Profile Info</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="appointment-tab" data-bs-toggle="tab"
                                            data-bs-target="#appointment" type="button" role="tab"
                                            aria-selected="false">Appointment Details</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab"
                                            data-bs-target="#reviews" type="button" role="tab"
                                            aria-selected="false">Reviews</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="previous-tab" data-bs-toggle="tab"
                                            data-bs-target="#previous" type="button" role="tab"
                                            aria-selected="false">Previous Booking</button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="profile" role="tabpanel"
                                        aria-labelledby="profile-tab">
                                        <div class="profile-card mb-0">
                                            <div class="profile-card-title">
                                                <h4>Contact Information</h4>
                                            </div>
                                            <div class="profile-contact-details">
                                                <ul>
                                                    <li>
                                                        <span>Email Address</span>
                                                        <h6>contact@example.com</h6>
                                                    </li>
                                                    <li>
                                                        <span>Phone Number</span>
                                                        <h6>+1 56565 556558</h6>
                                                    </li>
                                                    <li>
                                                        <span> Address</span>
                                                        <h6>1653 Davisson Street,Indianapolis, IN 46225</h6>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="profile-card-title">
                                                <h4>Short Bio</h4>
                                            </div>
                                            <div class="profile-card-content">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Varius
                                                    consectetur a at est diam ultricies. Egestas eros leo dapibus tellus
                                                    neque turpis. Nec in morbi adipiscing pretium accumsan urna ac,Lorem
                                                    ipsum dolor sit amet, consectetur adipiscing elit. Varius
                                                    consectetur a at est diam ultricies. Egestas eros leo dapibus tellus
                                                    neque turpis. Nec in morbi adipiscing pretium accumsan urna ac,Lorem
                                                    ipsum dolor sit amet, consectetur adipiscing elit. Varius
                                                    consectetur a at est diam ultricies. Egestas eros leo dapibus tellus
                                                    neque turpis. Nec in morbi adipiscing pretium accumsan urna ac,</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="appointment" role="tabpanel"
                                        aria-labelledby="appointment-tab">
                                        <div class="accordion" id="accordionPanel">
                                            <div class="accordion-item mb-4" id="short-bio">
                                                <h4 class="accordion-header" id="panelsStayOpen-short-bio">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseOne"
                                                        aria-expanded="true"
                                                        aria-controls="panelsStayOpen-collapseOne">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/short-bio.svg') }}"
                                                                alt="Icon"></span> Short Bio
                                                    </button>
                                                </h4>
                                                <div id="panelsStayOpen-collapseOne"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-short-bio">
                                                    <div class="accordion-body">
                                                        <div class="text show-more-height">
                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                                It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem Ipsum</p>

                                                            <ul>
                                                                <li>4 years of high school (3 years varsity)</li>
                                                                <li>3 years of college club badminton at Loyola
                                                                    Marymount</li>
                                                                <li>I grew up at North Venice Little League and
                                                                    represented</li>
                                                                <li>Southern California in 2017 for Senior State Champs.
                                                                </li>
                                                                <li>3 years on Varsity at Venice High School. Venice
                                                                    Varsity</li>
                                                                <li>badminton Western League Champs 2017.</li>
                                                                <li>2 years of Loyola Marymount University Club
                                                                    badminton.</li>
                                                            </ul>

                                                            <p>It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem Ipsum</p>

                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                                It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem Ipsum</p>

                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                            </p>

                                                            <p>It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem Ipsum</p>
                                                        </div>
                                                        <div class="show-more d align-items-center primary-text"><i
                                                                class="feather-plus-circle"></i>Show More</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4" id="lesson-with-me">
                                                <h4 class="accordion-header" id="panelsStayOpen-lesson-with-me">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseTwo"
                                                        aria-expanded="false"
                                                        aria-controls="panelsStayOpen-collapseTwo">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/lesson-with-me.svg') }}"
                                                                alt="Icon"></span> Lesson With me
                                                    </button>
                                                </h4>
                                                <div id="panelsStayOpen-collapseTwo"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-lesson-with-me">
                                                    <div class="accordion-body">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and
                                                            typesetting industry. Lorem Ipsum has been the industry's
                                                            standard dummy text ever since the 1500s, when an unknown
                                                            printer took a galley of type and scrambled it to make a
                                                            type specimen book. It has survived not only five centuries,
                                                            but also the leap into electronic typesetting, remaining
                                                            essentially unchanged. It was popularised in the 1960s with
                                                            the release of Letraset sheets containing Lorem Ipsum
                                                            passages, and more recently with desktop publishing software
                                                            like Aldus PageMaker including versions of Lorem Ipsum</p>
                                                        <ul>
                                                            <li><i class="feather-check-square"></i>Single Lesson</li>
                                                            <li><i class="feather-check-square"></i>2 Player Lesson
                                                            </li>
                                                            <li><i class="feather-check-square"></i>Small group Lesson
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4" id="coaching">
                                                <h4 class="accordion-header" id="panelsStayOpen-coaching">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseThree"
                                                        aria-expanded="false"
                                                        aria-controls="panelsStayOpen-collapseThree">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/coaching.svg') }}"
                                                                alt="Icon"></span> Coaching
                                                    </button>
                                                </h4>
                                                <div id="panelsStayOpen-collapseThree"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-coaching">
                                                    <div class="accordion-body">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and
                                                            typesetting industry. Lorem Ipsum has been the industry's
                                                            standard dummy text ever since the 1500s, when an unknown
                                                            printer took a galley of type and scrambled it to make a
                                                            type specimen book. It has survived not only five centuries,
                                                            but also the leap into electronic typesetting, remaining
                                                            essentially unchanged. It was popularised in the 1960s with
                                                            the release of Letraset sheets containing Lorem Ipsum
                                                            passages, and more recently with desktop publishing software
                                                            like Aldus PageMaker including versions of Lorem Ipsum</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4" id="gallery">
                                                <h4 class="accordion-header" id="panelsStayOpen-gallery">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseFive"
                                                        aria-expanded="false"
                                                        aria-controls="panelsStayOpen-collapseFive">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/gallery.svg') }}"
                                                                alt="Icon"></span> Gallery
                                                    </button>
                                                </h4>
                                                <div id="panelsStayOpen-collapseFive"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-gallery">
                                                    <div class="accordion-body">
                                                        <div class="owl-carousel gallery-slider owl-theme">
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('/assets/img/gallery/gallery4/gallery-15.jpg') }}">
                                                            </div>
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('/assets/img/gallery/gallery4/gallery-16.jpg') }}">
                                                            </div>
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('/assets/img/gallery/gallery4/gallery-17.jpg') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item" id="location">
                                                <h4 class="accordion-header" id="panelsStayOpen-location">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseSeven"
                                                        aria-expanded="false"
                                                        aria-controls="panelsStayOpen-collapseSeven">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/location.svg') }}"
                                                                alt="Icon"></span> Location
                                                    </button>
                                                </h4>
                                                <div id="panelsStayOpen-collapseSeven"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-location">
                                                    <div class="accordion-body">
                                                        <div class="google-maps">
                                                            <iframe
                                                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2967.8862835683544!2d-73.98256668525309!3d41.93829486962529!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89dd0ee3286615b7%3A0x42bfa96cc2ce4381!2s132%20Kingston%20St%2C%20Kingston%2C%20NY%2012401%2C%20USA!5e0!3m2!1sen!2sin!4v1670922579281!5m2!1sen!2sin"
                                                                height="170" style="border:0;"
                                                                allowfullscreen="" loading="lazy"
                                                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                                                        </div>
                                                        <div
                                                            class="dull-bg d-flex justify-content-start align-items-center mb-3">
                                                            <div class="white-bg me-2">
                                                                <i class="fas fa-location-arrow"></i>
                                                            </div>
                                                            <div class="">
                                                                <h6>Our Venue Location</h6>
                                                                <p>70 Bright St New York, USA</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="reviews" role="tabpanel"
                                        aria-labelledby="reviews-tab">
                                        <div class="review-box review-box-user d-flex">
                                            <div class="review-profile">
                                                <img src="{{ URL::asset('/assets/img/profiles/avatar-01.jpg') }}"
                                                    class="img-fluid" alt="User">
                                            </div>
                                            <div class="review-info">
                                                <h6 class="mb-2 tittle">Amanda Booked on 06/04/2023</h6>
                                                <div class="rating">
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <span class="">5.0</span>
                                                </div>
                                                <span class="success-text"><i class="feather-check"></i>Yes, I would
                                                    book again.</span>
                                                <h6>Absolutely perfect</h6>
                                                <p>If you are looking for a perfect place for friendly matches with your
                                                    friends or a competitive match, It is the best place.</p>
                                                <ul class="review-gallery">
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-01.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-01.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-02.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-02.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-03.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-03.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-04.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-04.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-05.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-05.jpg') }}">
                                                        </a>
                                                    </li>
                                                </ul>
                                                <span class="post-date">Sent on 11/03/2023</span>
                                            </div>
                                        </div>
                                        <div class="review-box review-box-user d-flex">
                                            <div class="review-profile">
                                                <img src="{{ URL::asset('/assets/img/profiles/avatar-01.jpg') }}"
                                                    class="img-fluid" alt="User">
                                            </div>
                                            <div class="review-info">
                                                <h6 class="mb-2 tittle">Amanda Booked on 06/04/2023</h6>
                                                <div class="rating">
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <span class="">5.0</span>
                                                </div>
                                                <h6>Awesome. Its very convenient to play.</h6>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                    industry. Lorem Ipsum has been the industry's standard dummy text
                                                    ever since the 1500s, when an unknown printer took a galley of type
                                                    and scrambled it to make a type specimen book. It has survived not
                                                    only five centuries, but also the leap into electronic!!</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="previous" role="tabpanel"
                                        aria-labelledby="previous-tab">
                                        <div class="preview-tab">
                                            <ul>
                                                <li>
                                                    <div class="preview-tabcontent">
                                                        <div class="preview-tabimg">
                                                            <img src="{{ URL::asset('/assets/img/services/service-01.jpg') }}"
                                                                alt="Service">
                                                        </div>
                                                        <div class="preview-tabname">
                                                            <h4>Leap Sports Academy</h4>
                                                            <h5>Court 1</h5>
                                                            <ul>
                                                                <li><span>Guests : 4</span></li>
                                                                <li><span>2 Hrs</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <h6>Date & Time</h6>
                                                    <span class="d-block">Mon, Jul 11</span>
                                                    <span>06:00 PM - 08:00 PM</span>
                                                </li>
                                                <li>
                                                    <h6>$400</h6>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="preview-tab">
                                            <ul>
                                                <li>
                                                    <div class="preview-tabcontent">
                                                        <div class="preview-tabimg">
                                                            <img src="{{ URL::asset('/assets/img/services/service-02.jpg') }}"
                                                                alt="Service">
                                                        </div>
                                                        <div class="preview-tabname">
                                                            <h4>Marsh Academy</h4>
                                                            <h5>Court 1</h5>
                                                            <ul>
                                                                <li><span>Guests : 4</span></li>
                                                                <li><span>2 Hrs</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <h6>Date & Time</h6>
                                                    <span class="d-block">Mon, Jul 11</span>
                                                    <span>06:00 PM - 08:00 PM</span>
                                                </li>
                                                <li>
                                                    <h6>$300</h6>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal custom-modal fade request-modal" id="profile-court" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Coach Profile</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="master-academy dull-whitesmoke-bg card master-academyview">
                                <div class="row d-flex align-items-center justify-content-center">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                                        <div class="d-sm-flex justify-content-start align-items-center">
                                            <a href="javascript:void(0);"><img class="corner-radius-10"
                                                    src="{{ URL::asset('/assets/img/master-academy.png') }}"
                                                    alt="Academy"></a>
                                            <div class="info">
                                                <h3 class="mb-2">Manchester Academy</h3>
                                                <div class="profile-set-content w-100 p-0">
                                                    <ul class="bg-transparent p-0">
                                                        <li>
                                                            <img src="{{ URL::asset('/assets/img/icons/location.svg') }}"
                                                                alt="Icon">
                                                            <h6> 70 Bright St New York, USA</h6>
                                                        </li>
                                                        <li>
                                                            <img src="{{ URL::asset('/assets/img/icons/call.svg') }}"
                                                                alt="Icon">
                                                            <h6>+3 80992 31212</h6>
                                                        </li>
                                                        <li>
                                                            <img src="{{ URL::asset('/assets/img/icons/mail.svg') }}"
                                                                alt="Icon">
                                                            <h6> yourmail@example.com</h6>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="coach-profile-set">
                                                    <ul class="bg-transparent p-0">
                                                        <li>
                                                            <div class="coach-profile-set-img">
                                                                <img src="{{ URL::asset('/assets/img/icons/indoor.svg') }}"
                                                                    alt="Icon">
                                                            </div>
                                                            <div class="coach-profile-set-contemt">
                                                                <h5>Venue </h5>
                                                                <span> Indoor</span>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="coach-profile-set-img ">
                                                                <img src="{{ URL::asset('/assets/img/profiles/avatar-01.jpg') }}"
                                                                    alt="User">
                                                            </div>
                                                            <div class="coach-profile-set-contemt">
                                                                <h5>Venue </h5>
                                                                <span> Indoor</span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                                        <ul class="d-sm-flex align-items-center justify-content-evenly">
                                            <li>
                                                <h3 class="d-inline-block">$150</h3><span>/{{__('string.hrs')}}</span>
                                                <p>up to 1 guests</p>
                                            </li>
                                            <li>
                                                <span><i class="feather-plus"></i></span>
                                            </li>
                                            <li class="text-center">
                                                <h3 class="d-inline-block">$5</h3><span>/{{__('string.hrs')}}</span>
                                                <p>each additional guest <br>up to 4 guests max</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-tab">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="profile-tab1" data-bs-toggle="tab"
                                            data-bs-target="#profile1" type="button" role="tab"
                                            aria-selected="true">Profile Info</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="appointment-tab1" data-bs-toggle="tab"
                                            data-bs-target="#appointment1" type="button" role="tab"
                                            aria-selected="false">Appointment Details</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="reviews-tab1" data-bs-toggle="tab"
                                            data-bs-target="#reviews1" type="button" role="tab"
                                            aria-selected="false">Reviews</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="previous-tab1" data-bs-toggle="tab"
                                            data-bs-target="#previous1" type="button" role="tab"
                                            aria-selected="false">Previous Booking</button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="profile1" role="tabpanel"
                                        aria-labelledby="profile-tab1">
                                        <div class="profile-card mb-0">
                                            <div class="profile-card-title">
                                                <h4>Contact Information</h4>
                                            </div>
                                            <div class="profile-contact-details mb-0">
                                                <ul>
                                                    <li>
                                                        <span>Email Address</span>
                                                        <h6>contact@example.com</h6>
                                                    </li>
                                                    <li>
                                                        <span>Phone Number</span>
                                                        <h6>+1 56565 556558</h6>
                                                    </li>
                                                    <li>
                                                        <span> Address</span>
                                                        <h6>1653 Davisson Street,Indianapolis, IN 46225</h6>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="appointment1" role="tabpanel"
                                        aria-labelledby="appointment-tab1">
                                        <div class="accordion">
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/Black.svg') }}"
                                                                alt="Icon"></span>Overview
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse ">
                                                    <div class="accordion-body">
                                                        <div class="text">
                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                                It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem Ipsum</p>
                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                            </p>
                                                            <p>It was popularised in the 1960s with the release of
                                                                Letraset sheets containing Lorem Ipsum passages, and
                                                                more recently with desktop publishing software like
                                                                Aldus PageMaker including versions of Lorem IpsumLorem
                                                                Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the
                                                                industry's standard dummy text ever since the 1500s,
                                                                when an unknown printer took a galley of type and
                                                                scrambled it to make a type specimen book. It has
                                                                survived not only five centuries, but also the leap into
                                                                electronic typesetting, remaining essentially unchanged.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/lesson-with-me.svg') }}"
                                                                alt="Icon"></span> Rules
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse ">
                                                    <div class="accordion-body">
                                                        <p><i class="feather-alert-octagon text-danger me-2"></i>Non
                                                            Marking Shoes are recommended not mandatory for Badminton.
                                                        </p>
                                                        <p><i class="feather-alert-octagon text-danger me-2"></i>A
                                                            maximum number of members per booking per badminton court is
                                                            admissible fixed by Venue Vendors</p>
                                                        <p><i class="feather-alert-octagon text-danger me-2"></i>No
                                                            pets, no seeds, no gum, no glass, no hitting or swinging
                                                            outside of the cage</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/lesson-with-me.svg') }}"
                                                                alt="Icon"></span> Amenities
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse">
                                                    <div class="accordion-body">
                                                        <ul class="amenities-set">
                                                            <li>
                                                                <span><i class="fa fa-check-circle text-success me-2"
                                                                        aria-hidden="true"></i>Parking</span>
                                                            </li>
                                                            <li>
                                                                <span><i class="fa fa-check-circle text-success me-2"
                                                                        aria-hidden="true"></i>Drinking Water</span>
                                                            </li>
                                                            <li>
                                                                <span><i class="fa fa-check-circle text-success me-2"
                                                                        aria-hidden="true"></i>First Aid</span>
                                                            </li>
                                                            <li>
                                                                <span><i class="fa fa-check-circle text-success me-2"
                                                                        aria-hidden="true"></i>Change Room</span>
                                                            </li>
                                                            <li>
                                                                <span><i class="fa fa-check-circle text-success me-2"
                                                                        aria-hidden="true"></i>Shower</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/includes1.svg') }}"
                                                                alt="Icon"></span> Includes
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse ">
                                                    <div class="accordion-body includes-set">
                                                        <ul>
                                                            <li><i class="feather-check-square"></i>Badminton Racket
                                                                Unlimited</li>
                                                            <li><i class="feather-check-square"></i>Bats</li>
                                                            <li><i class="feather-check-square"></i>Hitting Machines
                                                            </li>
                                                            <li><i class="feather-check-square"></i>Multiple Courts
                                                            </li>
                                                        </ul>
                                                        <ul>
                                                            <li><i class="feather-check-square"></i>Spare Players</li>
                                                            <li><i class="feather-check-square"></i>Instant Racket
                                                            </li>
                                                            <li><i class="feather-check-square"></i>Hitting Machines
                                                            </li>
                                                            <li><i class="feather-check-square"></i>Green Turfs</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item mb-4">
                                                <h4 class="accordion-header">
                                                    <button class="accordion-button" type="button">
                                                        <span class="icon-bg"><img
                                                                src="{{ URL::asset('/assets/img/icons/gallery.svg') }}"
                                                                alt="Icon"></span> Gallery
                                                    </button>
                                                </h4>
                                                <div class="accordion-collapse">
                                                    <div class="accordion-body">
                                                        <div class="owl-carousel gallery-slider owl-theme">
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('/assets/img/gallery/gallery4/gallery-18.jpg') }}">
                                                            </div>
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('/assets/img/gallery/gallery4/gallery-19.jpg') }}">
                                                            </div>
                                                            <div>
                                                                <img class="img-fluid" alt="Image"
                                                                    src="{{ URL::asset('/assets/img/gallery/gallery4/gallery-20.jpg') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="reviews1" role="tabpanel"
                                        aria-labelledby="reviews-tab1">
                                        <div class="review-box review-box-user d-flex">
                                            <div class="review-profile">
                                                <img src="{{ URL::asset('/assets/img/profiles/avatar-01.jpg') }}"
                                                    class="img-fluid" alt="User">
                                            </div>
                                            <div class="review-info">
                                                <h6 class="mb-2 tittle">Amanda Booked on 06/04/2023</h6>
                                                <div class="rating">
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <span class="">5.0</span>
                                                </div>
                                                <span class="success-text"><i class="feather-check"></i>Yes, I would
                                                    book again.</span>
                                                <h6>Absolutely perfect</h6>
                                                <p>If you are looking for a perfect place for friendly matches with your
                                                    friends or a competitive match, It is the best place.</p>
                                                <ul class="review-gallery">
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-01.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-01.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-02.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-02.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-03.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-03.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-04.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-04.jpg') }}">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::asset('/assets/img/gallery/gallery-05.jpg') }}"
                                                            data-fancybox="gallery">
                                                            <img class="img-fluid" alt="Image"
                                                                src="{{ URL::asset('/assets/img/gallery/gallery-05.jpg') }}">
                                                        </a>
                                                    </li>
                                                </ul>
                                                <span class="post-date">Sent on 11/03/2023</span>
                                            </div>
                                        </div>
                                        <div class="review-box review-box-user d-flex">
                                            <div class="review-profile">
                                                <img src="{{ URL::asset('/assets/img/profiles/avatar-01.jpg') }}"
                                                    class="img-fluid" alt="User">
                                            </div>
                                            <div class="review-info">
                                                <h6 class="mb-2 tittle">Amanda Booked on 06/04/2023</h6>
                                                <div class="rating">
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <span class="">5.0</span>
                                                </div>
                                                <h6>Awesome. Its very convenient to play.</h6>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                    industry. Lorem Ipsum has been the industry's standard dummy text
                                                    ever since the 1500s, when an unknown printer took a galley of type
                                                    and scrambled it to make a type specimen book. It has survived not
                                                    only five centuries, but also the leap into electronic!!</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="previous1" role="tabpanel"
                                        aria-labelledby="previous-tab1">
                                        <div class="preview-tab">
                                            <ul>
                                                <li>
                                                    <div class="preview-tabcontent">
                                                        <div class="preview-tabimg">
                                                            <img src="{{ URL::asset('/assets/img/featured/featured-05.jpg') }}"
                                                                alt="Venue">
                                                        </div>
                                                        <div class="preview-tabname">
                                                            <h4>Kevin Anderson</h4>
                                                            <h5>Onetime</h5>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <h6>Date & Time</h6>
                                                    <span class="d-block">Mon, Jul 11</span>
                                                    <span>06:00 PM - 08:00 PM</span>
                                                </li>
                                                <li>
                                                    <h6>$400</h6>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="preview-tab">
                                            <ul>
                                                <li>
                                                    <div class="preview-tabcontent">
                                                        <div class="preview-tabimg">
                                                            <img src="{{ URL::asset('/assets/img/featured/featured-06.jpg') }}"
                                                                alt="Venue">
                                                        </div>
                                                        <div class="preview-tabname">
                                                            <h4>Evon Raddick</h4>
                                                            <h5>Onetime</h5>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <h6>Date & Time</h6>
                                                    <span class="d-block">Mon, Jul 11</span>
                                                    <span>06:00 PM - 08:00 PM</span>
                                                </li>
                                                <li>
                                                    <h6>$300</h6>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['user-profile-othersetting']))
    <div class="modal custom-modal fade deactive-modal" id="deactive" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="account-deactive">
                        <img src="{{ URL::asset('/assets/img/icons/deactive-profile.svg') }}" alt="Icon">
                        <h3>Are You Sure You Want to Deactive Account</h3>
                        <p>If yes please click “Yes” button</p>
                        <div class="convenient-btns">
                            <a href="javascript:void(0);" data-bs-dismiss="modal"
                                class="btn btn-primary d-inline-flex align-items-center">
                                Yes <span><i class="feather-arrow-right-circle ms-2"></i></span>
                            </a>
                            <a href="javascript:void(0);" data-bs-dismiss="modal"
                                class="btn btn-secondary d-inline-flex align-items-center">
                                No <span><i class="feather-arrow-right-circle ms-2"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade deactive-modal" id="success-mail" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="account-deactive">
                        <img src="{{ URL::asset('/assets/img/icons/email-success.svg') }}" alt="Icon">
                        <h3>Email Changed Successfully</h3>
                        <p>Check your email on the confirmation</p>
                        <div class="convenient-btns">
                            <a href="javascript:void(0);"
                                class="btn btn-primary d-inline-flex align-items-center me-0">
                                <span><i class="feather-arrow-left-circle me-2"></i></span>Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['user-wallet']))
    <div class="modal custom-modal fade payment-modal" id="add-payment" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Add Payment to IBP Account</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="wallet-wrap wallet-modal">
                        <div class="wallet-amt">
                            <h5>Your IBP Account Balance</h5>
                            <h2>{{$settings->currency}}{{number_format($userDetails->wallet,2)}}</h2>
                        </div>
                    </div>

                    <div class="">
                        <input type="number" min="{{$fee->minimum}}" max="{{$fee->maximum}}" name="amount" id="amount" required class="form-control" placeholder="Enter Amount">
                    </div>
                    <span class="text-danger">Amount should be minimum  {{$settings->currency.number_format($fee->minimum,2)}} and maximum {{ $settings->currency.number_format($fee->maximum,2)}}</span>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</a>
                        <button id="confirmButton" class="btn btn-primary balance-add">Continue</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal custom-modal fade payment-modal" id="confirmPayment" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Confirm payment</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="wallet-wrap wallet-modal" id="confirmwalletAmount">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</a>
                        <button id="customButton" class="btn btn-primary balance-add">Process</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal custom-modal fade payment-modal" id="request-payment" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Request Withdraw</h4> 
                    </div>
                    <a href="{{ route('user.create.bank') }}" class="mr-2 btn btn-primary text-white"><i class="fa fa-plus"></i> Add New Account</a>
                </div>
                <div class="modal-body">
                    <div class="wallet-wrap wallet-modal">
                        <div class="wallet-amt">
                            <h5>Your IBP Account Balance</h5>
                            <h2>{{$settings->currency}}{{number_format($userDetails->wallet,2)}}</h2>
                        </div>
                    </div>
                    <?php 
                        $banks = DB::table('bank_details')->where('user_id', $userDetails->id)->where('user_type', 'customer')->get();
                    ?>
                    @if(count($banks) == '0')
                        <div class="input-space text-center mt-3">
                            <p>No Bank Accounts Found.</p>
                            <a href="{{ route('user.banks') }}" class="btn btn-primary">Add Bank</a>
                        </div>
                    @else
                        <div class="">
                            <input type="number" min="{{$withdraw_fee->minimum}}" max="{{$withdraw_fee->maximum}}" name="amount" id="withdraw_amount" required class="form-control" placeholder="Enter Amount" required>
                        </div>
                        <span class="text-danger mb-1">Amount should be minimum  {{$settings->currency.number_format($withdraw_fee->minimum,2)}} and maximum {{ $settings->currency.number_format($withdraw_fee->maximum,2)}}</span>
                            
                        @foreach($banks as $bank)
                            <div class="input-space">
                                <select name="bank_id" id="bank_id" class="form-control" required>
                                    <option value="{{$bank->id}}">{{$bank->bank_name}} ({{$bank->account_holder}})</option>
                                </select>
                            </div>
                        @endforeach
                        <div style="display: flex;justify-content: end;  align-items: center;">
                        <a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</a><span>&nbsp;&nbsp;</span>
                        <button id="proceedWithdrawButton" class="btn btn-primary balance-add">Continue</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade payment-modal" id="confirmWithdrawRequest" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Confirm Withdraw</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="wallet-wrap wallet-modal" id="confirmWithdrawwalletAmount">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</a>
                        <button id="confirmWithdrawButton" class="btn btn-primary balance-add">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade cancel-modal" id="add-new-card" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Add New Card</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-space">
                                    <label class="form-label">Card Number</label>
                                    <input type="text" class="form-control" id="CardNumber"
                                        placeholder="43576777687998998">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-space">
                                    <label class="form-label">Name On Card Number</label>
                                    <input type="text" class="form-control" placeholder="Sport">
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="input-space">
                                    <label class="form-label">Expiry Date</label>
                                    <input type="text" class="form-control" placeholder="06/2023">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-space">
                                    <label class="form-label">CVV </label>
                                    <input type="text" class="form-control" placeholder="099">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-check policy d-flex align-items-center">
                                    <div class="d-inline-block">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="policy">
                                    </div>
                                    <label class="form-check-label" for="policy">Save for Next Payment</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn btn-secondary">Reset</a>
                        <a href="javascript:;" class="btn btn-primary">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['booking-cancelled']))
    <div class="modal custom-modal fade request-modal" id="cancel-court" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Item Booking Details<span class="badge bg-danger ms-2">Cancelled</span>
                        </h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.item_information')}}</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/booking/booking-03.jpg') }}"
                                                        alt="User">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Wing Sports Academy</h6>
                                                    <p class="color-green">Court 1</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>$150 Upto 2 guests</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.price_per_guest')}}</h6>
                                            <p>$15</p>
                                        </li>
                                        <li>
                                            <h6>Maximum Number of Guests</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Player Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/profiles/avatar-06.jpg') }}"
                                                        alt="User">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Martina</h6>
                                                    <p>Since 05/05/2023</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Previosly Booked <i class="feather-alert-circle ms-1"></i></h6>
                                            <p>22 Jan 2023</p>
                                        </li>
                                        <li>
                                            <h6>No of Bookings</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>Date & Time</h6>
                                            <p>Mon, Jul 14<span class="d-block">05:00 PM - 08:00 PM</span></p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.total_no_hours')}}</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Items Booking Amount</h6>
                                            <p>$150</p>
                                        </li>
                                        <li>
                                            <h6>Additional Quantity</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Amount Additional Quantity</h6>
                                            <p>$30</p>
                                        </li>
                                        <li>
                                            <h6>Tax (Included)</h6>
                                            <p>$20</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentsetview">
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="color-green">$180</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>IBP Account</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Reason for Cancellation</h4>
                                </div>
                                <div class="user-review-details">
                                    <div class="user-review-content">
                                        <h6 class="text-danger">Cancelled By Coach</h6>
                                        <p>If you are looking for a perfect place for friendly matches with your friends
                                            or a competitive match, It is the best place.</p>
                                        <h5>Sent on 11/03/2023</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn table-btn-split">
                        <a href="javascript:;" class="btn initiate-table-btn">Initiate Refund</a>
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade request-modal" id="cancel-coach" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">item Booking Details<span class="badge bg-danger ms-2">Cancelled</span>
                        </h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Player Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/profiles/avatar-06.jpg') }}"
                                                        alt="User">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Martina</h6>
                                                    <p>Since 05/05/2023</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Previosly Booked <i class="feather-alert-circle ms-1"></i></h6>
                                            <p>22 Jan 2023</p>
                                        </li>
                                        <li>
                                            <h6>No of Bookings</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Coach Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/featured/featured-06.jpg') }}"
                                                        alt="Feature">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Angela Roudrigez</h6>
                                                    <div class="table-rating">
                                                        <div class="rating-point">
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <span>30 Reviews</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Location</h6>
                                            <p>Santa Monica, CA</p>
                                        </li>
                                        <li>
                                            <h6>Price Per Hour</h6>
                                            <p><span class="color-green">$200.00</span> / hr</p>
                                        </li>
                                        <li>
                                            <h6>Rank</h6>
                                            <p>Expert</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>Booking Type</h6>
                                            <p>Onetime</p>
                                        </li>
                                        <li>
                                            <h6>Date & Time</h6>
                                            <p>Mon, Jul 14
                                                <span>05:00 PM - 08:00 PM</span>
                                            </p>

                                        </li>
                                        <li>
                                            <h6>{{__('string.total_no_hours')}}</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Coaching Booking Amount</h6>
                                            <p>$200</p>
                                        </li>
                                        <li>
                                            <h6>Number of Hours</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="color-green">$180</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Wallet</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Reason for Cancellation</h4>
                                </div>
                                <div class="user-review-details">
                                    <div class="user-review-content">
                                        <h6 class="text-danger">Cancelled By Coach</h6>
                                        <p>If you are looking for a perfect place for friendly matches with your friends
                                            or a competitive match, It is the best place.</p>
                                        <h5>Sent on 11/03/2023</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn table-btn-split">
                        <a href="javascript:;" class="btn initiate-table-btn">Initiate Refund</a>
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade cancel-modal" id="cancel-court-modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Court Reject Reason111</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="card dashboard-card court-information">
                        <div class="card-header">
                            <h4>Court Information</h4>
                        </div>
                        <div class="appointment-info">
                            <ul class="appointmentset appointmentset-cancel">
                                <li>
                                    <div class="appointment-item">
                                        <div class="appointment-img">
                                            <img src="{{ URL::asset('/assets/img/booking/booking-03.jpg') }}"
                                                alt="User">
                                        </div>
                                        <div class="appointment-content">
                                            <h6>Wing Sports Academy</h6>
                                            <p class="color-green">Court 1</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <h6>{{__('string.price_per_guest')}}</h6>
                                    <p>$15</p>
                                </li>
                                <li>
                                    <h6>Maximum Number of Guests</h6>
                                    <p>2</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card dashboard-card court-information">
                        <div class="card-header">
                            <h4>Court Information</h4>
                        </div>
                        <div class="appointment-info">
                            <ul class="appointmentset appointmentset-cancel">
                                <li>
                                    <div class="appointment-item">
                                        <div class="appointment-img">
                                            <img src="{{ URL::asset('/assets/img/featured/featured-06.jpg') }}"
                                                alt="Feature">
                                        </div>
                                        <div class="appointment-content">
                                            <h6>Angela Roudrigez</h6>
                                            <span>Since 05/05/2023</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <h6>Previosly Booked</h6>
                                    <p>22 Jan 2023</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card dashboard-card court-information">
                        <div class="card-header">
                            <h4>{{__('string.appointment_information')}}</h4>
                        </div>
                        <div class="appointment-info appoin-border">
                            <ul class="appointmentset">
                                <li>
                                    <h6>{{__('string.booked_on')}}</h6>
                                    <p>Mon, Jul 14</p>
                                </li>
                                <li>
                                    <h6>Booking Type</h6>
                                    <p>Single Lesson</p>
                                    <p>3 Days * 1 hour @ <span class="text-primary">$150.00</span></p>
                                </li>
                                <li>
                                    <h6>{{__('string.total_no_hours')}}</h6>
                                    <p>2</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <form>
                        <div class="info-about">
                            <label class="form-label">Reason</label>
                            <textarea class="form-control" rows="3" placeholder="Enter Reject Reson"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn btn-primary">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade cancel-modal" id="cancel-coach-modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Coaching Reject Reason</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="card dashboard-card court-information">
                        <div class="card-header">
                            <h4>Court Information</h4>
                        </div>
                        <div class="appointment-info">
                            <ul class="appointmentset appointmentset-cancel">
                                <li>
                                    <div class="appointment-item">
                                        <div class="appointment-img">
                                            <img src="{{ URL::asset('/assets/img/booking/booking-03.jpg') }}"
                                                alt="User">
                                        </div>
                                        <div class="appointment-content">
                                            <h6>Wing Sports Academy</h6>
                                            <p class="color-green">Court 1</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <h6>{{__('string.price_per_guest')}}</h6>
                                    <p>$15</p>
                                </li>
                                <li>
                                    <h6>Maximum Number of Guests</h6>
                                    <p>2</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card dashboard-card court-information">
                        <div class="card-header">
                            <h4>Player Information</h4>
                        </div>
                        <div class="appointment-info">
                            <ul class="appointmentset appointmentset-cancel">
                                <li>
                                    <div class="appointment-item">
                                        <div class="appointment-img">
                                            <img src="{{ URL::asset('/assets/img/featured/featured-06.jpg') }}"
                                                alt="Feature">
                                        </div>
                                        <div class="appointment-content">
                                            <h6>Martina</h6>
                                            <span>Since 05/05/2023</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <h6>Previosly Booked</h6>
                                    <p>22 Jan 2023</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card dashboard-card court-information">
                        <div class="card-header">
                            <h4>{{__('string.appointment_information')}}</h4>
                        </div>
                        <div class="appointment-info appoin-border">
                            <ul class="appointmentset">
                                <li>
                                    <h6>{{__('string.booked_on')}}</h6>
                                    <p>Mon, Jul 14</p>
                                </li>
                                <li>
                                    <h6>Booking Type</h6>
                                    <p>Onetime</p>
                                </li>
                                <li>
                                    <h6>Number Of Hours</h6>
                                    <p>2</p>
                                </li>
                                <li>
                                    <h6>Booking Type</h6>
                                    <p>Date & Time</p>
                                    <p>05:00 PM - 08:00 PM</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <form>
                        <div class="info-about">
                            <label class="form-label">Reason</label>
                            <textarea class="form-control" placeholder="Enter Reject Reson"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn btn-primary">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['booking-completed']))
    <div class="modal custom-modal fade request-modal" id="complete-coach" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Coach Booking Details<span class="badge bg-success ms-2">Complete</span>
                        </h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Player Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/profiles/avatar-06.jpg') }}"
                                                        alt="User">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Martina</h6>
                                                    <p>Since 05/05/2023</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Previosly Booked <i class="feather-alert-circle ms-1"></i></h6>
                                            <p>22 Jan 2023</p>
                                        </li>
                                        <li>
                                            <h6>No of Bookings</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Coach Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/featured/featured-06.jpg') }}"
                                                        alt="Feature">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Angela Roudrigez</h6>
                                                    <div class="table-rating">
                                                        <div class="rating-point">
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <span>30 Reviews</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Location</h6>
                                            <p>Santa Monica, CA</p>
                                        </li>
                                        <li>
                                            <h6>Price Per Hour</h6>
                                            <p><span class="color-green">$200.00</span> / hr</p>
                                        </li>
                                        <li>
                                            <h6>Rank</h6>
                                            <p>Expert</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>Booking Type</h6>
                                            <p>Onetime</p>
                                        </li>
                                        <li>
                                            <h6>Date & Time</h6>
                                            <p>Mon, Jul 14
                                                <span>05:00 PM - 08:00 PM</span>
                                            </p>

                                        </li>
                                        <li>
                                            <h6>{{__('string.total_no_hours')}}</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Booking Days</h4>
                                </div>
                                <div class="booking-days">
                                    <ul>
                                        <li class="active">
                                            <img src="{{ URL::asset('/assets/img/icons/reset.svg') }}"
                                                class="me-2" alt="Icon">
                                            <i class="feather-check-circle me-2"></i>
                                            14 May 2023 - 7:00 PM
                                            <i class="fa fa-check-circle ms-2"></i>
                                        </li>
                                        <li class="active">
                                            <img src="{{ URL::asset('/assets/img/icons/reset.svg') }}"
                                                class="me-2" alt="Icon">
                                            <i class="feather-check-circle me-2"></i>
                                            15 May 2023 - 7:00 PM
                                            <i class="fa fa-check-circle ms-2"></i>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Coaching Booking Amount</h6>
                                            <p>$200</p>
                                        </li>
                                        <li>
                                            <h6>Number of Hours</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="color-green">$180</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Wallet</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Review Details</h4>
                                </div>
                                <div class="user-review-details">
                                    <div class="user-review-img">
                                        <img src="{{ URL::asset('/assets/img/profiles/avatar-02.jpg') }}"
                                            alt="User">
                                    </div>
                                    <div class="user-review-content">
                                        <div class="table-rating">
                                            <div class="rating-point">
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <span>5.0</span>
                                            </div>
                                        </div>
                                        <span><i class="fa fa-check me-2" aria-hidden="true"></i>Yes, I would book
                                            again.</span>
                                        <h6>Absolutely perfect</h6>
                                        <p>If you are looking for a perfect place for friendly matches with your
                                            friends or a competitive match, It is the best place.</p>
                                        <h5>Sent on 11/03/2023</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade request-modal" id="complete-court" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Court Booking Details<span class="badge bg-success ms-2">Completed</span>
                        </h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Court Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/booking/booking-03.jpg') }}"
                                                        alt="User">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Wing Sports Academy</h6>
                                                    <p class="color-green">Court 1</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>$150 Upto 2 guests</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.price_per_guest')}}</h6>
                                            <p>$15</p>
                                        </li>
                                        <li>
                                            <h6>Maximum Number of Guests</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Player Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/profiles/avatar-06.jpg') }}"
                                                        alt="User">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Martina</h6>
                                                    <p>Court 1</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Previosly Booked <i class="feather-alert-circle ms-1"></i></h6>
                                            <p>22 Jan 2023</p>
                                        </li>
                                        <li>
                                            <h6>No of Bookings</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>Date & Time</h6>
                                            <p>Mon, Jul 14<span class="d-block">05:00 PM - 08:00 PM</span></p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.total_no_hours')}}</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Court Booking Amount</h6>
                                            <p>$150</p>
                                        </li>
                                        <li>
                                            <h6>Additional Guests</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Amount Additional Guests</h6>
                                            <p>$30</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentsetview">
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="color-green">$180</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Wallet</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Review Details</h4>
                                </div>
                                <div class="user-review-details">
                                    <div class="user-review-img">
                                        <img src="{{ URL::asset('/assets/img/profiles/avatar-01.jpg') }}"
                                            alt="User">
                                    </div>
                                    <div class="user-review-content">
                                        <div class="table-rating">
                                            <div class="rating-point">
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <span>5.0</span>
                                            </div>
                                        </div>
                                        <span><i class="fa fa-check me-2" aria-hidden="true"></i>Yes, I would book
                                            again.</span>
                                        <h6>Absolutely perfect</h6>
                                        <p>If you are looking for a perfect place for friendly matches with your
                                            friends or a competitive match, It is the best place.</p>
                                        <h5>Sent on 11/03/2023</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['booking-checkout']))
    <div class="modal fade" id="bookingconfirmModal" tabindex="-1" aria-labelledby="bookingconfirmModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-center d-inline-block">
                    <img src="{{ URL::asset('/assets/img/icons/booking-confirmed.svg') }}" alt="User">
                </div>
                <div class="modal-body text-center">
                    <h3 class="mb-3">Booking has been Confirmed</h3>
                    <p>Check your email on the booking confirmation</p>
                </div>
                <div class="modal-footer text-center d-inline-block">
                    <a href="{{ url('user-dashboard') }}" class="btn btn-primary btn-icon"><i
                            class="feather-arrow-left-circle me-1"></i>Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['coach-booking']))
    <div class="modal custom-modal fade request-modal" id="upcoming-court" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Court Booking Details<span class="badge bg-info ms-2">Upcoming</span>
                        </h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Court Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/booking/booking-03.jpg') }}"
                                                        alt="User">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Wing Sports Academy</h6>
                                                    <p class="color-green">Court 1</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>$150 Upto 2 guests</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.price_per_guest')}}</h6>
                                            <p>$15</p>
                                        </li>
                                        <li>
                                            <h6>Maximum Number of Guests</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Player Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/profiles/avatar-06.jpg') }}"
                                                        alt="User">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Martina</h6>
                                                    <p>Court 1</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Previosly Booked <i class="feather-alert-circle ms-1"></i></h6>
                                            <p>22 Jan 2023</p>
                                        </li>
                                        <li>
                                            <h6>No of Bookings</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>$150 Upto 2 guests</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.price_per_guest')}}</h6>
                                            <p>$15</p>
                                        </li>
                                        <li>
                                            <h6>Maximum Number of Guests</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Court Booking Amount</h6>
                                            <p>$150</p>
                                        </li>
                                        <li>
                                            <h6>Additional Guests</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Amount Additional Guests</h6>
                                            <p>$30</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentsetview">
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="color-green">$180</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Wallet</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade request-modal" id="upcoming-coach" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Coach Booking Details<span class="badge bg-info ms-2">Upcoming</span>
                        </h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Player Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/profiles/avatar-06.jpg') }}"
                                                        alt="User">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Martina</h6>
                                                    <p>Since 05/05/2023</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Previosly Booked <i class="feather-alert-circle ms-1"></i></h6>
                                            <p>22 Jan 2023</p>
                                        </li>
                                        <li>
                                            <h6>No of Bookings</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Coach Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/featured/featured-06.jpg') }}"
                                                        alt="Feature">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Angela Roudrigez</h6>
                                                    <div class="table-rating">
                                                        <div class="rating-point">
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <span>30 Reviews</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Location</h6>
                                            <p>Santa Monica, CA</p>
                                        </li>
                                        <li>
                                            <h6>Price Per Hour</h6>
                                            <p class="text-primary fw-semibold fs-16">$200.00 / hr</p>
                                        </li>
                                        <li>
                                            <h6>Rank</h6>
                                            <p>Expert</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>Booking Type</h6>
                                            <p>Single Lesson</p>
                                            <p>3 Days * 1 hour @ <span class="text-primary">$150.00</span></p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.total_no_hours')}}</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Coaching Booking Amount</h6>
                                            <p>$200</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="text-primary fs-16">$420</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Visa Card</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['coach-dashboard']))
    <div class="modal custom-modal fade request-modal" id="upcoming-coach" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Coach Booking Details<span class="badge bg-info ms-2">Upcoming</span>
                        </h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Court Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/featured/featured-06.jpg') }}"
                                                        alt="Feature">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Angela Roudrigez</h6>
                                                    <div class="table-rating">
                                                        <div class="rating-point">
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <i class="fas fa-star filled"></i>
                                                            <span>30 Reviews</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Location</h6>
                                            <p>Santa Monica, CA</p>
                                        </li>
                                        <li>
                                            <h6>Price Per Hour</h6>
                                            <p>$200.00 / hr</p>
                                        </li>
                                        <li>
                                            <h6>Rank</h6>
                                            <p>Expert</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>Booking Type</h6>
                                            <p>Onetime</p>
                                        </li>
                                        <li>
                                            <h6>Date & Time</h6>
                                            <p>Mon, Jul 14
                                                <span>05:00 PM - 08:00 PM</span>
                                            </p>

                                        </li>
                                        <li>
                                            <h6>{{__('string.total_no_hours')}}</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Coaching Booking Amount</h6>
                                            <p>$200</p>
                                        </li>
                                        <li>
                                            <h6>Number of Hours</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="color-green">$180</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Wallet</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade request-modal" id="upcoming-court" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Court Booking Details<span class="badge bg-info ms-2">Upcoming</span>
                        </h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Court Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul class="appointmentset">
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/booking/booking-03.jpg') }}"
                                                        alt="Booking">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Wing Sports Academy</h6>
                                                    <p class="color-green">Court 1</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>$150 Upto 2 guests</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.price_per_guest')}}</h6>
                                            <p>$15</p>
                                        </li>
                                        <li>
                                            <h6>Maximum Number of Guests</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>$150 Upto 2 guests</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.price_per_guest')}}</h6>
                                            <p>$15</p>
                                        </li>
                                        <li>
                                            <h6>Maximum Number of Guests</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul class="appointmentset">
                                        <li>
                                            <h6>Court Booking Amount</h6>
                                            <p>$150</p>
                                        </li>
                                        <li>
                                            <h6>Additional Guests</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Amount Additional Guests</h6>
                                            <p>$30</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul class="appointmentsetview">
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="color-green">$180</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Wallet</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" data-bs-dismiss="modal" class="btn cancel-table-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal custom-modal fade payment-modal" id="add-payment" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Add Payment to Wallet</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="wallet-wrap wallet-modal">
                        <div class="wallet-amt">
                            <h5>Your Wallet Balance</h5>
                            <h2>$4,544</h2>
                        </div>
                    </div>
                    <form>
                        <div class="input-space">
                            <label class="form-label">Amount</label>
                            <input type="text" class="form-control" placeholder="Enter Amount">
                        </div>
                        <div class="or-div">
                            <h6>OR</h6>
                        </div>
                        <div class="add-wallet-amount form-check">
                            <ul>
                                <li class="active">
                                    <div class="add-wallet-checkbox">
                                        <input type="checkbox" class="form-check-input" id="value" checked>
                                        <label for="value">Add Value 1</label>
                                    </div>
                                    <div class="add-wallet-price">
                                        <span>+ $80</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="add-wallet-checkbox">
                                        <input type="checkbox" class="form-check-input" id="value1">
                                        <label for="value1">Add Value 2</label>
                                    </div>
                                    <div class="add-wallet-price">
                                        <span>+ $60</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="add-wallet-checkbox">
                                        <input type="checkbox" class="form-check-input" id="value2">
                                        <label for="value2">Add Value 3</label>
                                    </div>
                                    <div class="add-wallet-price">
                                        <span>+ $120</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="add-wallet-checkbox">
                                        <input type="checkbox" class="form-check-input" id="value3">
                                        <label for="value3">Add Value 4</label>
                                    </div>
                                    <div class="add-wallet-price">
                                        <span>+ $120</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="radio-setview">
                            <h6>Select Payment Gateway</h6>
                            <div class="radio">
                                <div class="form-check form-check-inline mb-3">
                                    <input class="form-check-input default-check me-1" type="radio"
                                        name="inlineRadioOptions" id="inlineRadio3" value="Credit Card">
                                    <label class="form-check-label" for="inlineRadio3">Credit Card</label>
                                </div>
                                <div class="form-check form-check-inline mb-0">
                                    <input class="form-check-input default-check me-1" type="radio"
                                        name="inlineRadioOptions" id="inlineRadio4" value="Paypal" checked>
                                    <label class="form-check-label" for="inlineRadio4">Paypal</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Reset</a>
                        <a href="javascript:;" class="btn btn-primary" data-bs-dismiss="modal"
                            aria-label="Close">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['coach-detail']))
    <div class="modal custom-modal fade payment-modal" id="add-review" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Write a Review</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-space">
                                    <label class="form-label">Your Name <span>*</span></label>
                                    <input type="text" class="form-control" id="reviewer-name"
                                        placeholder="Enter Your Name">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-space">
                                    <label class="form-label">Title of your review</label>
                                    <input type="text" class="form-control" id="title"
                                        placeholder="If you could say it in one sentence, what would you say?">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-space">
                                    <label class="form-label">Your Review <span>*</span></label>
                                    <textarea class="form-control" id="review" rows="3" placeholder="Enter Your Review"></textarea>
                                    <small class="text-muted"><span id="chars">100</span> characters
                                        remaining</small>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-space review">
                                    <label class="form-label">Rating <span>*</span></label>
                                    <div class="star-rating">
                                        <input id="star-5" type="radio" name="rating" value="star-5">
                                        <label for="star-5" title="5 stars">
                                            <i class="active fa fa-star"></i>
                                        </label>
                                        <input id="star-4" type="radio" name="rating" value="star-4">
                                        <label for="star-4" title="4 stars">
                                            <i class="active fa fa-star"></i>
                                        </label>
                                        <input id="star-3" type="radio" name="rating" value="star-3">
                                        <label for="star-3" title="3 stars">
                                            <i class="active fa fa-star"></i>
                                        </label>
                                        <input id="star-2" type="radio" name="rating" value="star-2">
                                        <label for="star-2" title="2 stars">
                                            <i class="active fa fa-star"></i>
                                        </label>
                                        <input id="star-1" type="radio" name="rating" value="star-1">
                                        <label for="star-1" title="1 star">
                                            <i class="active fa fa-star"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="terms-accept">
                                    <div class="d-flex align-items-center form-check">
                                        <input type="checkbox" id="terms_accept" class="form-check-input">
                                        <label for="terms_accept">I have read and accept <a
                                                href="{{ url('terms-condition') }}">Terms &amp;
                                                Conditions</a></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn btn-primary" data-bs-dismiss="modal"
                            aria-label="Close">Add Review</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['coach-payment']))
    <div class="modal fade" id="bookingconfirmModal" tabindex="-1" aria-labelledby="bookingconfirmModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-center d-inline-block">
                    <img src="{{ URL::asset('/assets/img/icons/booking-confirmed.svg') }}" alt="Icon">
                </div>
                <div class="modal-body text-center">
                    <h3 class="mb-3">Booking has been Confirmed</h3>
                    <p>Check your email on the booking confirmation</p>
                </div>
                <div class="modal-footer text-center d-inline-block">
                    <a href="{{ url('user-dashboard') }}" class="btn btn-primary"><i
                            class="feather-arrow-left-circle me-1"></i>Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['coach-request']))
    <div class="modal custom-modal fade request-modal" id="request-court" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Court Request</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Court Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul>
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/booking/booking-01.jpg') }}"
                                                        alt="User">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Wing Sports Academy</h6>
                                                    <p class="color-green">Court 1</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>$150 Upto 2 guests</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.price_per_guest')}}</h6>
                                            <p>$15</p>
                                        </li>
                                        <li>
                                            <h6>Maximum Number of Guests</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border">
                                    <ul>
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>$150 Upto 2 guests</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.price_per_guest')}}</h6>
                                            <p>$15</p>
                                        </li>
                                        <li>
                                            <h6>Maximum Number of Guests</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.payment_details')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border double-row">
                                    <ul>
                                        <li>
                                            <h6>Court Booking Amount</h6>
                                            <p>$150</p>
                                        </li>
                                        <li>
                                            <h6>Additional Guests</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Amount Additional Guests</h6>
                                            <p>$30</p>
                                        </li>
                                        <li>
                                            <h6>Service Charge</h6>
                                            <p>$20</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul>
                                        <li>
                                            <h6>{{__('string.total_amount_paid')}}</h6>
                                            <p class="color-green">$180</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.paid_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.transaction_id')}}</h6>
                                            <p>#5464164445676781641</p>
                                        </li>
                                        <li>
                                            <h6>{{__('string.payment_type')}}</h6>
                                            <p>Wallet</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn accept-btn"><i
                                class="feather-check-circle"></i>Accept</a>
                        <a href="javascript:;" class="btn cancel-table-btn"><i
                                class="feather-x-circle"></i>Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade request-modal" id="request-reject" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Court Reject Reason</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Court Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul>
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/booking/booking-01.jpg') }}"
                                                        alt="User">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Wing Sports Academy</h6>
                                                    <p class="color-green">Court 1</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>{{__('string.price_per_guest')}}</h6>
                                            <p>$15</p>
                                        </li>
                                        <li>
                                            <h6>Maximum Number of Guests</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Player Information</h4>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul>
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/booking/booking-02.jpg') }}"
                                                        alt="User">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Martina</h6>
                                                    <p>Since 05/05/2023</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Previosly Booked</h6>
                                            <p>22 Jan 2023</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border mb-0">
                                    <ul>
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>Booking Type</h6>
                                            <p>Onetime</p>
                                        </li>
                                        <li>
                                            <h6>Number Of Hours</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Date & Time</h6>
                                            <p>Mon, Jul 14</p>
                                            <p>05:00 PM - 08:00 PM</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <form>
                                <div class="reason-court">
                                    <label>Reason</label>
                                    <textarea class="form-control" rows="3" name="text" placeholder="Enter Reject Reson"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn accept-btn me-0">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade request-modal" id="request-coche" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Coaching Reject Reason</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Court Information</h4>
                                </div>
                                <div class="appointment-info">
                                    <ul>
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/booking/booking-01.jpg') }}"
                                                        alt="User">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Wing Sports Academy</h6>
                                                    <p class="color-green">Court 1</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>{{__('string.price_per_guest')}}</h6>
                                            <p>$15</p>
                                        </li>
                                        <li>
                                            <h6>Maximum Number of Guests</h6>
                                            <p>2</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>Player Information</h4>
                                </div>
                                <div class="appointment-info appoin-border ">
                                    <ul>
                                        <li>
                                            <div class="appointment-item">
                                                <div class="appointment-img">
                                                    <img src="{{ URL::asset('/assets/img/booking/booking-02.jpg') }}"
                                                        alt="User">
                                                </div>
                                                <div class="appointment-content">
                                                    <h6>Martina</h6>
                                                    <p>Since 05/05/2023</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Previosly Booked</h6>
                                            <p>22 Jan 2023</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card dashboard-card court-information">
                                <div class="card-header">
                                    <h4>{{__('string.appointment_information')}}</h4>
                                </div>
                                <div class="appointment-info appoin-border mb-0">
                                    <ul>
                                        <li>
                                            <h6>{{__('string.booked_on')}}</h6>
                                            <p>Mon, Jul 14</p>
                                        </li>
                                        <li>
                                            <h6>Booking Type</h6>
                                            <p>Onetime</p>
                                        </li>
                                        <li>
                                            <h6>Number Of Hours</h6>
                                            <p>2</p>
                                        </li>
                                        <li>
                                            <h6>Date & Time</h6>
                                            <p>Mon, Jul 14</p>
                                            <p>05:00 PM - 08:00 PM</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <form>
                                <div class="reason-court">
                                    <label>Reason</label>
                                    <textarea class="form-control" rows="3" name="text" placeholder="Enter Reject Reson"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn accept-btn me-0">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['coach-wallet']))
    <div class="modal custom-modal fade payment-modal" id="add-payment" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Add Payment to Wallet</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="wallet-wrap wallet-modal">
                        <div class="wallet-amt">
                            <h5>Your Wallet Balance</h5>
                            <h2>$4,544</h2>
                        </div>
                    </div>
                    <form>
                        <div class="input-space">
                            <label class="form-label">Amount</label>
                            <input type="text" class="form-control" placeholder="Enter Amount">
                        </div>
                        <div class="or-div">
                            <h6>OR</h6>
                        </div>
                        <div class="add-wallet-amount form-check">
                            <ul>
                                <li class="active">
                                    <div class="add-wallet-checkbox">
                                        <input type="checkbox" class="form-check-input" id="value" checked>
                                        <label for="value">Add Value 1</label>
                                    </div>
                                    <div class="add-wallet-price">
                                        <span>+ $80</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="add-wallet-checkbox">
                                        <input type="checkbox" class="form-check-input" id="value1">
                                        <label for="value1">Add Value 2</label>
                                    </div>
                                    <div class="add-wallet-price">
                                        <span>+ $60</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="add-wallet-checkbox">
                                        <input type="checkbox" class="form-check-input" id="value2">
                                        <label for="value2">Add Value 3</label>
                                    </div>
                                    <div class="add-wallet-price">
                                        <span>+ $120</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="add-wallet-checkbox">
                                        <input type="checkbox" class="form-check-input" id="value3">
                                        <label for="value3">Add Value 4</label>
                                    </div>
                                    <div class="add-wallet-price">
                                        <span>+ $120</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="radio-setview">
                            <h6>Select Payment Gateway</h6>
                            <div class="radio">
                                <div class="form-check form-check-inline mb-3">
                                    <input class="form-check-input default-check me-1" type="radio"
                                        name="inlineRadioOptions" id="inlineRadio3" value="Credit Card">
                                    <label class="form-check-label" for="inlineRadio3">Credit Card</label>
                                </div>
                                <div class="form-check form-check-inline mb-3">
                                    <input class="form-check-input default-check me-1" type="radio"
                                        name="inlineRadioOptions" id="inlineRadio4" value="Paypal" checked>
                                    <label class="form-check-label" for="inlineRadio4">Paypal</label>
                                </div>
                                <div class="form-check form-check-inline mb-0">
                                    <input class="form-check-input default-check me-1" type="radio"
                                        name="inlineRadioOptions" id="inlineRadio5" value="Paypal" checked>
                                    <label class="form-check-label" for="inlineRadio5">Wallet</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Reset</a>
                        <a href="javascript:;" class="btn btn-primary" data-bs-dismiss="modal"
                            aria-label="Close">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade payment-modal" id="add-new-card" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Add New Card</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-space">
                                    <label class="form-label">Card Number</label>
                                    <input type="text" class="form-control" id="CardNumber"
                                        placeholder="43576777687998998">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-space">
                                    <label class="form-label">Name On Card Number</label>
                                    <input type="text" class="form-control" placeholder="Sport">
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="input-space">
                                    <label class="form-label">Expiry Date</label>
                                    <input type="text" class="form-control" placeholder="06/2023">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-space">
                                    <label class="form-label">CVV </label>
                                    <input type="text" class="form-control" placeholder="099">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-check policy d-flex align-items-center">
                                    <div class="d-inline-block">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="policy">
                                    </div>
                                    <label class="form-check-label" for="policy">Save for Next Payment</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn btn-secondary">Reset</a>
                        <a href="javascript:;" class="btn btn-primary">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['lesson-payment']))
    <div class="modal fade" id="bookingconfirmModal" tabindex="-1" aria-labelledby="bookingconfirmModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-center d-inline-block">
                    <img src="{{ URL::asset('/assets/img/icons/booking-confirmed.svg') }}" alt="Icon">
                </div>
                <div class="modal-body text-center">
                    <h3 class="mb-3">Booking has been Confirmed</h3>
                    <p>Check your email on the booking confirmation</p>
                </div>
                <div class="modal-footer text-center d-inline-block">
                    <a href="{{ url('user-dashboard') }}" class="btn btn-primary btn-icon"><i
                            class="feather-arrow-left-circle me-1"></i>Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['profile-othersetting']))
    <div class="modal custom-modal fade deactive-modal" id="deactive" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="account-deactive">
                        <img src="{{ URL::asset('/assets/img/icons/deactive-profile.svg') }}" alt="Icon">
                        <h3>Are You Sure You Want to Deactive Account</h3>
                        <p>If yes please click “Yes” button</p>
                        <div class="convenient-btns">
                            <a href="javascript:void(0);" data-bs-dismiss="modal"
                                class="btn btn-primary d-inline-flex align-items-center">
                                Yes <span><i class="feather-arrow-right-circle ms-2"></i></span>
                            </a>
                            <a href="javascript:void(0);" data-bs-dismiss="modal"
                                class="btn btn-secondary d-inline-flex align-items-center">
                                No <span><i class="feather-arrow-right-circle ms-2"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom-modal fade deactive-modal" id="success-mail" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="account-deactive">
                        <img src="{{ URL::asset('/assets/img/icons/email-success.svg') }}" alt="Icon">
                        <h3>Email Changed Successfully</h3>
                        <p>Check your email on the confirmation</p>
                        <div class="convenient-btns">
                            <a href="{{ url('coach-dashboard') }}"
                                class="btn btn-primary d-inline-flex align-items-center me-0">
                                <span><i class="feather-arrow-left-circle me-2"></i></span>Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Route::is(['venue-details']))
    <div class="modal custom-modal fade payment-modal" id="add-review" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">Write a Review</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-space">
                                    <label class="form-label">Your Name <span>*</span></label>
                                    <input type="text" class="form-control" id="reviewer-name"
                                        placeholder="Enter Your Name">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-space">
                                    <label class="form-label">Title of your review</label>
                                    <input type="text" class="form-control" id="title"
                                        placeholder="If you could say it in one sentence, what would you say?">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-space">
                                    <label class="form-label">Your Review <span>*</span></label>
                                    <textarea class="form-control" id="review" rows="3" placeholder="Enter Your Review"></textarea>
                                    <small class="text-muted"><span id="chars">100</span> characters
                                        remaining</small>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-space review">
                                    <label class="form-label">Rating <span>*</span></label>
                                    <div class="star-rating">
                                        <input id="star-5" type="radio" name="rating" value="star-5">
                                        <label for="star-5" title="5 stars">
                                            <i class="active fa fa-star"></i>
                                        </label>
                                        <input id="star-4" type="radio" name="rating" value="star-4">
                                        <label for="star-4" title="4 stars">
                                            <i class="active fa fa-star"></i>
                                        </label>
                                        <input id="star-3" type="radio" name="rating" value="star-3">
                                        <label for="star-3" title="3 stars">
                                            <i class="active fa fa-star"></i>
                                        </label>
                                        <input id="star-2" type="radio" name="rating" value="star-2">
                                        <label for="star-2" title="2 stars">
                                            <i class="active fa fa-star"></i>
                                        </label>
                                        <input id="star-1" type="radio" name="rating" value="star-1">
                                        <label for="star-1" title="1 star">
                                            <i class="active fa fa-star"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="terms-accept">
                                    <div class="d-flex align-items-center form-check">
                                        <input type="checkbox" id="terms_accept" class="form-check-input">
                                        <label for="terms_accept">I have read and accept <a
                                                href="{{ url('terms-condition') }}">Terms &amp;
                                                Conditions</a></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="table-accept-btn">
                        <a href="javascript:;" class="btn btn-primary">Add Review</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
