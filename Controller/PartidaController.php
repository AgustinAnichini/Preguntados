<?php

class PartidaController
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
        $this->presenter->render("nuevaPartida", []);
    }

    public function siguientePregunta()
    {
        // Obtén la siguiente pregunta
        $pregunta = $this->model->siguientePregunta();

        // Obtén las respuestas para la pregunta
        $respuestas = $this->model->respuestas($pregunta[0]['id']); // array

        // Inicializa un array para almacenar los datos
        //$preguntaData = array();

        // Almacena la pregunta y las respuestas en el array
        //$preguntaData = array($pregunta, $respuestas);

        $preguntaData["pregunta"] = $pregunta;
        $preguntaData["respuesta"] = $respuestas;
        // Renderiza la vista pasando los datos
        $this->presenter->render("siguientePregunta", $preguntaData);
    }


}