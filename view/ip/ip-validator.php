<?php

namespace Anax\View;

/**
 * Render the ip POST form
 */
?>
<h1>Validator for ip-addresses</h1>
<form method="post">
    <input type="text" name="validateIp">
    <input type="submit" name="submit" id="submitButton" value="Validate">
    <div class="ip-verification-form">
        <p>IPv4 Dbwebb (verifies)</p>
        <input type="submit" name="validateDbwebb" class="ip-verification-button" value="Link">
    </div>
    <div class="ip-verification-form">
        <p>IPv6 Google DNS (verifies)</p>
        <input type="submit" name="validateGoogle" class="ip-verification-button" value="Link">
    </div>
    <div class="ip-verification-form">
        <p>Address that does not verify</p>
        <input type="submit" name="validateNone" class="ip-verification-button" value="Link">
    </div>
</form>
<div class="result-wrap">
    <?php if ($data) : ?>
        <h4>Verification results</h4>
        <p>Message: <?= $message ?>.</p>
        <p>Verified: <?= $verified ?>.</p>
        <p>IP-address: <?= $ip ?>.</p>
        <p>Hostname: <?= $host ?>.</p>
    <?php endif; ?>
</div>
<div>
    <h4>API</h4>
    <p>
        In order to fetch data from the API using Postman, 
        Advanced REST Client or similar programs. Send a POST request
        to: 'http://www.student.bth.se/~krmr18/dbwebb-kurser/ramverk1/me/redovisa/htdocs/verify-ip-api'
        with either the ip as a string, example: "194.47.150.9" or JSON format, example: <br><br>
        {<br>
            "ip":"194.47.150.9"<br>
        }<br>
        <br>
        The above example would yield in this result: <br><br>
        {<br>
            "message": "Anax\Controller\KrisVerifyIpApiController::indexActionPost, POST request was successful",<br>
            "verified": "ip4",<br>
            "ip": "194.47.150.9",<br>
            "host": "dbwebb.tekproj.bth.se"<br>
        }<br>
    </p>
</div>
