@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/platformEarnings.js') }}"></script>
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
            
            .pad-center{
                padding: 20px 0px !important;
            }
            
            .pad-center-alt{
                padding-left: 0px !important;
                padding-right: 0px !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Platform Earnings') }}</h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('platform.earnings') }}" method="GET">

                <input id="startfrom" type="hidden" name="from" value="{{ isset($from) ? $from : '' }}">
                <input id="endto" type="hidden" name="to" value="{{ isset($to) ? $to : '' }}">
              
                <div class="container px-3 mb-4">
                    <div class="row p-3 border bg-light">
                        <div class="col-md-6">
                            <label>Date Range</label>
                            <button type="button" class="btn btn-default form-control" id="daterange-btn">
                                <span id="drp">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <i class="fa fa-caret-down"></i>
                            </button>
                        </div>
                        <div class="col-md-4">
                            <label>Transaction Types</label>
                            <select name="type" class="form-control">
                                <option value="all" {{ ($type =='all') ? 'selected' : '' }}>All</option>
                                @foreach($transaction_types as $trans)
                                    <option value="{{ $trans->name }}" {{ ($trans->name == $type) ? 'selected' : '' }}>{{ $trans->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="btn" class="btn btn-primary btn-flat" id="btn" style="margin-top: 30px;">Filter</button>
                        </div>
                    </div>
                </div>
            </form>
                
            <div class="container px-3 mb-4">
                <div class="row gx-5">
                  
                    <div class="col-md-4 pad-left">
                        <div class="p-3 border bg-left bg-info"> 
                            <label class="mb-0 text-white" for="">Booking Earnings</label>
                           <h4  class="mt-0 p-data mb-0 text-white">{{ $settings->currency }}{{number_format($total_booking_earning,2)}}</h4>
                        </div>
                    </div>
                    <div class="col-md-4 pad-center">
                        <div class="p-3 border bg-center bg-primary"> 
                            <label class="mb-0 text-white" for="">Withrawal Earnings</label>
                           <h4  class="mt-0 p-data mb-0 text-white">{{ $settings->currency }}{{number_format($total_withdraw_earning,2)}}</h4>
                        </div>
                    </div>
                     <div class="col-md-4 pad-right">
                        <div class="p-3 border bg-right bg-success"> 
                            <label class="mb-0 text-white" for="">Total Earnings</label>
                           <h4  class="mt-0 p-data mb-0 text-white">{{ $settings->currency }}{{number_format($total_earning,2)}}</h4>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mt-4 pad-left">
                        <div class="p-3 border bg-left bg-info" >
                            <label class="mb-0 text-white" for="">Card Membership Fee</label>
                            <h4 class="mt-0 p-data mb-0 text-white">{{ $settings->currency }}{{number_format($technical_support,2)}}</h4>
                        </div>
                    </div>
                    <div class="col-md-4 mt-4 pad-center ">
                        <div class="p-3 border pad-center bg-primary " >
                            <label class="mb-0 text-white" for="">Account Maintenance Fee</label>
                           <h4  class="mt-0 p-data mb-0 text-white">{{ $settings->currency }}{{number_format($account_maintenance,2)}}</h4>
                        </div>
                    </div>
                    <div class="col-md-4 mt-4 pad-right ">
                        <div class="p-3 border bg-right bg-success">
                            <label class="mb-0 text-white" for="">IBP Revenue</label>
                           <h4  class="mt-0 p-data mb-0 text-white">{{ $settings->currency }}{{number_format($ibp_revenue,2)}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive col-12 mt-3 px-0">
                <table class="table table-striped w-100 word-wrap" id="platformEarningsTable">
                    <thead>
                        <tr>
                             <th>{{ __('Sr. No.') }}</th>
                            <th>{{ __('Transaction Number') }}</th>
                            <th>{{ __('Transaction Type') }}</th>
                            <th>{{ __('Order Amount') }}</th>
                            <th>{{ __('IBP Revenue  ') }}({{$revenue_per->ibp_revenue }}%)</th>
                            <th>{{ __('Account Maintenance') }}({{$revenue_per->account_maintenance }}%)</th>
                            <th>{{ __('Date & Time') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('asset/js/moment.js') }}" type="text/javascript"></script>
    <script src="{{ asset('asset/js/daterangepicker.js') }}" type="text/javascript"></script>
    
    <script type="text/javascript">
        var sDate;
        var eDate;
    
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
@endsection
