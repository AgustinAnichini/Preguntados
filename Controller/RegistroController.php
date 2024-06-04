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

    public function home()
    {
        $this->presenter->render("Registro", []);
    }

    public function registrar()
    {  // todo esto hay que llevarlo al servicio

      if(  isset($_POST["nombreCompleto"],$_POST["fechaNacimiento"],$_POST["sexo"],
          $_POST["pais"],$_POST["ciudad"],$_POST["email"],$_POST["password"],$_POST["confirmPassword"],$_POST["username"],
              $_POST["fotoPerfil"]) || isset($_FILES["fotoPerfil"]['name'])){

          $formData = $_POST;
          $formData['fotoPerfil']['name'] =  $_FILES['fotoPerfil']['name'];

          $this->model->sendEmail($formData);
          $this->presenter->render("login", []);
      }
    }
}