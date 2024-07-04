<?php $page = 'user-setting-password'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
       Change Password
        @endslot
        @slot('li_1')
            Home
        @endslot
        @slot('li_2')
       Change Password
        @endslot
    @endcomponent
    @component('components.user-dashboard')
	@endcomponent

	<div class="content court-bg">
		<div class="container">

			<div class="coach-court-list profile-court-list">
				<ul class="nav">
					<li><a  href="{{route('user-profile')}}">{{__('string.profile')}}</a></li>
					<li><a class="active" href="{{route('user-setting-password')}}">{{__('string.change_password')}}</a></li>
					<li><a href="{{route('user.banks')}}">{{__('string.withdraw_setting')}}</a></li>
				</ul>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="profile-detail-group">
						<div class="card ">
							<form class="form-group form-border" name="updatePassword" id="updatePassword"  method="post" >
							    @csrf
								<div class="row">
									<div class="col-lg-7 col-md-7">
										<div class="input-space">
											<label  class="form-label">Old Password</label>
											<input type="text" class="form-control" id="password" name="old_password" placeholder="Enter Old Password">
										</div>
									</div>
									<div class="col-lg-7 col-md-7">
										<div class="input-space">
											<label  class="form-label">New Password</label>
											<input type="text" class="form-control" name="new_password" id="new_password" placeholder="Enter New Password">
										</div>
									</div>
									<div class="col-lg-7 col-md-7">
										<div class="input-space mb-0">
											<label  class="form-label">Confirm Password</label>
											<input type="text" class="form-control" name="confirm_password" id="confirm_password" placeholder="Enter Confirm Password">
										</div>
									</div>
									<div class="col-lg-7 col-md-7">
										<div class="save-changes text-end mt-3">
    							        	<button  type="submit" class="btn btn-secondary save-profile">Save Change</button>
    						        	</div>
						        	</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection