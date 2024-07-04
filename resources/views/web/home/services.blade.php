<?php $page = 'services'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
         {{__('string.categories')}}
        @endslot
        @slot('li_1')
            {{__('string.home')}}
        @endslot
        @slot('li_2')
          {{__('string.categories')}}
        @endslot
    @endcomponent
    <style>
        .listing-img img {
            width: 100%;
            height: 328px;
        }
    </style>

	<div class="content">
		<div class="container">
			<section class="services">
				<ul class="nav nav-pills mb-3 navpills" id="pills-tab" role="tablist">
					<li class="nav-item" role="presentation">
					<button class="nav-link @if(Request::segment(2)=='all-categories')  active @endif" id="all-categories-tab" data-bs-toggle="pill" data-bs-target="#all-categories" type="button" role="tab" aria-controls="all-categories" aria-selected="@if(Request::segment(2)=='all-categories')  true @else false @endif">{{__('string.all_categories')}}</button>
					</li>
					@foreach($categories as $cat)
					<li class="nav-item" role="presentation">
				    	<button class="nav-link @if(Request::segment(2)==$cat->slug)  active @endif" id="{{$cat->slug}}-tab" data-bs-toggle="pill" data-bs-target="#{{$cat->slug}}" type="button" role="tab" aria-controls="{{$cat->slug}}" aria-selected="@if(Request::segment(2)==$cat->slug)  true @else false @endif">{{$cat->title}}</button>
					</li>
					@endforeach
				</ul>
				<div class="tab-content" id="pills-tabContent">
					<div class="tab-pane fade @if(Request::segment(2)=='all-categories') show active @endif" id="all-categories" role="tabpanel" aria-labelledby="all-categories-tab">
						<div class="row">
						    @foreach($services as $serv )
								<div class="col-12 col-sm-12 col-md-4 col-lg-4 d-flex">
									<div class="listing-item">
										<div class="listing-img">
											<a href="{{ url('item-details/'.$serv->slug) }}">
												<img src="{{url('/public/storage/'.$serv->thumbnail)}}" class="img-fluid" alt="Service">
											</a>
											
											<div class="fav-item-venues">
                                                <span class="tag tag-blue">{{$serv->category->title}}</span>
                                            <div class="list-reviews coche-star">
                                                <a href="javascript:void(0)" class="fav-icon @if(favouriteList($serv->id)) selected @endif" rel="{{$serv->id}}">
                                                    <i class="feather-heart" rel="{{$serv->id}}"></i>
                                                </a>
                                            </div>
                                            </div>
                                            <div class="hour-list dis-price">
                                                @if($serv->discount>0)
                                                <h5 class="tag tag-primary mx-2 original-price">{{$settings->currency}}{{number_format($serv->price,2)}}<span>/hr</span></h5>
                                                @endif
                                                <h5 class="tag tag-primary display-price">{{$settings->currency}}{{number_format($serv->price-($serv->price*$serv->discount)/100,2)}}<span>/hr</span></h5>
                                            </div>
										</div>
										<div class="listing-content text-center">
											<h3 class="listing-title">
												<a href="{{url('service-detail')}}">{{$serv->title}}</a>
											</h3>
											<p class="para">{{$serv->about}}</p>
										    <a href="{{ url('item-details/'.$serv->slug) }}" class="btn btn-secondary">{{__('string.view_details')}}</a>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
					
					@foreach($categories as $cat)
						<div class="tab-pane fade @if(Request::segment(2)==$cat->slug)  show active @endif" id="{{$cat->slug}}" role="tabpanel" aria-labelledby="{{$cat->slug}}-tab">
							<div class="row">
							    @foreach($cat->services as $serv )
    								<div class="col-12 col-sm-12 col-md-4 col-lg-4">
    									<div class="listing-item">
    										<div class="listing-img">
    											<a href="{{ url('item-details/'.$serv->slug) }}">
    												<img src="{{url('/public/storage/'.$serv->thumbnail)}}" class="img-fluid" alt="Service">
    											</a>
    											<div class="fav-item-venues">
                                                    <span class="tag tag-blue">{{$serv->category->title}}</span>
                                                <div class="list-reviews coche-star">
                                                    <a href="javascript:void(0)" class="fav-icon @if(favouriteList($serv->id)) selected @endif" rel="{{$serv->id}}">
                                                        <i class="feather-heart" rel="{{$serv->id}}"></i>
                                                    </a>
                                                </div>
                                                </div>
                                                <div class="hour-list dis-price">
                                                    @if($serv->discount>0)
                                                    <h5 class="tag tag-primary mx-2 original-price">{{$settings->currency}}{{number_format($serv->price,2)}}<span>/hr</span></h5>
                                                    @endif
                                                    <h5 class="tag tag-primary display-price">{{$settings->currency}}{{number_format($serv->price-($serv->price*$serv->discount)/100,2)}}<span>/hr</span></h5>
                                                </div>
    										</div>
    										<div class="listing-content text-center">
    											<h3 class="listing-title">
    												<a href="{{url('service-detail')}}">{{$serv->title}}</a>
    											</h3>
                                            	<p class="para">{{$serv->about}}</p>	
                                            	<a href="{{ url('item-details/'.$serv->slug) }}" class="btn btn-secondary">{{__('string.view_details')}}</a>
    										</div>
    									</div>
    								</div>
							    @endforeach	
							</div>
						</div>
					@endforeach
				</div>
			</section>
		</div>
	</div>
@endsection