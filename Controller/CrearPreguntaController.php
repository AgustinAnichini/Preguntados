<?php

class CrearPreguntaController{
    public function __construct($model, $presenter,$lobbyModel)
    {
        $this->model = $model;
        $this->presenter = $presenter;
        $this->lobbyModel = $lobbyModel;
    }

    public function home()
    {
        $categorias = $this->model->obtenerCategorias();
        $crearPreguntaData = array();
        $crearPreguntaData["categorias"] = $categorias;
        $this->presenter->render("crearPregunta",$crearPreguntaData);
    }

    function preguntaSugerida(){
        $categoria =$_POST["categoria"];
        $preguntaSugerida = $_POST["preguntaSugerida"];
        $respuestaCorrecta= $_POST["respuestaCorrecta"];
        $respuestaIncorrecta1 = $_POST["respuestaIncorrecta1"];
        $respuestaIncorrecta2 = $_POST["respuestaIncorrecta2"];
        $respuestaIncorrecta3 = $_POST["respuestaIncorrecta3"];

        $sePropuso = $this->model->sugerirPregunta($categoria,
                                    $preguntaSugerida,
                                    $respuestaCorrecta,
                                    $respuestaIncorrecta1,
                                    $respuestaIncorrecta2,
                                    $respuestaIncorrecta3);
        if ($sePropuso){
            header("Location: /lobby?mensajeUsuarioSugerir=" . urlencode("La pregunta fue sugerida con éxito"));
            exit;
        }else{
            header("Location: /lobby?mensajeUsuarioSugerir=" . urlencode("La pregunta fue sugerida con éxito"));
            exit;
        }

    }
}