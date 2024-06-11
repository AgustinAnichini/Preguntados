<?php

class HomeController
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
        $this->presenter->render("login", []);
    }
    public function validarHash()
    {
        if(!isset($_GET['hash'])){
            $this->presenter->render("registro", ["mensaje" => "El hash no fue proporcionado"]);
            return;
        }

        $hash = $_GET['hash'];
        $usuario = $this->model->buscarUsuarioPorHash($hash);

            if($usuario != null){
                $this->model->activarCuenta($usuario);
                var_dump($usuario);
                header("Location: /");
                exit();
            }else{
                $this->presenter->render("login",["mensaje"=>"El enlace no es valido"]);
            }

//        $this->presenter->render("login", []);
    }
    public function login()
    {
        $username = $_POST["email"];
        $password = $_POST["password"];

        $usuario = $this->model->validarLogin($username,$password);
//        var_dump($usuario);

        if ($usuario != null){
            $_SESSION["usuario"]=$usuario;
            $this->presenter->render("lobby", ["usuario"=>$usuario]);
        }else{
            header("Location: /Home");
            exit();
        }
    }


}