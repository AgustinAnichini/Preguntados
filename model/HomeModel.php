<?php

class HomeModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    function validarLogin($email,$password){

        $result = $this->database->query("SELECT * FROM usuarios WHERE mail = '$email' and contrasenia = '$password'");
        return $result[0];
    }
    function activarCuenta($usuario){
        $usuarioID = $usuario["id"];
        $this->database->execute("UPDATE usuarios SET cuenta_activa = 'true'  WHERE id = '$usuarioID'");
    }
    function buscarUsuarioPorHash($hash){
        $result = $this->database->query("SELECT * FROM usuarios WHERE token_validacion = '$hash'");
        return $result;
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
        return $result;// 30
    }

    function calcularRanking($puntajeTotal,$partidasJugadas){
        if ($partidasJugadas == 0){
            return 0;
        }
        $ranking = $puntajeTotal/$partidasJugadas;
        return $ranking;
    }
}
