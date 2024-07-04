<?php $page = 'index'; ?>
@extends('layout.mainlayout')

<?php 
    use App\Models\GlobalFunction;
    if(!empty($banner->image)){
        $banner_img = GlobalFunction::createMediaUrl($banner->image);
    }else{
        $banner_img = url('/assets/img/bg/banner.jpg');
    }
    
    if(!empty($lang)){
        $title_lang = 'question_'.$lang;
        $desc_lang = 'answer_'.$lang;
    }else{
        $title_lang = 'question_en';
        $desc_lang = 'answer_en';
    }
?>

<style>
    .view_more{
        color: #097E52;
        font-weight: 600;
        border: 2px solid #097E52;
        border-radius: 37px;
        padding:5px 20px
    }
    
    .hero-section .section-search .search-box {
        width: 100%;
        background: #F9F9F6;
        box-shadow: 0px 4px 24px rgba(212, 212, 212, 0.25);
        border-radius: 11px;
        padding: 4px !important;
        margin-top: 40px;
        display: inline-block;
    } 
    
    .hero-section{
        background-image: url({{$banner_img}}) !important;
    }
    
    .service-count-blk {
        left: 170px !important;
        width: 350px;
    }
    @media (max-width: 767.98px) {
    .service-count-blk .coach-count {
        position: relative;
        top: -60px;
        right:160px;}
    }
</style>
@section('content')

    <section class="hero-section">
        <div class="banner-shapes">
        </div>
        <div class="container">
            <div class="home-banner">
                <div class="row align-items-center w-100">
                    <div class="col-lg-9 col-md-10 mx-auto">
                        <div class="section-search aos" data-aos="fade-up">
                            <h4> {{ __('string.welcome_to_paradise') }}</h4>
                            <h1>{{__('string.your_passport_to_sun')}} <span>{{__('string.beach')}}</span> {{__('string.bliss')}}</h1>
                            <p class="sub-info">{{__('string.where_every_beach')}}</p>
                            <div class="search-box">
                                <form  class="mb-0 p-1" action="{{route('coaches-grid')}}" method="get">
                                    <div class="search-input line">
                                        <div class="form-group mb-0">
                                            <select class="select" name="category" required>
                                                 <option value="0">{{__("string.all_category")}}</option>
                                                @if(!empty($categories))
                                                  @foreach($categories as $cat)
                                                   <option  value="{{$cat->id}}">{{$cat->title}}</option>
                                                  @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="search-input">
                                        <div class="form-group mb-0">
                                            <input class="form-control" type="text"  name="keyword" placeholder="{{__('string.search_for')}}"  />
                                        </div>
                                    </div>
                                    <div class="search-btn">
                                        <button class="btn" type="submit"><i class="feather-search"></i><span class="search-text">Search</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section work-section">
        <div class="container">
            <div class="section-heading aos" data-aos="fade-up">
                <h2>{{__('string.how_it_works')}} <span>{{__('string.works')}}</span></h2>
                <p class="sub-title">{{__('string.easy_registration_process')}}</p>
            </div>
            <div class="row justify-content-center ">
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="work-grid w-100 aos" data-aos="fade-up">
                        <div class="work-icon">
                            <div class="work-icon-inner">
                                <img src="{{ URL::asset('/assets/img/icons/work-icon2.svg') }}" alt="Icon">
                            </div>
                        </div>
                        <div class="work-content">
                            <h5>
                                <a href="{{ url('register') }}">{{__('string.join_us')}}</a>
                            </h5>
                            <p>{{__('string.quick_and_easy_registration')}}</p>
                            <a class="btn" href="{{ url('register') }}">
                                {{__('string.register_now')}} <i class="feather-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="work-grid w-100 aos" data-aos="fade-up">
                        <div class="work-icon">
                            <div class="work-icon-inner">
                                <img src="{{ URL::asset('/assets/img/icons/work-icon1.svg') }}" alt="Icon">
                            </div>
                        </div>
                        <div class="work-content">
                            <h5>
                                <a href="{{ url('items-facilities') }}">{{__('string.select_facilities_items')}}</a>
                            </h5>
                            <p>{{__('string.book_beach_facilities')}}</p>
                            <a class="btn" href="{{ url('items-facilities') }}">
                                {{__('string.go_to_facilities')}} <i class="feather-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="work-grid w-100 aos" data-aos="fade-up">
                        <div class="work-icon">
                            <div class="work-icon-inner">
                                <img src="{{ URL::asset('/assets/img/icons/work-icon3.svg') }}" alt="Icon">
                            </div>
                        </div>
                        <div class="work-content">
                            <h5>
                                <a href="{{ url('items-facilities') }}">{{__('string.booking_process')}}</a>
                            </h5>
                            <p>{{__('string.easily_book')}}</p>
                            <a class="btn" href="{{ url('items-facilities') }}">
                               {{__('string.book_now')}} <i class="feather-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section featured-venues">
        <div class="container">
            <div class="section-heading aos" data-aos="fade-up">
                <h2>{{__('string.featured')}} <span>{{__('string.facilities')}}</span></h2>
                <p class="sub-title">{{__('string.advanced_offers_on')}}</p>
            </div>
            <div class="row">
                <div class="featured-slider-group ">
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
                                            @if(Auth::check())
                                              <a href="{{ url('booking-details/'.$facilities->slug) }}" class="user-book-now"><span><i class="feather-calendar me-2"></i></span>{{__('string.book_now')}}</a>
                                            @else
                                                 <a href="javascript:void(0)" class="user-book-now checkLogin"><span><i class="feather-calendar me-2"></i></span>{{__('string.book_now')}}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach  
                    </div>
                </div>
            </div>

            <div class="view-all text-center aos" data-aos="fade-up">
                <a href="{{ url('items-facilities') }}" class="btn btn-secondary d-inline-flex align-items-center">{{__('string.view_all_featured')}}<span class="lh-1"><i class="feather-arrow-right-circle ms-2"></i></span></a>
            </div>
        </div>
    </section>

    <section class="section service-section">
        <div class="container">
            <div class="section-heading aos" data-aos="fade-up">
                <h2>{{__('string.explore_our_categories')}} <span>{{__('string.categories')}}</span></h2>
                <p class="sub-title">{{__('string.fostering_excellence')}}</p>
            </div>
            <div class="row">
                @foreach($categories as $cat)
                    <div class="col-lg-4 col-md-6 d-flex">
                        <div class="service-grid w-100 aos" data-aos="fade-up">
                            <div class="service-img">
                                <a href="{{ route('categories',$cat->slug) }}">
                                    <img src="{{url('/public/storage/'.$cat->web_icon??$cat->icon)}}" class="img-fluid imgfluid"
                                        alt="Service">
                                </a>
                            </div>
                            <div class="service-content">
                                <h4 class="mb-4"><a href="{{ route('categories',$cat->slug) }}">{{$cat->title}}</a></h4>
                                <a href="{{ route('categories',$cat->slug) }}" class="view_more">{{__('string.learn_more')}}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="view-all text-center aos" data-aos="fade-up">
                <a href="{{ route('categories','all-categories') }}" class="btn btn-secondary d-inline-flex align-items-center">
                   {{__('string.view_all_categories')}} <span class="lh-1"><i class="feather-arrow-right-circle ms-2"></i></span>
                </a>
            </div>
        </div>
    </section>

    <section class="section convenient-section">
        <div class="container">
            <div class="convenient-content aos" data-aos="fade-up">
                <h2>{{__('string.convenient_flexible_scheduling')}}</h2>
                <p>{{__('string.find_and_book')}}</p>
            </div>
            <div class="convenient-btns aos" data-aos="fade-up">
                <a href="{{route('coaches-grid')}}" class="btn btn-primary d-inline-flex align-items-center">
                    {{__('string.book_now')}} <span class="lh-1"><i class="feather-arrow-right-circle ms-2"></i></span>
                </a>
                <a href="{{route('coaches-grid')}}" class="btn btn-secondary d-inline-flex align-items-center">
                   {{__('string.view_facilities')}} <span class="lh-1"><i class="feather-arrow-right-circle ms-2"></i></span>
                </a>
            </div>
        </div>
    </section>

    <section class="section best-services">
        <div class="container">
            <div class="section-heading aos" data-aos="fade-up">
                <h2>{{__('string.extra_benefits')}} <span>{{__('string.service_excellence')}}</span></h2>
                <p class="sub-title">{{__('string.advance_your')}}
                </p>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="best-service-img aos" data-aos="fade-up">
                        <img src="{{ URL::asset('/assets/img/main-services.png') }}" class="img-fluid" alt="Service">
                        <div class="service-count-blk">
                            <div class="coach-count coart-count">
                                <h3>{{__('string.facilities_services')}}</h3>
                                <h2><span class="counter-up">15</span>+</h2>
                                <h4>{{__('string.explore_nearby_beach')}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ask-questions aos" data-aos="fade-up">
                        <h3>{{__('string.frequently_asked_questions')}}</h3>
                        <p>{{__('string.here_are_some_faq')}}</p>
                        <div class="faq-info">
                            <div class="accordion" id="accordionExample">
                                
                                @foreach($faqs as $k=>$faq)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{$k}}">
                                            <a href="javascript:;" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapse{{$k}}" aria-expanded="true" aria-controls="collapse{{$k}}">
                                                {{ $faq->$title_lang }}
                                            </a>
                                        </h2>
                                        <div id="collapse{{$k}}" class="accordion-collapse collapse @if($k == '0') show @endif" aria-labelledby="heading{{$k}}" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="accordion-content">
                                                    <p>{{ $faq->$desc_lang }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                            </div>
                        </div>
                        <a href="{{ route('web.faqs') }}">
                            <center><button class="btn btn-primary mt-3">View All FAQ</button></center>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
