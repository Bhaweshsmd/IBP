@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/categorisedservices.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ $category->title }} Service</h4>
            @if(has_permission(session()->get('user_type'), 'add_services'))
                <a href="{{route('addService')}}" class="ml-auto btn btn-primary text-white">Add Service</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="categorisedservicesTable">
                    <thead>
                        <tr>
                            <th>{{ __('Item Code') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Time') }}<br>{{__('(Minutes)')}}</th>
                            <th>{{ __('Price') }}<br>{{__('(Local)')}}</th>
                            <th>{{ __('Discount') }} <br>{{__('(Local)')}}</th>
                            <th>{{ __('Price') }}<br>{{__('(Foreigner)')}}</th>
                            <th>{{ __('Discount') }} <br>{{__('(Foreigner)')}}</th>
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
@endsection
