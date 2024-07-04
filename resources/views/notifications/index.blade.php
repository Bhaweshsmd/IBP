@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/notifications.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('content')
    <style>
        #Section1 table.dataTable td {
            white-space: normal !important;
        }

        #Section2 table.dataTable td {
            white-space: normal !important;
        }

        .w-70 {
            width: 70% !important;
        }
        
        .select2-selection__choice__remove{
            border: 0px !important;
        }
        
        .select2-selection--multiple .select2-selection__choice__display {
            padding-left: 10px !important;
        }
        
        .select2-results__option{
            padding: 2px 15px !important;
        }
        
        .select2-search__field{
            margin-bottom: 7px !important;
        }
    </style>
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Notifications') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_notifications'))
                <a data-toggle="modal" data-target="#addUserNotiModal" href="" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Send Promotional Notification') }}</a>
            @endif
        </div>
        <div class="card-body">
            <ul class="nav nav-pills border-b mb-3  ml-0">
                <li role="presentation" class="nav-item">
                    <a class="nav-link pointer active" href="#Section2" role="tab" data-toggle="tab">
                        {{ __('Customer Notifications ') }} <span class="badge badge-transparent "></span>
                    </a>
                </li>
                <li role="presentation" class="nav-item">
                    <a class="nav-link pointer" href="#Section1" aria-controls="home" role="tab" data-toggle="tab">
                        {{ __('Promotional Notifications') }}<span class="badge badge-transparent "></span>
                    </a>
                </li>
            </ul>

            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane " id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="usersTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th class="w-50">{{ __('Notification') }}</th>
                                    <th >{{ __('Customer Name') }}</th>
                                    <th class="" >{{ __('Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="row tab-pane active" id="Section2">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="salonTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th class="w-40">{{ __('Notification') }}</th>
                                     <th >{{ __('Customer Name') }}</th>
                                     <th class="w-40" >{{ __('Date') }}</th>
                                    <th >{{ __('Type') }}</th>
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

    <div class="modal fade" id="editUserNotiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Promotional Notification') }}</h5>

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
                        
                        @if(has_permission(session()->get('user_type'), 'edit_notifications'))
                            <div class="form-group">
                                <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                            </div>
                        @endif
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
                    <h5>{{ __('Send Promotional Notification') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="addUserNotiForm" autocomplete="off" onsubmit='disableformButton()'>
                        @csrf
                        
                        <div class="form-group">
                            <label> {{ __('Users') }}</label>
                            <select class="form-control select2" name="user[]" style="width: 100%;" multiple>
                                <option value="all">All</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id}}">{{$user->first_name}} {{$user->last_name}} ({{$user->email}})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label> {{ __('Title') }}</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label> {{ __('Description') }}</label>
                            <textarea rows="10" style="height:200px !important;" type="text" name="description" class="form-control"
                                required></textarea>
                        </div>
                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}" id='submitformbtn'>
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
                    <h5>{{ __('Notify Users') }}</h5>

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
                        @if(has_permission(session()->get('user_type'), 'edit_notifications'))
                            <div class="form-group">
                                <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                            </div>
                        @endif
                    </form>
                </div>

            </div>
        </div>
    </div>
    
    @if(session()->has('notification_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('notification_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
    
    <script>
        $('.select2').select2({
            maximumSelectionLength: 3
        });
    </script>
@endsection
