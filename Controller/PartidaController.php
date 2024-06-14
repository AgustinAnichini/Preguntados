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
    public function iniciarPartida(){
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
                    $this->model->agregarPuntos($idPregunta);
                    $dataPausa = array();
                    $dataPausa["mensajeUsuario"] = "CORRECTA";
                    $dataPausa["pregunta"] = $this->model->buscarPreguntaPorId($idPregunta);
                    $dataPausa["partida"] = $_SESSION["partida"];
                    $this->presenter->render("pausa",$dataPausa);
                } else {
                    $this->presenter->render("finDelJuego", ['mensajeUsuario' => "INCORRECTA"]);
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













// entra en loop porque no hay mas preguntas de esa categoria

    //do {
    //$pregunta = $this->model->siguientePregunta();
    //  $preguntaYaRespondida = !$this->model->verificarPregunta($pregunta);
    //} while ($preguntaYaRespondida);


//    public function siguientePregunta()
//    {
//        if (isset($_POST["idCategoria"])) {
//            $idCategoria = $_POST["idCategoria"];
//            $maxIntentos = 10; // Para evitar bucles infinitos
//            $intentos = 0;
//            $pregunta = null;
//
//            // Bucle para encontrar una pregunta no respondida
//            while ($intentos < $maxIntentos) {
//                $pregunta = $this->model->siguientePregunta($idCategoria);
//                if (!$pregunta || !$this->model->verificarPregunta($pregunta)) {
//                    // Si no se encuentra una pregunta no respondida, se rompe el bucle
//                    break;
//                }
//                $intentos++;
//            }
//
//            if ($pregunta && !$this->model->verificarPregunta($pregunta)) {
//                // Obtiene las respuestas de la pregunta seleccionada
//                $respuestas = $this->model->respuestas($pregunta['id']);
//                // Prepara los datos para la vista
//                $preguntaData = array(
//                    "pregunta" => $pregunta,
//                    "respuesta" => $respuestas
//                );
//                // Renderiza la vista con los datos de la pregunta y las respuestas
//                $this->presenter->render("siguientePregunta", $preguntaData);
//            } else {
//                // Maneja el caso en el que no se encontró una pregunta no respondida
//                $this->ruleta();
////                $this->presenter->render("finalJuego", ['mensaje' => 'No hay más preguntas disponibles en esta categoría.']);
//            }
//        } else {
//            // Maneja el caso en el que no se envió el idCategoria
//            $this->presenter->render("error", ['mensaje' => 'Categoría no seleccionada.']);
//        }
//    }
}
//    public function verificarRespuesta()
//    {
//        if (isset($_GET['respuestaSeleccionada']) && isset($_GET['idPregunta'])) {
//            $idRespuesta = $_GET['respuestaSeleccionada'];
//            $idPregunta = $_GET['idPregunta'];
//
//            $esCorrecta = $this->model->verificarRespuesta($idPregunta,$idRespuesta);
//
//            if ($esCorrecta) {
//                // si la respondio bien, se agrega a respondida y va a ruleta de nuevo
//                $this->model->agregarPreguntaRespondida($idPregunta);
//                $this->ruleta();
//            } else {
//                $mensajeUsuario = "La miseria te persigue, Perdiste";
//                $this->presenter->render("nuevaPartida", ['mensajeUsuario' => $mensajeUsuario]);
//            }
//        } else {
//            $mensajeUsuario = "Debes seleccionar una respuesta.";
//            $this->presenter->render("nuevaPartida", ['mensajeUsuario' => $mensajeUsuario]);
//        }
//    }