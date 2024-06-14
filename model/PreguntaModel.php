<?php

class PreguntaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }



    function getPreguntas(){
        return $this->database->query("SELECT * FROM pregunta");
    }

    function obtenerCategoriaPorId($pregunta){
        $idPregunta = $pregunta["id"];
        $result = $this->database->query("SELECT * FROM categoria C
                                            JOIN pregunta P on C.id = P.id_categoria 
                                            WHERE P.id = $idPregunta");
        return $result[0];
    }

    function verificarSiContestoTodasLasPreguntas(){
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];
        $respondioTodas = false;

        $preguntasDelUsuario = $this->database->query("SELECT * FROM preguntaUsuario PU WHERE PU.idUsuario = $idUsuario");
        $cantPregUsuario = count($preguntasDelUsuario);

        $todasLasPreguntas = $this->getPreguntas();
        $cantDePreg = count($todasLasPreguntas);

        if($cantDePreg == $cantPregUsuario){
            $this->borrarPreguntasDelUsuario($idUsuario);
            $respondioTodas = true;
        }

        return $respondioTodas;
    }

    function borrarPreguntasDelUsuario($idUsuario){
        $this->database->execute("DELETE FROM preguntaUsuario PU WHERE PU.idUsuario = $idUsuario");
    }

    function verificarPregunta($pregunta){
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];
        $idPregunta = $pregunta["id"];
        $result = $this->database->query("SELECT * FROM preguntaUsuario PU WHERE PU.idPregunta = $idPregunta and PU.idUsuario = $idUsuario");
        return empty($result); // True si no encontró la pregunta
    }

    public function respuestas($idPregunta)
    {
        // Realiza la consulta para obtener las respuestas de una pregunta específica
        $result = $this->database->query("SELECT * FROM respuesta WHERE pregunta_id = '$idPregunta'");

        if (!$result) {
            return []; // Manejar el caso de error en la consulta
        }

        // Retorna directamente el resultado como un array de respuestas
        return $result;
    }

    function verificarRespuesta($idPregunta, $idRespuesta){
        $result = $this->database->query("SELECT r.correcta FROM respuesta r WHERE r.pregunta_id = '$idPregunta' and $idRespuesta like r.id");
        return $result[0]["correcta"];
    }
}
