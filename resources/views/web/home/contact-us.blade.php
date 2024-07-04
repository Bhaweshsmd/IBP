<?php $page = 'contact-us'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
            {{__('string.contact_us')}}
        @endslot
        @slot('li_1')
            {{__('string.home')}}
        @endslot
        @slot('li_2')
            {{__('string.contact_us')}}
        @endslot
    @endcomponent
    
    <script async src="https://www.google.com/recaptcha/api.js"></script>
    
    <style>
        .swal2-popup .swal2-styled.swal2-cancel {
            background-color: #1E425E !important;
            border-color: #1E425E;
            color: #000;
            border-radius: 5rem !important;
            font-weight: bold !important;
        }
        
        .swal2-popup .swal2-styled.swal2-cancel a {
            color: #fff !important;
        }
        
        .swal2-popup .swal2-styled.swal2-confirm {
            background-color: #23d9a6 !important;
            border-color: #23d9a6;
            color: #000;
            border-radius: 5rem !important;
            font-weight: bold !important;
        }
        
        .swal2-popup .swal2-styled.swal2-confirm a {
            color: #000 !important;
        }
        
        .swal2-popup .swal2-styled:focus {
            outline: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
        }
        
        .swal2-confirm{
            border-radius: 5rem !important;
            background: black !important;
            color: #fff !important;
        }
        
        .swal2-popup{
            border-radius: 25px !important;
        }
        .contact-us-page .details{padding:24px 0px !important;}
        .displaycenter{margin:auto;}
    </style>

    <div class="content blog-details contact-group">
        <div class="container">
            <h2 class="text-center mb-40">{{__('string.welcome_to_isidel')}}</h2>
            <p class="text-center">{{__('string.we_thrilled_you')}}</p>
        </div>
    </div>

    <div class="content blog-details contact-group">
        <div class="container">
            <h4 class="text-center mb-40">{{__('string.contact_information')}}</h4>
            <div class="row mb-40">
                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="details">
                        <i class="feather-phone-call d-flex justify-content-center align-items-center displaycenter"></i>
                        <div class="info text-center mb-5 mt-4">
                            <h4>{{__('string.phone_number')}}</h4>
                            <p class="mt-4">{{$company_details->salon_phone}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="details">
                        <i class="feather-map-pin d-flex justify-content-center align-items-center displaycenter"></i>
                        <div class="info text-center mt-4">
                            <h4>{{__('string.location')}}</h4>
                            <p>Isidel Beach Park</p>
                            <p>Kaya Gobernador N.Debrot 75B</p>
                            <p>Kralendijk, Bonaire</p>
                            <p>Caribbean Netherlands </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="details">
                        <i class="feather-map-pin d-flex justify-content-center align-items-center displaycenter"></i>
                        <div class="info text-center mt-4">
                            <h4>{{__('string.location')}}</h4>
                            <p>Bonaire Overheidsgebouwen N.V.</p>
                            <p>Kaya Grandi 2</p>
                            <p>Kralendijk, Bonaire</p>
                            <p>Caribbean Netherlands</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row mb-40 mt-5">
                        <h4 class="text-center mb-40">{{__('string.email_addresses')}}</h4>
                        
                        <div class="col-sm-3">
                            <div class="details">
                                <i class="feather-mail d-flex justify-content-center align-items-center displaycenter"></i>
                                <div class="info mt-3 text-center">
                                    <p><a href="mailto:info@isidelbeachpark.com">info@isidelbeachpark.com</a></p>
                                    <h4>{{__('string.general')}}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="details">
                                <i class="feather-mail d-flex justify-content-center align-items-center displaycenter"></i>
                                <div class="info mt-3 text-center">
                                    <p><a href="mailto:a.jansen@isidelbeachpark.com">a.jansen@isidelbeachpark.com</a></p>
                                    <h4>{{__('string.management')}}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="details">
                                <i class="feather-mail d-flex justify-content-center align-items-center displaycenter"></i>
                                <div class="info mt-3 text-center">
                                    <p><a href="mailto:finance@isidelbeachpark.com">finance@isidelbeachpark.com</a></p>
                                    <h4>{{__('string.finance')}}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="details">
                                <i class="feather-mail d-flex justify-content-center align-items-center displaycenter"></i>
                                <div class="info mt-3 text-center">
                                    <p><a href="mailto:info@example.com">pr@isidelbeachpark.com </a></p>
                                    <h4>{{__('string.press')}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="section dull-bg">
            <div class="container">
                <h2 class="text-center mb-40">{{__('string.reach_out_to_us')}}</h2>
                <p class="text-center">{{__('string.feelfree_to_get')}}</p>
                <form class="contact-us" method="Post" action="{{ route('submit.contact.us') }}">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 mb-3">
                            <label for="first-name" class="form-label">{{__('string.first_name')}}</label>
                            <input type="text" class="form-control" name="firstname" placeholder="{{__('string.enter_first_name')}}">
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 mb-3">
                            <label for="last-name" class="form-label">{{__('string.last_name')}}</label>
                            <input type="text" class="form-control" name="lastname" placeholder="{{__('string.enter_last_name')}}">
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 mb-3">
                            <label for="e-mail" class="form-label">{{__('string.email')}}</label>
                            <input type="text" class="form-control" name="email" placeholder="{{__('string.enter_email')}}">
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 mb-3">
                            <label for="phone" class="form-label">{{__('string.phone_number')}}</label>
                            <input type="text" class="form-control" name="phone" placeholder="{{__('string.enter_phone_number')}}">
                        </div>
                    </div>
                    <div>
                        <label for="comments" class="form-label">{{__('string.message')}}</label>
                        <textarea class="form-control" name="comments" rows="3" placeholder="{{__('string.enter_message')}}"></textarea>
                    </div>
                    <div class="g-recaptcha mt-4" data-sitekey={{$settings->recaptcha_key}}></div>
                    <button type="submit" class="btn btn-secondary d-flex align-items-center">{{__('string.submit')}}<i class="feather-arrow-right-circle ms-2"></i></button>
                </form>
            </div>
        </section>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('asset/cdnjs/sweetalert.min.js') }}"></script>
    <script src="https://unpkg.com/sweetalert2@7.8.2/dist/sweetalert2.all.js"></script>
    
    @if(session()->has('contact_success_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('contact_success_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
    
    @if(session()->has('contact_fail_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Warning",
                    text: "{{ session()->get('contact_fail_message') }}",
                    type: "warning",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
