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
            
            <form action="{{ route('page.update', $pages->id) }}" method="Post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                @csrf
                
                @foreach($languages as $language)
                    <?php 
                        $lang_desc = $language->short_name.'_description';
                        $lang_title = $language->short_name.'_title';
                    ?>
                    <div class="form-group">
                        <label>{{ $language->name }} Title</label>
                        <input type="text" class="form-control" name="{{$lang_title}}" value="<?php echo $pages->$lang_title; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>{{ $language->name }} Content</label>
                        <textarea id="summernote" class="summernote-simple" name="{{$lang_desc}}"><?php echo $pages->$lang_desc; ?></textarea>
                    </div>
                @endforeach
                
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" class="form-control" name="slug" value="<?php echo $pages->slug; ?>">
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1" <?php if($pages->status == '1'){ echo 'selected'; } ?>>Active</option>
                        <option value="0" <?php if($pages->status == '0'){ echo 'selected'; } ?>>Inctive</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" class="form-control">
                        <option value="1" <?php if($pages->type == '1'){ echo 'selected'; } ?>>Web</option>
                        <option value="2" <?php if($pages->type == '2'){ echo 'selected'; } ?>>App</option>
                        <option value="3" <?php if($pages->type == '3'){ echo 'selected'; } ?>>Both</option>
                    </select>
                </div>

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" id='submitformbtn'>Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
