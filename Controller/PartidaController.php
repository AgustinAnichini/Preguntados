<?php

class PartidaController
{
    private $model;
    private $preguntaModel;
    private $presenter;

    public function __construct($model, $presenter, $preguntaModel)
    {
        $this->model = $model;
        $this->preguntaModel = $preguntaModel;
        $this->presenter = $presenter;
    }

    public function home()
    {
        $this->presenter->render("nuevaPartida", []);
    }

    public function iniciarPartida()
    {
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];

        $this->model->iniciarPartida($idUsuario);
        $this->ruleta();
    }

    public function ruleta()
    {
        $contestoTodas = $this->preguntaModel->verificarSiContestoTodasLasPreguntas();
        do {
            $pregunta = $this->model->siguientePregunta();
            $preguntaYaRespondida = !$this->preguntaModel->verificarPregunta($pregunta);
        } while ($preguntaYaRespondida && !$contestoTodas);

        $categoria = $this->preguntaModel->obtenerCategoriaPorId($pregunta);
        $data = array();
        $data["pregunta"] = $pregunta;
        $data["categoria"] = $categoria;
        $this->presenter->render("ruleta", $data);
    }


    public function verificarRespuesta()
    {
        if (isset($_GET['respuestaSeleccionada']) && isset($_GET['idPregunta'])) {
            $idRespuesta = intval($_GET['respuestaSeleccionada']);
            $idPregunta = intval($_GET['idPregunta']);

            if ($idRespuesta > 0 && $idPregunta > 0) {
                $esCorrecta = $this->preguntaModel->verificarRespuesta($idPregunta, $idRespuesta);

                if ($esCorrecta) {
                    $this->model->agregarPreguntaRespondida($idPregunta);
                    $this->model->agregarPreguntasAcertadasALaPartida();
                    $this->model->agregarPuntos($idPregunta);
                    $this->model->actualizarPartida();

                    $dataPausa = array();
                    $dataPausa["mensajeUsuario"] = "CORRECTA";
                    $dataPausa["pregunta"] = $this->model->buscarPreguntaPorId($idPregunta);
                    $dataPausa["partida"] = $_SESSION["partida"];
                    $this->presenter->render("pausa", $dataPausa);
                } else {
                    $this->model->cerrarPartida();

                    $dataFin["mensajeUsuario"] = "INCORRECTA";
                    $dataFin["pregunta"] = $this->model->buscarPreguntaPorId($idPregunta);
                    $dataFin["partida"] = $_SESSION["partida"];
                    $this->presenter->render("finDelJuego", $dataFin);
                }
            } else {
                // Datos inválidos proporcionados
                $mensajeUsuario = "Error en la selección de respuesta.";
                $this->presenter->render("nuevaPartida", ['mensajeUsuario' => $mensajeUsuario]);
            }
        } else {
            $mensajeUsuario = "Debes seleccionar una respuesta.";
            $this->presenter->render("nuevaPartida", ['mensajeUsuario' => $mensajeUsuario]);
        }
    }

    public function siguientePregunta()
    {
        $idpregunta = $_POST["id"];
        $pregunta = $this->model->buscarPreguntaPorId($idpregunta);
        $respuestas = $this->preguntaModel->respuestas($pregunta['id']); // array
        $preguntaData = array();
        $preguntaData["pregunta"] = $pregunta;
        $preguntaData["respuesta"] = $respuestas;

        $this->presenter->render("siguientePregunta", $preguntaData);
    }

    function recargo()
    {
        $this->model->cerrarPartida();
        $this->model->actualizarUsuario();
        $partidasActualizadas = $this->model->partidasActualizadas();

        $usuario = $_SESSION["usuario"];
        $lobbyData = array();
        $lobbyData["usuario"] = $usuario;
        $lobbyData["partidasActualizadas"] = $partidasActualizadas;
        $this->presenter->render("lobby", $lobbyData);
    }
    
}