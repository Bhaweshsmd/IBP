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
            <h4>{{ __('Admin Details') }}</h4>
            <a href="{{ route('admins') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> Back To Admin List</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admins.update', $admin->user_id) }}" method="Post" enctype="multipart/form-data" onsubmit='disableformButton()'>
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
                        <label>Password</label>
                        <input type="password" class="form-control" name="user_password"> 
                    </div>
                    
                    @if($admin->user_type == '1')
                        <input type="hidden" value="1" name="user_type" class="form-control">
                    @else
                        <div class="form-group col-md-6">
                            <label>User Type</label>
                            <select name="user_type" class="form-control">
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}" @if($admin->user_type == $role->id) selected @endif>{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    
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
                    
                    @if(has_permission(session()->get('user_type'), 'edit_admin'))
                        <div class="form-group col-md-12 text-right">
                            <button type="submit" class="btn btn-primary" id='submitformbtn'>Update</button>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
