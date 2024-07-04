@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/faqs.js') }}"></script>
@endsection

@section('content')
    <style>
        #Section2 table.dataTable td {
            white-space: normal !important;
        }

        .w-30 {
            width: 30% !important;
        }
    </style>
    <div class="card mt-3">
        <div class="card-header">
            <h4>FAQ's</h4>
            
            @if(has_permission(session()->get('user_type'), 'view_faqs_category'))
                <a data-toggle="modal" data-target="#addCatModal" href="" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Add New Category') }}</a>
            @endif
            
            @if(has_permission(session()->get('user_type'), 'add_faqs'))
                <a href="{{ route('faqs.create') }}" class="ml-2 btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Add New FAQ') }}</a>
            @endif
        </div>
        <div class="card-body">
            <ul class="nav nav-pills border-b mb-3  ml-0">
                <li role="presentation" class="nav-item">
                    <a class="nav-link pointer active" href="#Section1" aria-controls="home" role="tab" data-toggle="tab">{{ __('Categories') }}
                        <span class="badge badge-transparent "></span>
                    </a>
                </li>

                <li role="presentation" class="nav-item">
                    <a class="nav-link pointer" href="#Section2" role="tab" data-toggle="tab">{{ __('FAQs') }}
                        <span class="badge badge-transparent "></span>
                    </a>
                </li>
            </ul>

            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="categoriesTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="row tab-pane" id="Section2">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="faqTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th class="w-30">{{ __('Question') }}</th>
                                    <th class="w-30">{{ __('Answer') }}</th>
                                    <th>{{ __('Category') }}</th>
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

    <div class="modal fade" id="editCatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Edit Category') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="editCatForm" autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" id="editCatId">

                        <div class="form-group">
                            <label> {{ __('Category') }}</label>
                            <input id="editCatTitle" type="text" name="title" class="form-control" required>
                        </div>
                        
                        @if(has_permission(session()->get('user_type'), 'edit_faqs_category'))
                            <div class="form-group text-right">
                                <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Update') }}">
                            </div>
                        @endif
                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <div class="modal fade" id="addCatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Add New Category') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="addCatForm" autocomplete="off">
                        @csrf

                        <div class="form-group">
                            <label> {{ __('Category') }}</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    @if(session()->has('faq_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('faq_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
