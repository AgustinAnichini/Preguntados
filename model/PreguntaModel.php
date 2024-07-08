<?php

class PreguntaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }



    function getPreguntas(){
        return $this->database->query("SELECT * FROM pregunta");
    }

    function obtenerCategoriaPorId($pregunta){
        $idPregunta = $pregunta["id"];
        $result = $this->database->query("SELECT * FROM categoria C
                                            JOIN pregunta P on C.id = P.id_categoria 
                                            WHERE P.id = $idPregunta");
        return $result[0];
    }

    function verificarSiContestoTodasLasPreguntas(){
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];
        $respondioTodas = false;

        $preguntasDelUsuario = $this->database->query("SELECT * FROM preguntaUsuario PU WHERE PU.idUsuario = $idUsuario");
        $cantPregUsuario = count($preguntasDelUsuario);

        $todasLasPreguntas = $this->getPreguntas();
        $cantDePreg = count($todasLasPreguntas);

        if($cantDePreg == $cantPregUsuario){
            $this->borrarPreguntasDelUsuario($idUsuario);
            // reiniciar preguntasAcertadas y respondidas
            $this->reiniciarPreguntasRespondidas($idUsuario);
            $this->reiniciarPreguntasAcertadasTotales($idUsuario);
            $respondioTodas = true;
        }

        return $respondioTodas;
    }

    function borrarPreguntasDelUsuario($idUsuario){
        $this->database->execute("DELETE FROM preguntaUsuario PU WHERE PU.idUsuario = $idUsuario");
    }

    function verificarPregunta($pregunta){
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];
        $idPregunta = $pregunta["id"];
        $result = $this->database->query("SELECT * FROM preguntaUsuario PU WHERE PU.idPregunta = $idPregunta and PU.idUsuario = $idUsuario");
        return empty($result); // True si no encontró la pregunta
    }

    public function respuestas($idPregunta)
    {
        $result = $this->database->query("SELECT * FROM respuesta WHERE pregunta_id = '$idPregunta'");

        if (!$result) {
            return [];
        }
        return $result;
    }
    public function obtenerIdDeRespuestas($idPregunta)
    {
        $idRespuestas = $this->database->query("SELECT id FROM respuesta WHERE pregunta_id = '$idPregunta'");
        var_dump("ESPACIO");
        if (!$idRespuestas) {
            return [];
        }

        return $idRespuestas;
    }

    function verificarRespuesta($idPregunta, $idRespuesta){
        $result = $this->database->query("SELECT r.correcta FROM respuesta r WHERE r.pregunta_id = '$idPregunta' and $idRespuesta like r.id");
        return $result[0]["correcta"];
    }
    function reiniciarPreguntasRespondidas($idUsuario){
        $this->database->execute("UPDATE usuarios SET preguntasRespondidas = 0 WHERE id = $idUsuario");
    }
    function reiniciarPreguntasAcertadasTotales($idUsuario){
        $this->database->execute("UPDATE usuarios SET preguntasAcertadasTotales = 0 WHERE id = $idUsuario");
    }

    function sumarCantidadEntregadas($idPregunta){
        $this->database->execute("UPDATE pregunta SET cantidadEntregadas = cantidadEntregadas + 1 WHERE id = $idPregunta");
    }

    function sumarCantidadPreguntaAcertada($idPregunta)
    {
        $this->database->execute("UPDATE pregunta SET cantidadAcertadas = cantidadAcertadas + 1 WHERE id = $idPregunta");
    }

    function calcularNivelDePregunta($idPregunta)
    {
        $resultadoEntregadas = $this->database->query("SELECT cantidadEntregadas FROM pregunta WHERE id = $idPregunta");
        $resultadoAcertadas = $this->database->query("SELECT cantidadAcertadas FROM pregunta WHERE id = $idPregunta");

        if ($resultadoEntregadas && $resultadoAcertadas) {
            $filaRespondidas = $resultadoEntregadas[0];
            $filaAcertadas = $resultadoAcertadas[0];

            $cantidadEntregadas = isset($filaRespondidas['cantidadEntregadas']) ? (int)$filaRespondidas['cantidadEntregadas'] : 0;
            $preguntasRespondidasCorrectamente = isset($filaAcertadas['cantidadAcertadas']) ? (int)$filaAcertadas['cantidadAcertadas'] : 0;

            if ($cantidadEntregadas >= 10) {
                $nivelPregunta = $preguntasRespondidasCorrectamente / (float)$cantidadEntregadas;

                if ($nivelPregunta >= 0.0 && $nivelPregunta < 0.3) {
                    $nivel = 'alto';
                } elseif ($nivelPregunta >= 0.3 && $nivelPregunta < 0.7) {
                    $nivel = 'medio';
                } elseif ($nivelPregunta >= 0.7 && $nivelPregunta <= 1.0) {
                    $nivel = 'bajo';
                } else {
                    $nivel = 'bajo'; // Manejo del caso por defecto
                }
            } else {
                $nivel = 'bajo';
            }
            $this->database->execute("UPDATE pregunta SET nivel_dificultad = '$nivel' WHERE id = $idPregunta");
        }
    }

    public function obtenerPreguntaPorId($idPregunta)
    {
        $sql = "SELECT * FROM pregunta where id = $idPregunta";
        $pregunta = $this->database->query($sql);
        return $pregunta;
    }
    public function obtenerPreguntaSugeridaPorId($idPregunta)
    {
        $sql = "SELECT * FROM preguntaSugerida where id = $idPregunta AND pendiente = true";
        $pregunta = $this->database->query($sql);
        return $pregunta;
    }

    public function respuestasSugeridas($idPregunta)
    {
        $result = $this->database->query("SELECT * FROM respuestaSugerida WHERE pregunta_id = '$idPregunta'");

        if (!$result) {
            return [];
        }
        return $result;
    }

    public function darDeBajaRespuestaSugerida($idPregunta){
        $sql = "DELETE FROM respuestaSugerida where id = $idPregunta";
        $this->database->execute($sql);
    }

}
