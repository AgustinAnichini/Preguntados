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
    public function login()
    {
        $username = $_POST["email"];
        $password = $_POST["password"];

        $usuario = $this->model->validarLogin($username,$password);
//        var_dump($usuario);

        if ($usuario != null){
            $this->presenter->render("lobby", [$usuario["nombre_usuario"]]);
            session_start();
        }else{
            header("Location: /Home");
            exit();
        }
    }
}