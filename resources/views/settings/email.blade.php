@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/email.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>{{ __('General Settings') }}</h4>
        </div>
        <div class="card-body">

            <form Autocomplete="off" class="form-group form-border" action="{{ route('email.settings.update') }}" method="post">
                @csrf

                <div class="form-row ">
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Mail Driver') }}</label>
                        <input value="{{ $email->mail_driver }}" type="text" class="form-control" name="mail_driver" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Mail Mailer') }}</label>
                        <input value="{{ $email->mail_mailer }}" type="text" class="form-control" name="mail_mailer" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Mail Host') }}</label>
                        <input value="{{ $email->mail_host }}" type="text" class="form-control" name="mail_host" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Mail Port') }}</label>
                        <input value="{{ $email->mail_port }}" type="text" class="form-control" name="mail_port" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Mail Username') }}</label>
                        <input value="{{ $email->mail_username }}" type="text" class="form-control" name="mail_username" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Mail Password') }}</label>
                        <input value="{{ $email->mail_password }}" type="text" class="form-control" name="mail_password" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Mail Encryption') }}</label>
                        <input value="{{ $email->mail_encryption }}" type="text" class="form-control" name="mail_encryption">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Mail From Address') }}</label>
                        <input value="{{ $email->mail_from_address }}" type="text" class="form-control" name="mail_from_address" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Mail From Name') }}</label>
                        <input value="{{ $email->mail_from_name }}" type="text" class="form-control" name="mail_from_name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Status') }}</label>
                        <select class="form-control" name="status" required>
                            <option value="1" @if($email->status == '1') selected @endif>Active</option>
                            <option value="2" @if($email->status == '2') selected @endif>Inactive</option>
                        </select>
                    </div>
                </div>
                
                @if(has_permission(session()->get('user_type'), 'edit_email_settings'))
                    <div class="form-group-submit text-right">
                        <button class="btn btn-primary " type="submit">{{ __('Update') }}</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
    
    @if(session()->has('settings_message_success'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('settings_message_success') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
    
    @if(session()->has('settings_message_error'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Warning",
                    text: "{{ session()->get('settings_message_error') }}",
                    type: "warning",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
