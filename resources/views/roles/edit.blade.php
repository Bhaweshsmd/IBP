@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/roles.js') }}"></script>
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
            <h4>{{ __('Role Details') }} :</h4>
            <a href="{{ route('roles') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> {{ __('Back to Roles List') }}</a>
        </div>
        <div class="card-body">
            <form action="{{ route('roles.update', $result->id) }}" method="Post">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Name</label>
                        <input type="text" value="{{ $result->name }}" class="form-control" name="name"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Display Name</label>
                        <input type="text" value="{{ $result->display_name }}" class="form-control" name="display_name"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Description</label>
                        <input type="text" value="{{ $result->description }}" class="form-control" name="description"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" @if($result->status == '1') selected @endif>Active</option>
                            <option value="0" @if($result->tatus == '0') selected @endif>Inctive</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Permissions</th>
                                        <th>View</th>
                                        <th>Add</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $i=0;
                                    @endphp

                                    @foreach ($permissions as $key => $value)

                                        <tr data-rel="{{$i}}">
    
                                            <td>{{ $key }}</td>
    
                                            @foreach ($value as $i => $v)
                                                <input type="hidden" value="{{ $v['user_type'] }}" name="user_type" id="user_type">
                                                <input type="hidden" value="{{ $result->id }}" name="id" id="id">
    
                                                @if (isset($v['display_name']))
                                                    <td>
                                                        <label class="checkbox-container">
                                                            <input type="checkbox" name="permission[]" id="{{'view_'.$i}}" value="{{ $v['id'] }}"  {{ in_array( $v['id'], $stored_permissions) ? 'checked' : '' }} class="{{ ($i % 4 == 0) ? 'view_checkbox' :'other_checkbox' }}">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
                                            @endforeach
                                        </tr>

                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                            <div id="error-message"></div>
                        </div>
                    </div>
                    <div class="form-group col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
