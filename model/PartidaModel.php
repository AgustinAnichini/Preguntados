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
            $result = $this->database->query("SELECT * FROM pregunta");

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



        function agregarPreguntaRespondida($idPregunta){
            $usuario = $_SESSION["usuario"];
            $idUsuario = $usuario["id"];
            $result = $this->database->execute("INSERT INTO preguntaUsuario (idPregunta,idUsuario) values ($idPregunta, $idUsuario)");
        }

        function iniciarPartida($idUsuario){
            $idUsuario = (int)$idUsuario; // Asegúrate de que $idUsuario es un entero
            $this->database->execute(
                "INSERT INTO partida (idUsuario, tiempo, puntaje, duracion, preguntasAcertadas, activa)
                 VALUES ($idUsuario, 0.0, 0, 0, 0, true)"

                // debemos poner en false cuando el usuario pierde
            );

            $partida = $this->database->query("SELECT * FROM partida WHERE idUsuario = $idUsuario AND activa like true");
            $_SESSION["partida"] = $partida[0];
        }

    function buscarPreguntaPorId($idPregunta){
        $result = $this->database->query("SELECT * FROM pregunta p WHERE p.id = '$idPregunta'");
        return $result[0];
    }

    function agregarPuntos($idPregunta)
    {
        $usuario = $_SESSION["usuario"];
        $partida = $_SESSION["partida"];
        $puntaje = $partida["puntaje"];
        $idPartida = $partida["id"];
        $pregunta = $this->buscarPreguntaPorId($idPregunta);
        $valorDePregunta = $pregunta["valor"];
        $puntajePartida = $puntaje;
        $puntajePartida += $valorDePregunta;

        $this->database->execute("UPDATE partida set puntaje = $puntajePartida where id = $idPartida");
    }
}