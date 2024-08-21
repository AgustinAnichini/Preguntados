<?php

class UsuarioController
{
    private $model;
    private $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function home()
    {
        if(!isset($_SESSION["usuario"])){
            $this->presenter->render("login", []);
            exit();
        }
        $this->presenter->render("Perfil", []);
    }
    public function verPerfil()
    {
        $idUsuario = $_GET["id"];

        $usuario = $this->model->buscarUsuario($idUsuario);
        $usuarioData = array();
        $usuarioData["usuario"] = $usuario;

        $this->presenter->render("perfil", $usuarioData);
    }
}