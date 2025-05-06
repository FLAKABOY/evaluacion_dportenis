<?php
namespace App\Models;
use PDO;
use PDOException;

class Model
{
    protected static $instance = null;
    protected $pdo;

    public function __construct()
    {
        $host = "localhost";
        $dbname = "evaluacion";
        $username = "root";
        $password = "";
        $charset = "utf8mb4";

        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    public function executeBaseProcedure( $procedureName, $params = [])
    {
        $sql = "CALL $procedureName(" . implode(',', array_fill(0, count($params), '?')) . ")";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
