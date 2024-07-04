@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/maintenance.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>{{ __('Maintenance Settings Details') }}</h4>
            <a  href="{{ route('maintainance') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> {{ __('Back to Maintenance Settings List') }}</a>
        </div>
        <div class="card-body">
            
            <form action="{{ route('store.maintainance') }}" method="Post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                @csrf
                
                @foreach($languages as $language)
                    <?php 
                        $lang_desc = 'message_'.$language->short_name;
                        $lang_title = 'subject_'.$language->short_name;
                    ?>
                    
                    <div class="form-group">
                        <label>{{ $language->name }} Subject</label>
                        <input type="text" class="form-control" name="{{$lang_title}}">
                    </div>
                    
                    <div class="form-group">
                        <label>{{ $language->name }} Message</label>
                        <textarea class="form-control" name="{{$lang_desc}}"></textarea>
                    </div>
                @endforeach
                
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" class="form-control" name="date">
                </div>
                
                <div class="form-group">
                    <label>From Time</label>
                    <input type="time" class="form-control" name="from_time">
                </div>
                
                <div class="form-group">
                    <label>To Time</label>
                    <input type="time" class="form-control" name="to_time">
                </div>
                
                <div class="form-group">
                    <label>Platform</label>
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
