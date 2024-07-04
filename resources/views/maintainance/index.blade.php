@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/maintenance.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Maintenance Settings') }}</h4>
            
            @if(has_permission(session()->get('user_type'), 'add_maintenance'))
                <a href="{{ route('create.maintainance') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Add New Maintenance Setting') }}</a>
            @endif
        </div>
        <div class="card-body">
            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="activeMaintenance">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Platform</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
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
    
    @if(session()->has('maintenance_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('maintenance_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
    
    @if(session()->has('maintenance_delete'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Warning",
                    text: "{{ session()->get('maintenance_delete') }}",
                    type: "warning",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection