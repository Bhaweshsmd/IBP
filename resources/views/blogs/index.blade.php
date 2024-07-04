@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/blogs.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('All Blogs') }}</h4>
            
            @if(has_permission(session()->get('user_type'), 'add_blogs'))
                <a href="{{ route('blog.create') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Add New Blog') }}</a>
            @endif
        </div>
        <div class="card-body">
            <div class="tab-content tabs" id="home">
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="activeBlogs">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Added By') }}</th>
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
    
    @if(session()->has('blog_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('blog_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
    
    @if(session()->has('blog_delete'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Warning",
                    text: "{{ session()->get('blog_delete') }}",
                    type: "warning",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection