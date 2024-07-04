<?php $page = 'events'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
            Events
        @endslot
        @slot('li_1')
            Home
        @endslot
        @slot('li_2')
            Events
        @endslot
    @endcomponent

    <div class="content">
        <div class="container">
            <section class="services">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                        <div class="listing-item">
                            <div class="listing-img">
                                <a href="{{ url('event-details') }}">
                                    <img src="{{ URL::asset('/assets/img/events/event-01.jpg') }}" class="img-fluid" alt="Event">
                                </a>
                                <div class="date-info text-center">
                                    <h2>20</h2>
                                    <h6>Sep, 2023</h6>
                                </div>
                            </div>
                            <div class="listing-content">
                                <ul class="d-flex justify-content-start align-items-center">
                                    <li>
                                        <i class="feather-clock"></i>06:20 AM
                                    </li>
                                    <li>
                                        <i class="feather-map-pin"></i>152, 1st Street New York
                                    </li>
                                </ul>
                                <h4 class="listing-title">
                                    <a href="{{ url('event-details') }}">Smash Masters</a>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                        <div class="listing-item">
                            <div class="listing-img">
                                <a href="{{ url('event-details') }}">
                                    <img src="{{ URL::asset('/assets/img/events/event-02.jpg') }}" class="img-fluid"
                                        alt="Event">
                                </a>
                                <div class="date-info text-center">
                                    <h2>19</h2>
                                    <h6>Sep, 2023</h6>
                                </div>
                            </div>
                            <div class="listing-content">
                                <ul class="d-flex justify-content-start align-items-center">
                                    <li>
                                        <i class="feather-clock"></i>06:20 AM
                                    </li>
                                    <li>
                                        <i class="feather-map-pin"></i>152, 1st Street New York
                                    </li>
                                </ul>
                                <h4 class="listing-title">
                                    <a href="{{ url('event-details') }}">Rise to Victory</a>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                        <div class="listing-item">
                            <div class="listing-img">
                                <a href="{{ url('event-details') }}">
                                    <img src="{{ URL::asset('/assets/img/events/event-03.jpg') }}" class="img-fluid"
                                        alt="Event">
                                </a>
                                <div class="date-info text-center">
                                    <h2>18</h2>
                                    <h6>Sep, 2023</h6>
                                </div>
                            </div>
                            <div class="listing-content">
                                <ul class="d-flex justify-content-start align-items-center">
                                    <li>
                                        <i class="feather-clock"></i>06:20 AM
                                    </li>
                                    <li>
                                        <i class="feather-map-pin"></i>152, 1st Street New York
                                    </li>
                                </ul>
                                <h4 class="listing-title">
                                    <a href="{{ url('event-details') }}">Shuttle Storm</a>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                        <div class="listing-item">
                            <div class="listing-img">
                                <a href="{{ url('event-details') }}">
                                    <img src="{{ URL::asset('/assets/img/events/event-04.jpg') }}" class="img-fluid"
                                        alt="Event">
                                </a>
                                <div class="date-info text-center">
                                    <h2>17</h2>
                                    <h6>Sep, 2023</h6>
                                </div>
                            </div>
                            <div class="listing-content">
                                <ul class="d-flex justify-content-start align-items-center">
                                    <li>
                                        <i class="feather-clock"></i>06:20 AM
                                    </li>
                                    <li>
                                        <i class="feather-map-pin"></i>152, 1st Street New York
                                    </li>
                                </ul>
                                <h4 class="listing-title">
                                    <a href="{{ url('event-details') }}">Flight of the Feathers</a>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                        <div class="listing-item">
                            <div class="listing-img">
                                <a href="{{ url('event-details') }}">
                                    <img src="{{ URL::asset('/assets/img/events/event-05.jpg') }}" class="img-fluid"
                                        alt="Event">
                                </a>
                                <div class="date-info text-center">
                                    <h2>16</h2>
                                    <h6>Sep, 2023</h6>
                                </div>
                            </div>
                            <div class="listing-content">
                                <ul class="d-flex justify-content-start align-items-center">
                                    <li>
                                        <i class="feather-clock"></i>06:20 AM
                                    </li>
                                    <li>
                                        <i class="feather-map-pin"></i>152, 1st Street New York
                                    </li>
                                </ul>
                                <h4 class="listing-title">
                                    <a href="{{ url('event-details') }}">Battle at the Net</a>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                        <div class="listing-item">
                            <div class="listing-img">
                                <a href="{{ url('event-details') }}">
                                    <img src="{{ URL::asset('/assets/img/events/event-06.jpg') }}" class="img-fluid"
                                        alt="Event">
                                </a>
                                <div class="date-info text-center">
                                    <h2>15</h2>
                                    <h6>Sep, 2023</h6>
                                </div>
                            </div>
                            <div class="listing-content">
                                <ul class="d-flex justify-content-start align-items-center">
                                    <li>
                                        <i class="feather-clock"></i>06:20 AM
                                    </li>
                                    <li>
                                        <i class="feather-map-pin"></i>152, 1st Street New York
                                    </li>
                                </ul>
                                <h4 class="listing-title">
                                    <a href="{{ url('event-details') }}">Badminton Fusion</a>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
