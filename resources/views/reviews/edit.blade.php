@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/reviews.js') }}"></script>
    <style>
        .starActive {
            color: orangered !important;
        }
        
        .starDisabled {
            color: rgb(184, 184, 184) !important;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>{{ __('Review Details') }}</h4>
            <a href="{{ route('reviews') }}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> Back To Reviews List</a>
        </div>
        <div class="card-body">
            <form action="{{ route('update.reviews', $review->id) }}" method="Post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                @csrf
                
                <?php 
                    $starDisabled = '<i class="fas fa-star starDisabled"></i>';
                    $starActive = '<i class="fas fa-star starActive"></i>';
        
                    $ratingBar = '';
                    for ($i = 0; $i < 5; $i++) {
                        if ($review->rating > $i) {
                            $ratingBar = $ratingBar . $starActive;
                        } else {
                            $ratingBar = $ratingBar . $starDisabled;
                        }
                    }
                    
                    echo $ratingBar;
                ?>
                
                <div class="form-row mt-3">
                    <div class="form-group col-md-6">
                        <label>Service</label>
                        <input class="form-control" value="{{$service->title}}" readonly> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Customer Id</label>
                        <input class="form-control" value="{{$user->profile_id}}" readonly> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Customer Name</label>
                        <input class="form-control" value="{{$user->first_name}} {{$user->last_name}}" readonly> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Booking Id</label>
                        <input class="form-control" value="{{$review->booking != null ? $review->booking->booking_id : ""}}" readonly> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Date</label>
                        <input class="form-control" value="{{date('d-m-Y h:i A',strtotime($review->created_at))}}" readonly> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Comment</label>
                        <input type="text" name="comment" class="form-control" value="{{$review->comment}}"> 
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="0"  @if($review->status == '0') selected @endif>Pending</option>
                            <option value="1"  @if($review->status == '1') selected @endif>Approved</option>
                            <option value="2"  @if($review->status == '2') selected @endif>Rejected</option>
                        </select>
                    </div>
                    
                    @if(has_permission(session()->get('user_type'), 'edit_reviews'))
                        <div class="form-group col-md-12 text-right">
                            <button type="submit" class="btn btn-primary" id='submitformbtn'>Update</button>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
