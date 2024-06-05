<?php

class   PartidaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    function siguientePregunta(){
        $preguntas = $this->database->query("SELECT * FROM pregunta");
        $totalPreguntas = count($preguntas);
        $idRandom = rand(0,$totalPreguntas);


        $result = $this->database->query("SELECT * FROM pregunta WHERE id = '$idRandom'");
        return $result;
    }
    function respuestas($id){

        $result = $this->database->query("SELECT * FROM respuesta WHERE pregunta_id = '$id'");
        return $result;
    }
}
