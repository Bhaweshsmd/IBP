<?php $page = 'otp-verify'; ?>
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
        
        #verify_now{
            float:right;
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
                                    <h2>{{__('string.verify_otp')}}</h2>
                                    <p>{{__('string.enter_otp_sent')}}</p>
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
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
                                                            <button class="btn btn-primary btn-block" id="verify_now" type="submit">{{__('string.verify_now')}}</button>
                                                            <p id="timer"></p>
                                                            <a href="javascript::void(0)"  id="resendBtn"><b>Resend OTP</b></a>
                                                        </div>
                                                    </div>
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
        </div>
    </div>
@endsection
