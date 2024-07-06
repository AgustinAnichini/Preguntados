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
    {
        if (isset($_POST["nombreCompleto"], $_POST["fechaNacimiento"], $_POST["sexo"],
                $_POST["pais"], $_POST["ciudad"], $_POST["email"], $_POST["password"], $_POST["confirmPassword"], $_POST["username"])
            && isset($_FILES["fotoPerfil"])) {

            $formData = $_POST;

            $fotoPerfil = $_FILES['fotoPerfil'];
            $nombreArchivo = $fotoPerfil['name'];
            $rutaTemporal = $fotoPerfil['tmp_name'];
            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
            $nombreUnico = uniqid('perfil_') . '.' . $extension;
            $rutaDestino = "./public/img/" . $nombreUnico;

            // Mueve el archivo subido a la ubicación deseada
            if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
                $formData['fotoPerfil'] = $nombreUnico; // Guarda solo el nombre del archivo
            }
            $this->model->sendEmail($formData);
            $this->presenter->render("login", []);
        }else{
            $this->presenter->render("login", []);
        }
    }
}