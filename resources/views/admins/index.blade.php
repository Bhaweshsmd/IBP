@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/admins.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Admins') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_admin'))
                <a href="{{ route('admins.create') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> Add New Admin</a>
            @endif
        </div>
        <div class="card-body">
            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="activeAdmins">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Profile') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('User Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Group') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if(session()->has('admin_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('admin_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection