@extends('layout.mainlayout')
@section('content')

    <?php
        if(!empty($lang)){
            $title_lang = $lang.'_title';
            $desc_lang = $lang.'_description';
        }else{
            $title_lang = 'en_title';
            $desc_lang = 'en_description';
        }
    ?>
    
    @component('components.breadcrumb')
        @slot('title')
            <?php echo $page->$title_lang; ?>
        @endslot
        @slot('li_1')
            Home
        @endslot
        @slot('li_2')
            <?php echo $page->$title_lang; ?>
        @endslot
    @endcomponent
		<div class="content">
			<div class="container">
				<?php echo $page->$desc_lang; ?>
			</div>
		</div>
@endsection
