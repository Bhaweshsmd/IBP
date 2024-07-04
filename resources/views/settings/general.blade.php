@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/settings.js') }}"></script>
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
            <h4>{{ __('General Settings') }}</h4>
        </div>
        <div class="card-body">

            <form Autocomplete="off" class="form-group form-border" id="globalSettingsForm" action="" method="post">
                @csrf

                <div class="form-row ">
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Currency') }}</label>
                        <input value="{{ $data->currency }}" type="text" class="form-control" name="currency" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Number of bookings users can have at a time') }}</label>
                        <input value="{{ $data->max_order_at_once }}" type="text" class="form-control" name="max_order_at_once" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Support Email') }}</label>
                        <input value="{{ $data->support_email }}" type="text" class="form-control" name="support_email" required>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Admin Email') }}</label>
                        <input value="{{ $data->admin_email }}" type="text" class="form-control" name="admin_email" required>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Contact Email') }}</label>
                        <input value="{{ $data->contact_email }}" type="text" class="form-control" name="contact_email" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Recaptcha Key') }}</label>
                        <input value="{{ $data->recaptcha_key }}" type="text" class="form-control" name="recaptcha_key" required>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Recaptcha Secret') }}</label>
                        <input value="{{ $data->recaptcha_secret }}" type="text" class="form-control" name="recaptcha_secret" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Twilio Id') }}</label>
                        <input value="{{ $data->twilio_sid }}" type="text" class="form-control" name="twilio_sid" required>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Twilio Auth Token') }}</label>
                        <input value="{{ $data->twilio_auth_token }}" type="text" class="form-control" name="twilio_auth_token" required>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Twilio Phone Number') }}</label>
                        <input value="{{ $data->twilio_phone_number }}" type="text" class="form-control" name="twilio_phone_number" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Pusher Id') }}</label>
                        <input value="{{ $data->pusher_id }}" type="text" class="form-control" name="pusher_id" required>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Pusher Key') }}</label>
                        <input value="{{ $data->pusher_key }}" type="text" class="form-control" name="pusher_key" required>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Pusher Secret') }}</label>
                        <input value="{{ $data->pusher_secret }}" type="text" class="form-control" name="pusher_secret" required>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Pusher Secret') }}</label>
                        <input value="{{ $data->pusher_cluster }}" type="text" class="form-control" name="pusher_cluster" required>
                    </div>
                </div>
                
                @if(has_permission(session()->get('user_type'), 'edit_settings'))
                    <div class="form-group-submit text-right">
                        <button class="btn btn-primary " type="submit">{{ __('Update') }}</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection
