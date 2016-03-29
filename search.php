<?php

//error_reporting(0);
require_once './vendor/autoload.php';

use MotoCheck\Client;

$chassisNo = $_GET['vim'];

if (!empty($chassisNo)) {
    $client = new Client();
    $details = $client->loadVehicleDetails($chassisNo);

    //we output the data as  JSON object. App will handle the rest.
    echo json_encode($details);
    
    //echo $details;
} else {
    
    $error = ['error' => "The chassis number is missing in the request"];
    echo json_encode($error);
}


