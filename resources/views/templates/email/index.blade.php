@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/emailContent.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('User Email Templates') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_emails'))
                <a  href="{{route('email.templates.store')}}" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Add New Email Templates') }}</a>
            @endif
           </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="couponsTable">
                    <thead>
                        <tr>
                             <th>{{ __('Sr. No.') }}</th>
                            <th>{{ __('Template Name') }}</th>
                            <th>{{ __('Email Subject') }}</th>
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
                    <h5>{{ __('Update Language Content') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form  method="post" enctype="multipart/form-data" id="editLangaugeFormContent"
                        autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" id="id">

                           <div class="form-group">
                            <label> {{ __('String') }}</label>
                            <input type="text" name="string" id="string" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('English (US)') }}</label>
                            <input type="text" name="en" id="en" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Papiamentu') }}</label>
                            <input type="text" name="pap"  id ="pap" class="form-control"  >
                        </div>
                        
                        <div class="form-group">
                            <label> {{ __('Dutch') }}</label>
                            <input type="text" name="nl"  id="nl" class="form-control"  >
                        </div>
                        <div class="form-group">
                            <label> {{ __('Status') }}</label>
                            <select class="form-control" name="active" id="editstatus" tabindex="-1" aria-hidden="true">
                              <option value="1">Active</option>
                              <option value="0">Inactive</option>
                            </select>
                        </div>
                     
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

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
                    <h5>{{ __('Add Language Content') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form  method="post" enctype="multipart/form-data"  id="addLanguageContend" autocomplete="off">
                        @csrf

                        <div class="form-group">
                            <label> {{ __('String') }}</label>
                            <input type="text" name="string" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('English (US)') }}</label>
                            <input type="text" name="en" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Papiamentu') }}</label>
                            <input type="text" name="pap" class="form-control"  >
                        </div>
                        
                        <div class="form-group">
                            <label> {{ __('Dutch') }}</label>
                            <input type="text" name="nl" class="form-control"  >
                        </div>
                        <div class="form-group">
                            <label> {{ __('Status') }}</label>
                            <select class="form-control" name="active" id="active" tabindex="-1" aria-hidden="true">
                              <option value="1">Active</option>
                              <option value="0">Inactive</option>
                            </select>
                        </div>
                      
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    @if(session()->has('email_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('email_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
