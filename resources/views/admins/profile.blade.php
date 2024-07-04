@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/admins.js') }}"></script>
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
            <h4>{{ $admin->first_name }} {{$admin->last_name}} <span class="badge badge-primary">{{ $role->name }}</span></h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admins.profile.update', $admin->user_id) }}" method="Post" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>First Name</label>
                        <input type="text" class="form-control" name="first_name" value="{{$admin->first_name}}"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Last Name</label>
                        <input type="text" class="form-control" name="last_name" value="{{$admin->last_name}}"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>User Name</label>
                        <input type="text" class="form-control" name="user_name" value="{{$admin->user_name}}"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="{{$admin->email}}"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Phone</label>
                        <input type="number" class="form-control" name="phone" value="{{$admin->phone}}"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1"  @if($admin->status == '1') selected @endif>Active</option>
                            <option value="0"  @if($admin->status == '0') selected @endif>Inctive</option>
                        </select>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Picture</label>
                        <input type="file" class="form-control" name="picture"> 
                        
                        <?php
                            use App\Models\GlobalFunction;
                            if(!empty($admin->picture)){
                                $imgUrl = GlobalFunction::createMediaUrl($admin->picture);
                                $image = '<img src="' . $imgUrl . '" width="100px" height="auto">';
                                echo $image;
                            }
                        ?>
                    </div>

                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h6 class="text-dark">{{ __('Update Password') }}</h6>
        </div>
        <div class="card-body">
            <form Autocomplete="off" class="form-group form-border" id="adminpassword" action="" method="post">
                @csrf
                <div class="">
                    <span>To change the password: Enter the password below and click on save.</span>
                </div>
                <div class="form-row mt-3">
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Old Password') }}</label>
                        <input type="password" class="form-control" name="old_password" value="" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">{{ __('New Password') }}</label>
                        <input type="password" class="form-control" name="new_password" value="" required>
                    </div>

                </div>
                <div class="form-group-submit">
                    <button class="btn btn-primary " type="submit">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
