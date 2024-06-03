<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ');
header('Access-Control-Allow-Headers:');

if (isset($_GET['event_id'])) {
    $eventId = urlencode($_GET['event_id']);
    $eventApiKey = 'pEBWAxY6PMccGxWvWLs40HkXMzpyym6o';
    $eventUrl = "https://app.ticketmaster.com/discovery/v2/events/$eventId.json?apikey=$eventApiKey";
    
    $eventResponse = file_get_contents($eventUrl);
    if ($eventResponse === FALSE) {
        http_response_code(500);
        echo json_encode(array("bad id error" => "event_id niepoprawne"));
        exit;
    }
    // dane z eventu
    $eventData = json_decode($eventResponse, true);
    
    // warunek jest wymagający, ale ticketmaser w json ma wszystjie dane
    if (isset($eventData['_embedded']['venues'][0]['location']['latitude']) && isset($eventData['_embedded']['venues'][0]['location']['longitude'])) {
        $lat = urlencode($eventData['_embedded']['venues'][0]['location']['latitude']);
        $lon = urlencode($eventData['_embedded']['venues'][0]['location']['longitude']);
        $date1 = isset($_GET['date1']) ? urlencode($_GET['date1']) : date('Y-m-d\TH:i:s');
        $date2 = isset($_GET['date2']) ? urlencode($_GET['date2']) : date('Y-m-d\TH:i:s', strtotime('+1 hour'));

        $weatherApiKey = 'HYGQ7Q357PZBWHQSZJT2QKKJK';
        $weatherUrl = "https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/$lat,$lon/$date1/$date2?key=$weatherApiKey";
        $weatherResponse = file_get_contents($weatherUrl);
        
        if ($weatherResponse === FALSE) {
            http_response_code(500);
            echo json_encode(array("no weather error" => "Brak informacji o pogodzie. Moze to być spowodowane datą, albo błędem hosta"));
            exit;
        }
        
        //dane z pogody
        $weatherData = json_decode($weatherResponse, true);
        
        //tutaj dane wszystko
        $combinedData = array(
            "event" => $eventData,
            "weather" => $weatherData
        );
        
        echo json_encode($combinedData, JSON_PRETTY_PRINT);
    } else {
        http_response_code(400);
        echo json_encode(array("no location error" => "Brak lokalizacji dla eventu. Sprawdz json z api.php"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("no id error" => "Nie podano event_id"));
}
?>
