<?php $page = 'user-wallet'; ?>
@extends('layout.mainlayout')

@section('content')
    @component('components.breadcrumb')
        @slot('title')
        {{__('string.ibp_account')}}
        @endslot
        @slot('li_1')
            {{__('string.home')}}
        @endslot
        @slot('li_2')
        {{__('string.ibp_account')}}
        @endslot
    @endcomponent
    @component('components.user-dashboard')
	@endcomponent

	<div class="content court-bg">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-lg-5 d-flex">
					<div class="wallet-wrap flex-fill">
						<div class="wallet-bal">
							<div class="wallet-img">
								<div class="wallet-amt">
									<h5>{{__('string.your_ibp_account_balance')}}</h5>
									<h2>{{$settings->currency}}{{number_format($userDetails->wallet,2)}}</h2>
								</div>
							</div>
							<div class="payment-btn">
								<a href="#" class="btn balance-add" data-bs-toggle="modal" data-bs-target="#add-payment">{{__('string.add_fund')}}</a>
							</div>
						</div>
						<ul>
							<li>
								<h6>{{__('string.total_funds_added')}}</h6>
								<h3>{{$settings->currency}}{{number_format($total_fund_added,2)}}</h3>
							</li>
							<li>
								<h6>{{__('string.total_bookings')}}</h6>
								<h3>{{$settings->currency}}{{number_format($total_bookings,2)}}</h3>
							</li>
							<li>
								<h6>{{__('string.total_refund')}}</h6>
								<h3>{{$settings->currency}}{{number_format($total_refund,2)}}</h3>
							</li>
						</ul>
					</div>									
				</div>
			
				<div class="col-md-12 col-lg-5 d-flex">
					<div class="wallet-wrap flex-fill" id="wallet-wrap">
						<div class="wallet-bal">
							<div class="wallet-img">
								<div class="wallet-amt">
									<h5>{{__('string.available_to_withdraw')}}</h5>
									<h2>{{$settings->currency}}{{number_format($userDetails->wallet,2)}}</h2>
								</div>
							</div>
							<div class="payment-btn">
								<a href="#" class="btn balance-add" data-bs-toggle="modal" data-bs-target="#request-payment">{{__('string.request_withdraw')}}</a>
							</div>
						</div>
						<ul>
							<li>
								<h6>{{__('string.pending_withdraw')}}</h6>
								<h3>{{$settings->currency}}{{number_format($total_withdraw_pending,2)}}</h3>
							</li>
							<li>
								<h6>{{__('string.success_withdraw')}}</h6>
								<h3>{{$settings->currency}}{{number_format($total_withdraw_success,2)}}</h3>
							</li>
							<li>
								<h6>{{__('string.total_withdraw')}}</h6>
								<h3>{{$settings->currency}}{{number_format($total_withdraw,2)}}</h3>
							</li>
						</ul>
					</div>									
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12">
				     @component('components.user-wallet',['type'=>$type])@endcomponent
					<div class="court-tab-content">
						<div class="card card-tableset">
							<div class="card-body">
								<div class="coache-head-blk">
									<div class="row align-items-center">
										<div class="col-lg-5">
											<div class="court-table-head">
												<h4>{{__('string.transaction')}}</h4>
											</div>
										</div>
										<div class="col-lg-7">
											<div class="table-search-top invoice-search-top">
												<div id="tablefilter"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="table-responsive table-datatble">
									<table class="table datatable">
										<thead class="thead-light">
                                            <tr>
                                                <th>Sr.No</th>
                                                <th>{{__('string.ref_id')}}</th>
                                                <th>{{__('string.transaction_for')}}</th>
                                                @if($type=='purchase')
                                                    <th>{{__('string.booking_id')}}</th>
                                                @endif
                                                <th>{{__('string.date_time')}}  </th>
                                                @if($type=='withdraw')
                                                    <th>{{__('string.request_amount')}}  </th>
                                                    <th>{{__('string.fee')}}  </th>
                                                @endif
                                                @if($type=='withdraw') 
                                                    <th>{{__('string.amount_received')}}</th>
                                                @elseif($type=='deposit') 
                                                    <th>{{__('string.deposit_amount')}}</th>
                                                @elseif($type=='refund') 
                                                    <th>{{__('string.refund_amount')}}</th>
                                                @else 
                                                    <th>{{__('string.payment')}}</th>
                                                @endif
                                                @if($type=='purchase')
                                                    <th>{{__('string.print_invoice')}}</th>
                                                @endif
                                                @if($type=='deposit')
                                                    <th>{{__('string.fee')}}</th>
                                                    <th>{{__('string.total_amount')}}</th>
                                                @endif
                                                @if($type=='refund')    
                                                    <th>{{__('string.booking_id')}}</th>
                                                    <th>{{__('string.total_booking_amount')}}</th>
                                                    <th>{{__('string.cancellation_charges')}}</th>
                                                @endif
                                            </tr>
										</thead>
										<tbody>
										    @foreach($statement as $k=>$value)
												<tr> 
												    <td>{{++$k}}</td>
													<td><a href="javascript::void(0)" class="text-primary">{{$value->transaction_id}}</a></td>
													<td>
														<h2 class="table-avatar">
															<span class="table-head-name flex-grow-1">
																<a href="#">
                                                                    @if($value->type==1)
                                                                       purchase
                                                                    @elseif($value->type==2)
                                                                        withdraw
                                                                    @elseif($value->type==3)
                                                                      refund
                                                                    @else
                                                                      deposit
                                                                    @endif
																</a>
															</span>
														</h2>
													</td>
													@if($type=='purchase')
												        <td><a href="javascript::void(0)" class="text-primary">{{$value->booking_id}}</a></td>
												    @endif 
													<td class="table-date-time">
														<h4>{{date('d M,Y',strtotime($value->created_at))}}<span>{{date('D H:i A',strtotime($value->created_at))}}</span></h4>
													</td>
													@if($type=='withdraw')
												   	     <td><span class="pay-dark fs-16">{{$settings->currency}}{{number_format($value->amount,2)}}</span></td>
												         <td><span class="text-danger">-{{$settings->currency}}{{number_format($value->charge_amount,2)}}</span></td>
												    @endif
												        
												    @if($type=='deposit')
												         <td><span class="pay-dark fs-16">{{$settings->currency}}{{number_format($value->amount,2)}}</span></td>
												         <td><span class="text-danger">+{{$settings->currency}}{{number_format($value->charge_amount,2)}}</span></td>
												    @endif
												    
													@if($type=='deposit')
													    <td><span class="pay-dark fs-16">{{$settings->currency}}{{number_format($value->total_amount,2)}}</span></td>
													@else
    													@if($value->booking_id)
    													    <td><span class="pay-dark fs-16">{{$settings->currency}}{{number_format($value->amount,2)}}</span></td>
    													@else
    												    	<td><span class="pay-dark fs-16">{{$settings->currency}}{{number_format($value->total_amount,2)}}</span></td>
    													@endif
													@endif
													
													@if($type=='purchase')
    													<?php
    													    $bookings = DB::table('bookings')->where('booking_id', $value->booking_id)->first();
    													?>
												        <td><a href="{{ route('booking.invoice', $bookings->id) }}" class="text-primary" target="_blank">Download</a></td>
												    @endif 
													
													@if($type=='refund')
												        <td><a href="javascript::void(0)" class="text-primary">{{$value->booking_id}}</a></td>
												     	<td><span class="pay-dark fs-16">{{$settings->currency}}{{number_format($value->total_amount,2)}}</span></td>
												     	<td><span class="text-danger">-{{$settings->currency}}{{number_format($value->charge_amount,2)}}</span></td>
                        							@endif
												</tr>
											@endforeach
										</tbody>
									</table>
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

    @component('components.modalpopup',['userDetails'=>$userDetails,'settings'=>$settings,'fee'=>$fee,'withdraw_fee'=>$withdraw_fee])
    @endcomponent
@endsection