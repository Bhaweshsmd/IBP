@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/cardtopups.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/style/viewService.css') }}">
    <style>
        #map {
            height:500px;
        }
        #modaldialog {
            max-width: 100% !important;
        }
        
        .card .card-header .form-control{
            height: auto !important;
        }
        
        .card-number{
            color: #bde1e2 !important;
            position: relative;
            top: 265px;
            font-size: 20px;
            margin-left: 40px;
        }
        
        .card-back-number{
            color: #000 !important;
            position: relative;
            top: 17px;
            font-size: 25px;
            margin-left: 20px;
        }
    </style>
@endsection

@section('content')
    <?php
        $user = DB::table('users')->where('id', $card->user_id)->first();
        $cashier = DB::table('admin_user')->where('user_id', $card->topup_by)->first();
        $carddetail = DB::table('cards')->where('id', $card->card_id)->first();
    ?>
    
    <div class="card">
        <div class="card-header">
            <h4>{{ __('Topup Details') }}</h4>
            <a href="{{ route('cards.topups') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> {{ __('Back to Topup List') }}</a>
            <a href="{{ route('topup.invoice', $card->id) }}" class="ml-2 btn btn-warning text-white">{{ __('Print') }}</a>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <p>Order Id : {{$card->order_id}}</p>
                    <p>Card Number : {{chunk_split($carddetail->card_number, 4, ' ')}}</p>
                    <p>Topup Amount : USD {{number_format($card->amount, 2, '.', ',')}}</p>
                    <p>Available Balance : USD {{number_format($carddetail->balance, 2, '.', ',')}}</p>
                </div>
                <div class="form-group col-md-6">
                    <p>User : 
                        @if(!empty($card->user_id))
                            @if(!empty($user))
                                {{$user->first_name}} {{$user->last_name}} ({{$user->email}})
                            @else
                                N/A
                            @endif
                        @else
                            N/A
                        @endif
                    </p>
                    <p>Topup By : 
                        @if(!empty($card->topup_by))
                            @if(!empty($cashier))
                                {{$cashier->first_name}} {{$cashier->last_name}}
                            @else
                                N/A
                            @endif
                        @else
                            N/A
                        @endif
                    </p>
                    <p>Topup On : {{ !empty($card->created_at) ? Carbon\Carbon::parse($card->created_at)->format('d-M-Y') : 'N/A'}}</p>
                    <p>Status : 
                        @if($card->status == '1')
                            Success
                        @else
                            Failed
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
