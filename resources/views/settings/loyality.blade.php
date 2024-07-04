@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/loyality.js') }}"></script>
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
            <h4>{{ __('Loyality Points') }}</h4>
        </div>
        <div class="card-body">
            <p><strong>{{$data->loyality_points}} Loyality Point = {{ $data->loyality_amount }} USD</strong></p>
            <form action="{{ route('cards.loyality.point') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="row">
                 <div class="form-group col-sm-6">
                    <label> Points</label>
                    <input type="number" name="loyality_points" min="0" class="form-control" required>
                </div>
                <div class="form-group col-sm-6">
                    <label> Amount ({{$data->currency}})</label>
                    <input type="number" name="amount" min="1" class="form-control" required>
                </div>
                
                @if(has_permission(session()->get('user_type'), 'edit_loyality_points'))
                    <div class="form-group col-sm-12">
                        <input class="btn btn-primary mr-1 float-right" type="submit" value=" {{ __('Submit') }}">
                    </div>
                @endif
               </div>    
            </form>
        </div>
    </div>
    
    @if(session()->has('settings_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('settings_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
