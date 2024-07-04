@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/users.js') }}"></script>
@endsection

@section('content')
    <style>
    </style>
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('All Customers') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_user'))
                <a href="{{ route('users.create') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> Add New Customer</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100" id="usersTable">
                    <thead>
                        <tr>
                            <th>{{ __('Sr. No.') }}</th>
                            <th>{{ __('Customer ID') }}</th>
                            <th>{{ __('Profile Image') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Customer Type') }}</th>
                            <th>{{ __('ID/Passport') }}</th>
                            <th>{{ __('Total Bookings') }}</th>
                            <th>{{ __('Register By') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUserNotiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Notify Customers') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="editUserNotiForm"
                        autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" id="editUserNotiId">

                        <div class="form-group">
                            <label> {{ __('Title') }}</label>
                            <input type="text" id="editUserNotiTitle" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Description') }}</label>
                            <textarea id="editUserNotiDesc" rows="10" style="height:200px !important;" type="text" name="description"
                                class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="addUserNotiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Notify Customers') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="addUserNotiForm"
                        autocomplete="off">
                        @csrf

                        <div class="form-group">
                            <label> {{ __('Title') }}</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Description') }}</label>
                            <textarea rows="10" style="height:200px !important;" type="text" name="description" class="form-control"
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="addSalonNotiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Notify Salons') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="addSalonNotiForm"
                        autocomplete="off">
                        @csrf

                        <div class="form-group">
                            <label> {{ __('Title') }}</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Description') }}</label>
                            <textarea rows="10" style="height:200px !important;" type="text" name="description" class="form-control"
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="editSalonNotiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Notify Customers') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="editSalonNotiForm"
                        autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" id="editSalonNotiId">

                        <div class="form-group">
                            <label> {{ __('Title') }}</label>
                            <input type="text" id="editSalonNotiTitle" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Description') }}</label>
                            <textarea id="editSalonNotiDesc" rows="10" style="height:200px !important;" type="text" name="description"
                                class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    @if(session()->has('user_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('user_message') }}",
                    type: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Close",
                    cancelButtonText: "<a href='{{ route('cards.assign') }}'>Assign Card</a>",
                    closeOnConfirm: false, 
                    customClass: "Custom_Cancel"
                })
            });
        </script>
    @endif
@endsection
