@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/taxes.js') }}"></script>
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
            <h4>{{ __('Tax Settings') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_taxes'))
                <a data-toggle="modal" data-target="#addTaxModal" href="" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Add New Tax') }}</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="taxesTable">
                    <thead>
                        <tr>
                            <th>{{ __('Tax Title') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Value') }}</th>
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

    <div class="modal fade" id="addTaxModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Add New Tax') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="addTaxForm"
                        autocomplete="off">
                        @csrf

                        <div class="form-group">
                            <label> {{ __('Tax Title') }}</label>
                            <input type="text" name="tax_title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Type') }}</label>
                            <select name="type" class="form-control">
                                <option value="0">{{ __('Percent') }}</option>
                                <option value="1">{{ __('Fixed') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Value') }}</label>
                            <input type="number" name="value" class="form-control" required>
                        </div>

                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="editTaxModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Edit Tax') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="editTaxForm"
                        autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" id="editTaxId">

                        <div class="form-group">
                            <label> {{ __('Tax Title') }}</label>
                            <input id="edit_tax_title" type="text" name="tax_title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Type') }}</label>
                            <select id="edit_tax_type" name="type" class="form-control">

                            </select>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Value') }}</label>
                            <input id="edit_tax_value" type="number" name="value" class="form-control" required>
                        </div>
                        
                        @if(has_permission(session()->get('user_type'), 'edit_taxes'))
                            <div class="form-group text-right">
                                <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Update') }}">
                            </div>
                        @endif
                    </form>
                </div>

            </div>
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
