<?php $page = 'service-map'; ?>


  <style>
        #map {
            height: 500px;
        }
  </style>  
<?php $__env->startSection('content'); ?>
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
        var domainUrl = "<?php echo e(url('/')); ?>";
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
    
    <?php if(!empty($data)): ?>
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <script>
                var lats = "<?php echo e($value->latitude); ?>";
                var lngs = "<?php echo e($value->logintude); ?>";
                var marker4 = L.marker([lats,lngs]).addTo(map);
                marker4.bindPopup("<a target='_blank' href='<?php echo e(url('item-details')); ?>/<?php echo e($value->slug); ?>'><img src='<?php echo e(App\Models\GlobalFunction::createMediaUrl($value->thumbnail)); ?>' alt='Venue'></a><br><b><?php echo e($value->title); ?></b>").openPopup();
            </script>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

    
<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/web/home/service_map.blade.php ENDPATH**/ ?>