@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/gateways.js') }}"></script>
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
            <h5 class="text-dark">{{ __('Payment Gateways') }}</h5>
        </div>
        <div class="card-body">
            <form Autocomplete="off" class="form-group form-border" id="paymentGatewayForm" action=""
                method="post">

                @csrf
                <div class="">
                    <span>- The platform supports one payment gateway only at a time. So users can recharge the wallet with the selected gateway only.</span><br>
                    <span>- Select the one gateway to use and then save it. Make sure to set required credentials for that gateways.</span>
                </div>
                <div class="form-row mt-3">
                    <div class="form-group col-md-4">
                        <label for="exampleFormControlSelect1">{{ __('Payment Gateway') }}</label>

                        <select name="payment_gateway" class="form-control">
                            <option {{ $data->payment_gateway == 1 ? 'selected' : '' }} value="1">
                                {{ __('Debit/Credit Card') }}
                            </option>
                        </select>
                    </div>
                </div>

                <h5 class="text-dark d-block">{{ __('Debit/Credit Card') }}</h5>

                <div class="form-row payment-gateway-card p-2">
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Public Key') }}</label>
                        <input value="{{ $data->stripe_publishable_key }}" type="text" class="form-control"
                            name="stripe_publishable_key">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Secret Key') }}</label>
                        <input value="{{ $data->stripe_secret }}" type="text" class="form-control"
                            name="stripe_secret">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Currency Code (***)') }}</label>
                        <input value="{{ $data->stripe_currency_code }}" type="text" class="form-control"
                            name="stripe_currency_code">
                    </div>
                </div>
                
                @if(has_permission(session()->get('user_type'), 'edit_payment'))
                    <div class="form-group-submit mt-3 text-right">
                        <button class="btn btn-primary " type="submit">{{ __('Save') }}</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection
