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




    function verificarRespuesta($idRespuesta){
        // debo comparar el ID  de la pregunta con el ID  de la respuesta, debe retornar un boolean

        $result = $this->database->query("SELECT r.correcta FROM respuesta r WHERE r.pregunta_id = '$idRespuesta'");
        return $result;
    }
}
