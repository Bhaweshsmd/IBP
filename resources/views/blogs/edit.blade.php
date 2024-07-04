<?php use App\Models\GlobalFunction; ?>

@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/blogs.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>{{ __('Blog Details') }}</h4>
            <a  href="{{ route('blogs') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> {{ __('Back to Blog List') }}</a>
        </div>
        <div class="card-body">
            
            <form action="{{ route('blog.update', $blogs->id) }}" method="Post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                @csrf
                
                @foreach($languages as $language)
                    <?php 
                        $lang_desc = $language->short_name.'_description';
                        $lang_title = $language->short_name.'_title';
                    ?>
                    <div class="form-group">
                        <label>{{ $language->name }} Title</label>
                        <input type="text" class="form-control" name="{{$lang_title}}" value="<?php echo $blogs->$lang_title; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>{{ $language->name }} Content</label>
                        <textarea id="summernote" class="summernote-simple" name="{{$lang_desc}}"><?php echo $blogs->$lang_desc; ?></textarea>
                    </div>
                @endforeach
                
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" class="form-control" name="slug" value="<?php echo $blogs->slug; ?>">
                </div>
                
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" class="form-control" name="image"><br>
                    @if(!empty($blogs->image))
                        <?php
                            $imgUrl = GlobalFunction::createMediaUrl($blogs->image);
                            $image = '<img src="' . $imgUrl . '" width="500px;" height="auto">';
                            echo $image;
                        ?>
                    @endif
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1" <?php if($blogs->status == '1'){ echo 'selected'; } ?>>Active</option>
                        <option value="0" <?php if($blogs->status == '0'){ echo 'selected'; } ?>>Inctive</option>
                    </select>
                </div>
                
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" id='submitformbtn'>Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
