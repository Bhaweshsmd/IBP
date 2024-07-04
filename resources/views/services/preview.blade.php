@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/viewService.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/style/viewService.css') }}">
    <style>
        #map {
            height:500px;
        }
        #modaldialog {
            max-width: 100% !important;
        }
    </style>
@endsection

@section('content')
    <input type="hidden" value="{{ $service->id }}" id="serviceId">
    
    <div class="card">
        <div class="card-header">
            <h4> {{ __('Preview Service Details') }} : </h4>
        </div>
        
        <div class="card-body">
            <div class="form-group">
                <div class="row"> 
                    <div class="col-md-12">
                        <div class="tab-content tabs" id="home">     
                            <div role="tabpanel" class="tab-pane" id="tabSlots">
                                <div class="slote-table table-responsive col-12">
                                    <div class="mt-2 d-flex">
                                        <label class="mb-0 text-grey" for="">{{ __('Monday') }}</label>
                                        <div class="slot-time-block">
                                            @foreach ($slots['mondaySlots'] as $item)
                                                <span class="ml-2 btn btn-danger deleteSlots" data-id={{$item['id'] }}>×</span>
                                                <div class="slot-time-inner">
                                                    <span class="slot-time">{{ date('h:i A',strtotime($item['time'])) }}-{{date('h:i A',strtotime('+'.$item['booking_hours'].'hour',strtotime($item['time'])))}} ({{$item['booking_hours']}} Hr)</span>
                                                    <span class="total-slot"> {{ $item['booking_limit'] }} {{ __('Slots') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mt-2 d-flex">
                                        <label class="mb-0 text-grey" for="">{{ __('Tuesday') }}</label>
                                        <div class="slot-time-block">
                                            @foreach ($slots['tuesdaySlots'] as $item)
                                                <span class="ml-2 btn btn-danger deleteSlots" data-id={{$item['id'] }}>×</span>
                                                <div class="slot-time-inner">
                                                    <span class="slot-time">{{ date('h:i A',strtotime($item['time'])) }}-{{date('h:i A',strtotime('+'.$item['booking_hours'].'hour',strtotime($item['time'])))}} ({{$item['booking_hours']}} Hr)</span>
                                                    <span class="total-slot"> {{ $item['booking_limit'] }} {{ __('Slots') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mt-2 d-flex">
                                        <label class="mb-0 text-grey" for="">{{ __('Wednesday') }}</label>
                                        <div class="slot-time-block">
                                            @foreach ($slots['wednesdaySlots'] as $item)
                                                <span class="ml-2 btn btn-danger deleteSlots" data-id={{$item['id'] }}>×</span>
                                                <div class="slot-time-inner">
                                                    <span class="slot-time">{{ date('h:i A',strtotime($item['time'])) }}-{{date('h:i A',strtotime('+'.$item['booking_hours'].'hour',strtotime($item['time'])))}} ({{$item['booking_hours']}} Hr)</span>
                                                    <span class="total-slot"> {{ $item['booking_limit'] }} {{ __('Slots') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mt-2 d-flex">
                                        <label class="mb-0 text-grey" for="">{{ __('Thursday') }}</label>
                                        <div class="slot-time-block">
                                            @foreach ($slots['thursdaySlots'] as $item)
                                                <span class="ml-2 btn btn-danger deleteSlots" data-id={{$item['id'] }}>×</span>
                                                <div class="slot-time-inner">
                                                    <span class="slot-time">{{ date('h:i A',strtotime($item['time'])) }}-{{date('h:i A',strtotime('+'.$item['booking_hours'].'hour',strtotime($item['time'])))}} ({{$item['booking_hours']}} Hr)</span>
                                                    <span class="total-slot"> {{ $item['booking_limit'] }} {{ __('Slots') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mt-2 d-flex">
                                        <label class="mb-0 text-grey" for="">{{ __('Friday') }}</label>
                                        <div class="slot-time-block">
                                            @foreach ($slots['fridaySlots'] as $item)
                                                <span class="ml-2 btn btn-danger deleteSlots" data-id={{$item['id'] }}>×</span>
                                                <div class="slot-time-inner">
                                                    <span class="slot-time">{{ date('h:i A',strtotime($item['time'])) }}-{{date('h:i A',strtotime('+'.$item['booking_hours'].'hour',strtotime($item['time'])))}} ({{$item['booking_hours']}} Hr)</span>
                                                    <span class="total-slot"> {{ $item['booking_limit'] }} {{ __('Slots') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mt-2 d-flex">
                                        <label class="mb-0 text-grey" for="">{{ __('Saturday') }}</label>
                                        <div class="slot-time-block">
                                            @foreach ($slots['saturdaySlots'] as $item)
                                                <span class="ml-2 btn btn-danger deleteSlots" data-id={{$item['id'] }}>×</span>
                                                <div class="slot-time-inner">
                                                    <span class="slot-time">{{ date('h:i A',strtotime($item['time'])) }}-{{date('h:i A',strtotime('+'.$item['booking_hours'].'hour',strtotime($item['time'])))}} ({{$item['booking_hours']}} Hr)</span>
                                                    <span class="total-slot"> {{ $item['booking_limit'] }} {{ __('Slots') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mt-2 d-flex">
                                        <label class="mb-0 text-grey" for="">{{ __('Sunday') }}</label>
                                        <div class="slot-time-block">
                                            @foreach ($slots['sundaySlots'] as $item)
                                                <span class="ml-2 btn btn-danger deleteSlots" data-id={{$item['id'] }}>×</span>
                                                <div class="slot-time-inner">
                                                    <span class="slot-time">{{ date('h:i A',strtotime($item['time'])) }}-{{date('h:i A',strtotime('+'.$item['booking_hours'].'hour',strtotime($item['time'])))}} ({{$item['booking_hours']}} Hr)</span>
                                                   <span class="total-slot"> {{ $item['booking_limit'] }} {{ __('Slots') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <form action="{{ route('addServiceToSalonWeb') }}" method="post" enctype="multipart/form-data" class="" autocomplete="off">
                @csrf
                <input type="hidden" name="id" value="{{ $service->id }}">
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
                                <input type="text" class="form-control" name="title"   value="{{ old('title',$service->title) }}" required>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="tabPapiamentutitle">
                            <div class="form-group ">
                                <label for="">{{ __('Title In Papiamentu ') }}</label>
                                <input type="text" class="form-control" name="title_in_papiamentu"   value="{{ old('title_in_papiamentu',$service->title_in_papiamentu) }}" >
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="tabDutchtitle">
                            <div class="form-group ">
                                <label for="">{{ __('Title In Dutch ') }}</label>
                                <input type="text" class="form-control" name="title_in_dutch"   value="{{ old('title_in_dutch',$service->title_in_dutch) }}" >
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-row ">
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Category') }}</label>
                        <select name="category_id" id="category_id" class="form-control" aria-label="Default select example">
                            @foreach ($categories as $cat)
                                <option {{ $cat->id == $service->category_id ? 'selected' : '' }} value="{{ $cat->id }}">{{ $cat->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <a data-toggle="modal" data-target="#addMapModal" href="" class="ml-auto badge bg-primary text-white text-white">{{ __('Select co-ordinates ') }}</a>
                        <label for="">{{ __('Service Latitude') }}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="latitude" id="latitude" value="{{ old('latitude',$service->latitude) }}" >
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Service Longitude') }}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="logintude" id="logintude" value="{{ old('logintude',$service->logintude) }}"   >
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
                            <input type="number" class="form-control" name="price" value="{{ old('price',$service->price) }}" required>
                        </div>
                    </div>
    
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Local Discount') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    %
                                </div>
                            </div>
                            <input type="number" class="form-control" name="discount" step="any" value="{{ old('discount',$service->discount) }}" >
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Local Cancellation Charges') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    %
                                </div>
                            </div>
                            <input type="number" class="form-control" name="local_cancellation_charges" step="any" value="{{ old('local_cancellation_charges',$service->local_cancellation_charges) }}" >
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
                            <input type="number" class="form-control" name="foreiner_price"  step="any" value="{{ old('foreiner_price',$service->foreiner_price) }}" required>
                        </div>
                    </div>
    
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Foreiner Discount') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    %
                                </div>
                            </div>
                            <input type="number" class="form-control" name="foreiner_discount" min=0 step="any" value="{{ old('foreiner_discount',$service->foreiner_discount) }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="">{{ __('Foreiner Cancellation Charges') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    %
                                </div>
                            </div>
                            <input type="number" class="form-control" name="foreiner_cancellation_charges" step="any" value="{{ old('foreiner_cancellation_charges',$service->foreiner_cancellation_charges) }}" >
                        </div>
                    </div>
                </div>
                
                <div class="form-row ">
                    <div class="form-group col-md-3">
                        <div class="form-group mt-0">
                            <label for="">{{ __('Thumbnail') }}</label>
                            <div class="d-flex mb-2">
                                <div class="service_image">
                                    <img width="100" class="rounded" height="100" src="{{ url('public/storage/'.$service->thumbnail) }}" alt="">
                                </div>
                            </div>
                        </div>
                        <label for="">{{ __('Service Thumbnail') }}</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="thumbnail" value="{{ old('thumbnail') }}" accept=".png,.jpeg,.jpg" >
                        </div>
                    </div>
                    <div class="form-group col-md-9">
                        <div class="form-group mt-0">
                            <label for="">{{ __('Gallery') }}</label>
                            <div class="d-flex mb-2">
                                @foreach ($service->images as $image)
                                    <div class="service_image">
                                        <img width="100" class="rounded" height="100" src="{{ url('public/storage/'.$image->image) }}" alt="">
                                        <i rel="{{ $image->id }}" class="fas fa-trash img-delete"></i>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <label for="">{{ __('Gallery') }}</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="images[]" value="{{ old('images[]') }}" multiple  accept=".png,.jpeg,.jpg">
                        </div>
                    </div>
                </div>
                
                <div class="form-row ">
                    <div class="form-group col-md-12">
                        <div class="form-group mt-0">
                            <label for="">{{ __('Service Map images') }}</label>
                            <div class="d-flex mb-2">
                                @foreach ($service->map_images as $image)
                                    <div class="service_image">
                                        <img width="100" class="rounded" height="100" src="{{ url('public/storage/'.$image->image) }}" alt="">
                                        <i rel="{{ $image->id }}" class="fas fa-trash map-img-delete"></i>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <label for="">{{ __('Service Map images') }}</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="map_images[]" value="{{ old('map_images[]') }}" multiple  accept=".png,.jpeg,.jpg">
                        </div>
                    </div>
                </div>
              
                <ul class="nav nav-pills border-b  ml-0">
                    <li role="presentation" class="nav-item active">
                        <a class="nav-link pointer active " href="#tabEnglish" role="tab" aria-controls="tabSlots" data-toggle="tab">{{ __('English') }}
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
                        <div role="tabpanel" class="tab-pane active" id="tabEnglish">
                            <div class="form-group">
                                <label for="">{{ __('About In English') }}</label>
                                <textarea type="text" class="form-control" name="about">{{ old('about',$service->about) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Rules In English') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="rules">{{old('rules',$service->rules)}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('History In English') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="history">{{old('history',$service->history)}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Included Items In English') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="included_items">{{old('included_items',$service->included_items)}}</textarea>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="tabPapiamentu">
                            <div class="form-group ">
                                <label for="">{{ __('About In Papiamentu') }}</label>
                                <textarea type="text" class="form-control" name="about_in_papiamentu">{{ old('about_in_papiamentu',$service->about_in_papiamentu) }}</textarea>
                            </div>
                            <div class="form-group ">
                                <label for="">{{ __('Rules In Papiamentu') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="rules_in_papiamentu">{{ old('rules_in_papiamentu',$service->rules_in_papiamentu) }}</textarea>
                            </div>
                            <div class="form-group ">
                                <label for="">{{ __('History In Papiamentu') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="history_in_papiamentu">{{old('history_in_papiamentu',$service->history_in_papiamentu)}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Included Items In Papiamentu') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="included_items_in_papiamentu">{{old('included_items_in_papiamentu',$service->included_items_in_papiamentu)}}</textarea>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="tabDutch">
                            <div class="form-group ">
                                <label for="">{{ __('About In Dutch') }}</label>
                                <textarea type="text" class="form-control" name="about_in_dutch">{{old('about_in_dutch',$service->about_in_dutch)}}</textarea>
                            </div>
                            <div class="form-group ">
                                <label for="">{{ __('Rules In Dutch') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="rules_in_dutch">{{old('rules_in_dutch',$service->rules_in_dutch)}}</textarea>
                            </div>
                            <div class="form-group ">
                                <label for="">{{ __('History In Dutch') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="history_in_dutch">{{old('history_in_dutch',$service->history_in_dutch)}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Included Items In Dutch') }}</label>
                                <textarea  class="summernote-simple" id="summernote" name="included_items_in_dutch">{{old('included_items_in_dutch',$service->included_items_in_dutch)}}</textarea>
                            </div>
                       </div>
                    </div>
                </div>
                
                <div class="form-group text-right">
                    <input class="btn btn-primary mr-1" type="submit" value="{{ __('Submit') }}">
                </div>
            </form>
        </div>
    </div>
    
    <div class="modal fade" id="addBannerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Add Times') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('add.booking.slots')}}" method="post" enctype="multipart/form-data" id="addBannerForm" autocomplete="off">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                        
                        <div class="form-group">
                            <label>{{ __('weekday') }}</label>
                            <select class="form-control" name="weekday" value="{{ old('weekday') }}" required>
                                <option value="" >{{__('Select Weekday')}}</option>
                                <option value="1" >{{__('Monday')}}</option>
                                <option value="2" >{{__('Tuesday')}}</option>
                                <option value="3" >{{__('Wednesday')}}</option>
                                <option value="4" >{{__('Thursday')}}</option>
                                <option value="5" >{{__('Friday')}}</option>
                                <option value="6" >{{__('Saturday')}}</option>
                                <option value="7" >{{__('Sunday')}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Select Booking Hours') }}</label>
                            <select class="form-control" name="booking_hours" value="{{ old('booking_hours') }}" required>
                                <option value="" >{{__('Select Booking Hours')}}</option>
                                @for($i=1;$i<=24;$i++) 
                                    <option value="{{$i}}" >{{$i}}{{__(' Hours')}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Time') }}</label>
                            <input class="form-control" type="time" name="time" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Slots') }}</label>
                            <input class="form-control" type="number" min='1' name="booking_limit" required>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="addMapModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" id="modaldialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Select co-ordinates to click on image') }}</h5>&nbsp; &nbsp;
                    <spna>Latitude:</spna>&nbsp;<span id="let">{{$service->latitude}}</span> &nbsp; &nbsp;
                    <spna>Longitude:</spna>&nbsp;<span id="long">{{$service->logintude}}</span>
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
                        var  latitude = "{{$service->latitude }}";
                        var  logintude = "{{$service->logintude }}";
                        var imageBounds = [[0, 0], [500,1400]];
                    
                        var imageOverlay   = L.imageOverlay(imageUrl, imageBounds).addTo(map);
                        map.fitBounds(imageBounds);
                        var bounds = imageOverlay.getBounds();
                        var centerLat = (bounds.getSouth() + bounds.getNorth()) / 2;
                        var centerLng = (bounds.getWest() + bounds.getEast()) / 2;
                        var center = L.latLng(centerLat, centerLng);
                        var marker = L.marker([latitude,logintude]).addTo(map);
                        marker.bindPopup("<b>{{ $service->title }}</b>").openPopup();
                    
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