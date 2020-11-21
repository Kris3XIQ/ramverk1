<?php

namespace Anax\View;

/**
 * Render IP-Geo-locator form.
 */
?>
<div class="geo-post-wrapper">
    <h1>IP Validator with geolocation API</h1>
    <form method="post">
        <input type="text" name="validateIp" value="<?= $user_host ?>" placeholder="<?= $user_host ?>">
        <?php if ($user_host) :?>
        <input type="submit" name="submit" id="submitButton" value="Validate">
        <?php endif; ?>
        <h4>Some test routes to show that it works</h4>
        <div class="ip-geo-test-routes">
            <input type="submit" name="validateDbwebb" class="ip-geo-test-button" value="IPv4 Dbwebb (verifies)">
        </div>
        <div class="ip-geo-test-routes">
            <input type="submit" name="validateGoogle" class="ip-geo-test-button" value="IPv6 Google DNS (verifies)">
        </div>
        <div class="ip-geo-test-routes">
            <input type="submit" name="validateNone" class="ip-geo-test-button" value="Address that does not verify">
        </div>
    </form>
</div>
<div class="geo-result-wrap">
    <h4>IP Geolocator API:</h4>
    <p>
        In order to fetch data from the API using Postman, 
        Advanced REST Client or similar programs. Send a POST request
        to: 
        <pre>
'http://www.student.bth.se/~krmr18/dbwebb-kurser/ramverk1/me/redovisa/htdocs/ip-geo-locator-api'
        </pre>
        following this JSON format { "ip":"194.47.150.2" } example:
    <pre>
{
    "ip":"194.47.150.2"
}
    </pre>
    The above example would yield in this result:
    <pre>
{
    "ip": "194.47.150.2",
    "type": "ipv4",
    "user_host": "::1",
    "result_host": "194.47.150.2",
    "country_name": "Sweden",
    "region_name": "Blekinge",
    "latitude": 56.16122055053711,
    "longitude": 15.586899757385254
}
    </pre>
    </p>
</div>

