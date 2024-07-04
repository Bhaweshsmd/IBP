<style>
	@media screen and (max-width: 780px) {
	    .statcontent{
	        padding: 0px 25px;
	    }
	    
	    .statistics-grid {
    	    width:320px;
            padding: 20px 22px !important;
        }
	}
	
	.fa-trash-alt{
        text-align: center;
        padding: 10;
        font-size: 12px;
        width: 34px;
        height: 34px;
        right: 3px;
        top: 3px;
        color: #FFFFFF;
        border-radius: 50px;
        background: #CA0D0D;
    }
    
    .statistics-grid .statistics-icon {
        background: unset !important;
        box-shadow: 0px 4px 44px rgba(211, 211, 211, 0.25);
        min-width: fit-content !important;
        height: unset !important;
        border-radius: 5px;
    }
</style>

<?php $page = 'user-dashboard'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        {{__('string.user_dashboard')}}
        @endslot
        @slot('li_1')
            {{__('string.home')}}
        @endslot
        @slot('li_2')
        {{__('string.user_dashboard')}}
        @endslot
    @endcomponent
    @component('components.user-dashboard')
	@endcomponent

	<div class="content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="card dashboard-card statistics-card">
						<div class="card-header">
							<h4>{{__('string.dashboard')}}</h4>
							<p>{{__('string.get_your_bookings payments_here')}}.</p>
						</div>
						<div class="row">
							<div class="col-xl-6 col-lg-6 col-md-6 ">
							     <a href="{{route('user-bookings')}}">
								<div class="statistics-grid flex-fill">
									<div class="statistics-content statcontent">
										<h3>{{$allbookingsCount}}</h3>
										<p>{{__('string.total_items_booked')}}</p>
									</div>
									<div class="statistics-icon">
										<img src="{{URL::asset('/assets/img/icons/list.png')}}" alt="Icon" width="60">
									</div>
								</div>
								  </a>	
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6">
							    <a href="{{route('user-bookings')}}">
								<div class="statistics-grid flex-fill">
									<div class="statistics-content statcontent">
										<h3>{{$settings->currency}}{{number_format($total_bookings,2)}}</h3>
										<p>{{__('string.booking_payments')}}</p>
									</div>
									<div class="statistics-icon">
										<img src="{{URL::asset('/assets/img/icons/correct.png')}}" alt="Icon" width="60">
									</div>
								</div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xl-7 col-lg-12 d-flex">
					<div class="card dashboard-card flex-fill">
						<div class="card-header card-header-info">
							<div class="card-header-inner">
								<h4>{{__('string.my_bookings')}}</h4>
								<p>(Latest 5 Bookings)</p>
							</div>
							<div class="card-header-btns">
								<nav>
									<div class="nav nav-tabs" role="tablist">
									  <a class="nav-link active" id="nav-Court-tab" href="{{route('user-bookings')}}" aria-selected="true">View All</a>
									</div>
								</nav>
							</div>
						</div>
						@if(count($allbookings))
							<div class="tab-content">
								<div class="tab-pane fade show active" id="nav-Court" role="tabpanel" aria-labelledby="nav-Court-tab"
									tabindex="0">
									<div class="table-responsive dashboard-table-responsive">
										<table class="table dashboard-card-table">
											<tbody>
											    @foreach($allbookings as $bookings)
												<tr>
													<td>
														<div class="academy-info">
															<a href="{{url('item-details/'.$bookings->service->slug)}}" class="academy-img  col-sm-3">
																<img src="{{url('/public/storage/'.$bookings->service->thumbnail)}}" alt="{{$bookings->service->title}}">
															</a>
															<div class="academy-content">
															
																<h6><a href="{{url('item-details/'.$bookings->service->slug)}}" >{{$bookings->service->title}}</a></h6>
																<ul>
																	<li>{{__('string.quantity')}} : {{$bookings->quantity}}</li>
																	<li>
																	    @if($bookings->booking_hours==16)
																	        {{__('string.whole_day')}}</li>
																	    @else
																	        {{$bookings->booking_hours??'1'}} {{__('string.hrs')}}
																	    @endif
																	</li>
																</ul>
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
															</div>
														</div>
													</td>
													<td>
														<h6>{{__('string.date_time')}}</h6>
														<p>{{date(' D, d M Y',strtotime($bookings->date))}}</p>
														<p>{{date('h:i A', strtotime($bookings->time));}}</p>
													</td>
													<td>
														<h4>{{$settings->currency}}{{number_format($bookings->payable_amount,2)}}</h4>
													</td>
												</tr>
											   @endforeach	
											</tbody>
										</table>
									</div>
								</div>
							</div>
						@else
						    <div class="noBooking mt-5 text-center">
                           	    <p>{{__('string.no_booking_currently')}}</p>
                           	</div>
                        @endif
					</div>
				</div>
				<div class="col-xl-5 col-lg-12 d-flex flex-column">
					<div class="card payment-card ">
						<div class="payment-info ">
							<div class="payment-content">
								<p>{{__('string.your_ibp_account_balance')}}</p>
								<h2>{{$settings->currency}}{{number_format($userDetails->wallet,2)}}</h2>
							</div>
						</div>
					</div>
					<div class="card dashboard-card academy-card">
						<div class="card-header card-header-info">
							<div class="card-header-inner">
								<h4>{{__('string.my_favourite_items')}}</h4>
							</div>
							<div class="card-header-btns">
								<nav>
									<div class="nav nav-tabs" role="tablist">
									    <a class="nav-link active" id="nav-Court-tab" href="{{route('favourite-services')}}" aria-selected="true">View All</a>
									</div>
								</nav>
							</div>
						</div>
						<div class="tab-content">
							<div class="tab-pane fade show active" id="nav-Favourites" role="tabpanel" aria-labelledby="nav-Favourites-tab" tabindex="0">
							    @if(count($wishlist))
									<div class="table-responsive dashboard-table-responsive">
										<table class="table dashboard-card-table">
											<tbody>
											    @foreach($wishlist as $bookings )
												<tr>
													<td>
														<div class="academy-info academy-info"> 
															<a href="{{url('item-details/'.$bookings->slug)}}" class="academy-img  col-sm-3">
																<img src="{{url('/public/storage/'.$bookings->thumbnail)}}" alt="Booking">
															</a>
															<div class="academy-content">
																<h6><a href="{{url('item-details/'.$bookings->slug)}}">{{$bookings->title}}</a></h6>
																<p>{{$settings->currency}}{{number_format($bookings->price-($bookings->price*$bookings->discount)/100,2)}} - 1 Hour</p>
															</div>
														</div>
													</td>
													<td>
														<div class="academy-icon">
															<a href="javascript:void(0)" class="fav-icon addtofavroute btn btn-icon logo-hide-btn btn-sm"  rel="{{$bookings->id}}"><i class="far fa-trash-alt"></i></a>
														</div>
													</td>
												</tr>
											@endforeach	
											</tbody>
										</table>
									</div>
								@else
    							    <div class="noBooking mt-2  text-center">
    	                           	    <p>{{__('string.no_favourite_items_currently')}}</p>
    	                           	</div>
                                @endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    @component('components.modalpopup')
    @endcomponent

@endsection