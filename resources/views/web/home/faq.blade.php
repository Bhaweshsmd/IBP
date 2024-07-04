<?php
    $page = 'faq';
    
    if(!empty($lang)){
        $title_lang = 'question_'.$lang;
        $desc_lang = 'answer_'.$lang;
    }else{
        $title_lang = 'question_en';
        $desc_lang = 'answer_en';
    }
?>

@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
            Faq
        @endslot
        @slot('li_1')
            Home
        @endslot
        @slot('li_2')
            Faq
        @endslot
    @endcomponent

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12 offset-sm-12 offset-md-1 col-md-10 col-lg-10">
                    <div class="ask-questions">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
