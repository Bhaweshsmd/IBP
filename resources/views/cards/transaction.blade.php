@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/cardtransaction.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Card Transactions') }} <p style="color: gray; font-size: 14px; margin-bottom: 0px;">{{ !empty($user->first_name) ? $user->first_name : ''}} {{ !empty($user->last_name) ? $user->last_name : ''}} ({{ chunk_split($card->card_number, 4, ' ') }})</p></h4>
            <a href="{{ route('cards.edit', request()->route('id')) }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> Back To Card Details</a>
        </div>
        <div class="card-body">
            <input type="hidden" name="card_id" id="card_id" value="{{request()->route('id')}}">
            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="activetransactions">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr.No.') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Card Number') }}</th>
                                    <th>{{ __('Transaction Id') }}</th>
                                    <th>{{ __('Booking Id') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Created On') }}</th>
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
@endsection