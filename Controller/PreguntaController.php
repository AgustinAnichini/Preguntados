<?php

class PreguntaController
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
        $this->presenter->render("login", []);
    }

}
