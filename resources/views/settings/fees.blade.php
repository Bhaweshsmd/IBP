@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/fees.js') }}"></script>
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
            <h4>{{ __('Fee Settings') }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="feeTable">
                    <thead>
                        <tr>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Charge Percentage') }}</th>
                             <th>{{ __('Minimum') }}</th>
                            <th>{{ __('Maximum') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="editFeeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabels"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Edit Fee') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="editFeeForm"
                        autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" id="editFeeId">

                        <div class="form-group">
                            <label> {{ __('Type') }}</label>
                            <select id="edit_fee_type" name="type" required class="form-control">

                            </select>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Charge percent') }}</label>
                            <input id="charge_percent" type="number" name="charge_percent" min="0" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Maximum') }}</label>
                            <input id="maximum" type="number" name="maximum" min="0" class="form-control" required>
                        </div>
                          <div class="form-group">
                            <label> {{ __('Minimum') }}</label>
                            <input id="minimum" type="number" name="minimum" min="0" class="form-control" required>
                        </div>
                        
                         <div class="form-group">
                            <label> {{ __('Day wise') }}</label>
                            <input id="day_wise" type="number" name="day_wise" min="0" class="form-control" >
                        </div>
                         <div class="form-group">
                            <label> {{ __('Week wise') }}</label>
                            <input id="week_wise" type="number" name="week_wise" min="0" class="form-control" >
                        </div>
                         <div class="form-group">
                            <label> {{ __('Month wise') }}</label>
                            <input id="month_wise" type="number" name="month_wise" min="0" class="form-control" >
                        </div>
                        
                        @if(has_permission(session()->get('user_type'), 'edit_fees'))
                            <div class="form-group text-right">
                                <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Update') }}">
                            </div>
                        @endif

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
