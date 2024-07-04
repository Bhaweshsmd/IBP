<?php $page = 'user-bookings'; ?>
@extends('layout.mainlayout')

@section('content')
    @component('components.breadcrumb')
        @slot('title')
        {{__('string.event_enquiry')}}
        @endslot
        @slot('li_1')
            {{__('string.home')}}
        @endslot
        @slot('li_2')
        {{__('string.event_enquiry')}}
        @endslot
    @endcomponent

	<div class="content court-bg">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="court-tab-content">
						<div class="card card-tableset">
							<div class="card-body">
								<div class="coache-head-blk">
									<div class="row align-items-center">
										<div class="col-md-5">
											<div class="court-table-head">
												<h4>{{__('string.event_enquiry')}}</h4>
											</div>
										</div>
										<div class="col-md-7">
											<div class="table-search-top">
												<div id="tablefilter"></div>
												<div class="request-coach-list">
													<div class="card-header-btns">
														<nav>
															<div class="nav nav-tabs" role="tablist">
																<a  class="nav-link active" href="{{ route('event-enquiry') }}" aria-selected="true">New Enquiry</a>
															</div>
														</nav>
													</div>
												</div>
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
													   <th>{{__('string.event_type')}}</th>
													    <th>{{__('string.quantity')}}</th>
													   <th>{{__('string.event_date')}}</th>
													   <th>{{__('string.enquiry_date')}}</th>
													   <th>{{__('string.message')}}</th>
													   <th>{{__('string.details')}}</th>
													   <th></th>
													</tr>
												</thead>
												<tbody>
												    @foreach($event_inquiries as $bookings)
														<tr>
														    <td>{{$loop->iteration}}</td>
														    <td>{{$bookings->event_type}}</td>
														     <td>{{$bookings->no_of_people}}</td>
															<td class="table-date-time">
																<h4>{{date('D, d M Y',strtotime($bookings->event_date))}}
																<span>{{date('h:i A', strtotime($bookings->event_date));}}</span>

																</h4>
															</td>
																<td class="table-date-time">
																<h4>{{date('D, d M Y',strtotime($bookings->created_at))}}
																<span>{{date('h:i A', strtotime($bookings->created_at));}}</span>

																</h4>
															</td>
                                                            <td>{{ strlen($bookings->message) > 20 ? substr($bookings->message,0,20)."..." : $bookings->message; }}</td>
															<td class="text-pink view-detail-pink"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#event-details{{$bookings->id}}"><i class="feather-eye"></i>{{__('string.view_details')}}</a></td>
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
    @component('components.modalpopup',['allbookings'=>$allbookings,'settings'=>$settings,'event_inquiries'=>$event_inquiries  ])@endcomponent
@endsection