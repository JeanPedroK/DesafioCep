<?php

namespace App\models;

use App\infra\Database;
use PDO;

class calculo extends Database {
    
    public function load()  {
        
       $conn = $this ->getConnection();

       $stmt = $conn->query("SELECT * FROM calculo  ORDER BY id DESC LIMIT 10");

       return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByID(int $id)  {
        
        $conn = $this ->getConnection();
 
        $stmt = $conn->query("SELECT * FROM calculo where id = $id");
 
        return $stmt->fetch(PDO::FETCH_ASSOC);
     }
     
    public function create(int $origem,int $destino,float $distance)  {
        
        $conn = $this ->getConnection();
 
        $stmt = $conn->query("INSERT INTO `calculo` 
                        (
                            `cepId_origem`, 
                            `cepId_destino`, 
                            `distance`
                        )
                        values
                        ( 
                            $origem,
                            $destino,
                            $distance
                        );
                    ");

        return $conn->lastInsertId();
    }

}


?>