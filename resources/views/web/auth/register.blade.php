<?php $page = 'register'; ?>
@extends('layout.mainlayout')
@section('content')

    <style>
        h2 {
            text-align:center;
            font-size:24px;
        }
        
        .col-md-5 {
               padding-right: 0px;
        }
        
        .col-md-7 {
               padding-left: 0px;
        }
        
        .form-control {
                padding: 23px !important;
        }
        
        @media screen and (min-width: 780px) {
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                height: 60px;
                line-height: 55px;
                font-size: 16px;
                border: none;
                background: #FAFAFA;
                border-radius: 10px 0px 0px 10px;
            }
            
            .mobradius{
                border-radius: 0px 10px 10px 0px !important;
            }
        }
        
        @media screen and (max-width: 768px) {
            span.select2.select2-container.select2-container--default {
               width: -webkit-fill-available !important;
            }
            
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                border-radius: 10px 10px 10px 10px !important;
            }
        }
        
        span.select2.select2-container.select2-container--default{
            width: -webkit-fill-available !important;
        }

        .fa-solid{
            color:#ffffff !important;
        }
        
        .txtcolor{
            color:#1E425E !important;font-size: 25px !important;
        }
        
        .authendication-pages .content form .btn-block {
            margin: 16px 0 12px 0;
        }
        
        @media screen and (min-width: 768px) {
            .backtohome{
                position: relative;
                left: 79%;
                color: #097E52;
            }
        }
        @media screen and (max-width: 500px) {
            .backtohome{
                position: relative;
                left: 65%;
                color: #097E52;
            }
        }

        .authendication-pages .content form .form-group{
            margin-bottom: 20px !important;
        }
        
        @media screen and (max-width: 480px) {
            .spacegap{
                margin-top: 2px !important;
            }
            
            .mobradius{
                border-radius: 10px 10px 10px 10px !important;
            }
        }
    
        @media screen and (min-width: 768px) {
            .spacegap{
                margin-top: 0px !important;
            }
        }
    
        @media only screen and (min-width: 768px) {
            .col-md-5.mt-lg-0.mt-3.spacegap{
                /*width: -webkit-fill-available !important;*/
                width: fill-available !important;
            }
        }
    </style>

    <div class="content mt-lg-0 mt-5">
        <div class="container wrapper no-padding">
            <div class="row no-margin vph-100">
                <div class="col-12 col-sm-12 col-md-12 col-lg-6 no-padding">
                    <div class="banner-bg register">
                        <div class="row no-margin h-100">
                            <div class="col-sm-10 col-md-10 col-lg-10 mx-auto">
                                <div class="h-100 d-flex justify-content-center align-items-center">
                                    <div class="text-bg register text-center">
                                        <div class="btn btn-limegreen">
                                            <a href="{{ url('/') }}">
                                                <img src="{{ URL::asset('/assets/img/isidel.png') }}" class="img-fluid" alt="Logo" style="max-width: 40%;">
                                            </a>
                                        </div>
                                        <p class="txtcolor"><strong>{{__('string.register_now_for_membership')}}</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-6 no-padding">
                    <div class="dull-pg">
                        <div class="row no-margin vph-100 d-flex align-items-center justify-content-center">
                            <div class="col-sm-10 col-md-10 col-lg-10 mx-auto">
                                <div class="shadow-card">
                                    <h2>{{__('string.get_started_with')}}</h2>

                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active d-flex align-items-center" id="user-tab"
                                                data-bs-toggle="tab" data-bs-target="#user" type="button" role="tab"
                                                aria-controls="user" aria-selected="true">
                                                <span class="d-flex justify-content-center align-items-center"></span>{{__('string.i_am_bonaire_resident')}}
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link d-flex align-items-center" id="coach-tab"
                                                data-bs-toggle="tab" data-bs-target="#coach" type="button" role="tab"
                                                aria-controls="coach" aria-selected="false">
                                                <span class="d-flex justify-content-center align-items-center"></span>{{__('string.i_am_Non_Resident')}}
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="user" role="tabpanel" aria-labelledby="user-tab">
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <form action="{{route('otp-verify')}}" method="post" autocomplete="on">
                                                @csrf
                                                <input type="hidden" class="form-control" name="user_type" id="user_type" value="{{old('user_type',0)}}" placeholder="User type" required> 
                                                <div class="form-group mt-3 mb-3">
                                                    <div class="group-img">
                                                        <i class="feather-user"></i>
                                                        <input type="text" class="form-control" name="first_name" value="{{old('first_name')}}" placeholder="{{__('string.first_name')}}" required>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3 mb-3">
                                                    <div class="group-img">
                                                        <i class="feather-user"></i>
                                                        <input type="text" class="form-control" name="last_name" value="{{old('last_name')}}" placeholder="{{__('string.last_name')}}" required>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3 mb-3">
                                                    <div class="group-img">
                                                        <i class="feather-mail"></i>
                                                        <input type="text" class="form-control" name="email" value="{{old('email')}}"  placeholder="{{__('string.email')}}" required>
                                                    </div>
                                                </div>
                                                <div class="row form-group mt-lg-3">
                                                    <div class="col-md-5 mt-lg-0 mt-3 spacegap">
                                                        <select class="form-control" id="country" name="country" value="" required="" style="border-radius: 10px 10px 10px 10px; border-right: 1px solid #e3e3e3;">
                                                	        <option data-selectCountry="selectcountry" value="{{old('country')}}" select="disabled">{{__('string.select_country')}}</option>
                                                	        @foreach($countries as $country)
                                                	             <option data-countryCode="{{$country->short_name}}" value="+{{$country->phone_code}}" >(+{{$country->phone_code}}) {{$country->name}}</option>
                                                	     	@endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-7 mt-lg-0 mt-3 mx-lg-0 spacegap">
                                                        <div class="group-img">
                                                        <i class="feather-phone"></i>
                                                        <input type="text " class="form-control mobradius" name="phone_number" value="{{old('phone_number')}}" placeholder="{{__('string.mobile_number')}}" required >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3 mt-lg-0 mt-3">
                                                    <div class="group-img">
                                                        <i class="feather-user"></i>
                                                        <input type="text" class="form-control" name="identity" value="{{old('identity')}}" placeholder="{{__('string.local_id')}}" required>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3 mb-3">
                                                    <div class="pass-group group-img">
                                                        <i class="toggle-password feather-eye-off"></i>
                                                        <input type="password" name="password" class="form-control pass-input" placeholder="{{__('string.password')}}"  value="{{old('password')}}" required>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3 mb-3">
                                                    <div class="pass-group group-img">
                                                        <i class="toggle-password-confirm feather-eye-off"></i>
                                                        <input type="password"  name="confirm_password" class="form-control pass-confirm" placeholder="{{__('string.confirm_password')}}" value="{{old('confirm_password')}}" required>
                                                    </div>
                                                </div>
                                                <div
                                                    class="form-check d-flex justify-content-start align-items-center policy">
                                                    <div class="d-inline-block">
                                                        <input class="form-check-input" type="checkbox" value="" id="policy" required>
                                                    </div>
                                                    <label class="form-check-label" for="policy">{{__('string.by_continuing_you_indicate')}} <a href="{{ url('terms-condition') }}"> {{__('string.terms_of_use')}}</a></label>
                                                </div>
                                                <button class="btn btn-secondary register-btn d-inline-flex justify-content-center align-items-center w-100 btn-block" type="submit">{{__('string.create_account')}}
                                                    <i class="feather-arrow-right-circle ms-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="coach" role="tabpanel" aria-labelledby="coach-tab">
                                            <form action="{{route('otp-verify')}}" method="post">
                                                @csrf
                                                <input type="hidden" class="form-control" name="user_type" id="user_type" value="{{old('user_type',1)}}" placeholder="User type" required> 
                                                <div class="form-group mt-3 mb-3">
                                                    <div class="group-img">
                                                        <i class="feather-user"></i>
                                                        <input type="text" class="form-control" name="first_name" value="{{old('first_name')}}" placeholder="{{__('string.first_name')}}" required>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3 mb-3">
                                                    <div class="group-img">
                                                        <i class="feather-user"></i>
                                                        <input type="text" class="form-control" name="last_name" value="{{old('last_name')}}" placeholder="{{__('string.last_name')}}" required>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3 mb-3">
                                                    <div class="group-img">
                                                        <i class="feather-mail"></i>
                                                        <input type="text" class="form-control" name="email" value="{{old('email')}}"  placeholder="{{__('string.email')}}" required>
                                                    </div>
                                                </div>
                                                <div class="row form-group mt-lg-3">
                                                    <div class="col-md-5 mt-lg-0 mt-3">
                                                        <select class="form-control" id="countries" name="country" value="" required="" style="border-radius: 10px 10px 10px 10px; border-right: 1px solid #e3e3e3;">
                                                	        <option data-selectCountry="selectcountry" value="{{old('country')}}" select="disabled">{{__('string.select_country')}}</option>
                                                	        @foreach($countries as $country)
                                                	             <option data-countryCode="{{$country->short_name}}" value="+{{$country->phone_code}}"> (+{{$country->phone_code}})  {{$country->name}}</option>
                                                	     	@endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-7 mt-lg-0 mt-3 mx-lg-0 spacegap">
                                                        <div class="group-img">
                                                        <i class="feather-phone"></i>
                                                        <input type="text " class="form-control mobradius" name="phone_number" value="{{old('phone_number')}}" placeholder="{{__('string.mobile_number')}}" required >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3 mt-lg-0 mt-3">
                                                    <div class="group-img">
                                                        <i class="feather-user"></i>
                                                        <input type="text" class="form-control" name="identity" value="{{old('identity')}}" placeholder="{{__('string.passport_number')}}" required>
                                                    </div>
                                                </div>
                                             
                                                <div class="form-group mt-3 mb-3">
                                                    <div class="pass-group group-img">
                                                        <i class="toggle-password feather-eye-off"></i>
                                                        <input type="password" name="password" class="form-control pass-input" placeholder="{{__('string.password')}}"  value="{{old('password')}}" required>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3 mb-3">
                                                    <div class="pass-group group-img">
                                                        <i class="toggle-password-confirm feather-eye-off"></i>
                                                        <input type="password"  name="confirm_password" class="form-control pass-confirm" placeholder="{{__('string.confirm_password')}}" value="{{old('confirm_password')}}" required>
                                                    </div>
                                                </div>
                                                <div
                                                    class="form-check d-flex justify-content-start align-items-center policy">
                                                    <div class="d-inline-block">
                                                        <input class="form-check-input" type="checkbox" value="" id="policy" required>
                                                    </div>
                                                    <label class="form-check-label" for="policy">{{__('string.by_continuing_you_indicate')}} <a href="{{ url('terms-condition') }}">{{__('string.terms_of_use')}}</a></label>
                                                </div>
                                                <button class="btn btn-secondary register-btn d-inline-flex justify-content-center align-items-center w-100 btn-block" type="submit">{{__('string.create_account')}}
                                                    <i class="feather-arrow-right-circle ms-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <a class="text-sucess backtohome" href="{{ url('/') }}">{{__('Back to home')}}</a>
                                </div>
                                <div class="bottom-text text-center">
                                    <p>{{__('string.have_an_account')}} <a href="{{ url('login') }}">{{__('string.signin')}}</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
