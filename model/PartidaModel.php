<?php

class   PartidaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function siguientePregunta()
    {
        // Primero, obtenemos todas las preguntas
        $result = $this->database->query("SELECT id FROM pregunta");

        if (!$result) {
            return null; // Manejar el caso de error en la consulta
        }

        // Creamos un array para almacenar los IDs
        $preguntas = [];
        foreach ($result as $row) {
            $preguntas[] = $row['id'];
        }

        // Seleccionamos un ID aleatorio
        if (count($preguntas) > 0) {
            $idRandom = $preguntas[array_rand($preguntas)];

            // Obtenemos la pregunta con el ID aleatorio
            $resultPregunta = $this->database->query("SELECT * FROM pregunta WHERE id = '$idRandom'");

            if (!$resultPregunta) {
                return null; // Manejar el caso de error en la consulta
            }

            // Asumimos que siempre habrá solo una pregunta con este ID
            return $resultPregunta[0];
        } else {
            return null; // No hay preguntas en la base de datos
        }
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

    function agregarPreguntaRespondida($idPregunta){
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];
        $result = $this->database->execute("INSERT INTO preguntaUsuario (idPregunta,idUsuario) values ($idPregunta, $idUsuario)");
    }

    function verificarPregunta($pregunta){
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];
        $idPregunta = $pregunta["id"];
        $result = $this->database->query("SELECT * FROM preguntaUsuario PU WHERE PU.idPregunta = $idPregunta and PU.idUsuario = $idUsuario");
        return empty($result); // True si no encontró la pregunta
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
    function getPreguntas(){
        return $this->database->query("SELECT * FROM pregunta");
    }
}
