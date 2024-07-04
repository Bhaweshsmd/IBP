@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/assigncards.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Assigned Cards') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_assign_card'))
                <a data-toggle="modal" data-target="#generatecards" href="" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Assign New Card') }}</a>
            @endif
        </div>
        <div class="card-body">
            
            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="assignactiveCards">
                            <thead>
                                <tr>
                                    <th>{{ __('S.No.') }}</th>
                                    <th>{{ __('Card') }}</th>
                                    <th>{{ __('Card Number') }}</th>
                                    <th>{{ __('Fee') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Balance') }}</th>
                                    <th>{{ __('Assigned To') }}</th>
                                    <th>{{ __('Contact') }}</th>
                                    <th>{{ __('Assigned By') }}</th>
                                    <th>{{ __('Assigned On') }}</th>
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
    
    <div class="modal fade" id="generatecards" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Assign New Card') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('assign.cards.store') }}" method="post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                        @csrf

                        <div class="form-group">
                            <label>{{ __('Select User') }}</label>
                            <select name="user_id" class="form-control form-control-sm" placeholder="Select User" id="select_user">
                                <option value="">Select User</option>
                                @if(!empty($users))
                                    @foreach($users as $val)
                                        <option value="{{$val->id}}">{{$val->first_name}} {{$val->last_name}} ({{$val->email}})</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label> {{ __('Select Cards') }}</label>
                            <select name="card_id" class="form-control form-control-sm" placeholder="Select Card" id="select_card">
                                <option value="">Select Card</option>
                                @if(!empty($cards))
                                    @foreach($cards as $val)
                                        <option value="{{$val->id}}">{{chunk_split($val->card_number, 4, ' ')}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Assign') }}" id='submitformbtn'>
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
    
    @if(session()->has('card_assign'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('card_assign') }}",
                    type: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<a href='{{route('cards.edit', session()->get('card_id'))}}'>Card Details</a>",
                    cancelButtonText: "<a href='{{route('assign.invoice', session()->get('card_id'))}}' target='_blank'>Print</a>",
                    closeOnConfirm: false, 
                    customClass: "Custom_Cancel"
                })
            });
        </script>
    @endif
    
    @if(session()->has('card_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('card_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection