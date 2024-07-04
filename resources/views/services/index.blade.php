@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/services.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Services') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_services'))
                <a href="{{route('addService')}}" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> Add New Service</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="servicesTable">
                    <thead>
                        <tr>
                            <th>{{ __('Sr. No.') }}</th>
                            <th>{{ __('Item Code') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Time') }}<br>{{__('(Minutes)')}}</th>
                            <th>{{ __('Price') }}<br>{{__('(Bonaire Resident)')}}</th>
                            <th>{{ __('Discount') }} <br>{{__('(Bonaire Resident)')}}</th>
                             <th>{{ __('Price') }}<br>{{__('(Non-Resident)')}}</th>
                            <th>{{ __('Discount') }} <br>{{__('(Non-Resident)')}}</th>
                            <th>{{ __('Status ') }} <br>{{__('(On/Off)')}}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    
    @if(session()->has('service_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('service_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
