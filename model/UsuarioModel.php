<?php

class UsuarioModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    function agregarPuntosObtenidosAlUsuario()
    {
        $partida = $_SESSION["partida"];
        $puntajePartida = $partida["puntaje"];
        var_dump($puntajePartida);// llega bien
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];

        $this->database->execute("UPDATE usuarios set puntajeTotal = puntajeTotal + '$puntajePartida' WHERE id = $idUsuario");
    }

    function agregarPartidaJugada($idUsuario){
        // a partir del usuario, agregamos 1 a partidas jugadas
        $this->database->execute("UPDATE usuarios set partidasJugadas = partidasJugadas + 1 WHERE id = $idUsuario");
    }
}