<?php $page = 'coach-detail'; ?>
@extends('layout.mainlayout')
@section('content')

    <div class="banner">
        <img src="{{ URL::asset('/public/storage/'.$company_details->banner)}}" alt="Banner" style="width:100%;height:173px">
    </div>
  
    <div class="content">
        <div class="container">
            <div class="row move-top">
                <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                    <div class="dull-bg corner-radius-10 coach-info d-md-flex justify-content-start align-items-start">
                        <div class="profile-pic">
                            <a href="javascript:void(0);"><img alt="User" class="corner-radius-10" src="{{ URL::asset('/public/storage/'.$company_details->owner_photo)}}" width="130"></a>
                        </div>
                        <div class="info w-100">
                            <div class="d-sm-flex justify-content-between align-items-start">
                                <h3 class="d-flex align-items-center justify-content-start mb-0">{{$company_details->salon_name}}<span class="d-flex justify-content-center align-items-center"><i class="fas fa-check-double"></i></span></h3>
                            </div>
                            
                            <ul class="d-sm-flex align-items-center">
                                <li class="d-flex align-items-center">
                                    <div class="white-bg d-flex align-items-center review">
                                        <span class="white-bg dark-yellow-bg d-flex align-items-center">{{number_format($company_details->rating,1)}}</span><span>{{$total_review}} Reviews</span>
                                    </div>
                                </li>
                                <li><i class="feather-phone-call"></i>&nbsp;{{$company_details->salon_phone}}</li>
                                <li><i class="feather-mail"></i><a href="mailto:{{$company_details->email}}">&nbsp;{{$company_details->email}}</a></li>
                                <br>
                            </ul>
                            <p><i class="feather-map-pin"></i>&nbsp; {{$company_details->salon_address}}</p>
                            <hr>
                        </div>
                    </div>
                    <div class="venue-options white-bg mb-4">
                        <ul class="clearfix">
                            <li class="active"><a href="#short-bio">Details</a></li>
                            <li><a href="#serviceID">Services</a></li>
                            <li><a href="#gallery">Gallery</a></li>
                            <li><a href="#reviews">Reviews</a></li>
                            <li><a href="#location">Area Map</a></li>
                        </ul>
                    </div>

                    <div class="accordion" id="accordionPanel">
                        <div class="accordion-item mb-4" id="short-bio">
                            <h4 class="accordion-header" id="panelsStayOpen-short-bio">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                    Details
                                </button>
                            </h4>
                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-short-bio">
                                <div class="accordion-body">
                                    <div class="text show-more-height">
                                        {{ $company_details->salon_about}}
                                    </div>
                                    <div class="show-more d align-items-center primary-text"><i class="feather-plus-circle"></i>Show More</div>
                                </div>
                            </div>
                        </div>
                    
                        <div class="accordion-item mb-4" id="gallery">
                            <h4 class="accordion-header" id="panelsStayOpen-gallery">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
                                    Gallery
                                </button>
                            </h4>
                            <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-gallery">
                                <div class="accordion-body">
                                    <div class="owl-carousel gallery-slider owl-theme">
                                         @if(!empty($gallery))
                                            @foreach($gallery as $gall)
                                                <div class="gallery-widget-item">
                                                    <a class="corner-radius-10" href="{{ url('public/storage/'.$gall->image) }}" data-fancybox="gallery2">
                                                        <img class="img-fluid corner-radius-10" alt="Image" src="{{ url('public/storage/'.$gall->image) }}">
                                                    </a>
                                                </div>
                                           @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item mb-4" id="reviews">
                            <div class="accordion-header" id="panelsStayOpen-reviews">
                                <div class="accordion-button d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSix" aria-controls="panelsStayOpen-collapseSix">
                                    <span class="w-75 mb-0">
                                        {{__('string.reviews')}}
                                    </span>
                                </div>
                            </div>
                            <div id="panelsStayOpen-collapseSix" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-reviews">
                                <div class="accordion-body">
                                    <div class="row review-wrapper">
                                        <div class="col-lg-3">
                                            <div class="ratings-info corner-radius-10 text-center">
                                                <h3>{{number_format($company_details->rating,1)}}</h3>
                                                <span>out of 5.0</span>
                                                
                                                <div class="rating">
                                                    @for($i=0;$i< (int)$company_details->rating;$i++)
                                                        <i class="fas fa-star filled"></i>
                                                    @endfor
                                                    @for($i=0;$i < 5-(int)$company_details->rating;$i++)
                                                        <i class="fa fa-star" aria-hidden="true" style="color:grey"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        @if(count($reviews))
                                            <div class="col-lg-9">
                                                <div class="recommended">
                                                    <h5>Recommended by {{count($reviews)}}+ Visitors</h5>
                                                    <div class="row">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif    
                                    </div>

                                    @foreach($reviews as $review)
                                        <div class="review-box d-md-flex">
                                            <div class="review-profile">
                                                <img src="{{ URL::asset('/public/storage/'.$review->user->profile_image)}}" alt="User">
                                            </div>
                                            <div class="review-info">
                                                <div class="rating">
                                                    @for($i=0;$i<$review->rating;$i++)
                                                        <i class="fas fa-star filled"></i>
                                                    @endfor
                                                    @for($i=0;$i < 5-$review->rating;$i++)
                                                        <i class="fa fa-star" aria-hidden="true" style="color:grey"></i>
                                                    @endfor 
                                                  
                                                    <span class="">{{number_format($review->rating,1)}}</span>
                                                    <h6>{{ucfirst($review->user->first_name)}}&nbsp;&nbsp;{{$review->user->last_name}}</h6>
                                                </div>
                                                <span class="success-text"><i class="feather-check"></i>{{$review->comment}}</span>
                                                <br>
                                                <span class="post-date">{{$review->created_at}}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-0" id="location">
                            <h4 class="accordion-header" id="panelsStayOpen-location">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSeven" aria-expanded="false" aria-controls="panelsStayOpen-collapseSeven">
                                    Area Map
                                </button>
                            </h4>
                            <div id="panelsStayOpen-collapseSeven" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-location">
                                <div class="accordion-body">
                                    <div class="owl-carousel map-images-slider owl-theme">
                                        @if(!empty($map_images))
                                            @foreach($map_images as $gallery)
                                                <a class="corner-radius-10" href="{{ url('public/storage/'.$gallery->image) }}" data-fancybox="gallery3">
                                                    <img class="img-fluid corner-radius-10" alt="Image" src="{{ url('public/storage/'.$gallery->image) }}">
                                                </a>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <aside class="col-12 col-sm-12 col-md-12 col-lg-4 theiaStickySidebar">
                    <div class="white-bg book-coach">
                        <h4 class="border-bottom">Availability</h4>
                        <ul class="">
                            <li class="d-flex align-items-center"><img src="{{ asset('assets/img/icons/since.svg') }}" alt="Icon">&nbsp;Monday-Friday&nbsp;{{date('h:i A',strtotime($company_details->mon_fri_from))}}-{{date('h:i A',strtotime($company_details->mon_fri_to))}}</li>
                            <li class="d-flex align-items-center"><img src="{{ asset('assets/img/icons/since.svg') }}" alt="Icon">&nbsp;Saturday-Sunday&nbsp;{{date('h:i A',strtotime($company_details->sat_sun_to))}}-{{date('h:i A',strtotime($company_details->sat_sun_to))}}</li>
                        </ul>
                    </div>
                    
                    <div class="white-bg">
                        <h4 class="border-bottom">Share Company Profile</h4>
                        <ul class="social-medias d-flex">
                            <li class="facebook"><a href="javascript:void(0);"><i class="fa-brands fa-facebook-f"></i></a></li>
                            <li class="instagram"><a href="javascript:void(0);"><i class="fa-brands fa-instagram"></i></a></li>
                            <li class="behance"><a href="javascript:void(0);"><i class="fa-brands fa-behance"></i></a></li>
                            <li class="twitter"><a href="javascript:void(0);"><i class="fa-brands fa-twitter"></i></a></li>
                            <li class="pinterest"><a href="javascript:void(0);"><i class="fa-brands fa-pinterest"></i></a></li>
                            <li class="linkedin"><a href="javascript:void(0);"><i class="fa-brands fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>

        <section class="innerpagebg pt-4 pb-5" id="serviceID">
            <div class="container">
                <div class="featured-slider-group">
                    <h3 class="mb-4">Services </h3>
                    <div class="owl-carousel featured-venues-slider owl-theme">
                        @foreach($featured_facilities as $facilities)    
                            <div class="featured-venues-item aos" data-aos="fade-up">
                                <div class="listing-item mb-0">
                                    <div class="listing-img">
                                        <a href="{{ url('item-details/'.$facilities->slug) }}">
                                            <img src="{{url('/public/storage/'.$facilities->thumbnail)}}" alt="Venue" >
                                        </a>
                                        
                                        <div class="fav-item-venues">
                                            <span class="tag tag-blue">{{$facilities->category->title}}</span>
                                        </div>
                                        <div class="hour-list dis-price">
                                            @if($facilities->discount>0)
                                                <h5 class="tag tag-primary mx-3 original-price">{{$settings->currency}}{{number_format($facilities->price,2)}}<span>/hr</span></h5>
                                            @endif
                                            <h5 class="tag tag-primary display-price">{{$settings->currency}}{{number_format($facilities->price-($facilities->price*$facilities->discount)/100,2)}}<span>/hr</span></h5>
                                        </div>
                                    </div>
                                    <div class="listing-content">
                                        <div class="list-reviews">
                                            <div class="d-flex align-items-center">
                                                <span class="rating-bg">{{number_format($facilities->rating,1)}}</span><span>{{count($facilities->reviews)}} {{__('string.reviews')}}</span>
                                            </div>
                                            <a href="javascript:void(0)" class="fav-icon @if(favouriteList($facilities->id)) selected @endif" rel="{{$facilities->id}}">
                                                <i class="feather-heart" rel="{{$facilities->id}}"></i>
                                            </a>
                                        </div>
                                        <h3 class="listing-title">
                                            <a href="{{ url('item-details/'.$facilities->slug) }}">{{$facilities->title}}</a>
                                        </h3>
                                        <div class="listing-details-group">
                                            <p class="para">{{$facilities->about}}</p>
                                            <ul>
                                            </ul>
                                        </div>
                                        <div class="listing-button">
                                            <div class="listing-venue-owner">
                                            </div>
                                            <a href="{{ url('item-details/'.$facilities->slug) }}" class="user-book-now"><span><i class="feather-calendar me-2"></i></span>{{__('string.book_now')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach 
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
