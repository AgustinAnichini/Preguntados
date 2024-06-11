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
    public function nuevaPartida()
    {
        $this->model->crearNuevaPartida();
        $this->presenter->render("nuevaPartida", [] );
    }
    public function verificarRespuesta()
    {
        if (isset($_GET['respuestaSeleccionada']) && isset($_GET['idPregunta'])) {
            $idRespuesta = $_GET['respuestaSeleccionada'];
            $idPregunta = $_GET['idPregunta'];

            $esCorrecta = $this->model->verificarRespuesta($idPregunta,$idRespuesta);

            if ($esCorrecta) {
                $this->model->agregarPreguntaRespondida($idPregunta);
                $this->siguientePregunta();
            } else {
                $mensajeUsuario = "La miseria te persigue, Perdiste";
                $this->presenter->render("nuevaPartida", ['mensajeUsuario' => $mensajeUsuario]);
            }
        } else {
            // Manejar el caso donde no se envió una respuesta o falta el idPregunta
            $mensajeUsuario = "Debes seleccionar una respuesta.";
            $this->presenter->render("nuevaPartida", ['mensajeUsuario' => $mensajeUsuario]);
        }
    }

    public function siguientePregunta()
    {
        // Obtén la siguiente pregunta
        // se busco una pregunta, hay que verif. que el usuario no la haya respondido --> preguntaUsuario
        $contestoTodas = $this->model->verificarSiContestoTodasLasPreguntas();
        do{
            $pregunta = $this->model->siguientePregunta();
            $preguntaYaRespondida = !$this->model->verificarPregunta($pregunta);
        } while ($preguntaYaRespondida && !$contestoTodas);


//        if (!$pregunta) {
//            // Manejar el caso en el que no hay más preguntas
//            $this->presenter->render("finalJuego", []);
//            return;
//        }

        // Obtén las respuestas para la pregunta
        $respuestas = $this->model->respuestas($pregunta['id']); // array

        // Inicializa un array para almacenar los datos
        $preguntaData = array();

        // Almacena la pregunta y las respuestas en el array
        $preguntaData["pregunta"] = $pregunta;
        $preguntaData["respuesta"] = $respuestas;
        //$preguntaData["idUsuario"] = $_POST["idUsuario"];

        // Renderiza la vista pasando los datos
        $this->presenter->render("siguientePregunta", $preguntaData);
    }
}