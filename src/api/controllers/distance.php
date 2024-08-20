<?php

namespace App\controllers;

use App\models\calculo;
use App\models\cep;
use App\helpers\Distance as calculateDistance;

class distance
{
    function Get()
    {
        $CEP = new cep;        
        $CALCULO = new calculo;   
        
        $all =  $CALCULO->load();

        foreach ($all as &$value) {
            $value['origem'] = $CEP->getByID($value['cepId_origem']);
            $value['destino'] = $CEP->getByID($value['cepId_destino']);
        }

        return $all;
    }

    function Calculate($req)
    {
        $origem = (int) $req['origem'];
        $destino = (int) $req['destino'];

        if (!$origem || !$destino) {
            return false;
        }
      
        $CEP = new cep;  
        
        $origem = $CEP->getByCep($origem);
        $destino = $CEP->getByCep($destino);

        if (!$origem || !$destino) {
            return false;
        }

        $distance = new calculateDistance();

        $metrs = $distance->calculateDistance($origem,$destino);

        $CALCULO = new calculo;   

        $id = $CALCULO->create((int ) $origem['id'],(int ) $destino['id'],(float) $metrs);

        $value = $CALCULO->getById($id );

        $value['origem'] = $CEP->getByID($value['cepId_origem']);
        $value['destino'] = $CEP->getByID($value['cepId_destino']);
        
        return $value;
    }
}
