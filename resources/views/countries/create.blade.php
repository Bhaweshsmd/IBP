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
            <h4>{{ __('Admins Details') }}</h4>
            <a href="{{ route('admins') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> Back To Admin List</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admins.store') }}" method="Post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>First Name</label>
                        <input type="text" class="form-control" name="first_name"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Last Name</label>
                        <input type="text" class="form-control" name="last_name"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>User Name</label>
                        <input type="text" class="form-control" name="user_name"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Phone</label>
                        <input type="number" class="form-control" name="phone"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Password</label>
                        <input type="password" class="form-control" name="user_password"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>User Type</label>
                        <select name="user_type" class="form-control">
                            @foreach($roles as $role)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inctive</option>
                        </select>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Picture</label>
                        <input type="file" class="form-control" name="picture"> 
                    </div>

                    <div class="form-group col-md-12 text-right">
                        <button type="submit" class="btn btn-primary" id='submitformbtn'>Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
