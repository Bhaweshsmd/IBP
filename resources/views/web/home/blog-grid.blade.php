<?php use App\Models\GlobalFunction; ?>

@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
            {{__('string.blogs')}}
        @endslot
        @slot('li_1')
            {{__('string.home')}}
        @endslot
        @slot('li_2')
            {{__('string.blogs')}}
        @endslot
    @endcomponent

	<div class="content blog-grid">
		<div class="container">
			<div class="row">
			    @foreach($blogs as $blog)
			        <?php
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
                            $blogimgUrl = URL::asset('/assets/img/venues/Travel Guide.jpg');
                            $blogimage = '<img src="' . $blogimgUrl . '" width="100%">';
                        } else {
                            $blogimgUrl = GlobalFunction::createMediaUrl($blog->image);
                            $blogimage = '<img src="' . $blogimgUrl . '" width="100%">';
                        }
                    ?>
					<div class="col-12 col-sm-12 col-md-6 col-lg-4">
					    <div class="featured-venues-item">
							<div class="listing-item">										
								<div class="listing-img">
									<a href="{{route('web.pages', $blog->slug)}}">
										<?php echo $blogimage; ?>
									</a>
									<div class="fav-item-venues news-sports">
									</div>
								</div>										
								<div class="listing-content news-content">
									<div class="listing-venue-owner">
										<div class="navigation">
											<a href="javascript:void(0)"><?php echo $image; ?> {{$admin_detail->first_name}} {{$admin_detail->last_name}} </a>
											<span ><i class="feather-calendar"></i>{{ Carbon\Carbon::parse($blog->created_at)->format('d-M-Y') }}</span>
										</div>												
									</div>
									<h3 class="listing-title blog-title">
										<a href="{{route('web.pages', $blog->slug)}}">{{$blog->$title_lang}}</a>
									</h3>
								</div>
							</div>
						</div>
					</div>
				@endforeach
			</div>
			{{ $blogs->links() }}
		</div>
	</div>
@endsection