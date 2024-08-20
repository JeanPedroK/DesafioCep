<?php
namespace App\infra;

use Exception;
use PDO;
use PDOException;

class Database
{
    private $host = '';
    private $dbName = '';
    private $username = '';
    private $password = '';
    private $conn;

    public function __construct()
    {
            
        $this->host = getenv('DB_HOST');
        $this->dbName = getenv('DB_DATABASE');
        $this->username = getenv('DB_USER');
        $this->password = getenv('DB_PASSWORD');

        $this->connect();
    }

    private function connect()
    {
        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName . ';charset=utf8';
            $this->conn = new PDO($dsn, $this->username, $this->password);
            // Define o modo de erro do PDO para exceção
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Em caso de erro, lança uma exceção com a mensagem de erro
            throw new Exception('Erro ao conectar com o banco de dados: ' . $e->getMessage());
        }
    }

    // Método para obter a conexão
    public function getConnection(): PDO 
    {
        return $this->conn;
    }

    // Método para fechar a conexão (opcional)
    public function closeConnection()
    {
        $this->conn = null;
    }
}

