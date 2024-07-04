@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/salons.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Company Profile') }}</h4>
        </div>
        <div class="card-body">
            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="activeSalonTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Company ID') }}</th>
                                    <th>{{ __('Company Name') }}</th>
                                    <th>{{ __('Lifetime Earnings') }}</th>
                                    <th>{{ __('Contact') }}</th>
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
@endsection
