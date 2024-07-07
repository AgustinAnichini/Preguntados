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
                "INSERT INTO partida (idUsuario, puntaje, duracion, preguntasAcertadas, activa)
                 VALUES ($idUsuario, 0, '00:00:00', 0, true)"

                // debemos poner en false cuando el usuario pierde
            );

            $this->actualizarPartida();
        }


    function buscarPreguntaPorId($idPregunta){
        $sql = "SELECT * FROM pregunta WHERE id = $idPregunta";
        $result = $this->database->query($sql);
        //antes era -> return $result[0];
        return $result;
    }

    function agregarPuntos($idPregunta)
    {
        $partida = $_SESSION["partida"];
        $puntaje = $partida["puntaje"];
        $idPartida = $partida["id"];
        $pregunta = $this->buscarPreguntaPorId($idPregunta);
        $valorDePregunta = $pregunta[0]["valor"];
        $puntajePartida = $puntaje;
        $puntajePartida += $valorDePregunta;

        $this->database->execute("UPDATE partida set puntaje = $puntajePartida WHERE id = $idPartida");
    }
    function cerrarPartida()
    {
        $partida = $_SESSION["partida"];
        $idPartida = $partida["id"];

        $this->database->execute("UPDATE partida set activa = false WHERE id = $idPartida");
    }

    function agregarPreguntasAcertadasALaPartida()
    {
        $partida = $_SESSION["partida"];
        $idPartida = $partida["id"];

        $this->database->execute("UPDATE partida SET preguntasAcertadas = preguntasAcertadas + 1 WHERE id = $idPartida");

    }

    function actualizarPartida(){
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];
        $partida = $this->database->query("SELECT * FROM partida WHERE idUsuario = $idUsuario AND activa like true");
        $_SESSION["partida"] = $partida[0];
    }


    function actualizarUsuario(){
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];
        $usuarioBuscado = $this->database->query("SELECT * FROM usuarios WHERE id = $idUsuario");
        $_SESSION["partida"] = $usuarioBuscado[0];
    }

    function partidasActualizadas(){
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];

        $partidasActualizadas = $this->database->query("SELECT * FROM partida WHERE idUsuario = $idUsuario LIMIT 5");
        return $partidasActualizadas;
    }

    function actualizarDuracionDePartida($duracion)
    {
        $partida = $_SESSION["partida"];
        $idPartida = $partida["id"];

        $sql = "UPDATE partida SET duracion = '$duracion' WHERE id = $idPartida";
        $this->database->execute($sql);
    }

    function calcularPuntajeMasAltoDelUsuario()
    {
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];

        $sql = "SELECT MAX(puntaje) AS maxPuntaje FROM partida WHERE idUsuario = $idUsuario";
        $resultado = $this->database->query($sql);

        if ($resultado && count($resultado) > 0) {
            $maxPuntaje = $resultado[0]['maxPuntaje'];
            $this->asignarPuntajeMasAlto($maxPuntaje, $idUsuario);
        } else {
            // Si no hay resultado, manejar el caso, por ejemplo, asignar un puntaje por defecto o lanzar un error.
            $this->asignarPuntajeMasAlto(0, $idUsuario);
        }
    }

    function asignarPuntajeMasAlto($puntajeMasAltoDelUsuario, $idUsuario)
    {
        $puntajeMasAltoDelUsuario = (int)$puntajeMasAltoDelUsuario;  // Asegurar que sea un entero
        $idUsuario = (int)$idUsuario;  // Asegurar que sea un entero

        $sql = "UPDATE usuarios SET puntajeMasAlto = $puntajeMasAltoDelUsuario WHERE id = $idUsuario";
        $this->database->execute($sql);
    }

}