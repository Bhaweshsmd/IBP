@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/banks.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Banks') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_banks'))
                <a href="{{ route('create.bank') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> Add New Bank</a>
            @endif
            @if(has_permission(session()->get('user_type'), 'view_banks'))
                <a href="{{ route('adminWithdraws') }}" class="ml-2 btn btn-primary text-white"><i class="fa fa-chevron-left"></i> Back To Withdrawal Requests</a>
            @endif
        </div>
        <div class="card-body">
            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="activeBanks">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('User Name') }}</th>
                                    <th>{{ __('Bank Name') }}</th>
                                    <th>{{ __('Account Number') }}</th>
                                    <th>{{ __('Account Holder') }}</th>
                                    <th>{{ __('Swift Code') }}</th>
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
    
    @if(session()->has('bank_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('bank_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection