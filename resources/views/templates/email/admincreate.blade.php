@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/adminEmailContent.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Add Email') }}</h4>
            <a  href="{{route('admin.email.templates')}}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> {{ __('Back to Email Template') }}</a>
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <form  method="post" enctype="multipart/form-data"   autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm-4" >
                            <label> {{ __('Template Name') }}</label>
                            <input type="text" name="title" id="title"  class="form-control" value="{{old('title')}}" required>
                        </div>
                        <div class="form-group col-sm-4" >
                            <label> {{ __('Subject In English') }}</label>
                            <input type="text" name="email_subjects" id="email_subjects"  class="form-control" value="{{ old('email_subjects') }}" required>
                        </div>
                        <div class="form-group col-sm-4">
                            <label> {{ __(' Subject In Papiamentu') }}</label>
                            <input type="text" name="email_subject_pap" id="email_subject_pap" value="{{ old('email_subject_pap') }}" class="form-control" >
                        </div>
                        <div class="form-group col-sm-4">
                            <label> {{ __('Subject In Dutch ') }}</label>
                            <input type="text" name="email_subject_nl"  id ="email_subjects_nl" value="{{ old('email_subject_nl') }}" class="form-control"  >
                        </div>
                        <div class="form-group col-sm-12">
                            <label> {{ __('Content In English') }}</label>
                            <textarea type="text" name="email_content"  class="summernote-simple" id="summernote"  required  >{{old('email_content')}}</textarea>
                        </div>
                         <div class="form-group col-sm-12">
                            <label> {{ __('Content In Papiamentu') }}</label>
                            <textarea type="text" name="email_content_pap"   class="summernote-simple" id="summernote"  >{{old('email_content_pap')}}</textarea>
                        </div>
                         <div class="form-group col-sm-12">
                            <label> {{ __('Content In Dutch') }}</label>
                            <textarea type="text" name="email_content_nl"    class="summernote-simple" id="summernote"  >{{old('email_content_nl')}}</textarea>
                        </div>
                     
                        <div class="form-group col-sm-12 text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>
                    </div>    
                </form>
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
@endsection
