<?php

class HomeController
{
    private $model;
    private $lobbyModel;
    private $presenter;

    public function __construct($model, $presenter,$lobbyModel)
    {
        $this->model = $model;
        $this->presenter = $presenter;
        $this->lobbyModel = $lobbyModel;
    }

    public function home()
    {
        $this->presenter->render("login", []);
    }
    public function logOut()
    {
        $_SESSION=[];
        $this->presenter->render("login", []);
    }
    public function validarHash()
    {
        if(!isset($_GET['hash'])){
            $lobbyData = array();
            $lobbyData["mensajeUsuarioValidado"] = "El hash NO fue proporcionado";
            $this->presenter->render("login", $lobbyData);
        }else {
            $hash = $_GET['hash'];
            $usuario = $this->model->buscarUsuarioPorHash($hash);

            if ($usuario != null) {
                $this->model->activarCuenta($hash);
                $lobbyData = array();
                $lobbyData["mensajeUsuarioValidado"] = "El usuario fue validado con exito";
                $this->presenter->render("login", $lobbyData);
            } else {
                $lobbyData = array();
                $lobbyData["mensajeUsuarioValidado"] = "El usuario NO fue validado con exito";
                $this->presenter->render("login", $lobbyData);
            }
        }
    }
    public function login()
    {
        $username = $_POST["email"];
        $password = $_POST["password"];

        $usuario = $this->model->validarLogin($username,$password);

        if ($usuario !== null) {
            $_SESSION["usuario"] = $usuario;
            $roll = $usuario["roll"];

            $homeData = array();
            $homeData["usuario"] = $_SESSION["usuario"];

            switch ($roll) {
                case 'jugador':
                    $this->handleJugador();
                    break;
                case 'editor':
                    $this->presenter->render("lobby_editor", $homeData);
                    break;
                case 'admin':
                    $this->presenter->render("lobby_admin", $homeData);
                    break;
                default:
                    header("Location: /Home");
                    exit();
            }
        } else {
            header("Location: /Home");
            exit();
        }
    }

    private function handleJugador()
    {
        $partidasActualizadas = $this->lobbyModel->partidasActualizadas();
        $this->lobbyModel->actualizarUsuario();

        $homeData = array();
        $homeData["usuario"] = $_SESSION["usuario"];
        $homeData["partidasActualizadas"] = $partidasActualizadas;

        $this->presenter->render("lobby", $homeData);
    }

// temas a resolver
// Cuando reescribo la url, me ingresa a la app pero sin session
// Los jugadores en base de datos, no son coherentes con las preguntas que hay
//
// temas a resolver

}