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
        // aca debe comenzar el timer (duracion total de la partida)

        $fechaActual = new DateTime();
        // Formatear la fecha para obtener solo los minutos y segundos actuales
        $tiempoInicioPartida = $fechaActual->format('H:i:s');
        $_SESSION["tiempoInicioPartida"] = $tiempoInicioPartida;

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
                    $mensajeUsuario= "CORRECTA";
                    $this->preguntaAcertada($idPregunta, $mensajeUsuario);
                } else {
                    $mensajeUsuario= "INCORRECTA";
                    $this->preguntaNOacertada($idPregunta,$mensajeUsuario);
                }
            } else {
                $this->finDelJuego();
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

    function finDelJuego(){
        $duracion= $this->calcularDuracionPartida();
        $this->model->actualizarDuracionDePartida($duracion);
        $this->model->actualizarPartida();
        $this->model->cerrarPartida();
        $mensajeUsuario = "Te quedaste sin tiempo!!";

        $dataFin["mensajeUsuario"] = $mensajeUsuario;
        $dataFin["partida"] = $_SESSION["partida"];
        $this->presenter->render("finDelJuego", $dataFin);
    }

    function preguntaAcertada($idPregunta,$mensajeUsuario){
        $this->model->agregarPreguntaRespondida($idPregunta);
        $this->model->agregarPreguntasAcertadasALaPartida();
        $this->model->agregarPuntos($idPregunta);
        $this->model->actualizarPartida();

        $dataPausa = array();
        $dataPausa["mensajeUsuario"] = $mensajeUsuario;
        $dataPausa["pregunta"] = $this->model->buscarPreguntaPorId($idPregunta);
        $dataPausa["partida"] = $_SESSION["partida"];
        $this->presenter->render("pausa", $dataPausa);
    }

    function preguntaNOacertada($idPregunta,$mensajeUsuario)
    {
        $duracion= $this->calcularDuracionPartida();
        $this->model->actualizarDuracionDePartida($duracion);
        $this->model->actualizarPartida();
        $this->model->cerrarPartida();

        $dataFin["mensajeUsuario"] = $mensajeUsuario;
        $dataFin["pregunta"] = $this->model->buscarPreguntaPorId($idPregunta);
        $dataFin["partida"] = $_SESSION["partida"];
        $this->presenter->render("finDelJuego", $dataFin);
    }

    function calcularDuracionPartida()
    {
        if (isset($_SESSION["tiempoInicioPartida"])) {
            $tiempoInicio = DateTime::createFromFormat('H:i:s', $_SESSION["tiempoInicioPartida"]);

            $fechaActual = new DateTime();
            $diferencia = $tiempoInicio->diff($fechaActual);
            // Formatear la diferencia como 'HH:MM:SS'
            $duracion = $diferencia->format('%H:%I:%S');
            var_dump($duracion);// llega bien

            return $duracion;
        } else {
            return '00:00:12';
        }
    }
}