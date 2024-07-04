<?php $page = 'user-card'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
         {{__('string.membership_card')}}
        @endslot
        @slot('li_1')
            Home
        @endslot
        @slot('li_2')
       {{__('string.membership_card')}}
        @endslot
    @endcomponent
    @component('components.user-dashboard')
	@endcomponent
	
	@php
    	if(session()->get('locale') == 'pap'){
    	   $fron_url=   URL::asset('/assets/img/bg/carddesigns/Paiamentu/IBPCardFrontPapiamentu.PNG');
    	   $backend_url= URL::asset('/assets/img/bg/carddesigns/Paiamentu/IBPCardBackPapiamentu.PNG');
    	}elseif(session()->get('locale') == 'nl'){
    	    $fron_url=   URL::asset('/assets/img/bg/carddesigns/Dutch/IBPCardFrontDutch.PNG');
    	    $backend_url= URL::asset('/assets/img/bg/carddesigns/Dutch/IBPCardBackDutch.PNG');
    	}else{
    	    $fron_url=   URL::asset('/assets/img/bg/carddesigns/English/IBPCardFrontEnglish.PNG');
    	    $backend_url= URL::asset('/assets/img/bg/carddesigns/English/IBPCardBackEnglish.PNG');
    	}
	@endphp
	
	<style>
	    .wallet-wrap {
	        background-image: url("{{$fron_url}}");
	        background-repeat: no-repeat;
            background-size: cover;
            padding:50px;
	    }
	    
	    #wallet-wrap-back{
	        background-image: url("{{$backend_url}}");
	    }
	    
	   .card-number h4 {
	        float: right;
            letter-spacing: 3px;
            position: absolute;
            bottom: 4%;
            left: 11%;
	    }
	    
	    #cardNumber h4 {
            background: rgb(19 76 104 / 0%); 
            float: right;
            position: absolute;
            top: 38%;
            left: 7%;
        }
        
        .card-number{
            color:#bde1e2 !important;
        }
	   
	   .wallet-amt h4 {
	        background: rgb(31 61 76 / 71%);
	        position: absolute;
            left: 74%;
            top: 95%;
	    }
	   
	   .wallet-wrap .wallet-bal {
	       border-bottom: 0px solid #F9F9F6;
	   }
	   
        .containers {
            width:100%;
            max-width: 400px;
            height: 250px;
            position: relative;
            -ms-perspective: 800px;
            perspective: 800px;
            border-radius: 4px;
        }

        .cards {
            width: 100%;
            height: 100%;
            position: absolute;
            transform-style: preserve-3d;
            transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-radius: 6px;
            cursor: pointer;
        }
        
        .cards div {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            display: -ms-flexbox;
            display: box;
            display: flex;
            -o-box-pack: center;
            justify-content: center;
            -o-box-align: center;
            align-items: center;
        }
        
        .cards .back {
            transform: rotateY(180deg);
        }
        
        .cards.flipped {
            transform: rotateY(180deg);
        }
        
        .card-code{
            position: absolute;
            top: 15%;
            left:3%;
        }
	</style>
	
		<div class="content court-bg">
			<div class="container">

				<div class="row justify-content-center">
					<div class="containers col-md-12 col-lg-12">
                        <div class="cards">
                            <div class="front">
                                <div class="wallet-wrap flex-fill">
        							<div class="wallet-bal">
        								<div class="wallet-img">
        								</div>
        								<div class="wallet-amt">
        									<h4 style="color: white;">{{ $settings->currency }}{{number_format($carddetails->balance??0,2)}}</h4>
        								</div>
        							</div>
    								<div class="card-number">
    									<h4 style="color: white;">{{chunk_split($carddetails->card_number??'',4,' ')??'xxxx xxxx xxxx'}}</h4>
    								</div>
						         </div>
						    </div>
                            <div class="back">
                                <div class="wallet-wrap flex-fill" id="wallet-wrap-back">
                                    <p class="card-code">
                                        @if($carddetails)
                                            <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($carddetails->card_number, 'I25')}}" alt="barcode" />
                                        @endif
                                    </p>

        							<div class="wallet-bal">
        								<div class="wallet-img">
        								</div>
        							</div>
    								<div class="card-number" id="cardNumber">
    									<h4 style="color: black;">{{chunk_split($carddetails->card_number??'',4,' ')??'xxxx xxxx xxxx'}}</h4>
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
												   <th>{{__('string.ref_id')}}</th>
												   <th>{{__('string.transaction_for')}}</th>
												   <th>{{__('string.date_time')}}</th>
												   <th>{{__('string.payment')}}</th>
												   <th>{{__('string.booking_id')}}</th>
												</tr>
											</thead>
											<tbody>
											    @if($cardtransaction)
											    @foreach($cardtransaction as $transaction)
												<tr>
													<td><a href="javascript:void(0)" class="text-primary">{{$transaction->transaction_id}}</a></td>
													<td>
														<h2 class="table-avatar">
															<span class="table-head-name flex-grow-1">
																<a href="#">{{$transaction->type}}</a>
															</span>
														</h2>
													</td>
													<td class="table-date-time">
														<h4>{{date('D,d M Y H:i A',strtotime($transaction->created_at))}}</h4>
													</td>	
													<td><span class="pay-dark fs-16">{{ $settings->currency }}{{number_format($transaction->amount??0,2)}}</span></td>
													<td class="table-date-time">
														{{$transaction->booking_id??'N/A'}}
													</td>
												</tr>
												@endforeach
												@endif
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
        @component('components.modalpopup')
        @endcomponent
    @endsection