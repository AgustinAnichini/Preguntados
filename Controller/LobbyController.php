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
        if(!isset($_SESSION["usuario"])){
            $this->presenter->render("login", []);
            exit();
        }
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

        if (isset($_GET["mensajeUsuarioSugerir"])){
            $mensajeUsuarioSugerir = $_GET["mensajeUsuarioSugerir"];
            $lobbyData["mensajeUsuarioSugerir"] = $mensajeUsuarioSugerir;
            $_GET["mensajeUsuarioSugerir"]=null;
        }
        $this->presenter->render("lobby", $lobbyData);
    }
}