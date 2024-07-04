@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/cards.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/daterangepicker.css') }}">
    
    <style>
        .pad-left{
            padding-left: 0px !important;
        }
        
        .pad-right{
            padding-right: 0px !important;
        }
        
        @media (max-width: 768px) {
            .pad-left{
                padding-left: 0px !important;
                padding-right: 0px !important;
            }
            
            .pad-right{
                padding-left: 0px !important;
                padding-right: 0px !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('All Cards List') }}</h4>
            
            @if(has_permission(session()->get('user_type'), 'add_assign_card'))
                <a data-toggle="modal" data-target="#assigncards" href="" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Assign New Card') }}</a>
            @endif
            @if(has_permission(session()->get('user_type'), 'view_genearte_card'))
                <a data-toggle="modal" data-target="#generatecards" href="" class="ml-2 btn btn-primary text-white"><i class="fa fa-credit-card"></i> {{ __('Generate Cards') }}</a>
            @endif
            @if(has_permission(session()->get('user_type'), 'view_export_card'))
                <a data-toggle="modal" data-target="#exportcards" href="" class="ml-2 btn btn-primary text-white"><i class="fas fa-file-export"></i> {{ __('Export Cards') }}</a>
            @endif
        </div>
        <div class="card-body">
            
            <form class="form-horizontal" action="{{ route('cards') }}" method="GET">

                <input id="startfrom" type="hidden" name="from" value="{{ isset($from) ? $from : '' }}">
                <input id="endto" type="hidden" name="to" value="{{ isset($to) ? $to : '' }}">
              
                <div class="container px-3 mb-4">
                    <div class="row gx-5 p-3 border bg-light">
                        <div class="col-md-6">
                            <label>Date Range</label>
                            <button type="button" class="btn btn-default form-control" id="daterange-btn">
                                <span id="drp">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <i class="fa fa-caret-down"></i>
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="btn" class="btn btn-primary btn-flat" id="btn" style="margin-top: 30px;">Filter</button>
                        </div>
                    </div>
                </div>
            </form>
                
            <div class="container px-3 mb-4">
                <div class="row">
                    <div class="col-md-6 pad-left">
                        <div class="p-3 border bg-success"> 
                            <label class="mb-0 text-white" for="">Assign Card Earnings</label>
                            <h4 class="mt-0 p-data mb-0 text-white">{{ $settings->currency }}{{number_format($assign_card_earning,2)}}</h4>
                        </div>
                    </div>
                    <div class="col-md-6 pad-right">
                        <div class="p-3 border bg-info"> 
                            <label class="mb-0 text-white" for="">Card Topup Earnings</label>
                            <h4 class="mt-0 p-data mb-0 text-white">{{ $settings->currency }}{{number_format($topup_card_earning,2)}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="activeCards">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Card') }}</th>
                                    <th>{{ __('Card Number') }}</th>
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
                    <h5>{{ __('Generate Cards') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('cards.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        
                        <input class="form-control" type="hidden" id="icon" name="language" value="2" required>

                        <div class="form-group">
                            <label>{{ __('Enter number of cards to generate') }}</label>
                            <input class="form-control" type="number" id="icon" name="number_cards" required>
                        </div>

                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Generate') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <div class="modal fade" id="exportcards" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Export Cards') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('export.cards') }}" method="post" enctype="multipart/form-data">
                        @csrf                       
                        <div class="form-group">
                            <label>From Date</label>
                            <input class="form-control" type="date" id="from_date" name="from_date" required="">
                        </div>
                        
                        <div class="form-group">
                            <label>To Date</label>
                            <input class="form-control" type="date" id="to_date" name="to_date" required="">
                        </div>

                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value="Export">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="assigncards" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Assign New Card') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('assign.cards.store') }}" method="post" enctype="multipart/form-data">
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
                                @if(!empty($assigncards))
                                    @foreach($assigncards as $val)
                                        <option value="{{$val->id}}">{{chunk_split($val->card_number, 4, ' ')}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Assign') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <script src="{{ asset('asset/js/moment.js') }}" type="text/javascript"></script>
    <script src="{{ asset('asset/js/daterangepicker.js') }}" type="text/javascript"></script>
    
    <script type="text/javascript">
        var sDate;
        var eDate;
    
        //Date range as a button
        $('#daterange-btn').daterangepicker(
          {
            ranges   : {
              'Today'       : [moment(), moment()],
              'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
              'Last 30 Days': [moment().subtract(29, 'days'), moment()],
              'This Month'  : [moment().startOf('month'), moment().endOf('month')],
              'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate  : moment()
          },
          function (start, end)
          {
            var sessionDate      = '{{ Session::get('date_format_type') }}';
            var sessionDateFinal = sessionDate.toUpperCase();
    
            sDate = moment(start, 'MMMM D, YYYY').format('YYYY-MM-DD');
            $('#startfrom').val(sDate);
    
            eDate = moment(end, 'MMMM D, YYYY').format('YYYY-MM-DD');
            $('#endto').val(eDate);
    
            $('#daterange-btn span').html('&nbsp;' + sDate + ' - ' + eDate + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
          }
        )
    
        $(document).ready(function()
        {
            $("#daterange-btn").mouseover(function() {
                $(this).css('background-color', 'white');
                $(this).css('border-color', 'grey !important');
            });
    
            var startDate = "{!! $from !!}";
            var endDate   = "{!! $to !!}";
            // alert(startDate);
            if (startDate == '') {
                $('#daterange-btn span').html('<i class="fa fa-calendar"></i> &nbsp;&nbsp; Pick a date range &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
            } else {
                $('#daterange-btn span').html(startDate + ' - ' + endDate + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
            }
    
            $("#user_input").on('keyup keypress', function(e)
            {
                if (e.type=="keyup" || e.type=="keypress")
                {
                    var user_input = $('form').find("input[type='text']").val();
                    if(user_input.length === 0)
                    {
                        $('#user_id').val('');
                        $('#error-user').html('');
                        $('form').find("button[type='submit']").prop('disabled',false);
                    }
                }
            });
        });
    </script>

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
                    cancelButtonText: "<a href='{{route('assign.invoice', session()->get('card_id'))}}'>Print</a>",
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