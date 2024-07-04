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
            <form action="{{ route('users.update', $user->id) }}" method="Post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Customer Type<span class="text-danger">*</span></label>
                        @if($user->user_type == '0')
                            <input type="text" class="form-control" value="Bonaire Resident" readonly>
                        @else
                            <input type="text" class="form-control" value="Non-Resident" readonly> 
                        @endif
                        <input type="hidden" class="form-control" name="user_type" value="{{$user->user_type}}"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        @if($user->user_type == '0')
                            <label>Local ID Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="local_number" value="{{$user->identity}}"> 
                        @else
                            <label>Passport Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="passport_number" value="{{$user->identity}}"> 
                        @endif
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>First Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="first_name" value="{{$user->first_name}}"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Last Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="last_name" value="{{$user->last_name}}"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Email<span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" value="{{$user->email}}"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <label>Phone Code<span class="text-danger">*</span></label>
                                <select class="form-control" id="country" name="country" value="" required="" style="border-radius: 10px 0px 0px 10px; border-right: 1px solid #e3e3e3;">
                                    <option data-selectCountry="selectcountry" select="disabled">Select Country</option>
                                    @foreach($countries as $country)
                                        <option data-countryCode="{{$country->short_name}}" value="+{{$country->phone_code}}" @if($country->phone_code == $user->country_code) selected @endif>{{$country->name}} (+{{$country->phone_code}})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-7">
                                <label>Phone<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="phone" value="{{$user->phone_number}}"> 
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Password<span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Status<span class="text-danger">*</span></label>
                        <select name="is_block" class="form-control">
                            <option value="1" @if($user->is_block == '1') selected @endif>Block</option>
                            <option value="0" @if($user->is_block == '0') selected @endif>Active</option>
                        </select>
                    </div>
                     
                    <div class="form-group col-md-6">
                        <label>Profile Picture</label> <small>(optional)</small>
                        <input type="file" class="form-control" name="profile_image"> 
                        
                        <?php
                            use App\Models\GlobalFunction;
                            if (!empty($user->profile_image)) {
                                $imgUrl = GlobalFunction::createMediaUrl($user->profile_image);
                                $image = '<img src="' . $imgUrl . '" width="100" height="auto">';
                                echo $image;
                            }
                        ?>
                    </div>
                    
                    @if(has_permission(session()->get('user_type'), 'edit_user'))
                        <div class="form-group col-md-12 text-right">
                            <button type="submit" class="btn btn-primary" id='submitformbtn'>Update</button>
                        </div>
                    @endif
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
@endsection
