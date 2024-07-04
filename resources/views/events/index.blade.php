@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/eventenquiry.js') }}"></script>
@endsection
@php
use App\Models\Users;

@endphp
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
    </style>
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Event Enquiries') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_enquiries'))
                <a data-toggle="modal" data-target="#addUserNotiModal" href="" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Add New Event Type') }}</a>
            @endif
        </div>
        <div class="card-body">
            <ul class="nav nav-pills border-b mb-3  ml-0">
                 <li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#Section2" role="tab"
                        data-toggle="tab">{{ __('Event Enquiry List') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer  " href="#Section1"
                        aria-controls="home" role="tab" data-toggle="tab">{{ __('Event Type') }}<span
                            class="badge badge-transparent "></span></a>
                </li>

               
            </ul>

            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane " id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="eventTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th class="">{{ __('Event Name') }}</th>
                                    <th class="">{{ __('Order') }}</th>
                                    <th class="">{{ __('status') }}</th>
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
                        <table class="table table-striped w-100" id="eventList" >
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th class="">{{ __('Fullname') }}</th>
                                    <th class="">{{ __('Email') }}</th>
                                    <th class="">{{ __('Phone') }}</th>
                                    <th class="">{{ __('Event Type') }}</th>
                                    <th class="w-70">{{ __('Message') }}</th>
                                    <th class="">{{ __('No of People') }}</th>
                                    <th>{{ __('Event Date') }}</th>
                                    <th>{{ __('Enquiry Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($enquiry_list))
                                    @foreach($enquiry_list as $value)
                                        <?php      
                                            $user_details = Users::where('id',$value->user_id)->first();
                                            if(!empty($user_details)){
                                                $url = url('users.profile/'.$value->user_id);
                                            }else{
                                                $url = '#';
                                            }
                                        ?>
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td><a href="{{ $url }}">{{ !empty($user_details->first_name) ? $user_details->first_name : ''}} {{ !empty($user_details->last_name) ? $user_details->last_name : ''}}</a></td>
                                            <td>{{$user_details->email}}</td>
                                            <td>{{$user_details->formated_number}}</td>
                                            <td>{{$value->event_type}}</td>
                                            <td>{{$value->message}}</td>
                                            <td>{{$value->no_of_people}}</td>
                                            <td>{{$value->event_date}}</td>
                                            <td>{{$value->created_at}}</td>
                                         </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editEventType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Edit Event Type') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="editUserNotiForm"
                        autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" id="editUserNotiId" >

                         <div class="form-group">
                            <label> {{ __('Event Name') }}</label>
                            <input type="text" name="title" class="form-control" id="title" required>
                        </div>
                        
                         <div class="form-group">
                            <label> {{ __('Order') }}</label>
                            <input type="number" name="short_id" class="form-control" id="short_id" required>
                        </div>
                        
                        <div class="form-group">
                            <label> {{ __('Status') }}</label>
                            <select name="status" class="form-control" id="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        
                        @if(has_permission(session()->get('user_type'), 'edit_enquiries'))
                            <div class="form-group text-right">
                                <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Update') }}">
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
                    <h5>{{ __('Add New Event Type') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="addEventType"
                        autocomplete="off">
                        @csrf

                        <div class="form-group">
                            <label> {{ __('Event Name') }}</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        
                         <div class="form-group">
                            <label> {{ __('Order') }}</label>
                            <input type="number" name="short_id" min="0"  class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label> {{ __('Status') }}</label>
                          <select name="status" class="form-control" required>
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
    
    @if(session()->has('event_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('event_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
