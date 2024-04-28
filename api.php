// kodzik printujacy raw data z zapytania z publicznego API TicketMasteta
// how to use -> domena/api.php?query=parameter&query=parameter...

// query parameters ktore uzywamy -> keyword? countryCode? startEndDateTime?

// wszystkie parametry sa bazowo ustawione jako string, najlepiej tak dziala, ale jak trzeba bedzie zmienic, zmienie


// przyklad uzycia na localhost -> http://127.0.0.1/API/api.php?keyword=dżem&countryCode=PL





<?php

$baseUrl = "https://app.ticketmaster.com/discovery/v2/events.json";
// zmien na swoj apiKey jezeli potrzebujesz
$apiKey = "pEBWAxY6PMccGxWvWLs40HkXMzpyym6o";

$url = $baseUrl . "?apikey=" . urlencode($apiKey);

// M.C. Hammer - U Can't Touch This
foreach ($_GET as $key => $value) {
    $url .= "&" . urlencode($key) . "=" . urlencode($value);
}


// M.C. Hammer - U Can't Touch This
function fetch_event($url) {

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);

    $data = curl_exec($curl);
    curl_close($curl);

    return $data;
}


$response_data = fetch_event($url);
$json_data = json_decode($response_data, true);

// ta linijka powoduje, ladnie ulozony output, jezeli nie potrzebne, zakomentowac
$pretty_json = json_encode($json_data, JSON_PRETTY_PRINT);

// to samo co powyzej
header('Content-Type: application/json');

echo $pretty_json;
?>
