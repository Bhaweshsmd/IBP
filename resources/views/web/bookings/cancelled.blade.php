<?php $page = 'user-cancelled'; ?>
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
											<li><a  href="{{url('user-bookings')}}">All Booking</a></li>
											<li><a  href="{{url('user-ongoing')}}">Confirmed</a></li>
											<li><a  href="{{url('user-complete')}}">Completed</a></li>
											<li><a class="active" href="{{url('user-cancelled')}}">Cancelled</a></li>
										</ul>
									</div>
								</div>
								<div class="col-xl-5 col-lg-5 col-sm-12 col-12">
									<div class="sortby-filter-group court-sortby">
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
													</tr>
												</thead>
												<tbody>
												    @if(!empty($allbookings))
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
															</h4>															</td>
														<td>{{date('D, d M Y',strtotime($bookings->created_at))}}</td>
														<td><span class="pay-dark fs-16">{{$settings->currency}}{{$bookings->payable_amount}}</span></td>
														<td>
														    <span class="badge bg-danger">
														        <i class="feather-check-square me-1"></i>Cancelled
														    </span>
														</td>
														<td class="text-pink view-detail-pink"><a href="javascript:;" data-bs-toggle="modal" data-bs-target="#upcoming-court{{$bookings->booking_id}}"><i class="feather-eye"></i>View Details</a></td>
													</tr>
												  @endforeach
												@endif	
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