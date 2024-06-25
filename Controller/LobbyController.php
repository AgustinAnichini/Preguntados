<?php

class LobbyController{
    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function home()
    {
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