<?php

namespace Anax\View;

/**
 * Render IP-Geo-locator form.
 */
?>
<div class="geo-post-wrapper">
    <h1>IP Validator with geolocation</h1>
    <form method="post">
        <input type="text" name="validateIp" value="<?= $user_host ?>" placeholder="<?= $user_host ?>">
        <?php if ($user_host) :?>
        <input type="submit" name="submit" id="submitButton" value="Validate">
        <?php endif; ?>
    </form>
</div>
<?php if ($type) : ?>
    <div class="geo-result-wrap">
        <h4>IP-Geolocator results:</h4>
        <div class="geo-information">
            <p>IP-address of current user: <?= $user_host ?>.</p>
            <p>Searched for IP-address: <?= $ip ?>.</p>
            <p>Hostname of searchresult: <?= $result_host ?>.</p>
            <p>IP-type: <?= $type ?>.</p>
            <p>Country: <?= $country_name ?>.</p>
            <p>Region: <?= $region_name ?>.</p>
        </div>
        <div class="flag" style="background-image: url('<?= $flag ?>');"></div>
    </div>
<?php endif; ?>
<?php if (!$type) : ?>
    <div>
        <h4>Could not verify</h4>
        <p>'<?= $ip ?>' does not appear to be a valid IP-address.</p>
    </div>
<?php endif; ?>
<div class="geo-result-map-container">
    <div id="mapdiv" style="width:820px; height:400px;"></div>
        <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
        <script>
        map = new OpenLayers.Map("mapdiv");
        map.addLayer(new OpenLayers.Layer.OSM());

        var lonLat = new OpenLayers.LonLat(<?= $longitude ?>, <?= $latitude ?>)
                .transform(
                new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
                map.getProjectionObject() // to Spherical Mercator Projection
                );

        var zoom=15;

        var markers = new OpenLayers.Layer.Markers( "Markers" );
        map.addLayer(markers);

        markers.addMarker(new OpenLayers.Marker(lonLat));

        map.setCenter (lonLat, zoom);
    </script>
</div>
