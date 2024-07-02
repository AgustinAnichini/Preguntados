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
        $this->presenter->render("Perfil", []);
    }
    public function verPerfil()
    {
        $idPerfil = $_GET["id"];
        // busco el perfil por ID
        $this->presenter->render("Perfil", []);
    }

}