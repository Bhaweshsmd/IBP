<?php $page = 'service-map'; ?>
@extends('layout.mainlayout')

  <style>
        #map {
            height: 500px;
        }
  </style>  
@section('content')
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
        var domainUrl = "{{ url('/') }}";
        var map = L.map('map', {
            crs: L.CRS.Simple // Use Simple coordinate reference system
        });
        var imageUrl = domainUrl+'/assets/map.jpg';
        var imageBounds = [[0, 0], [500,1400]]; // Adjust according to your image size
        var imageOverlay   = L.imageOverlay(imageUrl, imageBounds).addTo(map);
    
        map.fitBounds(imageBounds);
        var bounds = imageOverlay.getBounds();
        var centerLat = (bounds.getSouth() + bounds.getNorth()) / 2;
        var centerLng = (bounds.getWest() + bounds.getEast()) / 2;
        var center = L.latLng(centerLat, centerLng);
        console.log("Center coordinates:", centerLat, centerLng);
    </script>
    
    @if(!empty($data))
        @foreach($data as $value)
            <script>
                var lats = "{{$value->latitude}}";
                var lngs = "{{$value->logintude}}";
                var marker4 = L.marker([lats,lngs]).addTo(map);
                marker4.bindPopup("<a target='_blank' href='{{url('item-details')}}/{{$value->slug}}'><img src='{{App\Models\GlobalFunction::createMediaUrl($value->thumbnail)}}' alt='Venue'></a><br><b>{{$value->title}}</b>").openPopup();
            </script>
        @endforeach
    @endif

@endsection

    