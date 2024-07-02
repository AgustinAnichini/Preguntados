<?php

class PartidaController
{
    private $model;
    private $preguntaModel;
    private $usuarioModel;
    private $presenter;

    public function __construct($model, $presenter, $preguntaModel, $usuarioModel)
    {
        $this->model = $model;
        $this->preguntaModel = $preguntaModel;
        $this->presenter = $presenter;
        $this->usuarioModel = $usuarioModel;
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
        $this->usuarioModel->agregarPartidaJugada($idUsuario);
        $this->ruleta();
    }

    public function ruleta()
    {
        $Usuario = $_SESSION['usuario'];
        $nivelUsuario = $Usuario['nivel'];

        $contestoTodas = $this->preguntaModel->verificarSiContestoTodasLasPreguntas();
        do {
            // metodo de comparar nivel de usuario con pregunta
            $pregunta = $this->model->siguientePregunta();
            $preguntaYaRespondida = !$this->preguntaModel->verificarPregunta($pregunta);
            $nivelPregunta = $pregunta['nivel_dificultad'];
        } while ($preguntaYaRespondida && $nivelUsuario == $nivelPregunta);

        var_dump($nivelUsuario); // false - false
        var_dump($nivelPregunta);

        $IdUsuario = $Usuario['id'];
        $idPregunta = $pregunta['id'];

        $this->model->agregarPreguntaRespondida($idPregunta);// a la tabla preguntaUsuario
        $this->usuarioModel->sumarUnaPreguntaRespondidaAlUsuario($IdUsuario); //a la tabla Usuario
        $this->preguntaModel->sumarCantidadEntregadas($idPregunta); //a la tabla Pregunta
        $this->usuarioModel->calcularDificultadUsuario($IdUsuario);

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

    function finDelJuego(){
        $duracion= $this->calcularDuracionPartida();
        $this->model->actualizarDuracionDePartida($duracion);
        $this->usuarioModel->agregarPuntosObtenidosAlUsuario();
        // metodo que consulta si el puntaje obtenido es el mas alto que obtuvo

        $this->model->actualizarPartida();
        $this->model->cerrarPartida();
        $mensajeUsuario = "Te quedaste sin tiempo!!";

        $dataFin["mensajeUsuario"] = $mensajeUsuario;
        $dataFin["partida"] = $_SESSION["partida"];
        $this->presenter->render("finDelJuego", $dataFin);
    }

    function preguntaAcertada($idPregunta,$mensajeUsuario){
        $this->usuarioModel->agregarPreguntasAcertadasAlUsuario();// agregar una pregunta respondida correctamente al usuario
        $this->preguntaModel->sumarCantidadPreguntaAcertada($idPregunta);// agregar uno a pregunta respondida correctamente
        $this->preguntaModel->calcularNivelDePregunta($idPregunta);//calcular el nivel de dificultad de la pregunta
        $this->model->agregarPreguntasAcertadasALaPartida(); // agregar una pregunta respondida correctamente a la partida
        // para calcular el nivel
        // debemos sumar las preguntas acertadas del usuario

        $this->model->agregarPuntos($idPregunta);
        $this->model->actualizarUsuario();
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
        $this->usuarioModel->agregarPuntosObtenidosAlUsuario();
        $this->model->actualizarUsuario();
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