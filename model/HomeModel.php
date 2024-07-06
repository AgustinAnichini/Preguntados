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
    public function activarCuenta($hash)
    {
        $usuario = $this->buscarUsuarioPorHash($hash);

        if ($usuario) {
            $usuarioID = $usuario["id"];
            $sql = "UPDATE usuarios SET cuenta_activa = true WHERE id = '$usuarioID'";
            $this->database->execute($sql);
        }
    }

    public function buscarUsuarioPorHash($hash)
    {
        $sql = "SELECT * FROM usuarios WHERE token_validacion = '$hash'";
        $result = $this->database->query($sql);
        return $result ? $result[0] : null; // Devuelve la primera fila encontrada o null si no se encontró ningún usuario
    }

}
