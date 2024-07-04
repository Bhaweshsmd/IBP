@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/banks.js') }}"></script>
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
            <h4>{{ __('Bank Details') }}</h4>
            <a href="{{ route('banks') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> Back To Bank List</a>
        </div>
        <div class="card-body">
            <form action="{{ route('store.bank') }}" method="Post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>User Name</label>
                        <input type="text" class="form-control" value="{{ $admin_detials->first_name }} {{ $admin_detials->last_name }}" readonly> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Bank Name</label>
                        <input type="text" class="form-control" name="bank_name"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Account Number</label>
                        <input type="text" class="form-control" name="account_number"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Account Holder</label>
                        <input type="text" class="form-control" name="account_holder"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Swift Code</label>
                        <input type="text" class="form-control" name="swift_code"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inctive</option>
                        </select>
                    </div>

                    <div class="form-group col-md-12 text-right">
                        <button type="submit" class="btn btn-primary" id='submitformbtn'>Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
