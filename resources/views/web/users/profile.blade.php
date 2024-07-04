<?php $page = 'user-profile'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        {{__('string.user_profile')}}
        @endslot
        @slot('li_1')
            {{__('string.home')}}
        @endslot
        @slot('li_2')
        {{__('string.user_profile')}}
        @endslot
    @endcomponent
    @component('components.user-dashboard')
	@endcomponent

	<div class="content court-bg">
		<div class="container">

			<div class="coach-court-list profile-court-list">
				<ul class="nav">
					<li><a class="active" href="{{route('user-profile')}}">{{__('string.profile')}}</a></li>
					<li><a href="{{route('user-setting-password')}}">{{__('string.change_password')}}</a></li>
					<li><a href="{{route('user.banks')}}">{{__('string.withdraw_setting')}}</a></li>
				</ul>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="profile-detail-group">
						<div class="card ">
						    <form autocomplete="off" class="form-group form-border" name="editUserDetails" id="editUserDetails"  method="post">
                                @csrf
								<div class="row">
									<div class="col-md-12">
										<div class="file-upload-text">
											<div class="file-upload">
												<img src="{{$imgUrl}}" class="img-fluid" alt="Upload" style="height:85px;">
												<p>{{__('string.upload_photo')}}</p>
												<span>
													<i class="feather-edit-3"></i>
													<input type="file"  id="file-input" name="profile_image" accept=".jpg,.png,jpeg">
												</span>
											</div>
											<h5>{{__('string.upload')}}</h5>
										</div>
									</div>
										<div class="col-lg-4 col-md-6">
										<div class="input-space">
											<label  class="form-label">{{__('string.user_type')}}</label>
											<input type="text" class="form-control" name="user_type" id="user_type" readonly value="{{$user_type}}" placeholder="Enter User Type">
										</div>
									</div>
									<div class="col-lg-4 col-md-6">
										<div class="input-space">
											<label  class="form-label">{{__('string.first_name')}}</label>
											<input type="text" class="form-control" name="first_name" id="first_name" value="{{$user_details->first_name}}" placeholder="Enter First Name">
										</div>
									</div>
									<div class="col-lg-4 col-md-6">
										<div class="input-space">
											<label  class="form-label">{{__('string.last_name')}}</label>
											<input type="text" class="form-control"   name="last_name" id="last_name" value="{{$user_details->last_name}}" placeholder="Enter Last Name">
										</div>
									</div>
									<div class="col-lg-4 col-md-6">
										<div class="input-space">
											<label  class="form-label">{{__('string.email')}}</label>
											<input type="email" class="form-control" name="email" readonly id="email" value="{{$user_details->email??''}}" placeholder="Enter Email Address" readonly>
										</div>
									</div>
									<div class="col-lg-4 col-md-6">
										<div class="input-space">
											<label  class="form-label">{{__('string.phone_number')}}</label>
											<input type="text" class="form-control" id="phone_number" readonly name="phone_number"  value="{{$user_details->formated_number??''}}" placeholder="Enter Phone Number" readonly>
										</div>
									</div>
										<div class="col-lg-4 col-md-6">
										<div class="input-space">
											<label  class="form-label">{{__('string.local_id')}}</label>
											<input type="text" class="form-control" id="identity"  name="identity"  value="{{$user_details->identity??''}}" placeholder="" readonly>
										</div>
									</div>
								
								</div>
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
@endsection
