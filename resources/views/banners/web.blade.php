@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/webbanners.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Web Banners') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_web_banners'))
                <a data-toggle="modal" data-target="#addBannerModal" href="" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Add New Web Banner') }}</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="webbannersTable">
                    <thead>
                        <tr>
                            <th>{{ __('Sr. No.') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    
    <div class="modal fade" id="addBannerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Add New Web Banner') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" id="addwebBannerForm" autocomplete="off" onsubmit='disableformButton()'>
                        @csrf
                        <div class="form-group">
                            <label>{{ __('Web Banner') }}</label>
                            <input class="form-control" type="file" name="image" required>
                        </div>
                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}" id='submitformbtn'>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @if(session()->has('banner_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('banner_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
