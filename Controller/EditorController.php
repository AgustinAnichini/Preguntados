<?php


class EditorController
{
    private $model;
    private $presenter;

    public function __construct($model,$preguntaModel ,$presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
        $this->preguntaModel = $preguntaModel;
    }

    public function home()
    {

        $this->presenter->render("lobby_editor", []);
    }

    public function crearPregunta(){
        $this->presenter->render("formulario_editor");
    }
    //------------------------------------DAR DE ALTA PREGUNTAS----------------------------------------------------

    public function altaPregunta()
    {
        $categoria =$_POST["categoria"];
        $pregunta = $_POST["pregunta"]; // texto
        $respuestaCorrecta= $_POST["respuestaCorrecta"];
        $respuestaIncorrecta1 = $_POST["respuestaIncorrecta1"];
        $respuestaIncorrecta2 = $_POST["respuestaIncorrecta2"];
        $respuestaIncorrecta3 = $_POST["respuestaIncorrecta3"];
        $nivel_dificultad = $_POST["nivel_dificultad"];
        $valor = $_POST["valor"];
        // cantidadAcertadas,cantidadEntregadas,tiempo_respuesta
        // 0                    0               30 --> SIEMPRE

        $sePropuso = $this->model->altaPregunta( $categoria,
                                    $pregunta, // tetxto
                                    $respuestaCorrecta,
                                    $respuestaIncorrecta1,
                                    $respuestaIncorrecta2,
                                    $respuestaIncorrecta3,
                                    $nivel_dificultad,
                                    $valor);
        if ($sePropuso){
            header("Location: /lobby_editor?mensajeUsuarioSugerir=" . urlencode("La pregunta fue dada de ALTA"));
            exit;
        }else{
            header("Location: /lobby_editor?mensajeUsuarioSugerir=" . urlencode("La pregunta NO pudo ser dada de alta"));
            exit;
        }
    }
    public function darDeAltaPreguntaSugerida(){
        $idPregunta=$_GET["id"];

        $pregunta = $this->model->obtenerPreguntaSugeridaPorId($idPregunta);
        $respuestas = $this->preguntaModel->respuestasSugeridas($idPregunta);

        $respuestasIncorrectas = array();
        $respuestaCorrecta = null;
        foreach ($respuestas as $respuesta){
            if($respuesta["correcta"]==1){
                $respuestaCorrecta = $respuesta;
            }else{
                array_push($respuestasIncorrectas, $respuesta);
            }
        }

        $categoria =$pregunta["categoria"];
        $pregunta = $pregunta["pregunta"]; // texto
        $nivel_dificultad = $pregunta["nivel_dificultad"];
        $valor = $pregunta["valor"];
        $respuestaIncorrecta1 = $respuestasIncorrectas[0];
        $respuestaIncorrecta2 = $respuestasIncorrectas[1];
        $respuestaIncorrecta3 = $respuestasIncorrectas[2];


        $seInserto = $this->model->altaPregunta( $categoria,
            $pregunta, // tetxto
            $respuestaCorrecta,
            $respuestaIncorrecta1,
            $respuestaIncorrecta2,
            $respuestaIncorrecta3,
            $nivel_dificultad,
            $valor);

        $this->model->darDeBajaPreguntaSugerida($idPregunta);
        $this->preguntaModel->darDeBajaRespuestaSugerida($idPregunta);
        if ($seInserto){
            header("Location: /lobby_editor?mensajeUsuarioSugerir=" . urlencode("La pregunta  sugerida fue dada de ALTA"));
            exit;
        }else{
            header("Location: /lobby_editor?mensajeUsuarioSugerir=" . urlencode("La pregunta  sugerida NO pudo ser dada de alta"));
            exit;
        }
    }



//------------------------------------DAR DE BAJA PREGUNTAS----------------------------------------------------

    public function darDeBajaExistente(){
        $idPregunta =$_GET["id"];

        $this->model->darDeBajaExistente($idPregunta);
        header("Location: /lobby_editor?mensajeUsuarioSugerir=" . urlencode("La pregunta fue dada de BAJA"));
        exit;
    }
    public function darDeBajaPreguntaSugerida(){
        $idPregunta =$_GET["id"];

        $this->model->darDeBajaPreguntaSugerida($idPregunta);
        header("Location: /lobby_editor?mensajeUsuarioSugerir=" . urlencode("La pregunta fue dada de BAJA"));
        exit;
    }
    public function darDeBajaPreguntaReportada(){
        $idPregunta =$_GET["id"];

        $this->model->darDeBajaPreguntaReportada($idPregunta);
        $this->model->darDeBajaExistente($idPregunta);
        header("Location: /lobby_editor?mensajeUsuarioSugerir=" . urlencode("La pregunta fue dada de BAJA"));
        exit;
    }


//------------------------------------LISTAS DE PREGUNTAS ----------------------------------------------------


    public function gestionarPreguntasReportadas()
    {
        $preguntasReportadas = $this->model->obtenerPreguntasReportadas();

        $reportadasData = array();
        $reportadasData["preguntas-reportadas"] = $preguntasReportadas;
        $this->presenter->render("lista_gestion_preguntas", $reportadasData);
    }

    public function gestionarPreguntasSugeridas()
    {
        $preguntasSugeridas = $this->model->obtenerPreguntasSugeridas();

        $sugeridasData = array();
        $sugeridasData["preguntas-sugeridas"] = $preguntasSugeridas;
        $this->presenter->render("lista_gestion_preguntas", $sugeridasData);
    }
    public function gestionarPreguntasExistentes()
    {
        $preguntasExistentes = $this->model->obtenerPreguntasExistentes();

        $existenteData = array();
        $existentesData["preguntas-existentes"] = $preguntasExistentes;
        $this->presenter->render("lista_gestion_preguntas", $existentesData);
    }



//------------------------------------GESTIONAR UNA PREGUNTA ----------------------------------------------------

    public function gestionarPreguntaExistente()
    {
        $idGestion=$_GET["id"];

        $pregunta = $this->preguntaModel->obtenerPreguntaPorId($idGestion);
        $respuestas = $this->preguntaModel->respuestas($idGestion);
        $gestionData = array();
        $gestionData["gestiones-preguntas-existentes"] = " ";
        $gestionData["respuestas"] = $respuestas;
        $gestionData["pregunta"] = $pregunta;
        $this->presenter->render("gestionar_pregunta", $gestionData);
    }
    public function gestionarPreguntaSugerida()
    {
        $idGestion=$_GET["id"];

        $pregunta = $this->preguntaModel->obtenerPreguntaSugeridaPorId($idGestion);
        $respuestas = $this->preguntaModel->respuestasSugeridas($idGestion);
        $gestionData = array();
        $gestionData["gestiones-preguntas-sugeridas"] = " ";
        $gestionData["respuestas"] = $respuestas;
        $gestionData["pregunta"] = $pregunta;
        $this->presenter->render("gestionar_pregunta", $gestionData);
    }
    public function gestionarPreguntaReportada()
    {
        $idGestion=$_GET["id"];

        $pregunta = $this->preguntaModel->obtenerPreguntaPorId($idGestion);
        $respuestas = $this->preguntaModel->respuestas($idGestion);
        $gestionData = array();
        $gestionData["gestiones-preguntas-reportadas"] = " ";
        $gestionData["respuestas"] = $respuestas;
        $gestionData["pregunta"] = $pregunta;
        $this->presenter->render("gestionar_pregunta", $gestionData);
    }


//------------------------------------MODIFICAR PREGUNTA ----------------------------------------------------

    public function modificarPregunta( $categoria,
                                       $idPregunta,
                                       $texto,
                                       $respuestaCorrecta,
                                       $respuestaIncorrecta1,
                                       $respuestaIncorrecta2,
                                       $respuestaIncorrecta3,
                                       $nivel_dificultad,
                                       $valor){

        // la modifico completa
        $this->model->modificarPregunta($categoria,
            $idPregunta,
            $texto,
            $respuestaCorrecta,
            $respuestaIncorrecta1,
            $respuestaIncorrecta2,
            $respuestaIncorrecta3,
            $nivel_dificultad,
            $valor);

        header("Location: /lobby_editor?mensajeUsuarioSugerir=" . urlencode("La pregunta fue MODIFICADA"));
        exit;
    }

    public function mostrarFormModificarPreguntaReportada(){
        $idPregunta=$_GET["id"];
        $preguntaReportada = $this->model->obtenerPreguntaReportadaPorId($idPregunta);
        $respuestasReportadas = $this->model->obtenerRespuestasReportadas($preguntaReportada);

        $respuestasIncorrectas = array();
        $respuestaCorrecta = null;
        foreach ($respuestasReportadas as $respuesta){
            if($respuesta["correcta"]==1){
                $respuestaCorrecta = $respuesta;
            }else{
                array_push($respuestasIncorrectas, $respuesta);
            }
        }
        $gestionData = array();
        $gestionData["preguntaReportada"] = $preguntaReportada;
        $gestionData["respuestasIncorrectas"] = $respuestasIncorrectas;
        $gestionData["respuestaCorrecta"] = $respuestaCorrecta;

        $this->presenter->render("formulario_editor", $gestionData);
    }


// Debe existir un tipo de usuario editor, que le permite dar de alta, baja y modificar las preguntas. --> HECHO
// DE TODAS LAS PREGUNTAS

// A su vez puede revisar las PREGUNTAS REPORTADAS, para aprobar o dar de baja, y PREGUNTAS SUGERIDAS sugeridas por usuarios.

// ALTA --> HECHO
// BAJA --> problema ID --> HECHO
// MODIFICAR --> problema ID --> que se modifica ? que ingrese toda la pregunta de nuevo con las respuestas --> HECHO

}