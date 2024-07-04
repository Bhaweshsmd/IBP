@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/users.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/style/viewService.css') }}">
    <style>
        #map {
            height:500px;
        }
        #modaldialog {
            max-width: 100% !important;
        }
        
        .card .card-header .form-control{
            height: auto !important;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>{{ __('Customer Details') }}</h4>
            <a href="{{ route('users') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> Back To Customer List</a>
        </div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="Post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Customer Type<span class="text-danger">*</span></label>
                        <select name="user_type" class="form-control" id="getFname" onchange="admSelectCheck(this);" required>
                            <option value="0" id="samOption">Bonaire Resident</option>
                            <option value="1" id="admOption">Non-Resident</option>
                        </select>
                    </div>
                    
                    <div class="form-group col-md-6" id="samDivCheck">
                        <label>Local ID Number<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="local_number">  
                    </div>
                    
                    <div class="form-group col-md-6" id="admDivCheck" style="display:none;">
                        <label>Passport Number<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="passport_number"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>First Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="first_name" required> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Last Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="last_name" required> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Email<span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" required> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <label>Phone Code<span class="text-danger">*</span></label>
                                <select class="form-control" id="country" name="country" value="" required="" style="border-radius: 10px 0px 0px 10px; border-right: 1px solid #e3e3e3;">
                                    <option data-selectCountry="selectcountry" select="disabled">Select Country</option>
                                    @foreach($countries as $country)
                                        <option data-countryCode="{{$country->short_name}}" value="+{{$country->phone_code}}" >{{$country->name}} (+{{$country->phone_code}})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-7">
                                <label>Phone<span class="text-danger">*</span></label>
                                <input oninput="this.value=this.value.slice(0,this.maxLength)" type="number" maxlength="13" minlength="5" name="phone" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Status<span class="text-danger">*</span></label>
                        <select name="is_block" class="form-control" required>
                            <option value="0">Active</option>
                            <option value="1">Block</option>
                        </select>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Profile Picture</label> <small>(optional)</small>
                        <input type="file" class="form-control" name="profile_image"> 
                    </div>

                    <div class="form-group col-md-12 text-right">
                        <button type="submit" class="btn btn-primary" id='submitformbtn'>Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function admSelectCheck(nameSelect)
        {
            if(nameSelect){
                admOptionValue = document.getElementById("admOption").value;
                if(admOptionValue == nameSelect.value){
                    document.getElementById("admDivCheck").style.display = "block";
                }
                else{
                    document.getElementById("admDivCheck").style.display = "none";
                }
        
                samOptionValue = document.getElementById("samOption").value;
                if(samOptionValue == nameSelect.value){
                    document.getElementById("samDivCheck").style.display = "block";
                }
                else{
                    document.getElementById("samDivCheck").style.display = "none";
                }
            }
            else{
                document.getElementById("admDivCheck").style.display = "none";
                document.getElementById("samDivCheck").style.display = "none";
            }
        }
    </script>
    
    @if(session()->has('user_error_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Warning",
                    text: "{{ session()->get('user_error_message') }}",
                    type: "warning",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
