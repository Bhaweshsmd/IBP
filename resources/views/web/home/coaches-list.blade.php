<?php $page = 'coaches-list'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
          {{__('string.items_facilities_list')}}
        @endslot
        @slot('li_1')
            {{__('string.home')}}
        @endslot
        @slot('li_2')
            {{__('string.items_facilities_list')}}
        @endslot
    @endcomponent
    <style>
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
                                                    <a href="{{url('items-facilities?category=')}}{{Request::get('category')}}&keyword={{Request::get('keyword')}}" >
                                                        <img src="{{ URL::asset('/assets/img/icons/sort-01.svg') }}" alt="Icon">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('items-facilities-list?category=')}}{{Request::get('category')}}&keyword={{Request::get('keyword')}}" class="active">
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
                    <ul class="nav nav-pills mb-3 d-flex navpills ">
                        <li class="nav-item grid-nav-item @if(!Request::get('category')) active-item @endif"><a href="{{url('items-facilities?category=')}}{{0}}&keyword={{Request::get('keyword')}}">All</a></li>
                        @foreach($categories as $category)
                            <li for="{{$category->id}}" class="nav-item grid-nav-item @if(Request::get('category')==$category->id) active-item @endif "><a id="{{$category->id}}" href="{{url('items-facilities?category=')}}{{$category->id}}&keyword={{Request::get('keyword')}}">{{$category->title}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="row">
                @foreach($services as $serv)
                    <div class="col-lg-12">
                        <div class="featured-venues-item">
                            <div class="listing-item listing-item-grid coach-listview">
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
                                </div>
                                <div class="listing-content">
                                    <div class="list-reviews near-review near-review-list listing-price">
                                        <div class="d-flex align-items-center">
                                            <span class="rating-bg">{{number_format($serv->rating,1)}}</span><span>{{count($serv->reviews)}} {{__('string.reviews')}}</span>
                                        </div>
                                        @if($serv->discount>0)
                                            <span class="mile-away mx-3  list_view">{{$settings->currency}}{{number_format($serv->price,2)}} <span>/hr</span></span>
                                        @endif
                                        <span class="mile-away list-view"> {{$settings->currency}}{{number_format($serv->price-($serv->price*$serv->discount)/100,2)}} <span>/hr</span></span>
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
                                        <p class="para para-length">{{$serv->about}}</p>
                                    </div>
                                    <div class="avalbity-review avalbity-review-list">
                                        <ul class="profile-coache-list">
                                            <li>
                                                <a href="{{ url('item-details/'.$serv->slug) }}" class="btn btn-primary w-100"><i class="feather-eye me-2"></i> View Items</a>
                                            </li>
                                            @if(Auth::check())
                                                <li>
                                                    <a href="{{ url('booking-details/'.$serv->slug) }}" class="btn btn-secondary w-100"><i class="feather-calendar me-2"></i> Book Now</a>
                                                </li>
                                            @else
                                                <li>
                                                    <a href="javascript:void(0)" class="btn btn-secondary w-100 checkLogin"><i class="feather-calendar me-2"></i> Book Now</a>
                                                </li>
                                            @endif
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
