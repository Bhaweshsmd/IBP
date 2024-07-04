<?php $page = 'account-verification'; ?>
@extends('layout.mainlayout')
@section('content')

    <style>
        header.header-sticky {
            display:none;
        }
        
        .footer {
            display:none;
        }
        
        .content {
            background-image: url(../assets/img/bg/otp-background.png);
            background-repeat: no-repeat;
            background-size: cover;
            padding: 0px 0;
        }
        
        .backtohome {
            position: relative;
            left: 79%;
            color: #097E52;
            top: 11px;
        }
    </style>

     <div class="content">
        <div class="container wrapper no-padding">
            <div class="row no-margin vph-100">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 no-padding">
                    <div class="dull-pg">
                        <div class="row no-margin vph-100 d-flex align-items-center justify-content-center">
                            <div class="col-sm-12 col-md-12 col-lg-6 mx-auto">
                                <header class="text-center">
                                    <a href="{{ url('/') }}">
                                        <img src="{{ URL::asset('/assets/img/isidel.png') }}" class="img-fluid" alt="Logo" style="max-width: 25%;margin-top: -60px">
                                    </a>
                                </header>
                                <div class="shadow-card">
                                    @if(!Session::get('otp-sent'))  
                                        <h3>{{__('string.mobile_no_not')}}</h3>
                                        <p>{{__('string.please_verify_mobile')}} <b>({{$userDetails['formated_number']??''}}) </b> {{__('string.to_continue_account')}}</p>
                                        <a href="{{route('account-verification-otp')}}" class="btn btn-secondary w-100 d-inline-flex justify-content-center align-items-center" type="submit">
                                           {{__('string.send_otp')}} <i class="feather-arrow-right-circle ms-2"></i>
                                        </a>
                                        <a class="text-sucess backtohome" href="{{route('user.logout')}}">{{__('string.back_home')}}</a>
                                    @endif
                                    
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    
                                    @if(Session::get('otp-sent'))            
                                        <form class="tab-content" method="post">
                                            @csrf
                                            <div class="tab-pane fade show active" id="user" role="tabpanel" aria-labelledby="user-tab">
                                                <div id="otpverification">
                                                    <div class="form-group">
                                                        <div class="group-img">
                                                            <input type="text" class="form-control" id="otp" name="otp" value="{{old('otp')}}" placeholder="{{__('string.enter_otp')}}" required></br>
                                                        </div>
                                                    </div>
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button class="btn btn-primary btn-block" type="submit" style="float:right">{{__('string.verify_now')}}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endif      
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
