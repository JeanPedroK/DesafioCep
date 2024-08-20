<?php

namespace App\controllers;

use App\models\cep as ModelsCep;

class cep
{
    function GetCep($cep)
    {
        $CEP = new ModelsCep;        

        $rows = $CEP->getByCep((int) $cep);

        if ($rows && sizeof($rows)) {
            return $rows;
        }
        
        $url = "https://brasilapi.com.br/api/cep/v2/" . $cep;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpCode == 200 && $response !== false) {
            $resp = json_decode($response, JSON_OBJECT_AS_ARRAY);

            $longitude = $resp['location']['coordinates']['longitude'] ?? 0;
            $latitude = $resp['location']['coordinates']['latitude'] ?? 0;

            $id = $CEP->create((int) $resp['cep'],(float) $longitude,(float) $latitude);

            return $CEP->getByID($id);
        } 
        
        return false;
    }
}
