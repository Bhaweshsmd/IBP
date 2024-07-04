<?php $page = 'user-bookings'; ?>
@extends('layout.mainlayout')

@section('content')
    @component('components.breadcrumb')
        @slot('title')
        {{__('string.support_ticket')}}
        @endslot
        @slot('li_1')
            {{__('string.home')}}
        @endslot
        @slot('li_2')
        {{__('string.support_ticket')}}
        @endslot
    @endcomponent
<style>
    .user-info-wrapper {
    position: relative;
    width: 100%;
    margin-bottom: -1px;
    padding-top: 90px;
    padding-bottom: 30px;
    border: 1px solid #e1e7ec;
    border-top-left-radius: 7px;
    border-top-right-radius: 7px;
    overflow: hidden
}

.user-info-wrapper .user-cover {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 120px;
    background-position: center;
    background-color: #f5f5f5;
    background-repeat: no-repeat;
    background-size: cover
}

.user-info-wrapper .user-cover .tooltip .tooltip-inner {
    width: 230px;
    max-width: 100%;
    padding: 10px 15px
}

.user-info-wrapper .info-label {
    display: block;
    position: absolute;
    top: 18px;
    right: 18px;
    height: 26px;
    padding: 0 12px;
    border-radius: 13px;
    background-color: #fff;
    color: #606975;
    font-size: 12px;
    line-height: 26px;
    box-shadow: 0 1px 5px 0 rgba(0, 0, 0, 0.18);
    cursor: pointer
}

.user-info-wrapper .info-label>i {
    display: inline-block;
    margin-right: 3px;
    font-size: 1.2em;
    vertical-align: middle
}

.user-info-wrapper .user-info {
    display: table;
    position: relative;
    width: 100%;
    padding: 0 18px;
    z-index: 5
}

.user-info-wrapper .user-info .user-avatar,
.user-info-wrapper .user-info .user-data {
    display: table-cell;
    vertical-align: top
}

.user-info-wrapper .user-info .user-avatar {
    position: relative;
    width: 115px
}

.user-info-wrapper .user-info .user-avatar>img {
    display: block;
    width: 100%;
    border: 5px solid #fff;
    border-radius: 50%
}

.user-info-wrapper .user-info .user-avatar .edit-avatar {
    display: block;
    position: absolute;
    top: -2px;
    right: 2px;
    width: 36px;
    height: 36px;
    transition: opacity .3s;
    border-radius: 50%;
    background-color: #fff;
    color: #606975;
    line-height: 34px;
    box-shadow: 0 1px 5px 0 rgba(0, 0, 0, 0.2);
    cursor: pointer;
    opacity: 0;
    text-align: center;
    text-decoration: none
}

.user-info-wrapper .user-info .user-avatar .edit-avatar::before {
    font-family: feather;
    font-size: 17px;
    content: '\e058'
}

.user-info-wrapper .user-info .user-avatar:hover .edit-avatar {
    opacity: 1
}

.user-info-wrapper .user-info .user-data {
    padding-top: 48px;
    padding-left: 12px
}

.user-info-wrapper .user-info .user-data h4 {
    margin-bottom: 2px
}

.user-info-wrapper .user-info .user-data span {
    display: block;
    color: #9da9b9;
    font-size: 13px
}

.user-info-wrapper+.list-group .list-group-item:first-child {
    border-radius: 0
}

.user-info-wrapper+.list-group .list-group-item:first-child {
    border-radius: 0;
}
.list-group-item:first-child {
    border-top-left-radius: 7px;
    border-top-right-radius: 7px;
}
.list-group-item:first-child {
    border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;
}
a.list-group-item {
    padding-top: .87rem;
    padding-bottom: .87rem;
}
a.list-group-item, .list-group-item-action {
    transition: all .25s;
    color: #606975;
    font-weight: 500;
}
.with-badge {
    position: relative;
    padding-right: 3.3rem;
}
.list-group-item {
    border-color: #e1e7ec;
    background-color: #fff;
    text-decoration: none;
}
.list-group-item {
    position: relative;
    display: block;
    padding: .75rem 1.25rem;
    margin-bottom: -1px;
    background-color: #fff;
    border: 1px solid rgba(0,0,0,0.125);
}

.badge.badge-primary {
    background-color: #0da9ef;
}
.with-badge .badge {
    position: absolute;
    top: 50%;
    right: 1.15rem;
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
}
.list-group-item i {
    margin-top: -4px;
    margin-right: 8px;
    font-size: 1.1em;
}



.comment {
    display: block;
    position: relative;
    margin-bottom: 30px;
    padding-left: 66px
}

.comment .comment-author-ava {
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: 50px;
    border-radius: 50%;
    overflow: hidden
}

.comment .comment-author-ava>img {
    display: block;
    width: 100%
}

.comment .comment-body {
    position: relative;
    padding: 24px;
    border: 1px solid #e1e7ec;
    border-radius: 7px;
    background-color: #fff
}

.comment .comment-body::after,
.comment .comment-body::before {
    position: absolute;
    top: 12px;
    right: 100%;
    width: 0;
    height: 0;
    border: solid transparent;
    content: '';
    pointer-events: none
}

.comment .comment-body::after {
    border-width: 9px;
    border-color: transparent;
    border-right-color: #fff
}

.comment .comment-body::before {
    margin-top: -1px;
    border-width: 10px;
    border-color: transparent;
    border-right-color: #e1e7ec
}

.comment .comment-title {
    margin-bottom: 8px;
    color: #606975;
    font-size: 14px;
    font-weight: 500
}

.comment .comment-text {
    margin-bottom: 12px
}

.comment .comment-footer {
    display: table;
    width: 100%
}

.comment .comment-footer>.column {
    display: table-cell;
    vertical-align: middle
}

.comment .comment-footer>.column:last-child {
    text-align: right
}

.comment .comment-meta {
    color: #9da9b9;
    font-size: 13px
}

.comment .reply-link {
    transition: color .3s;
    color: #606975;
    font-size: 14px;
    font-weight: 500;
    letter-spacing: .07em;
    text-transform: uppercase;
    text-decoration: none
}

.comment .reply-link>i {
    display: inline-block;
    margin-top: -3px;
    margin-right: 4px;
    vertical-align: middle
}

.comment .reply-link:hover {
    color: #0da9ef
}

.comment.comment-reply {
    margin-top: 30px;
    margin-bottom: 0
}
.float-right{
    float:right;
}
@media (max-width: 576px) {
    .comment {
        padding-left: 0
    }
    .comment .comment-author-ava {
        display: none
    }
    .comment .comment-body {
        padding: 15px
    }
    .comment .comment-body::before,
    .comment .comment-body::after {
        display: none
    }
}
</style>

	<div class="content court-bg">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="court-tab-content">
						<div class="card card-tableset">
							<div class="card-body">
				                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                @if(session('success'))
                                    <div class="alert alert-success">
                                        <ul>
                                             <li>{{session('success')}}</li>
                                       </ul>
                                    </div>   
                                    <h1></h1>
                                @endif 	
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="padding-top-2x mt-2 hidden-lg-up"></div>
                                        <div class="table-responsive margin-bottom-2x mb-3">
                                            <a href="{{route('tickets')}}" class="btn btn-success float-right mb-3" >{{__('string.view_all_tickets')}}</a>
                                            <table class="table margin-bottom-none">
                                                <thead>
                                                    <tr>
                                                        <th>{{__('string.ticket_id')}}</th>
                                                        <th>{{__('string.date_submitted')}}</th>
                                                        <th>{{__('string.reason')}}</th>
                                                        <th>{{__('string.priority')}}</th>
                                                        <th>{{__('string.status')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{$tickets->ticket_id}}</td>
                                                        <td>{{date('D, d M Y',strtotime($tickets->created_at))}}</td>
                                                        <td>{{$tickets->reason}}</td>
                                                        <td><span class="text-warning">{{$tickets->priority}}</span></td>
                                                        <td><span class="text-primary">{{$tickets->status}}</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        @php 
                                            if($userDetails->profile_image){
                                                $profile_image= url('/public/storage/'.$userDetails->profile_image);
                                            }else{
                                                $profile_image= "https://placehold.jp/150x150.png";
                                            }
                                        @endphp
                         
                                        @if($ticket_message)
                                            @foreach($ticket_message as $message)
                                                @if($message->type=="user")
                                                    <div class="comment">
                                                        <div class="comment-author-ava"><img src="{{$profile_image}}" style="height: 50px;" alt="Avatar"></div>
                                                        <div class="comment-body">
                                                            <p class="comment-text"><?php echo $message->message; ?></p> 
                                                            <div class="comment-footer"><span class="comment-meta">{{$userDetails->first_name.' '.$userDetails->last_name}}</span>&nbsp;<span class="comment-meta">{{date('D, d M Y',strtotime($message->created_at))}}</span></div>
                                                        </div>
                                                        @if($message->attachment)
                                                             <a href="{{url('/public/storage/'.$message->attachment)}}" target="_blank"  class="ml-5 text-success" >{{__('string.view_attachment')}}</a>
                                                        @endif
                                                    </div>
                                                @else
                                                    @php 
                                                        if($company_details->owner_photo){
                                                            $company_image= url('/public/storage/'.$company_details->owner_photo);
                                                        }else{
                                                            $company_image= "https://placehold.jp/150x150.png";
                                                        }
                                                    @endphp   
                                                   
                                                    <div class="comment">
                                                        <div class="comment-author-ava"><img src="{{$company_image}}" style="height: 50px;" alt="Avatar"></div>
                                                        <div class="comment-body">
                                                            <p class="comment-text"><?php echo $message->message; ?></p>
                                                            <div class="comment-footer"><span class="comment-meta">{{$company_details->salon_name}}</span>&nbsp;<span class="comment-meta">{{date('D, d M Y',strtotime($message->created_at))}}</span></div>
                                                        </div>
                                                        @if($message->attachment)
                                                            <a href="{{url('/public/storage/'.$message->attachment)}}" target="_blank"  class="ml-5 text-success" >{{__('string.view_attachment')}}</a>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
      
                                        <h5 class="mb-30 padding-top-1x">Leave Message</h5>
                                        <form method="post" action="{{route('tickets.reply')}}" enctype="multipart/form-data" >
                                            @csrf
                                            <div class="form-group">
                                                <textarea class="form-control form-control-rounded" name="message" id="review_text" rows="8" placeholder="{{__('string.Write_message')}}" required=""></textarea>
                                            </div>
                                            <div class="form-group mt-3">
                                                <input class="form-control form-control-rounded" type="file" name="attachment" id="review_text" rows="8" accept=".jpg,.png,.jpeg,.pdf" >
                                            </div>
                                            <div class="text-right">
                                                <button class="btn btn-outline-primary mt-4" type="submit">{{__('string.submit_message')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
							</div>
						</div> 
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection