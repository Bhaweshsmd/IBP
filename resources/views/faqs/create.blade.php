@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/faq.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>{{ __('FAQ Details') }}</h4>
            <a  href="{{ route('faqs') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> Back to FAQ's List</a>
        </div>
        <div class="card-body">
            
            <form action="{{ route('faqs.store') }}" method="Post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                @csrf
                
                <div class="form-group">
                    <label> {{ __('Category') }}</label>
                    <select name="category_id" id="category" class="form-control form-control-sm" aria-label="Default select example">
                        @foreach ($cats as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                @foreach($languages as $language)
                    <?php 
                        $lang_desc = 'question_'.$language->short_name;
                        $lang_title = 'answer_'.$language->short_name;
                    ?>
                    
                    <div class="form-group">
                        <label>{{ $language->name }} Question</label>
                        <input type="text" class="form-control" name="{{$lang_desc}}">
                    </div>
                    
                    <div class="form-group">
                        <label>{{ $language->name }} Answer</label>
                        <textarea class="form-control" name="{{$lang_title}}"></textarea>
                    </div>
                @endforeach
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" id='submitformbtn'>Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
