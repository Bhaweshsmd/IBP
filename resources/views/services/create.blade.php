@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/viewService.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/style/viewService.css') }}">
@endsection
<style>
    #map {
        height:500px;
    }
    
    .modal-dialog {
        max-width: 100% !important;
    }
</style>
@section('content')

    <div class="card">
        <div class="card-header">
            <h4>{{ __('Service Details') }}</h4>
            <a href="{{route('services')}}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> Back To Service List</a>
        </div>
        <div class="card-body">
            <form action="{{route('preview.services')}}" method="post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                @csrf
                <ul class="nav nav-pills border-b  ml-0">
                    <li role="presentation" class="nav-item active">
                        <a class="nav-link pointer active " href="#tabSlotstitle" role="tab" aria-controls="tabSlots" data-toggle="tab">{{ __('English') }}
                            <span class="badge badge-transparent "></span>
                        </a>
                    </li>
                    <li role="presentation" class="nav-item ">
                        <a class="nav-link pointer  " href="#tabPapiamentutitle" role="tab" aria-controls="tabSlots" data-toggle="tab">{{ __('Papiamentu') }}
                            <span class="badge badge-transparent "></span>
                        </a>
                    </li>
                    <li role="presentation" class="nav-item ">
                        <a class="nav-link pointer " href="#tabDutchtitle" role="tab" aria-controls="tabSlots" data-toggle="tab">{{ __('Dutch') }}
                            <span class="badge badge-transparent "></span>
                        </a>
                    </li>
                </ul>
                
                <div class="form-row">
                    <div class="tab-content tabs col-sm-12" id="home">     
                        <div role="tabpanel" class="tab-pane active" id="tabSlotstitle">
                            <div class="form-group ">
                                <label for="">{{ __('Title In English') }}</label>
                                <input type="text" class="form-control" name="title"   value="{{ old('title') }}">
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="tabPapiamentutitle">
                            <div class="form-group ">
                                <label for="">{{ __('Title In Papiamentu ') }}</label>
                                <input type="text" class="form-control" name="title_in_papiamentu"   value="{{ old('title_in_papiamentu') }}">
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="tabDutchtitle">
                            <div class="form-group ">
                                <label for="">{{ __('Title In Dutch ') }}</label>
                                <input type="text" class="form-control" name="title_in_dutch"   value="{{ old('title_in_dutch') }}">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-row ">
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Category') }}</label>
                        <select class="form-control" name="category_id" value="{{ old('category') }}" required>
                            <option value="" >{{__('Select category')}}</option>
                            @if(!empty($categories))
                                @foreach($categories as $cat)
                                    <option value="{{$cat->id}}" >{{$cat->title}}</option>
                                @endforeach
                            @endif  
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <a data-toggle="modal" data-target="#addBannerModal" href="" class="ml-auto badge bg-primary text-white text-white">{{ __('Select co-ordinates ') }}</a>
                        <label for="">{{ __('Service Latitude') }}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="latitude" id="latitude" value="{{ old('latitude') }}" >
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Service Longitude') }}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="logintude" id="logintude" value="{{ old('logintude') }}"   >
                        </div>
                    </div>
                    
                   
                    <input type="hidden" class="form-control" name="service_time"  value="60"  required>
                </div>
                
                <div class="form-row ">
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Resident Price Per Hours') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    {{ $settings->currency }}
                                </div>
                            </div>
                            <input type="number" class="form-control" name="price" step="any" value="{{ old('price') }}" required>
                        </div>
                    </div>
                   
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Resident Discount') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    %
                                </div>
                            </div>
                            <input type="number" class="form-control" name="discount" step="any" value="{{ old('discount') }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Resident Cancellation Charges') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    %
                                </div>
                            </div>
                            <input type="number" class="form-control" name="local_cancellation_charges" step="any" value="{{ old('local_cancellation_charges') }}" >
                        </div>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Non-Resident Price Per Hours') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    {{ $settings->currency }}
                                </div>
                            </div>
                            <input type="number" class="form-control" name="foreiner_price"  step="any" value="{{ old('foreiner_price') }}" required>
                        </div>
                    </div>
    
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Non-Resident Discount') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    %
                                </div>
                            </div>
                            <input type="number" class="form-control" name="foreiner_discount" min=0 step="any" value="{{ old('foreiner_discount') }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Non-Resident Cancellation Charges') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    %
                                </div>
                            </div>
                            <input type="number" class="form-control" name="foreiner_cancellation_charges" step="any" value="{{ old('foreiner_cancellation_charges') }}" >
                        </div>
                    </div>
                </div>
                
                <div class="form-row ">
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Service Thumbnail') }}</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="thumbnail" value="{{ old('thumbnail') }}" accept=".png,.jpeg,.jpg" required>
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Gallery') }}</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="images[]" value="{{ old('images[]') }}" multiple  accept=".png,.jpeg,.jpg">
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Service Map images') }}</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="map_images[]" value="{{ old('map_images[]') }}" multiple  accept=".png,.jpeg,.jpg">
                        </div>
                    </div>
                </div>
                <div class="form-row ">
                  <div class="form-group col-md-6">
                        <label for="">{{ __('Service Type') }}</label>
                        <div class="input-group">
                             <select class="form-control" name="service_type" value="{{ old('service_type') }}" required>
                                <option value="0" >{{__('Quantity Wise')}}</option>
                                <option value="1" >{{__('Member Wise')}}</option>
                                </select>
                        </div>
                    </div>
                       <div class="form-group col-md-6">
                        <label for="">{{ __('Qauntity') }}</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="qauntity" min="1"  value="{{ old('qauntity') }}" required>
                        </div>
                    </div>
                  </div> 
                <ul class="nav nav-pills border-b  ml-0">
                    <li role="presentation" class="nav-item active">
                        <a class="nav-link pointer active " href="#tabSlots" role="tab" aria-controls="tabSlots" data-toggle="tab">{{ __('English') }}
                            <span class="badge badge-transparent "></span>
                        </a>
                    </li>
                    <li role="presentation" class="nav-item ">
                        <a class="nav-link pointer  " href="#tabPapiamentu" role="tab" aria-controls="tabSlots" data-toggle="tab">{{ __('Papiamentu') }}
                            <span class="badge badge-transparent "></span>
                        </a>
                    </li>
                    <li role="presentation" class="nav-item ">
                        <a class="nav-link pointer " href="#tabDutch" role="tab" aria-controls="tabSlots" data-toggle="tab">{{ __('Dutch') }}
                            <span class="badge badge-transparent "></span>
                        </a>
                    </li>
                </ul>
                
                <div class="form-row ">
                    <div class="tab-content tabs col-sm-12" id="home">     
                        <div role="tabpanel" class="tab-pane active" id="tabSlots">
                            <div class="form-group">
                                <label for="">{{ __('About In English') }}</label>
                                <textarea type="text" class="form-control" name="about">{{ old('about') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Rules In English') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="rules">{{old('rules')}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('History In English') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="history">{{old('history')}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Included Items In English') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="included_items">{{old('included_items')}}</textarea>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="tabPapiamentu">
                            <div class="form-group ">
                                <label for="">{{ __('About In Papiamentu') }}</label>
                                <textarea type="text" class="form-control" name="about_in_papiamentu">{{ old('about_in_papiamentu') }}</textarea>
                            </div>
                            <div class="form-group ">
                                <label for="">{{ __('Rules In Papiamentu') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="rules_in_papiamentu">{{old('rules_in_papiamentu')}}</textarea>
                            </div>
                            <div class="form-group ">
                                <label for="">{{ __('History In Papiamentu') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="history_in_papiamentu">{{old('history_in_papiamentu')}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Included Items In Papiamentu') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="included_items_in_papiamentu">{{old('included_items_in_papiamentu')}}</textarea>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="tabDutch">
                            <div class="form-group ">
                                <label for="">{{ __('About In Dutch') }}</label>
                                <textarea type="text" class="form-control" name="about_in_dutch">{{ old('about_in_dutch') }}</textarea>
                            </div>
                             <div class="form-group ">
                                <label for="">{{ __('Rules In Dutch') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="rules_in_dutch">{{old('rules_in_dutch')}}</textarea>
                            </div>
                            <div class="form-group ">
                                <label for="">{{ __('History In Dutch') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="history_in_dutch">{{old('history_in_dutch')}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Included Items In Dutch') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="included_items_in_dutch">{{old('included_items_in_dutch')}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group text-right">
                    <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Preview') }}" id='submitformbtn'>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="addBannerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Select co-ordinates to click on image') }}</h5>&nbsp; &nbsp;
                    <spna>Latitude:</spna>&nbsp;<span id="let">0.0</span> &nbsp; &nbsp;
                    <spna>Longitude:</spna>&nbsp;<span id="long">0.0</span>
                    <button type="button" class="close btn btn-success" data-dismiss="modal" aria-label="Close" style="padding: 7px 12px; background-color: green;">Save</button>
                </div>
                <div class="modal-body">
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
                    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

                    <div class="content map-content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xl-12 map-right">
                                    <div id="map" class="maplisting"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        var map = L.map('map', {
                            crs: L.CRS.Simple
                        });
                
                        var imageUrl = "{{asset('assets/map.jpg')}}";
                        var imageBounds = [[0, 0], [500,1400]];
                        var imageOverlay   = L.imageOverlay(imageUrl, imageBounds).addTo(map);
                        map.fitBounds(imageBounds);
                    
                        var bounds = imageOverlay.getBounds();
                        var centerLat = (bounds.getSouth() + bounds.getNorth()) / 2;
                        var centerLng = (bounds.getWest() + bounds.getEast()) / 2;
                        var center = L.latLng(centerLat, centerLng);
                
                
                        map.on('click', function(e) {
                            var lat = e.latlng.lat;
                            var lng = e.latlng.lng;
                            $("#latitude").val(lat);
                            $("#logintude").val(lng);
                            $("#let").text(lat);
                            $("#long").text(lng);
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection