<?php

class RegistroController
{
    private $model;
    private $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function login()
    {
        $this->presenter->render("Registro", []);
    }
}