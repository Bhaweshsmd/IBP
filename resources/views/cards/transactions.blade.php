@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/cardtransactions.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Card Transactions') }}</h4>
        </div>
        <div class="card-body">
            
            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="activetransactions">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr.No.') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Card Number') }}</th>
                                    <th>{{ __('Transaction Id') }}</th>
                                    <th>{{ __('Booking Id') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Created On') }}</th>
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

                    <form action="{{ route('cards.store') }}" method="post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                        @csrf
                        
                        <input class="form-control" type="hidden" id="icon" name="language" value="2" required>

                        <div class="form-group">
                            <label>{{ __('No. of Cards') }}</label>
                            <input class="form-control" type="number" id="icon" name="number_cards" required>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}" id='submitformbtn'>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <div class="modal fade" id="scancard" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Scan Card') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <input type="text" class="form-control" id="barcode">
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
                    <form action="{{ route('export.cards') }}" method="post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                        @csrf                       
                        <div class="form-group">
                            <label>From Date</label>
                            <input class="form-control" type="date" id="from_date" name="from_date" required="">
                        </div>
                        
                        <div class="form-group">
                            <label>To Date</label>
                            <input class="form-control" type="date" id="to_date" name="to_date" required="">
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" Submit" id='submitformbtn'>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).on('input', '#barcode', function(){
            var card_id = $('#barcode').val();
            $.ajax({
                type:"POST",
                url: "{{ route('cards.details') }}",
                dataType: "json",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "card_id": card_id
                },
                success:function(result){
                    window.location.href = result.redirect_url;
                }
            });
        });
    </script>
@endsection