<?php

namespace App\models;

use App\infra\Database;
use PDO;

class cep extends Database {

    public function getByID(int $id)  {
        
        $conn = $this ->getConnection();
 
        $stmt = $conn->query("SELECT * FROM cep where id = $id");
 
        return $stmt->fetch(PDO::FETCH_ASSOC);
     }
    
    public function getByCep(int $cep)  {
        
       $conn = $this ->getConnection();

       $stmt = $conn->query("SELECT * FROM cep where cep = $cep");

       return $stmt->fetch(PDO::FETCH_ASSOC);
    }
      
    public function create(int $cep,float $lat,float $lng)  {
        
        $conn = $this ->getConnection();

        $sql = "INSERT INTO `cep` 
                        ( 
                            cep, 
                            lat, 
                            lng
                        )
                        values
                        ( 
                            $cep, 
                            $lat, 
                            $lng 
                        );
                    ";

        $conn->query($sql);

        return $conn->lastInsertId();
     }

}


?>