<?php $page = 'user-complete'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        User Bookings
        @endslot
        @slot('li_1')
            Home
        @endslot
        @slot('li_2')
        User Bookings
        @endslot
    @endcomponent
    @component('components.user-dashboard')
	@endcomponent
	
	<style>
	    .visit-btns{
            cursor:pointer;   
        }
        
        .availabe{
            border: 1px solid;
            border-color: green;
            padding: 7px 9px 3px 7px;
            border-radius: 10px;
            margin: 10px;
            background: green;
            color: #fff;
        }
        
        .notavailabe{
            border: 1px solid;
            border-color: red;
            padding: 7px 9px 3px 7px;
            border-radius: 10px;
            margin: 10px;
            background: red;
            color: #fff;
            cursor: not-allowed;
        }
        
        form label span {
            color: #FFF;
        }
        
        .visit-rsn{
            margin-bottom: 2px;
        }
        
        #bookedColor{
            width: 20px;
            height: 20px;
            background: red;
            display: flex;
            border-radius: 50%;
        }
        
        #availableColor{
            width: 20px;
            height: 20px;
            background: green;
            display: flex;
            border-radius: 50%;
        }
        
        .justifyContent{
            justify-content: space-between;
        }
	</style>

	<div class="content court-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="sortby-section court-sortby-section">
						<div class="sorting-info">
							<div class="row d-flex align-items-center">
								<div class="col-xl-7 col-lg-7 col-sm-12 col-12">
									<div class="coach-court-list">
										<ul class="nav">
											<li><a href="{{url('user-bookings')}}">All Booking</a></li>
											<li><a  href="{{url('user-ongoing')}}">Confirmed</a></li>
											<li><a class="active" href="{{url('user-complete')}}">Completed</a></li>
											<li><a href="{{url('user-cancelled')}}">Cancelled</a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="court-tab-content">
						<div class="card card-tableset">
							<div class="card-body">
								<div class="coache-head-blk">
									<div class="row align-items-center">
										<div class="col-md-5">
											<div class="court-table-head">
												<h4>My Bookings</h4>
											</div>
										</div>
										<div class="col-md-7">
											<div class="table-search-top">
												<div id="tablefilter"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-content">
									<div class="tab-pane fade show active" id="nav-Recent" role="tabpanel" aria-labelledby="nav-Recent-tab"
										tabindex="0">
										<div class="table-responsive table-datatble">
											<table class="table  datatable">
												<thead class="thead-light">
													<tr>
                                                        <th>{{__('string.sr_no')}}</th>
                                                        <th>{{__('string.booking_id')}}</th>
                                                        <th>{{__('string.items_name')}}</th>
                                                        <th>{{__('string.schedule_date_time')}}</th>
                                                        <th>{{__('string.booking_date')}}</th>
                                                        <th>{{__('string.payment')}}</th>
                                                        <th>{{__('string.status')}}</th>
                                                        <th>{{__('string.details')}}</th>
                                                        <th></th>
													</tr>
												</thead>
												<tbody>
												    @foreach($allbookings as $k=>$bookings)
														<tr>
														    <td>{{++$k}}</td>
														    <td>{{$bookings->booking_id}}</td>
															<td>
																<h2 class="table-avatar">
																	<a href="{{url('item-details/'.$bookings->service->slug)}}"  target="_blank" class="avatar avatar-sm flex-shrink-0"><img class="avatar-img" src="{{url('/public/storage/'.$bookings->service->thumbnail)}}" alt="title"></a>
																	<span class="table-head-name flex-grow-1">
																		<a href="{{url('item-details/'.$bookings->service->slug)}}" target="_blank">{{$bookings->service->title}}</a>
																		<span>{{$bookings->service->category->title}}</span>
																	</span>
																</h2>
															</td>
															<td class="table-date-time">
							                                    <h4>{{date('D, d M Y',strtotime($bookings->date))}}
																    <span>{{date('h:i A', strtotime($bookings->time));}}-{{date('h:i A',strtotime('+'.$bookings['booking_hours'].'hour',strtotime($bookings['time'])))}}</span>
																</h4>															
															</td>	
															<td>{{date('D, d M Y',strtotime($bookings->created_at))}}</td>
															<td><span class="pay-dark fs-16">{{$settings->currency}}{{$bookings->payable_amount}}</span></td>
															<td class="text-pink view-detail-pink"><a href="javascript:;" data-bs-toggle="modal" data-bs-target="#upcoming-court{{$bookings->booking_id}}"><i class="feather-eye"></i>View Details</a></td>
															<td class="table-rating">
															    @php 
                                                                    $review=App\Models\SalonReviews::where('salon_id',$bookings->service_id)->where('booking_id',$bookings->id)->first();
                                                                @endphp
																<div class="rating-point">
																    @if(!empty($review))
													                    @for($i=0;$i<$review->rating;$i++)
                                                                            <i class="fas fa-star filled"></i>
                                                                        @endfor
                                                                        @for($i=0;$i < 5-$review->rating;$i++)
                                                                            <i class="fa fa-star" aria-hidden="true" style="color:grey"></i>
                                                                        @endfor
                                                                    @else
                                                                        @for($i=0;$i <5;$i++)
                                                                            <i class="fa fa-star" aria-hidden="true" style="color:grey"></i>
                                                                        @endfor
                                                                    @endif
															   </div>
															</td>
															<td><span class="badge bg-success"><i class="feather-check-square me-1"></i>Completed</span></td>
															<td class="text-end">
																<div class="dropdown dropdown-action table-drop-action">
																	<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>
																	<div class="dropdown-menu dropdown-menu-end">
																    	@if($bookings->status==2 && $bookings->is_rated==0)
																	        <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add-review{{$bookings->booking_id}}"><i class="feather-star"></i>Write Review</a>
																	        <a class="dropdown-item" href="{{ route('booking.invoice', $bookings->id) }}" target="_blank"><i class="fa fa-print"></i>{{__('string.print_invoice')}}</a>
																	    @endif
																	</div>
																</div>
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div> 
						<div class="tab-footer">
							<div class="row">
								<div class="col-md-6">
									<div id="tablelength"></div>
								</div>
								<div class="col-md-6">
									<div id="tablepage"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
    @component('components.modalpopup',['allbookings'=>$allbookings,'settings'=>$settings  ])@endcomponent
@endsection