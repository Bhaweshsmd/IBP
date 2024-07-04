<?php $page = 'coaches-map-sidebar'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
           {{__('string.items_facilities')}}
        @endslot
        @slot('li_1')
           {{__('string.home')}}
        @endslot
        @slot('li_2')
           {{__('string.items_facilities')}}
        @endslot
    @endcomponent
    
    <style>
        .coach-btn {
            border-bottom: 0px;
            margin: 0 0 5px;
            padding: 0 0 5px;
        }
        .sortby-section .sorting-info .sortby-filter-group .grid-listview {
            margin: 0;
            padding: 0;
            border-right: unset;
        }
    </style>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sortby-section">
                        <div class="sorting-info">
                            <div class="row d-flex align-items-center">
                                <div class="col-xl-4 col-lg-3 col-sm-12 col-12">
                                    <div class="count-search">
                                        <p><span>{{count($services)}}+</span> {{__('string.items_facilities_listed')}}</p>
                                    </div>
                                </div>
                                <div class="col-xl-8 col-lg-9 col-sm-12 col-12">
                                    <div class="sortby-filter-group">
                                        <div class="grid-listview">
                                            <ul class="nav">
                                                <li>
                                                    <a href="{{url('items-facilities?category=')}}{{Request::get('category')}}&keyword={{Request::get('keyword')}}" class="active">
                                                        <img src="{{ URL::asset('/assets/img/icons/sort-01.svg') }}" alt="Icon">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('items-facilities-list?category=')}}{{Request::get('category')}}&keyword={{Request::get('keyword')}}">
                                                        <img src="{{ URL::asset('/assets/img/icons/sort-02.svg') }}" alt="Icon">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                <ul class="nav nav-pills mb-3 d-flex  navpills">
                  <li class="nav-item grid-nav-item @if(!Request::get('category')) active-item @endif"><a href="{{url('items-facilities?category=')}}{{0}}&keyword={{Request::get('keyword')}}">All</a></li>
                  @foreach($categories as $category)
                    <li for="{{$category->id}}" class="nav-item grid-nav-item @if(Request::get('category')==$category->id) active-item @endif "><a id="{{$category->id}}" href="{{url('items-facilities?category=')}}{{$category->id}}&keyword={{Request::get('keyword')}}">{{$category->title}}</a></li>
                  @endforeach
               </ul>
                  </div>
            </div>

            <div class="row justify-content-center">
                @foreach($services as $serv)
                    <div class="col-lg-4 col-md-6">
                        <div class="featured-venues-item">
                            <div class="listing-item listing-item-grid">
                                <div class="listing-img">
                                    <a href="{{ url('item-details/'.$serv->slug) }}">
                                        <img src="{{ url('public/storage/'.$serv->thumbnail) }}" alt="Venue">
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
                                        @if($serv->discount > 0)
                                        <h5 class="tag tag-primary mx-3 original-price ">{{$settings->currency}}{{number_format($serv->price, 2)}} <span>/hr</span></h5>
                                        @endif
                                        <h5 class="tag tag-primary display-price">{{$settings->currency}}{{number_format($serv->price-($serv->price*$serv->discount)/100,2)}} <span>/hr</span></h5>
                                        
                                    </div>
                                </div>
                         
                                <div class="listing-content">
                                    <div class="avalbity-review">
                                        <ul>
                                            <li>
                                                <div class="list-reviews mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <span class="rating-bg">{{number_format($serv->rating,1)}}</span><span>{{count($serv->reviews)}} {{__('string.reviews')}}</span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <h3 class="listing-title">
                                        <a href="{{ url('item-details/'.$serv->slug) }}">{{$serv->title}}</a>
                                    </h3>
                                    <ul class="mb-2">
                                        <li>
                                            <span>
                                                <i class="feather-map-pin me-2"></i>Port Alsworth, AK
                                            </span>
                                        </li>
                                    </ul>
                                    <div class="listing-details-group">
                                        <?php
                                            $servabout = strip_tags($serv->about);
                                            if (strlen($servabout) > 50) {
                                                $stringCut = substr($servabout, 0, 50);
                                                $endPoint = strrpos($stringCut, ' ');
                                                $servabout = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                            }
                                        ?>
                                        <p>{{$servabout}}... <a href="{{ url('item-details/'.$serv->slug) }}" class="text-primary">Read More</a></p>
                                    </div>
                                    <div class="coach-btn">
                                        <ul>
                                            <li>
                                                <a href="{{ url('item-details/'.$serv->slug) }}" class="btn btn-primary w-100"><i class="feather-eye me-2"></i>{{__('string.view_items')}}</a>
                                            </li>
                                            <li>
                                                @if(Auth::check())
                                                    <a href="{{ route('booking-details',$serv->slug) }}" class="btn btn-secondary w-100"><i class="feather-calendar me-2"></i>{{__('string.book_now')}}</a>
                                                @else
                                                    <a href="javascript:void(0)" class="btn btn-secondary w-100 checkLogin"><i class="feather-calendar me-2"></i>{{__('string.book_now')}}</a>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
