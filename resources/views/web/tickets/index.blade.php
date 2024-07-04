<?php $page = 'user-bookings'; ?>
@extends('layout.mainlayout')

@section('content')
    @component('components.breadcrumb')
        @slot('title')
        {{__('string.support_ticket')}}
        @endslot
        @slot('li_1')
            {{__('string.home')}}
        @endslot
        @slot('li_2')
        {{__('string.support_ticket')}}
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
												<h4>{{__('string.ticket')}}</h4>
											</div>
										</div>
										<div class="col-md-7">
											<div class="table-search-top">
												<div id="tablefilter"></div>
												<div class="request-coach-list">
													<div class="card-header-btns">
														<nav>
															<div class="nav nav-tabs" role="tablist">
															    <a href="#" class="btn balance-add nav-link active" data-bs-toggle="modal" data-bs-target="#request-payment">{{__('string.new_ticket')}}</a>
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
													   <th>{{__('string.ticket_id')}}</th>
													   <th>{{__('string.subject')}}</th>
													   <th>{{__('string.reason')}}</th>
													   <th>{{__('string.priority')}}</th>
													   <th>{{__('string.status')}}</th>
													   <th>{{__('string.date')}}</th>
													   <th></th>
													</tr>
												</thead>
												<tbody>
												    @foreach($tickets as $bookings)
														<tr>
														    <td>{{$bookings->ticket_id}}</td>
														    <td>{{$bookings->subject}}</td>
														    <td>{{$bookings->reason}}</td>
														    <td>{{$bookings->priority}}</td>
														    <td>{{$bookings->status}}</td>
															<td class="table-date-time">
																<h4>{{date('D, d M Y',strtotime($bookings->created_at))}}</h4>
															</td>
															<td class="text-pink view-detail-pink"><a href="{{route('tickets.view',$bookings->id)}}" ><i class="feather-eye"></i>{{__('string.view')}}</a></td>
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
	
	<div class="modal custom-modal fade payment-modal" id="request-payment" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="form-header modal-header-title">
                        <h4 class="mb-0">{{__('string.new_ticket')}}</h4>
                    </div>
                    <a class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="input-space">
                        <input type="text" name="subject" id="subject" required class="form-control" placeholder="{{__('string.subject')}}" required>
                    </div>
                    <div class="input-space">
                        <select class="form-control select" name="priority" id="priority" required>
                            <option value="">{{__('string.select_priority')}} </option>
                            <option value="Low">{{__('string.low')}}</option>
                            <option value="Medium">{{__('string.medium')}}</option>
                             <option value="High">{{__('string.high')}}</option>
                        </select>
                    </div>
                    <div class="input-space">
                        <select class="form-control select" name="reason" id="reason" required>
                            <option value="">Select Reason </option>
                            @if($suppprtTicketReason)
                                @foreach($suppprtTicketReason as $type)
                                   <option value="{{$type->title}}">{{$type->title}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                   
                    <div class="input-space">
                        <textarea type="text" name="description" id="description" required class="form-control" placeholder="{{__('string.description')}}" required></textarea>
                    </div>
                    
                    <a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">{{__('string.cancel')}}</a>
                    <button id="submitSupporticket" class="btn btn-primary balance-add">{{__('string.submit')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection