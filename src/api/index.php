<?php
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');

header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit();
}

use App\controllers\cep;
use App\controllers\distance ;

require 'vendor/autoload.php';

Flight::route('POST /distance', function () {
  $req = Flight::request()->getBody();
  $req = json_decode($req, JSON_OBJECT_AS_ARRAY);

  $distance = new distance();
  $responseDistance = $distance->Calculate($req);

  if (!$responseDistance || !sizeof($responseDistance)) {
    return Flight::response()->status(404);
  }

  Flight::json($responseDistance);
});



Flight::route('GET /distance', function () {
  $distance = new distance();
  $responseDistance = $distance->Get();

  if (!$responseDistance || !sizeof($responseDistance)) {
    return Flight::response()->status(404);
  }

  Flight::json($responseDistance);
});


Flight::route('/cep/@cep', function ($cep) {
  $CEP = new cep();
  $response = $CEP->GetCep($cep);

  if (!$response || !sizeof($response)) {
    return Flight::response()->status(404);
  }

  Flight::json($response);
});


Flight::start();
