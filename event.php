<?php

$baseUrl = "https://app.ticketmaster.com/discovery/v2/events";
$apiKey = "pEBWAxY6PMccGxWvWLs40HkXMzpyym6o";

if (!isset($_GET['event_id'])) {
    die('Error: event_id parameter is missing.');
}

$eventId = $_GET['event_id'];
$url = $baseUrl . "/" . urlencode($eventId) . ".json" . "?apikey=" . urlencode($apiKey);

function fetch_event($url) {
    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);

    $data = curl_exec($curl);
    if (curl_errno($curl)) {
        die('Curl error: ' . curl_error($curl));
    }
    curl_close($curl);

    return $data;
}

$response_data = fetch_event($url);
$json_data = json_decode($response_data, true);

$pretty_json = json_encode($json_data, JSON_PRETTY_PRINT);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

echo $pretty_json;
?>
