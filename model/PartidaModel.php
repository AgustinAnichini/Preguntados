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
        $idRandom = rand(0,);


        $result = $this->database->query("SELECT * FROM pregunta WHERE id = '$idRandom'");
        return $result[0];
    }
    function respuestas($id){

        $result = $this->database->query("SELECT * FROM respuesta WHERE id = '$id'");
        return $result;
    }
}
