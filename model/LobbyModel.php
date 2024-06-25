<?php

class LobbyModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    function actualizarUsuario(){
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];
        $usuarioActualizado = $this->database->query("SELECT * FROM usuarios WHERE id = $idUsuario");
        $_SESSION["usuario"] = $usuarioActualizado[0];
    }

   function partidasActualizadas(){
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];

        $partidasActualizadas = $this->database->query("SELECT * FROM partida WHERE idUsuario = $idUsuario LIMIT 5");
        return $partidasActualizadas;
    }

    function obtenerRankingDeUsuario(){
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];
        $puntajeTotal = $usuario["puntajeTotal"];
        $partidasJugadas = $usuario["partidasJugadas"];

        $ranking = $this->calcularRanking($puntajeTotal,$partidasJugadas);
        $this->database->execute("UPDATE usuarios SET ranking = '$ranking' WHERE id = '$idUsuario'");

       $result= $this->database->query("SELECT ranking FROM usuarios WHERE id = '$idUsuario'");
       return $result;
    }
    function calcularRanking($puntajeTotal,$partidasJugadas){
       $ranking = $puntajeTotal/$partidasJugadas;
        return $ranking;
    }
}