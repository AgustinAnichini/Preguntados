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

   function actualizarPartidas(){
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];

        $partidasActualizadas = $this->database->query("SELECT * FROM partida WHERE idUsuario = $idUsuario LIMIT 5");
        return $partidasActualizadas;
    }
}