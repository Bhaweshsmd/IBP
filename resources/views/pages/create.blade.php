@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/pages.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>{{ __('Page Details') }}</h4>
            <a  href="{{ route('pages') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> {{ __('Back to Page List') }}</a>
        </div>
        <div class="card-body">
            
            <form action="{{ route('page.store') }}" method="Post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                @csrf
                
                @foreach($languages as $language)
                    <?php 
                        $lang_desc = $language->short_name.'_description';
                        $lang_title = $language->short_name.'_title';
                    ?>
                    <div class="form-group">
                        <label>{{ $language->name }} Title</label>
                        <input type="text" class="form-control" name="{{$lang_title}}">
                    </div>
                    
                    <div class="form-group">
                        <label>{{ $language->name }} Content</label>
                        <textarea id="summernote" class="summernote-simple" name="{{$lang_desc}}"></textarea>
                    </div>
                @endforeach
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inctive</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" class="form-control">
                        <option value="1">Web</option>
                        <option value="2">App</option>
                        <option value="3">Both</option>
                    </select>
                </div>

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" id='submitformbtn'>Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
