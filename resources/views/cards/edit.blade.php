@extends('include.app')
@section('content')

    <?php
        $user = DB::table('users')->where('id', $card->assigned_to)->first();
        $cashier = DB::table('admin_user')->where('user_id', $card->assigned_by)->first();
        $generated = DB::table('admin_user')->where('user_id', $card->generated_by)->first();
        $language = DB::table('languages')->where('id', $card->language)->first();
        
        if($card->language == '1'){
            $card_front = asset('public/cards/englishfront.PNG');
            $card_back = asset('public/cards/englishback.PNG');
        }elseif($card->language == '2'){
            $card_front = asset('public/cards/papiamentufront.PNG');
            $card_back = asset('public/cards/papiamentuback.PNG');
        }elseif($card->language == '3'){
            $card_front = asset('public/cards/dutchfront.PNG');
            $card_back = asset('public/cards/dutchback.PNG');
        }
    ?>
	
    <script src="{{ asset('asset/script/admins.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/style/viewService.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
            top: 255px;
            font-size: 20px;
            margin-left: 40px;
        }
        
        .card-back-number{
            color: #000 !important;
            position: relative;
            bottom: 203px;
            font-size: 22px;
            margin-left: 20px;
        }

        .creditdebitcard {
          width: 100%;
          max-width: 400px;
          -webkit-transform-style: preserve-3d;
          transform-style: preserve-3d;
          transition: -webkit-transform 0.6s;
          -webkit-transition: -webkit-transform 0.6s;
          transition: transform 0.6s;
          transition: transform 0.6s, -webkit-transform 0.6s;
          cursor: pointer;
        }
    
        .creditdebitcard .front{
          position: relative;
          width: 100%;
          max-width: 400px;
          -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
          -webkit-font-smoothing: antialiased;
          color: #47525d;
          bottom: 50px;
        }
        
        .creditdebitcard .back {
          position: relative;
          width: 100%;
          max-width: 400px;
          -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
          -webkit-font-smoothing: antialiased;
          color: #47525d;
          bottom: 300px;
        }
    
        .creditdebitcard .back {
          -webkit-transform: rotateY(180deg);
          transform: rotateY(180deg);
        }
    
        .creditdebitcard.flipped {
          -webkit-transform: rotateY(180deg);
          transform: rotateY(180deg);
        }
    
        .wallet-wrap-back{
            background-image: url("{{$card_back}}");
            background-size: cover;
            width: 400px;
            height: 250px;
            box-shadow: none !important;
            border-radius: 15px;
        }
    
        .wallet-wrap-front{
            background-image: url("{{$card_front}}");
            background-size: cover;
            width: 400px;
            height: 250px;
            box-shadow: none !important;
            border-radius: 15px;
        }
        
        .tooltip {
          position: relative;
          display: inline-block;
        }
        
        .tooltip .tooltiptext {
          visibility: hidden;
          width: 140px;
          background-color: #555;
          color: #fff;
          text-align: center;
          border-radius: 6px;
          padding: 5px;
          position: absolute;
          z-index: 1;
          bottom: 150%;
          left: 50%;
          margin-left: -75px;
          opacity: 0;
          transition: opacity 0.3s;
        }
        
        .tooltip .tooltiptext::after {
          content: "";
          position: absolute;
          top: 100%;
          left: 50%;
          margin-left: -5px;
          border-width: 5px;
          border-style: solid;
          border-color: #555 transparent transparent transparent;
        }
        
        .tooltip:hover .tooltiptext {
          visibility: visible;
          opacity: 1;
        }
	</style>
    
    @if($card->language == '3')
        <style>
            .card-code{
                padding-top: 15px;
                padding-left: 20px;
            }
        </style>
    @else
        <style>
            .card-code{
                position: relative;
                bottom: 215px;
                padding-left: 11px;
            }
        </style>
    @endif
    
    <div class="card">
        <div class="card-header">
            <h4>{{ __('Card Details') }} @if(empty($card->assigned_to)) (Not Assigned) @endif</h4>
            <a  href="{{ route('cards') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> {{ __('Back to Card List') }}</a>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <div class="creditdebitcard preload">
                      <div class="front">
                        <p class="card-number">{{chunk_split($card->card_number, 4, ' ')}}</p>
                        <svg id="cardfront" class="wallet-wrap-front"></svg>
                      </div>
                      <div class="back">
                        <svg id="cardback" class="wallet-wrap-back">
                            <p class="card-code"><img src="data:image/png;base64,{{DNS1D::getBarcodePNG($card->card_number, 'I25')}}" alt="barcode" /></p>
                            <p class="card-back-number">{{chunk_split($card->card_number, 4, ' ')}}</p>
                        </svg>
                      </div>
                    </div>
                    
                    <div class="form-group col-md-12" style="position: relative; bottom: 340px; left: 30px;">
                        <h5 class="mb-3">
                            <strong>Card Number :</strong> 
                            <span>{{chunk_split($card->card_number, 4, ' ')}} <a onclick="myFunction()" onmouseout="outFunc()"><i class="fa fa-copy" id="myTooltip"></i></a></span>
                        </h5>
                        <h5 class="mb-3">
                            <strong>Balance :</strong> 
                            <span>USD {{number_format($card->balance, 2, '.', ',')}}</span>
                        </h5>
                        <h5 class="mb-3">
                            <strong>Loyality Points :</strong> 
                            <span>{{$card->loyality_points}}</span>
                        </h5>
                        
                        <div class="mt-5">
                            <a data-toggle="modal" data-target="#cardtopups" href="#">
                                <button class="btn btn-primary" style="font-size: 12px; padding: 5px 11px;"><i class="fa fa-arrow-up"></i> New Topup</button>
                            </a>
                            <a href="{{ route('card.transaction', $card->id) }}">
                                <button class="btn btn-primary" style="font-size: 12px; padding: 5px 11px;"><i class="fa fa-exchange"></i> All Transactions</button>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#generatecards">
                                <button class="btn btn-primary" style="font-size: 12px; padding: 5px 11px;"><i class="fa fa-edit"></i> Change Status</button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div style="border: 1px solid #8080804f; border-radius: 20px; padding: 15px;">
                        <h4 style="color: #009544;">Customer Details</h4>
                        <p>
                            <strong>Customer :</strong> 
                            <span style="float: right;">
                                @if(!empty($card->assigned_to))
                                    @if(!empty($user))
                                        {{$user->first_name}} {{$user->last_name}}
                                    @else
                                        N/A
                                    @endif
                                @else
                                    N/A
                                @endif
                            </span>
                        </p>
                        <p>
                            <strong>Email :</strong> 
                            <span style="float: right;">
                                @if(!empty($card->assigned_to))
                                    @if(!empty($user))
                                        {{$user->email}}
                                    @else
                                        N/A
                                    @endif
                                @else
                                    N/A
                                @endif
                            </span>
                        </p>
                        <p>
                            <strong>Phone :</strong> 
                            <span style="float: right;">
                                @if(!empty($card->assigned_to))
                                    @if(!empty($user))
                                        {{$user->formated_number}}
                                    @else
                                        N/A
                                    @endif
                                @else
                                    N/A
                                @endif
                            </span>
                        </p>
                        
                        <p>
                            <strong>Created On :</strong> 
                            <span style="float: right;">
                                @if(!empty($card->assigned_to))
                                    @if(!empty($user))
                                        {{ !empty($user->created_at) ? Carbon\Carbon::parse($user->created_at)->format('d-M-Y') : 'N/A'}}
                                    @else
                                        N/A
                                    @endif
                                @else
                                    N/A
                                @endif
                            </span>
                        </p>
                        
                        <h4 class="mt-5" style="color: #009544;">Card Details</h4>
                        <p>
                            <strong>Assigned By :</strong> 
                            <span style="float: right;">
                                @if(!empty($card->assigned_by))
                                    @if(!empty($cashier))
                                        {{$cashier->first_name}} {{$cashier->last_name}}
                                    @else
                                        N/A
                                    @endif
                                @else
                                    N/A
                                @endif
                            </span>
                        </p>
                        <p>
                            <strong>Assigned On :</strong> 
                            <span style="float: right;">{{ !empty($card->assigned_on) ? Carbon\Carbon::parse($card->assigned_on)->format('d-M-Y') : 'N/A'}}</span>
                        </p>
                        <p>
                            <strong>Card Status : </strong> 
                            <span style="float: right;">
                                @if($card->status == '1')
                                    Active
                                @elseif($card->status == '2')
                                    Inactive
                                @else
                                    Block
                                @endif 
                            </span>
                        </p>
                        <p>
                            <strong>Language : </strong> 
                            <span style="float: right;">{{$language->name}}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="generatecards" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Change Card Status') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('cards.status.update', $card->id) }}" method="post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                        @csrf

                        <div class="form-group">
                            <label> {{ __('Change Status') }}</label>
                            <select name="status" class="form-control form-control-sm" aria-label="Default select example">
                                <option value="1" @if($card->status == '1') selected @endif>Active</option>
                                <option value="2" @if($card->status == '2') selected @endif>Inactive</option>
                                <option value="3" @if($card->status == '3') selected @endif>Block</option>
                            </select>
                        </div>

                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}" id='submitformbtn'>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <div class="modal fade" id="cardtopups" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Card Topup') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('cards.topups.store') }}" method="post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                        @csrf

                        <div class="form-group">
                            <label> {{ __('Card Number') }}</label>
                            <input class="form-control" type="hidden" id="card_id" name="card_id" value="{{$card->id}}">
                            <input class="form-control" type="text" id="card_number" value="{{chunk_split($card->card_number, 4, ' ')}}" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>{{ __('Enter Amount') }}</label>
                            <input class="form-control" type="number" id="icon" name="amount" required>
                        </div>

                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}" id='submitformbtn'>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <script src='https://cdnjs.cloudflare.com/ajax/libs/imask/3.4.0/imask.min.js'></script>

    <script type="text/javascript">
        window.onload = function () {
            document.querySelector(".preload").classList.remove("preload");
            document.querySelector(".creditdebitcard").addEventListener("click", function () {
                if (this.classList.contains("flipped")) {
                  this.classList.remove("flipped");
                } else {
                  this.classList.add("flipped");
                }
            });
        };
    </script>
    
    <script>
        function myFunction() {
          var copyText = document.getElementById("card_number");
          copyText.select();
          copyText.setSelectionRange(0, 99999);
          navigator.clipboard.writeText(copyText.value);
          
          var tooltip = document.getElementById("myTooltip");
          tooltip.innerHTML = "<i class='fa fa-check text-success'><i>";
        }
        
        function outFunc() {
          var tooltip = document.getElementById("myTooltip");
        }
    </script>
    
    @if(session()->has('card_topup'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('card_topup') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
