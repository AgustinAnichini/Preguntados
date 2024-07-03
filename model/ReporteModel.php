<?php

class ReporteModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    function reportarPregunta($idPregunta,$texto){
        $sql = "INSERT INTO reporte (id_pregunta, texto) VALUES ('$idPregunta','$texto')";
        $this->database->execute($sql);
    }

    function obtenerRespuestasPorIdPregunta($idPregunta){
        $result = $this->database->query("SELECT * FROM respuesta WHERE pregunta_id = '$idPregunta'");
        if (!$result) {
            return [];
        }
        return $result;
    }
}