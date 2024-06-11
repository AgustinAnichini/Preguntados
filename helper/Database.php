<?php

class Database
{
    private $conn;

    public function __construct($servername, $username, $password, $dbname)
    {
        $this->conn = mysqli_connect($servername, $username, $password, $dbname);

        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function query($sql)
    {
        $result = mysqli_query($this->conn, $sql);

        if ($result instanceof mysqli_result) {
            // Si es un objeto mysqli_result, retorna el conjunto de resultados como un array asociativo
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            // Si no es un objeto mysqli_result, retorna el resultado de la consulta
            return $result;
        }
    }

    public function execute($sql)
    {
        if (mysqli_query($this->conn, $sql)) {
            return true; // Inserción exitosa
        } else {
            return false; // Inserción fallida
        }
    }

    public function getError()
    {
        return mysqli_error($this->conn);
    }

    public function __destruct()
    {
        mysqli_close($this->conn);
    }

}