<?php

namespace Anax\View;

?>
<h1>Weather API service</h1>
<form method="post">
    <input type="text" name="input">
    <input type="submit" name="submit" id="submitButton" value="input">
</form>
<div class="result-wrap">
    <?php if ($statusCode) : ?>
        <h4>Weather forecast</h4>
        <p>
            Couldn't find the location you were trying to find, make sure you have right
            country code, following this format, example: Stockholm, SE. Alternatively
            you can search for an IP-address, example: "194.47.150.2".
        </p>
    <?php endif; ?>
    <h4>Weather API:</h4>
    <p>
        In order to fetch data from the API using Postman, 
        Advanced REST Client or similar programs. Send a POST request
        to: 
        <pre>
'http://www.student.bth.se/~krmr18/dbwebb-kurser/ramverk1/me/redovisa/htdocs/weather-api'
        </pre>
        following this JSON format { "input":"194.47.150.2" } example:
    <pre>
{
    "input":"194.47.150.2"
}
    </pre>
    <p>alternatively:</p>
    <pre>
{
    "input":"Karlskrona, SE"
}
    </pre>
    The above example would yield in this result:
    <pre>
    {
    "status code": 200,
    "name": "Karlskrona, SE",
    "current_weather": {
        "main": "Clouds",
        "description": "broken clouds",
        "temperature": 5,
        "feels_like": 1.15
    },
    "yesterday": {
        "date": "Thu, 03 Dec 2020 14:00:00 +0000",
        "main": "Clouds",
        "description": "broken clouds",
        "temperature": 2,
        "feels_like": -3.64
    },
    "2_days_ago": {
        "date": "Wed, 02 Dec 2020 14:00:00 +0000",
        "main": "Clouds",
        "description": "broken clouds",
        "temperature": 4,
        "feels_like": -0.89
    },
    "3_days_ago": {
        "date": "Tue, 01 Dec 2020 14:00:00 +0000",
        "main": "Clouds",
        "description": "overcast clouds",
        "temperature": 4,
        "feels_like": 0.49
    },
    "4_days_ago": {
        "date": "Mon, 30 Nov 2020 14:00:00 +0000",
        "main": "Clouds",
        "description": "broken clouds",
        "temperature": 1,
        "feels_like": -1.49
    },
    "5_days_ago": {
        "date": "Sun, 29 Nov 2020 14:00:00 +0000",
        "main": "Clouds",
        "description": "broken clouds",
        "temperature": 1,
        "feels_like": -3.33
    }
}
    </pre>
    </p>
</div>
