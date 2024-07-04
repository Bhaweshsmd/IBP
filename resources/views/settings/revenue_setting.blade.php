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
            <h4>{{ __('Revenue Setting') }}</h4>
        </div>
        <div class="card-body">

            <form Autocomplete="off" class="form-group form-border" id="globalRevenueSettingsForm" action=""
                method="post">

                @csrf

                <div class="form-row ">
                    <div class="form-group col-md-6">
                        <label for="">{{ __('IBP Revenue (%)') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-percent"></i>
                                </div>
                            </div>
                            <input value="{{ $data->ibp_revenue??0 }}" type="number" min="0" class="form-control" name="ibp_revenue">
                        </div>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="">{{ __('Customer Account Maintenance (%)') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-percent"></i>
                                </div>
                            </div>
                            <input value="{{ $data->account_maintenance??0 }}" type="number" min="0" class="form-control" name="account_maintenance">
                        </div>
                    </div>
                </div>
                
                @if(has_permission(session()->get('user_type'), 'edit_revenue_setting'))
                    <div class="form-group-submit text-right">
                        <button class="btn btn-primary " type="submit">{{ __('Update') }}</button>
                    </div>
                @endif

            </form>
            
               <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="revenueTable">
                    <thead>
                        <tr>
                            <th>{{ __('Sr No.') }}</th>
                            <th>{{ __('IBP Revenue (%)') }}</th>
                            <th>{{ __('Customer Account Maintenance (%)') }}</th>
                            <th>{{ __('Updated Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
