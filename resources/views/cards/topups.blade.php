@extends('include.app')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script></script>

@section('header')
    <script src="{{ asset('asset/script/cardtopups.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Card Topups') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_card_topup'))
                <a data-toggle="modal" data-target="#cardtopups" href="" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Card Topup') }}</a>
            @endif
        </div>
        <div class="card-body">
            
            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="activeCardtopups">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Order Id') }}</th>
                                    <th>{{ __('Card Number') }}</th>
                                    <th>{{ __('Customer Name') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Topup By') }}</th>
                                    <th>{{ __('Topup On') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
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
                            <label> {{ __('Select Card') }}</label>
                            <select name="card_id" class="form-control form-control-sm" placeholder="Select Card" id="select_card">
                                <option value="">Select Card</option>
                                @if(!empty($cards))
                                    @foreach($cards as $val)
                                    <?php 
                                        $user = DB::table('users')->where('id', $val->assigned_to)->first();
                                    ?>
                                        <option value="{{$val->id}}">{{chunk_split($val->card_number, 4, ' ')}} ({{$user->email}})</option>
                                    @endforeach
                                @endif
                            </select>
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
    
    <script type="text/javascript">
    	$(document).ready(function () {
          $('#select_user').selectize({
              sortField: 'text'
          });
      });
    </script>
    
    <script type="text/javascript">
    	$(document).ready(function () {
          $('#select_card').selectize({
              sortField: 'text'
          });
      });
    </script>
    
    @if(session()->has('card_topup'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('card_topup') }}",
                    type: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<a href='{{route('cards.topup.edit', session()->get('card_id'))}}'>Topup Details</a>",
                    cancelButtonText: "<a href='{{route('topup.invoice', session()->get('card_id'))}}' target='_blank'>Print</a>",
                    closeOnConfirm: false, 
                    customClass: "Custom_Cancel"
                })
            });
        </script>
    @endif
@endsection