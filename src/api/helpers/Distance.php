<?php

namespace App\helpers;

class Distance
{
    private $earthRadius;

    public function __construct($earthRadius = 6371)
    {
        $this->earthRadius = $earthRadius;
    }

    private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
        // Convertendo de graus para radianos
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $this->earthRadius;
    }

    public function calculateDistance($from, $to)
    {
        return $this->haversineGreatCircleDistance($from['lat'], $from['lng'], $to['lat'], $to['lng']);
    }
}
