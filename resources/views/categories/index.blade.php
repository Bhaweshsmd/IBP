@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/salonCategories.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Categories') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_categories'))
                <a data-toggle="modal" data-target="#addSalonCatModal" href="" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Add New Category') }}</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="categoriesTable">
                    <thead>
                        <tr>
                            <th>{{ __('Sr. No.') }}</th>
                            <th>{{ __('App Image') }}</th>
                            <th>{{ __('Web Image') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Sorting') }}</th>
                             <th>{{ __('Status  (On/Off)') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Edit Category Modal --}}
    <div class="modal fade" id="editSalonCatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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

                    <form action="" method="post" enctype="multipart/form-data" id="editSalonCatForm"
                        autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" id="editSalonCatId">

                        <div class="form-group">
                            <img id="imgSalonCat" src="" alt="" class="rounded" width="50"
                                height="50">
                        </div>

                        <div class="form-group">
                            <label>{{ __('App Icon') }}</label>
                            <input class="form-control" type="file" id="icon" name="icon">
                        </div>
                         <div class="form-group">
                            <img id="webImgSalonCat" src="" alt="" class="rounded" width="50"
                                height="50">
                        </div>
                         <div class="form-group">
                            <label>{{ __('Web Icon') }}</label>
                            <input class="form-control" type="file" id="web_icon" name="web_icon" >
                        </div>

                        <div class="form-group">
                            <label> {{ __('Category Name (English)') }}</label>
                            <input id="editSalonCatTitle" type="text" name="title" class="form-control" required>
                        </div>
                         <div class="form-group">
                            <label> {{ __('Category Name (Papiamento) ') }}</label>
                            <input id="title_in_papiamentu" type="text" name="title_in_papiamentu" class="form-control" required>
                        </div>
                        
                         <div class="form-group">
                            <label> {{ __('Category Name (Dutch)') }}</label>
                            <input id="title_in_dutch" type="text" name="title_in_dutch" class="form-control" required>
                        </div>
                        
                         <div class="form-group">
                            <label> {{ __('Sort') }}</label>
                            <input id="sort" type="number" name="sort" min="0" class="form-control" required>
                        </div>
                        
                        @if(has_permission(session()->get('user_type'), 'edit_categories'))
                            <div class="form-group text-right">
                                <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Update') }}">
                            </div>
                        @endif

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <div class="modal fade" id="addSalonCatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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

                    <form action="" method="post" enctype="multipart/form-data" id="addSalonCatForm"
                        autocomplete="off" onsubmit='disableformButton()'>
                        @csrf

                        <div class="form-group">
                            <label>{{ __('App Icon') }}</label>
                            <input class="form-control" type="file" id="icon" name="icon" required>
                        </div>
                        
                         <div class="form-group">
                            <label>{{ __('Web Icon') }}</label>
                            <input class="form-control" type="file" id="web_icon" name="web_icon" required>
                        </div>

                        <div class="form-group">
                            <label> {{ __('Category Name') }}</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                          <div class="form-group">
                            <label> {{ __('Category Name (Papiamento) ') }}</label>
                            <input id="title_in_papiamentu" type="text" name="title_in_papiamentu" class="form-control" required>
                        </div>
                        
                         <div class="form-group">
                            <label> {{ __('Category Name (Dutch)') }}</label>
                            <input id="title_in_dutch" type="text" name="title_in_dutch" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Sort') }}</label>
                            <input id="sort" type="number" name="sort" min="0" class="form-control" required>
                        </div>
                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}" id='submitformbtn'>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    
    @if(session()->has('category_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('category_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
