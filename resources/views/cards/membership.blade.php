@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/cardmemberships.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Card Membership') }}</h4>
        </div>
        <div class="card-body">
            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="membershipsCards">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr.No.') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Card Number') }}</th>
                                    <th>{{ __('Fee') }}</th>
                                    <th>{{ __('Assigned On') }}</th>
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
@endsection