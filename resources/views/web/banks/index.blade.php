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
			
			<a href="{{ route('user.create.bank') }}" class="mr-2 btn btn-primary text-white"><i class="fa fa-plus"></i> Add New Account</a>
			
			<div class="row">
				<div class="col-sm-12">
					<div class="court-tab-content">
						<div class="card card-tableset">
							<div class="card-body">
								<div class="coache-head-blk">
									<div class="row align-items-center">
										<div class="col-lg-5">
											<div class="court-table-head">
												<h4>Banks</h4>
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
									<table class="table datatable" id="activeBanks">
										<thead class="thead-light">
											<tr>
											   <th>{{__('string.bankname')}}</th>
											   <th>{{__('string.account_number')}}</th>
											   <th>{{__('string.account_holder')}}</th>
											   <th>{{__('string.swiftcode')}}</th>
											   <th>{{__('string.action')}}</th>
											</tr>
										</thead>
										<tbody>
										    @if($banks)
											    @foreach($banks as $bank)
    												<tr>
    													<td>{{$bank->bank_name}}</td>
    													<td>{{$bank->account_number}}</td>
    													<td>{{$bank->account_holder}}</td>
    													<td>{{$bank->swift_code}}</td>
    													<td>
    													    <a href="{{ route('user.edit.bank', $bank->id) }}" class="mr-2 btn btn-primary text-white"><i class="fa fa-edit"></i></a>
                                                            <a href="" class="mr-2 btn btn-danger text-white delete" rel='{{$bank->id}}'><i class="fa fa-trash"></i></a>
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
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js" ></script>
	
	@if(session()->has('bank_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('bank_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
    
    <script>
        $("#activeBanks").on("click", ".delete", function (event) {
            event.preventDefault();
            var id = $(this).attr("rel");
            var url = `{{url('user-delete-bank')}}` + "/" + id;
            $(function () {
                swal({
                    title: "Warning",
                    text: "{{__('string.doYouReallyWantToContinue')}}",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Cancel",
                    cancelButtonText: "<a href='" + url + "'>Continue</a>",
                    closeOnConfirm: false, 
                    customClass: "Custom_Cancel"
                })
            });
        });
    </script>
    @component('components.modalpopup')
    @endcomponent
@endsection