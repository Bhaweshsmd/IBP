<?php
    use App\Models\GlobalFunction;
    
    if(!empty($lang)){
        $title_lang = $lang.'_title';
        $desc_lang = $lang.'_description';
    }else{
        $title_lang = 'en_title';
        $desc_lang = 'en_description';
    }
    
    $admin_detail = DB::table('admin_user')->where('user_id', $blog->added_by)->first();
    if(!empty($admin_detail->picture)){
        $imgUrl = GlobalFunction::createMediaUrl($admin_detail->picture);
        $image = '<img src="' . $imgUrl . '" width="50" height="auto">';
    }else{
        $image = '<img src="https://placehold.jp/150x150.png" width="50" height="auto">';
    }
    
    if ($blog->image == null) {
        $blogimgUrl = URL::asset('/assets/img/venues/defaultblog.jpg');
        $blogimage = '<img src="' . $blogimgUrl . '" width="100%">';
    } else {
        $blogimgUrl = GlobalFunction::createMediaUrl($blog->image);
        $blogimage = '<img src="' . $blogimgUrl . '" width="100%">';
    }
?>
                        
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Blog Details
        @endslot
        @slot('li_1')
            Home
        @endslot
        @slot('li_2')
        Blog Details
        @endslot
    @endcomponent
    
    <style>
        ul li {
            list-style-type: inherit !important;
        }
    </style>
		<div class="content blog-details">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 col-md-10 col-lg-8 mx-auto">
					    <div class="featured-venues-item">
							<div class="listing-item blog-info">										
								<div class="listing-img">
									<a href="{{url('blog-details')}}">
										<?php echo $blogimage; ?>
									</a>
								</div>										
								<div class="listing-content news-content">
									<div class="listing-venue-owner blog-detail-owner d-lg-flex justify-content-between align-items-center">
										<div class="navigation">
											<a href="javascript:void(0)"><?php echo $image; ?> {{$admin_detail->first_name}} {{$admin_detail->last_name}}</a>
											<span ><i class="feather-calendar"></i>{{ Carbon\Carbon::parse($blog->created_at)->format('d-M-Y') }}</span>
										</div>
									</div>
									<h2 class="listing-title">
										<a href="javascript:void(0)">{{ $blog->$title_lang }}</a>
									</h2>
									<?php echo $blog->$desc_lang; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    @endsection