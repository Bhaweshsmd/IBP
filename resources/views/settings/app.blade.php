@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/appsettings.js') }}"></script>
@endsection

<style>
    .payment-gateway-card {
        background-color: rgb(245, 245, 245);
        border-radius: 10px;
    }
</style>

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>{{ __('App Settings') }}</h4>
        </div>
        <div class="card-body">

            <form Autocomplete="off" class="form-group form-border"  action="{{ route('app.settings.update') }}" method="post">
                @csrf

                <div class="form-row ">
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Android Version') }}</label>
                        <input value="{{ $data->android_version }}" type="text" class="form-control" name="android_version" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Android URL') }}</label>
                        <input value="{{ $data->android_url }}" type="text" class="form-control" name="android_url" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="">{{ __('IOS Version') }}</label>
                        <input value="{{ $data->ios_version }}" type="text" class="form-control" name="ios_version" required>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="">{{ __('IOS URL') }}</label>
                        <input value="{{ $data->ios_url }}" type="text" class="form-control" name="ios_url" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="">{{ __('Firebase Key') }}</label>
                        <input value="{{ $data->firebase_key }}" type="text" class="form-control" name="firebase_key" required>
                    </div>
                </div>
                
                @if(has_permission(session()->get('user_type'), 'edit_app_settings'))
                    <div class="form-group-submit text-right">
                        <button class="btn btn-primary " type="submit">{{ __('Update') }}</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
    
    @if(session()->has('settings_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('settings_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
