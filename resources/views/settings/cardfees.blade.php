@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/cardfees.js') }}"></script>
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
            <h4>{{ __('Assign Card Fees') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_assign_card_fees'))
                <a data-toggle="modal" data-target="#updatefeesModal" href="" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Update New Fee') }}</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="assignactiveCards">
                    <thead>
                        <tr>
                            <th>{{ __('S.No.') }}</th>
                            <th>{{ __('Fee') }}</th>
                            <th>{{ __('Updated By') }}</th>
                            <th>{{ __('Updated On') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    
    <div class="modal fade" id="updatefeesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabels"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Update New Fees') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form method="post" enctype="multipart/form-data" action="{{ route('assign.card.fees.store') }}" autocomplete="off" onsubmit='disableformButton()'>
                        @csrf
                        
                        <div class="form-group">
                            <label> {{ __('Fee') }}</label>
                            <input id="fee" type="number" name="fee" min="0" class="form-control" required>
                        </div>

                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}" id='submitformbtn'>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    @if(session()->has('card_fee'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('card_fee') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
