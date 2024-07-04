@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/aboutus.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>{{ __('About Us') }}</h4>
            <div class="border-bottom-0 border-dark border"></div>
        </div>
        <div class="card-body">

            <form Autocomplete="off" class="form-group form-border" action="" method="post" id="aboutus" required>
                @csrf
                
                @foreach($languages as $language)
                    <?php 
                        $lang_desc = $language->short_name.'_description';
                        $lang_title = $language->short_name.'_title';
                    ?>
                    <div class="form-group">
                        <label>{{ $language->name }} Title</label>
                        <input type="text" class="form-control" name="content" value="<?php echo $pages->$lang_title; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>{{ $language->name }} Content</label>
                        <textarea id="summernote" class="summernote-simple" name="content"> <?php echo $pages->$lang_desc; ?> </textarea>
                    </div>
                @endforeach
                    
                @if(has_permission(session()->get('user_type'), 'edit_pages'))
                    <div class="form-group text-right">
                        <input class="btn btn-primary mr-1" type="submit" value="Submit">
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection
