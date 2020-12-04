<?php

namespace Anax\View;

?>
<h1>Weather service</h1>
<?php if (!$weatherCurrentJSON) : ?>
<p>Type in a location following this format: <br>"Sweden,SE" <br>or <br>"Denmark,DK".<br> 
Alternatively you can type in an ip-address, example: <br>"194.47.150.2", 
<br>or <br>"2001:4860:4860::888". <br>Both IPv4 and IPv6 works.</p>
<?php endif; ?>
<form method="post">
    <input type="text" name="input" placeholder="Location or ip...">
    <input type="submit" name="submit" id="submitButton" value="input">
</form>
<?php if ($weatherCurrentJSON && $weatherCurrentJSON["cod"] != "404") : ?>
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
<?php endif; ?>
<div class="result-wrap">
    <?php if ($weatherCurrentJSON && $weatherCurrentJSON["cod"] != "404") : ?>
        <h4>Weather forecast for <?= $weatherCurrentJSON["name"] ?>, <?= $weatherCurrentJSON["sys"]["country"] ?></h4>
        <p>Weather: <?= $weatherCurrentJSON["weather"][0]["main"] ?>.</p>
        <p>Description: <?= $weatherCurrentJSON["weather"][0]["description"] ?>.</p>
        <p>Temperature: <?= $weatherCurrentJSON["main"]["temp"] ?>°C.</p>
        <p>Feels like: <?= $weatherCurrentJSON["main"]["feels_like"] ?>°C.</p>
        <h3>Weather for the past five days:</h3>
        <h4>Yesterday: (<?= gmdate('r', $pastOne["hourly"][14]["dt"]) ?>):</h4>
            <p>Weather: <?= $pastOne["hourly"][14]["weather"][0]["main"] ?>.</p>
            <p>Description: <?= $pastOne["hourly"][14]["weather"][0]["description"] ?>.</p>
            <p>Temperature: <?= $pastOne["hourly"][14]["temp"] ?>°C.</p>
            <p>Feels like: <?= $pastOne["hourly"][14]["feels_like"] ?>°C.</p>
        <h4>Two days ago: (<?= gmdate('r', $pastTwo["hourly"][14]["dt"]) ?>):</h4>
            <p>Weather: <?= $pastTwo["hourly"][14]["weather"][0]["main"] ?>.</p>
            <p>Description: <?= $pastTwo["hourly"][14]["weather"][0]["description"] ?>.</p>
            <p>Temperature: <?= $pastTwo["hourly"][14]["temp"] ?>°C.</p>
            <p>Feels like: <?= $pastTwo["hourly"][14]["feels_like"] ?>°C.</p>
        <h4>Three days ago: (<?= gmdate('r', $pastThree["hourly"][14]["dt"]) ?>):</h4>
            <p>Weather: <?= $pastThree["hourly"][14]["weather"][0]["main"] ?>.</p>
            <p>Description: <?= $pastThree["hourly"][14]["weather"][0]["description"] ?>.</p>
            <p>Temperature: <?= $pastThree["hourly"][14]["temp"] ?>°C.</p>
            <p>Feels like: <?= $pastThree["hourly"][14]["feels_like"] ?>°C.</p>
        <h4>Four days ago: (<?= gmdate('r', $pastFour["hourly"][14]["dt"]) ?>):</h4>
            <p>Weather: <?= $pastFour["hourly"][14]["weather"][0]["main"] ?>.</p>
            <p>Description: <?= $pastFour["hourly"][14]["weather"][0]["description"] ?>.</p>
            <p>Temperature: <?= $pastFour["hourly"][14]["temp"] ?>°C.</p>
            <p>Feels like: <?= $pastFour["hourly"][14]["feels_like"] ?>°C.</p>
        <h4>Five days ago: (<?= gmdate('r', $pastFive["hourly"][14]["dt"]) ?>):</h4>
            <p>Weather: <?= $pastFive["hourly"][14]["weather"][0]["main"] ?>.</p>
            <p>Description: <?= $pastFive["hourly"][14]["weather"][0]["description"] ?>.</p>
            <p>Temperature: <?= $pastFive["hourly"][14]["temp"] ?>°C.</p>
            <p>Feels like: <?= $pastFive["hourly"][14]["feels_like"] ?>°C.</p>
    <?php endif; ?>
    <?php if ($weatherCurrentJSON && $weatherCurrentJSON["cod"] == "404") : ?>
        <h4>Weather forecast</h4>
        <p>
            Couldn't find the location you were trying to find, make sure you have right
            country code, following this format, example: Stockholm, SE
        </p>
    <?php endif; ?>
</div>
