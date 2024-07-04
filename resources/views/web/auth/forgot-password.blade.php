<?php $page = 'forgot-password'; ?>
@extends('layout.mainlayout')
@section('content')
 <style>
        .col-md-5 {
            padding-right: 0px;
        }
        
        .col-md-7 {
            padding-left: 0px;
        }
        
        .form-control {
            padding: 18px !important;
            margin: 0px 10px;
        }
        
        .content.blur-ellipses{background-image: url(../assets/img/bg/otp-background.png);
            background-repeat: no-repeat;
            background-size: cover;
            padding: 0px 0;
        }
    </style>
    
    <div class="content blur-ellipses">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-6 mx-auto vph-100 d-flex align-items-center">
                    <div class="forgot-password w-100">
                        <header class="text-center forgot-head-title">
                            <a href="{{ url('/') }}">
                                 <img src="{{ URL::asset('/assets/img/isidel.png') }}" class="img-fluid" alt="Logo" style="max-width: 127px;" >
                            </a>
                        </header>
                        <div class="shadow-card">
                            <h2>{{__('string.forgot_password')}}</h2>
                            <p>{{__('string.enter_registered_phone')}}</p>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form method="post">
                                @csrf
                                    <div class="row form-group">
                                        <div class="col-md-5">
                                            <select class="form-control" id="country" name="country" value="" required="" style="border-radius: 10px 0px 0px 10px; border-right: 1px solid #e3e3e3;">
                                    	        <option data-selectCountry="selectcountry" value="{{old('country')}}" select="disabled">{{__('string.select_country')}}</option>
                                                @foreach($countries as $country)
                                    	             <option data-countryCode="{{$country->short_name}}" value="+{{$country->phone_code}}">(+{{$country->phone_code}}) {{$country->name}} </option>
                                    	     	@endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" name="phone_number" value="{{old('phone_number')}}" placeholder="{{__('string.mobile_number')}}" required style="    border-radius: 0px 10px 10px 0px;">
                                        </div>
                                    </div>
                                <button class="btn btn-secondary w-100 d-inline-flex justify-content-center align-items-center" type="submit">{{__('string.submit')}}
                                    <i class="feather-arrow-right-circle ms-2"></i>
                                </button>
                            </form>
                        </div>
                        <div class="bottom-text text-center">
                            <p>{{__('string.remember_password')}} <a href="{{ url('login') }}">{{__('string.signin')}}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
