<?php $page = 'login'; ?>
@extends('layout.mainlayout')
@section('content')

    <style>
        .fa-solid{
            color:#ffffff !important;
        }
        
        .txtcolor{
            color:#1E425E !important;
            font-size:25px !important;
        }
        
        .shadow-card{
            padding:30px !important;
        }
        
        h2 {
            text-align:center;
            font-size:25px;
        }
        
        .authendication-pages .content form .btn-block {
            margin: 16px 0 12px 0;
        }
        .authendication-pages .content form .form-check.form-switch .form-check-input:checked {
            background-color: #e8f0fe;
        }
        
        @media screen and (max-width: 500px){
        .backtohome{
                position: relative;
                left: 65%;
                color: #097E52;
            }
        }
        @media screen and (min-width: 768px){
            .backtohome{
                position: relative;
                left: 79%;
                color: #097E52;
            }
        }
    </style>

    <div class="content">
        <div class="container wrapper no-padding">
            <div class="row no-margin vph-100">
                <div class="col-12 col-sm-12 col-lg-6 no-padding">
                    <div class="banner-bg login">
                        <div class="row no-margin h-100">
                            <div class="col-sm-10 col-md-10 col-lg-10 mx-auto">
                                <div class="h-100 d-flex justify-content-center align-items-center">
                                    <div class="text-bg register text-center">
                                        <div class="btn btn-limegreen"><a href="{{ url('/') }}">
                                        <img src="{{ URL::asset('/assets/img/isidel.png') }}" class="img-fluid" 
                                            alt="Logo" style="max-width: 40%;">
                                    </a></div>
                                        <p class="txtcolor"><strong>{{__('string.login_right_away')}}</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12  col-lg-6 no-padding">
                    <div class="dull-pg">
                        <div class="row no-margin vph-100 d-flex align-items-center justify-content-center">
                            <div class="col-sm-10 col-md-10 col-lg-10 mx-auto">
                                <div class="shadow-card mt-4">
                                    <h2>{{__('string.welcome_back')}}</h2>
                                    <p style="text-align:center">{{__('string.login_into_your_account')}}</p>
                                     @if (\Session::has('message'))
                                        @if (\Session::get('status')) 
                                        <div class="alert alert-success">
                                        @else
                                        <div class="alert alert-danger">
                                        @endif
                                        <ul>
                                       <li>{!! \Session::get('message') !!}</li>
                                      </ul>
                                     @endif 
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="user" role="tabpanel" aria-labelledby="user-tab">
                                            <form action="{{route('user.login')}}" method="post">
                                                @csrf
                                                <div class="form-group mb-3 mt-3">
                                                    <div class="group-img">
                                                        <i class="feather-user"></i>
                                                        <input type="text" class="form-control" name="email"
                                                            placeholder="{{__('string.email')}}">
                                                    </div>
                                                </div>
                                                <div class="form-group form-group mb-3 mt-3">
                                                    <div class="pass-group group-img">
                                                        <i class="toggle-password feather-eye-off"></i>
                                                        <input type="password" class="form-control pass-input"
                                                            placeholder="{{__('string.password')}}" name="password">
                                                    </div>
                                                </div>
                                                <div
                                                    class="form-group d-sm-flex align-items-center justify-content-between">
                                                    <div
                                                        class="form-check form-switch d-flex align-items-center justify-content-start">
                                                        <input class="form-check-input" type="checkbox" name="remember_token" id="user-pass" value="1">
                                                        <label class="form-check-label" for="user-pass">{{__('string.remember_password')}}</label>
                                                    </div>
                                                    <span><a href="{{ url('forgot-password') }}" class="forgot-pass">{{__('string.forgot_password')}}</a></span>
                                                           
                                                </div>
                                                <button class="btn btn-secondary register-btn d-inline-flex justify-content-center align-items-center w-100 btn-block" type="submit">{{__('string.sign_in')}}
                                                    <i class="feather-arrow-right-circle ms-2"></i>
                                                </button>
                                                <a class="text-sucess backtohome" href="{{ url('/') }}">{{__('Back to home')}}</a>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="coach" role="tabpanel" aria-labelledby="coach-tab">
                                            <form action="index">
                                                <div class="form-group">
                                                    <div class="group-img">
                                                        <i class="feather-user"></i>
                                                        <input type="text" class="form-control"
                                                            placeholder="Email / Username">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="pass-group group-img">
                                                        <i class="toggle-password feather-eye-off"></i>
                                                        <input type="password" class="form-control pass-input"
                                                            placeholder="Password">
                                                    </div>
                                                </div>
                                                <div
                                                    class="form-group d-sm-flex align-items-center justify-content-between">
                                                    <div
                                                        class="form-check form-switch d-flex align-items-center justify-content-start">
                                                        <input class="form-check-input" type="checkbox" name="remember_token"  id="user-login">
                                                        <label class="form-check-label" for="user-login">{{__('string.remember_password')}}</label>
                                                    </div>
                                                    <span><a href="{{ url('forgot-password') }}"
                                                            class="forgot-pass">{{__('string.forgot_password')}}</a></span>
                                                </div>
                                                <button
                                                    class="btn btn-secondary register-btn d-inline-flex justify-content-center align-items-center w-100 btn-block"
                                                    type="submit">{{__('string.sign_in')}}<i
                                                        class="feather-arrow-right-circle ms-2"></i></button>
                                                <div class="form-group">
                                                    <div class="login-options text-center">
                                                        <span class="text">{{__('string.continue_with')}}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <ul
                                                        class="social-login d-flex justify-content-center align-items-center">
                                                        <li class="text-center">
                                                            <button type="button"
                                                                class="btn btn-social d-flex align-items-center justify-content-center">
                                                                <img src="{{ URL::asset('/assets/img/icons/google.svg') }}"
                                                                    class="img-fluid" alt="Google">
                                                                <span>{{__('string.google')}}</span>
                                                            </button>
                                                        </li>
                                                        <li class="text-center">
                                                            <button type="button"
                                                                class="btn btn-social d-flex align-items-center justify-content-center">
                                                                <img src="{{ URL::asset('/assets/img/icons/facebook.svg') }}"
                                                                    class="img-fluid" alt="Facebook">
                                                                <span>{{__('string.facebook')}}</span>
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="bottom-text text-center">
                                    <p>{{__('string.dont_have_an_account')}} <a href="{{ url('register') }}">{{__('string.sign_up')}}</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
