@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/adminWithdraws.js') }}"></script>
@endsection

@section('content')
    <style>
        .bank-details span {
            display: block;
            line-height: 18px;
        }
    </style>
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Admin Withdrawal Requests') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_admin_withdrawal'))
                <a data-toggle="modal" data-target="#addSalonCatModal" href="" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Request to Withdraw Earning') }}</a>
            @endif
            @if(has_permission(session()->get('user_type'), 'view_banks'))
                <a href="{{ route('banks') }}" class="ml-2 btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Bank Settings') }}</a>
            @endif
        </div>
        <div class="card-body">
            <div class="container px-0 mb-3">
                  <div class="row gx-5">
                    <div class="col">
                     <div class="p-3 border bg-warning"> 
                             <label class="mb-0 text-white" for="">Available to Withdraw</label>
                           <h4  class="mt-0 p-data mb-0 text-white">{{ $settings->currency }}<span id="available_amount_to_withdraw">{{number_format($ibp_revenue-$total_withdrawal-$card_memb_fee,2)}}</span></h4 ></div>
                    </div>
                    <div class="col">
                      <div class="p-3 border bg-primary   ">
                          <label class="mb-0 text-white" for="">Pending Withdrawal</label>
                                                           <h4  class="mt-0 p-data mb-0 text-white">{{ $settings->currency }}{{number_format($pending_withdrawal,2)}}</h4 ></div>
                    </div>
                    <div class="col">
                      <div class="p-3 border bg-success">
                          <label class="mb-0 text-white" for="">Completed Withdrawal</label>
                                       <h4  class="mt-0 p-data mb-0 text-white">{{ $settings->currency }}{{number_format($completed_withdrawal,2)}}</h4 ></div>
                    </div>
                    <div class="col">
                      <div class="p-3 border bg-danger">
                          <label class="mb-0 text-white" for="">Rejected Withdrawal</label>
                                       <h4  class="mt-0 p-data mb-0 text-white">{{ $settings->currency }}{{number_format($rejected_withdrawal,2)}}</h4 ></div>
                    </div>
                  </div>
                </div>
            <ul class="nav nav-pills border-b mb-3  ml-0">

                <li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#Section1" aria-controls="home"
                        role="tab" data-toggle="tab">{{ __('Pending') }}<span
                            class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section2" role="tab"
                        data-toggle="tab">{{ __('Completed') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section3" role="tab"
                        data-toggle="tab">{{ __('Rejected') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
            </ul>

            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="pendingTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Request Number') }}</th>
                                    <th>{{ __('Requested By') }}</th>
                                     <th>{{ __('Staff Name') }}</th>
                                    <th>{{ __('Request Amount & Status') }}</th>
                                    <th>{{ __('Bank Details') }}</th>
                                    <th>{{ __('Placed On') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="row tab-pane" id="Section2">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="completedTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Request Number') }}</th>
                                     <th>{{ __('Requested By') }}</th>
                                     <th>{{ __('Staff Name') }}</th>
                                     <th>{{ __('Request Amount & Status') }}</th>
                                    <th>{{ __('Bank Details') }}</th>
                                    <th>{{ __('Summary') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="row tab-pane" id="Section3">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="rejectedTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Request Number') }}</th>
                                    <th>{{ __('Requested By') }}</th>
                                    <th>{{ __('Staff Name') }}</th>
                                    <th>{{ __('Request Amount & Status') }}</th>
                                    <th>{{ __('Bank Details') }}</th>
                                    <th>{{ __('Summary') }}</th>
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

    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Reject Withdrawal') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="rejectForm" autocomplete="off" onsubmit='disableformButton()'>
                        @csrf
                        <input type="hidden" id="rejectId" name="id">
                        <div class="form-group">
                            <label> {{ __('Summary') }}</label>
                            <textarea rows="10" style="height:200px !important;" type="text" name="summary" class="form-control"></textarea>
                        </div>
                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}"  id='submitformbtn'>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="completeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Complete Withdrawal') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="completeForm" autocomplete="off">
                        @csrf
                        <input type="hidden" id="completeId" name="id">
                        <div class="form-group">
                            <label> {{ __('Summary') }}</label>
                            <textarea rows="10" style="height:200px !important;" type="text" name="summary" class="form-control"></textarea>
                        </div>
                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <div class="modal fade" id="addSalonCatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Request Withdraw') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="wallet-wrap wallet-modal">
                        <div class="wallet-amt">
                            <h5>Available to withdraw</h5>
                            <h2 class="text-success">{{$settings->currency}}<span id="available_amount">{{number_format($ibp_revenue-$total_withdrawal-$card_memb_fee,2)}}</span></h2>
                            <input type="hidden"  name="total_available_amount" id="total_available_amount" value="{{$ibp_revenue-$total_withdrawal}}" >
                        </div>
                    </div>

                    <form action="" method="post" enctype="multipart/form-data" id="adminWithdrawalRequest" autocomplete="off" onsubmit='disableformButton()'>
                        @csrf
                        <input type="hidden"  name="withdraw_fee" id="withdraw_fee" value="{{$withdraw_fee->charge_percent}}" required class="form-control" placeholder="Enter Amount" required>

                        <div class="form-group">
                            <input type="number" min="{{$withdraw_fee->minimum}}" max="{{$withdraw_fee->maximum}}" name="amount" id="withdraw_amount" required class="form-control" placeholder="Enter Amount" required>
                        </div>
                        
                        <div class="form-group">
                            <select name="bank_id" class="form-control" required>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->bank_name }} ({{ $bank->account_number }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <input class="btn btn-primary mr-1 float-right" type="submit" value=" {{ __('Submit') }}" id='submitformbtn'>
                            <h6>Total you will get in your Account : <span class="text-success" style="font-size: 25px;">{{$settings->currency}}<span id="total_get_amount">0.0</span></span></h6>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @if(session()->has('withdraw_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('withdraw_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
    
    @if(session()->has('withdraw_alert_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Warning",
                    text: "{{ session()->get('withdraw_alert_message') }}",
                    type: "warning",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
