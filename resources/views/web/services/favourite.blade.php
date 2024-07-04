<?php $page = 'favourite-services'; ?>
@extends('layout.mainlayout')
<style>
    .academy-card .academy-icon a {
        color: #A8A8A8;
        font-size: 17px !important;
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
</style>
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        My Favourite Items
        @endslot
        @slot('li_1')
            Home
        @endslot
        @slot('li_2')
        My Favourite Items
        @endslot
    @endcomponent
    @component('components.user-dashboard')
	@endcomponent

	<div class="content">
		<div class="container">
			<div class="row">
				<div class="col-xl-12 col-lg-12 d-flex flex-column">
					<div class="card dashboard-card academy-card">
						<div class="card-header card-header-info">
							<div class="card-header-inner">
								<h4>{{__('string.my_favourite_items')}}</h4>
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
    															</div>
    														</div>
    													</td>
    													<td><p>{{$settings->currency}}{{number_format($bookings->price-($bookings->price*$bookings->discount)/100,2)}} - 1 Hour</p></td>
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
							
							<div class="tab-pane fade" id="nav-FavouritesCoaching" role="tabpanel" aria-labelledby="nav-FavouritesCoaching-tab" tabindex="0">
								<div class="table-responsive dashboard-table-responsive">
									<table class="table dashboard-card-table">
										<tbody>
											<tr>
												<td>
													<div class="academy-info academy-info">
														<a href="{{url('user-bookings')}}" class="academy-img">
															<img src="{{URL::asset('/assets/img/featured/featured-05.jpg')}}" alt="Booking">
														</a>
														<div class="academy-content">
															<h6><a href="{{url('user-bookings')}}">Kevin Anderson</a></h6>
															<p>10 Bookings</p>
														</div>
													</div>
												</td>
												<td>
													<div class="academy-icon">
														<a href="{{url('user-bookings')}}">
															<i class="feather-chevron-right"></i>
														</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="academy-info academy-info">
														<a href="{{url('user-bookings')}}" class="academy-img">
															<img src="{{URL::asset('/assets/img/featured/featured-06.jpg')}}" alt="Booking">
														</a>
														<div class="academy-content">
															<h6><a href="{{url('user-bookings')}}">Angela Roudrigez</a></h6>
															<p>20 Bookings</p>
														</div>
													</div>
												</td>
												<td>
													<div class="academy-icon">
														<a href="{{url('user-bookings')}}">
															<i class="feather-chevron-right"></i>
														</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="academy-info academy-info">
														<a href="{{url('user-bookings')}}" class="academy-img">
															<img src="{{URL::asset('/assets/img/featured/featured-07.jpg')}}" alt="Booking">
														</a>
														<div class="academy-content">
															<h6><a href="{{url('user-bookings')}}">Evon Raddick</a></h6>
															<p>30 Bookings</p>
														</div>
													</div>
												</td>
												<td>
													<div class="academy-icon">
														<a href="{{url('user-bookings')}}">
															<i class="feather-chevron-right"></i>
														</a>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
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