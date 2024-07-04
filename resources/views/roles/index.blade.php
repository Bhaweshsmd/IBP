@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/roles.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Manage Roles') }}</h4>
            <a href="{{ route('roles.create') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> Add New Role</a>
        </div>
        <div class="card-body">
            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="activeRoles">
                            <thead>
                                <tr>
                                    <th>{{ __('S.No.') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Display Name') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Status') }}</th>
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
    
    @if(session()->has('roles_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('roles_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection