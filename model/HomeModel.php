<?php

class HomeModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    function validarLogin($email,$password){

        $result = $this->database->query("SELECT * FROM usuarios WHERE mail = '$email' and contrasenia_hash = '$password'");
        return $result[0];
    }
}
