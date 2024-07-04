@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/reviews.js') }}"></script>
@endsection

@section('content')
    <style>
        .starDisabled {
            color: rgb(184, 184, 184) !important;
        }

        .starActive {
            color: orangered !important;
        }
    </style>
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Reviews') }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="reviewsTable">
                    <thead>
                        <tr>
                            <th>{{ __('Sr. No.') }}</th>
                            <th>{{ __('Rating') }}</th>
                            <th>{{ __('Service') }}</th>
                            <th>{{ __('Customer ID') }}</th>
                            <th>{{ __('Customer Name') }}</th>
                            <th class="w-30">{{ __('Comment') }}</th>
                            <th>{{ __('Booking ID') }}</th>
                            <th>{{ __('Date&Time') }}</th>
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
    
    @if(session()->has('review_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('review_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
