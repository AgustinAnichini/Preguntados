<?php

class ReporteController{
    public function __construct($model, $presenter, $partidaModel)
    {
        $this->model = $model;
        $this->presenter = $presenter;
        $this->partidaModel = $partidaModel;
    }

    public function home()
    {
        $idPregunta = $_GET["idPregunta"];
        $pregunta = $this->partidaModel->buscarPreguntaPorId($idPregunta);
        $respuestas = $this->model->obtenerRespuestasPorIdPregunta($idPregunta);

        $reporteData = array();
        $reporteData["pregunta"] = $pregunta;
        $reporteData["respuesta"] = $respuestas;

        $this->presenter->render("reportar_pregunta",$reporteData);
    }

    public function reportarPregunta(){
        $texto = $_GET["texto"];
        $idPregunta= $_GET["idPregunta"];

        $this->model->reportarPregunta($idPregunta,$texto);
        $this->partidaModel->actualizarPartida();
        $this->partidaModel->cerrarPartida();

        $mensajeUsuario = "La pregunta fue reportada";
        $dataFin = array();
        $dataFin["mensajeUsuario"] = $mensajeUsuario;
        $dataFin["partida"] = $_SESSION["partida"];
        $this->presenter->render("finDelJuego", $dataFin);
    }
}