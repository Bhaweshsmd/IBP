@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/langauge.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Languages') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_languages'))
                <a data-toggle="modal" data-target="#addCouponModal" href="" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i>  {{ __('Add New Language') }}</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="couponsTable">
                    <thead>
                        <tr>
                             <th>{{ __('Sr. No.') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Short Name') }}</th>
                            <th>{{ __('Flag') }}</th>
                             <th>{{ __('status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="modal fade" id="editLangaugeForms" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Update Language') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form  method="post" enctype="multipart/form-data" id="editLangaugeForm"
                        autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" id="id">

                        <div class="form-group">
                            <label> {{ __('Name') }}</label>
                            <input id="name" type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Short Name') }}</label>
                            <input id="short_name" type="text" name="short_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Flag') }}</label>
                            <input type="file" name="flag" class="form-control" accept=".jpg,.jpeg,.png" >
                        </div>
                        <div class="form-group">
                            <label> {{ __('Status') }}</label>
                            <select class="form-control" name="status" id="editstatus" tabindex="-1" aria-hidden="true">
                              <option value="Active">Active</option>
                              <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        @if(has_permission(session()->get('user_type'), 'edit_languages'))
                            <div class="form-group text-right">
                                <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Update') }}">
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="addCouponModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Add New Language') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{route('language.store')}}" method="post" enctype="multipart/form-data"  autocomplete="off">
                        @csrf

                        <div class="form-group">
                            <label> {{ __('Name') }}</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Short name') }}</label>
                            <input type="text" name="short_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Flag') }}</label>
                            <input type="file" name="flag" class="form-control" accept=".jpg,.jpeg,.png" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Status') }}</label>
                            <select class="form-control" name="status" id="status" tabindex="-1" aria-hidden="true">
                              <option value="Active">Active</option>
                              <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                      
                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    @if(session()->has('lang_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('lang_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
