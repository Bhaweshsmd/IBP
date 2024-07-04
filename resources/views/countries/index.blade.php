@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/country.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Country Settings') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_country'))
                <a data-toggle="modal" data-target="#addCountryModal" href="" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Add New Country') }}</a>
            @endif
        </div>
        <div class="card-body">
            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="activeCountries">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Short Name') }}</th>
                                    <th>{{ __('ISO3') }}</th>
                                    <th>{{ __('Number Code') }}</th>
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
    
    <div class="modal fade" id="editCountryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Edit Country') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="editCountryForm" autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" id="editCountryId">
                        
                        <div class="form-group">
                            <label> {{ __('Name') }}</label>
                            <input id="editname" type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Shor Name') }}</label>
                            <input id="editshortname" type="text" name="short_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('ISO3') }}</label>
                            <input id="editiso3" type="text" name="iso3" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Number Code') }}</label>
                            <input id="editnumbercode" type="text" name="number_code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Phone Code') }}</label>
                            <input id="editphonecode" type="text" name="phone_code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Currency Code') }}</label>
                            <input id="editcurrencycode" type="text" name="currency_code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Status') }}</label>
                            <select id="editstatus" name="status" class="form-control" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        @if(has_permission(session()->get('user_type'), 'edit_country'))
                            <div class="form-group text-right">
                                <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Update') }}" >
                            </div>
                        @endif
                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <div class="modal fade" id="addCountryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Add New Country') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('countries.store') }}" method="post" enctype="multipart/form-data" autocomplete="off" >
                        @csrf
                        
                        <div class="form-group">
                            <label> {{ __('Name') }}</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Shor Name') }}</label>
                            <input type="text" name="short_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('ISO3') }}</label>
                            <input type="text" name="iso3" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Number Code') }}</label>
                            <input type="text" name="number_code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Phone Code') }}</label>
                            <input type="text" name="phone_code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Currency Code') }}</label>
                            <input type="text" name="currency_code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Status') }}</label>
                            <select name="status" class="form-control" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}"  >
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    @if(session()->has('country_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('country_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection