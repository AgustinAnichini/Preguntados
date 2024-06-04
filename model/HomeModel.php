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
}
