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
        $usuario = $_SESSION["usuario"];

        $homeData = array();
        $homeData["usuario"] = $usuario;
        $this->presenter->render("lobby_editor", $homeData);
    }

    public function crearPregunta(){
        $this->presenter->render("formulario_editor");
    }
    //------------------------------------DAR DE ALTA PREGUNTAS----------------------------------------------------

    public function altaPregunta()
    {
        $usuario = $_SESSION["usuario"];

        $categoria =$_POST["categoria"];
        $pregunta = $_POST["pregunta"]; // texto
        $respuestaCorrecta= $_POST["respuesta-correcta"];
        $respuestaIncorrecta1 = $_POST["respuesta-incorrecta-1"];
        $respuestaIncorrecta2 = $_POST["respuesta-incorrecta-2"];
        $respuestaIncorrecta3 = $_POST["respuesta-incorrecta-3"];
        $nivel_dificultad = $_POST["nivel_dificultad"];
        $valor = $_POST["valor"];
        // cantidadAcertadas,cantidadEntregadas,tiempo_respuesta
        // 0                    0               30 --> SIEMPRE

        $sePropuso = $this->model->altaNuevaPregunta( $categoria,
                                    $pregunta, // tetxto
                                    $respuestaCorrecta,
                                    $respuestaIncorrecta1,
                                    $respuestaIncorrecta2,
                                    $respuestaIncorrecta3,
                                    $nivel_dificultad,
                                    $valor);
        if ($sePropuso){
            $homeData = array();
            $homeData["usuario"] = $usuario;
            $homeData["mensajeUsuarioSugerir"] = "La pregunta repotada fue dada de ALTA";
            $this->presenter->render("lobby_editor", $homeData);
        }else{
            $homeData = array();
            $homeData["usuario"] = $usuario;
            $homeData["mensajeUsuarioSugerir"] = "La pregunta repotada NO fue dada de ALTA";
            $this->presenter->render("lobby_editor", $homeData);
        }
    }
    public function darDeAltaPreguntaSugerida(){
        $idPregunta=$_GET["id"];
        $usuario = $_SESSION["usuario"];

        $pregunta = $this->preguntaModel->obtenerPreguntaSugeridaPorId($idPregunta);
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

        $categoria =$pregunta[0]['id_categoria'];
        $textoPregunta = $pregunta[0]['texto'];
        $nivel_dificultad = $pregunta[0]["nivel_dificultad"];
        $valor = $pregunta[0]["valor"];
        $respuestaIncorrecta1 = $respuestasIncorrectas[0];
        $respuestaIncorrecta2 = $respuestasIncorrectas[1];
        $respuestaIncorrecta3 = $respuestasIncorrectas[2];


        $seInserto = $this->model->altaPreguntaSugerida( $categoria,
            $textoPregunta, // texto
            $respuestaCorrecta,
            $respuestaIncorrecta1,
            $respuestaIncorrecta2,
            $respuestaIncorrecta3,
            $nivel_dificultad,
            $valor);

        $this->model->darDeBajaPreguntaSugerida($idPregunta);
        //$this->preguntaModel->darDeBajaRespuestaSugerida($idPregunta);
        if ($seInserto){
            $homeData = array();
            $homeData["usuario"] = $usuario;
            $homeData["mensajeUsuarioSugerir"] = "La pregunta sugerida fue dada de ALTA";
            $this->presenter->render("lobby_editor", $homeData);
        }else{
            $homeData = array();
            $homeData["usuario"] = $usuario;
            $homeData["mensajeUsuarioSugerir"] = "La pregunta sugerida NO fue dada de ALTA";
            $this->presenter->render("lobby_editor", $homeData);
        }
    }



//------------------------------------DAR DE BAJA PREGUNTAS----------------------------------------------------

    public function darDeBajaExistente(){
        $idPregunta =$_GET["id"];
        $usuario = $_SESSION["usuario"];

        $this->model->darDeBajaExistente($idPregunta);

        $homeData = array();
        $homeData["usuario"] = $usuario;
        $homeData["mensajeUsuarioSugerir"] = "La pregunta existente fue dada de BAJA";
        $this->presenter->render("lobby_editor", $homeData);
    }
    public function darDeBajaPreguntaSugerida(){
        $idPregunta =$_GET["id"];
        $usuario = $_SESSION["usuario"];

        $this->model->darDeBajaPreguntaSugerida($idPregunta);

        $homeData = array();
        $homeData["usuario"] = $usuario;
        $homeData["mensajeUsuarioSugerir"] = "La pregunta sugerida fue RECHAZADA";
        $this->presenter->render("lobby_editor", $homeData);
    }
    public function darDeBajaPreguntaReportada(){
        $idPregunta =$_GET["id"];
        $usuario = $_SESSION["usuario"];

        $this->model->darDeBajaExistente($idPregunta);
        $this->model->darDeBajaPreguntaUsuario($idPregunta);
        $this->model->darDeBajaPreguntaReportada($idPregunta);


        $homeData = array();
        $homeData["usuario"] = $usuario;
        $homeData["mensajeUsuarioSugerir"] = "La pregunta repotada fue dada de BAJA";
        $this->presenter->render("lobby_editor", $homeData);

    }


//------------------------------------LISTAS DE PREGUNTAS ----------------------------------------------------


    public function gestionarPreguntasReportadas()
    {
        $preguntasReportadas = $this->model->obtenerPreguntasReportadas();

        $reportadasData = array();
        $reportadasData["preguntas-reportadas"] = $preguntasReportadas;
        $reportadasData["titulo-preguntas-reportadas"] = "Gestionar preguntas reportadas";
        $this->presenter->render("lista_gestion_preguntas", $reportadasData);
    }

    public function gestionarPreguntasSugeridas()
    {
        $preguntasSugeridas = $this->model->obtenerPreguntasSugeridas();

        $sugeridasData = array();
        $sugeridasData["preguntas-sugeridas"] = $preguntasSugeridas;
        $sugeridasData["titulo-preguntas-sugeridas"] = "Gestionar preguntas sugeridas";

        $this->presenter->render("lista_gestion_preguntas", $sugeridasData);
    }
    public function gestionarPreguntasExistentes()
    {
        $preguntasExistentes = $this->model->obtenerPreguntasExistentes();

        $existenteData = array();
        $existentesData["preguntas-existentes"] = $preguntasExistentes;
        $existentesData["titulo-preguntas-existentes"] = "Gestionar preguntas existentes";
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

    public function mostrarFormModificarPreguntaReportada(){
        $idPregunta=$_GET["id"];

        $preguntaReportada = $this->model->obtenerPreguntaPorId($idPregunta);
        $respuestasReportadas = $this->preguntaModel->respuestas($idPregunta);// id
        $mensajeReporte = $this->model->obtenerMensajeReporte($idPregunta);

        $respuestasIncorrectas = array();
        $respuestaCorrecta = null;
        foreach ($respuestasReportadas as $index => $respuesta) {
            $respuesta['index'] = $index; // Asignar índices comenzando en 1
            if (isset($respuesta["correcta"]) && $respuesta["correcta"] == 1) {
                $respuestaCorrecta = $respuesta;
            } else {
                array_push($respuestasIncorrectas, $respuesta);
            }
            $index + 1;
        }

        $gestionData = array();
        $gestionData["preguntaReportada"] = $preguntaReportada;
        $gestionData["mensajeReporte"] = $mensajeReporte;
        $gestionData["respuestasIncorrectas"] = $respuestasIncorrectas;// array
        $gestionData["respuestaCorrecta"] = $respuestaCorrecta;

        $this->presenter->render("formulario_editor", $gestionData);

    }

    public function modificarPreguntaReportada(){
        $usuario = $_SESSION["usuario"];
        $idPregunta = $_POST["id"];


        $categoria =$_POST["categoria"];
        $pregunta = $_POST["pregunta"]; // texto
        $respuestaCorrecta= $_POST["respuesta-correcta"];
        $respuestaIncorrecta1 = $_POST["respuesta-incorrecta-1"];
        $respuestaIncorrecta2 = $_POST["respuesta-incorrecta-2"];
        $respuestaIncorrecta3 = $_POST["respuesta-incorrecta-3"];
        $nivel_dificultad = $_POST["nivel_dificultad"];
        $valor = $_POST["valor"];
        // en el post --> llega bien


            $this->model->darDeBajaPreguntaReportada($idPregunta);
            $this->model->modificarCategoria($categoria, $idPregunta);
            $this->model->modificarDificultad($nivel_dificultad, $idPregunta);
            $this->model->modificarTexto($pregunta, $idPregunta);
            $this->model->modificarValor($valor, $idPregunta);

            $idDeRespuestas = $this->preguntaModel->obtenerIdDeRespuestas($idPregunta);
            $this->model->modificarRespuestas($idPregunta,$idDeRespuestas,$respuestaCorrecta,$respuestaIncorrecta1,$respuestaIncorrecta2,$respuestaIncorrecta3);

            $homeData = array();
            $homeData["usuario"] = $usuario;
            $homeData["mensajeUsuarioSugerir"] = "La pregunta repotada fue MODIFICADA";
            $this->presenter->render("lobby_editor", $homeData);

    }

}
// Debe existir un tipo de usuario editor, que le permite dar de alta, baja y modificar las preguntas. --> HECHO
// DE TODAS LAS PREGUNTAS

// A su vez puede revisar las PREGUNTAS REPORTADAS, para aprobar o dar de baja, y PREGUNTAS SUGERIDAS sugeridas por usuarios.

// ALTA --> HECHO
// BAJA --> problema ID --> HECHO
// MODIFICAR --> problema ID --> que se modifica ? que ingrese toda la pregunta de nuevo con las respuestas --> HECHO


