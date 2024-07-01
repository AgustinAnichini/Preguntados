<?php

class LobbyController{
    public function __construct($model, $presenter,$UsuarioModel)
    {
        $this->model = $model;
        $this->presenter = $presenter;
        $this->UsuarioModel = $UsuarioModel;
    }

    public function home()
    {
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];

        $this->UsuarioModel->calcularDificultadUsuario($idUsuario);
        $this->model->obtenerRankingDeUsuario();
        $this->model->actualizarUsuario();
        $partidasActualizadas = $this->model->partidasActualizadas();

        $usuario = $_SESSION["usuario"];
        $lobbyData = array();
        $lobbyData["usuario"] = $usuario;
        $lobbyData["partidasActualizadas"] = $partidasActualizadas;
        $this->presenter->render("lobby", $lobbyData);
    }
}