<?php $page = 'user-banks'; ?>
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
	
	<div class="content court-bg">
		<div class="container">
		    
		    <div class="coach-court-list profile-court-list">
				<ul class="nav">
					<li><a href="{{route('user-profile')}}">{{__('string.profile')}}</a></li>
					<li><a href="{{route('user-setting-password')}}">{{__('string.change_password')}}</a></li>
					<li><a class="active" href="{{route('user.banks')}}">{{__('string.withdraw_setting')}}</a></li>
				</ul>
			</div>
			
			<div class="row">
				<div class="col-sm-12">
					<div class="profile-detail-group">
						<div class="card ">
						    <div class="coache-head-blk mb-3">
								<div class="row align-items-center">
									<div class="col-lg-5">
										<div class="court-table-head">
											<h4>Edit Account</h4>
										</div>
									</div>
									<div class="col-lg-7">
										<div class="table-search-top invoice-search-top">
											<a href="{{ route('user.banks') }}" class="mr-2 btn btn-primary text-white"><i class="fa fa-chevron-lef"></i> Back To Account List</a>
										</div>
									</div>
								</div>
							</div>
							
						    <form autocomplete="off" class="form-group form-border" action="{{ route('user.update.bank', $bank->id) }}"  method="post">
                                @csrf
								<div class="row">
									<div class="col-lg-6 col-md-6">
										<div class="input-space">
											<label  class="form-label">{{__('string.bankname')}}</label>
											<input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Enter Bank Name" value="{{ $bank->bank_name }}">
										</div>
									</div>
									<div class="col-lg-6 col-md-6">
										<div class="input-space">
											<label  class="form-label">{{__('string.account_number')}}</label>
											<input type="text" class="form-control" name="account_number" id="account_number" placeholder="Enter Account Number" value="{{ $bank->account_number }}">
										</div>
									</div>
									<div class="col-lg-6 col-md-6">
										<div class="input-space">
											<label  class="form-label">{{__('string.account_holder')}}</label>
											<input type="text" class="form-control" name="account_holder" id="account_holder" placeholder="Enter Account Holder" value="{{ $bank->account_holder }}">
										</div>
									</div>
									<div class="col-lg-6 col-md-6">
										<div class="input-space">
											<label  class="form-label">{{__('string.swiftcode')}}</label>
											<input type="text" class="form-control" name="swift_code" id="swift_code" placeholder="Enter Swift Code" value="{{ $bank->swift_code }}">
										</div>
									</div>
								</div>
						       <span>Note - Make sure your bank details is correct & proper to avoid delays in withdraw process.</span>

								<div class="save-changes text-end">
						     	    <button  type="submit" class="btn btn-secondary save-profile">{{__('string.save_changes')}}</button>
					        	</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    @component('components.modalpopup')
    @endcomponent
@endsection