<?php

class UsuarioModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    function agregarPuntosObtenidosAlUsuario()
    {
        $partida = $_SESSION["partida"];
        $puntajePartida = $partida["puntaje"];
        var_dump($puntajePartida);// llega bien
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];

        $this->database->execute("UPDATE usuarios set puntajeTotal = puntajeTotal + '$puntajePartida' WHERE id = $idUsuario");
    }

    function agregarPartidaJugada($idUsuario){
        // a partir del usuario, agregamos 1 a partidas jugadas
        $this->database->execute("UPDATE usuarios set partidasJugadas = partidasJugadas + 1 WHERE id = $idUsuario");
    }

    function sumarUnaPreguntaRespondidaAlUsuario($idUsuario)
    {
        // para cuando calculemos el nivel:

        // hay que consultar la tabla preguntaUsuario para obtener cuantas veces aparece el id de ese usuario en la tabla
        // ahi ya obtengo las preguntas respondidas del usuario, y le deberia sumar 1 al usuario --> preguntasRespondidas

        $this->database->execute("UPDATE usuarios set preguntasRespondidas = preguntasRespondidas + 1 WHERE id = $idUsuario");
    }


    function agregarPreguntasAcertadasAlUsuario()
    {
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];

        $this->database->execute("UPDATE usuarios SET preguntasAcertadasTotales = preguntasAcertadasTotales + 1 WHERE id = $idUsuario");
    }

    function calcularDificultadUsuario($IdUsuario) {
        $resultadoRespondidas = $this->database->query("SELECT preguntasRespondidas FROM usuarios WHERE id = $IdUsuario");
        $resultadoAcertadas = $this->database->query("SELECT preguntasAcertadasTotales FROM usuarios WHERE id = $IdUsuario");

        // Verifica que las consultas fueron exitosas y obtiene los valores numéricos de los resultados
        if ($resultadoRespondidas && $resultadoAcertadas) {
            // Obtiene la primera fila de los resultados como un array
            $filaRespondidas = $resultadoRespondidas[0];
            $filaAcertadas = $resultadoAcertadas[0];

            // Extrae los valores de las claves asociativas
            $preguntasRespondidas = isset($filaRespondidas['preguntasRespondidas']) ? (int)$filaRespondidas['preguntasRespondidas'] : 0;
            $preguntasRespondidasCorrectamente = isset($filaAcertadas['preguntasAcertadasTotales']) ? (int)$filaAcertadas['preguntasAcertadasTotales'] : 0;

            if ($preguntasRespondidas >= 10) {
                $nivelUsuario = $preguntasRespondidasCorrectamente / (float)$preguntasRespondidas;

                // Determina y actualiza el nivel del usuario
                if ($nivelUsuario >= 0.0 && $nivelUsuario < 0.3) {
                    $nivel = 'bajo';
                } elseif ($nivelUsuario >= 0.3 && $nivelUsuario < 0.7) {
                    $nivel = 'medio';
                } elseif ($nivelUsuario >= 0.7 && $nivelUsuario <= 1.0) {
                    $nivel = 'alto';
                } else {
                    $nivel = 'bajo'; // Manejo del caso por defecto
                }
            } else {
                $nivel = 'bajo';
            }
            $this->database->execute("UPDATE usuarios SET nivel = '$nivel' WHERE id = $IdUsuario");
        }
    }

    function obtenerPorcentajeUsuario($usuario) {
        $idUsuario = $usuario['id'];
        $totalPreguntas = $this->obtenerCantidadTotalPreguntas();

        $sql = "SELECT COUNT(*) AS preguntasAcertadasTotales FROM usuarios WHERE id = '$idUsuario'";
        $result = $this->database->query($sql);

        if ($result && count($result) > 0) {
            $preguntasAcertadasTotales = $result[0]['preguntasAcertadasTotales'];

            $porcentaje = ($preguntasAcertadasTotales / $totalPreguntas) * 100;
            return $porcentaje;
        } else {
            return 0;
        }
    }

    function obtenerCantidadTotalPreguntas()
    {
        $sql = "SELECT  COUNT(*) AS cantidad FROM pregunta";
        $result = $this->database->query($sql);
        return $result [0]['cantidad'];
    }

    function buscarUsuarioPorID($idUsuario){
        $sql = "SELECT * FROM usuarios WHERE id = '$idUsuario'";
        $result = $this->database->query($sql);
        return $result[0];
    }

    public function obtenerCantidadUsuariosMenores()
    {
        $limiteSuperiorMenores = 18; // Menores de 18 años (ejemplo)

        $sql = "SELECT COUNT(*) AS cantidadMenores FROM usuarios 
            WHERE DATEDIFF(CURRENT_DATE, fecha_nacimiento) / 365.25 < $limiteSuperiorMenores";

        $result = $this->database->query($sql);

        return $result[0]['cantidadMenores'];
    }

    public function obtenerCantidadUsuariosAdultos()
    {
        $limiteInferiorAdultos = 18; // Adultos mayores de 18 años (ejemplo)

        $sql = "SELECT COUNT(*) AS cantidadAdultos FROM usuarios 
            WHERE DATEDIFF(CURRENT_DATE, fecha_nacimiento) / 365.25 >= $limiteInferiorAdultos";

        $result = $this->database->query($sql);
        return $result[0]['cantidadAdultos'];
    }

    public function obtenerCantidadUsuariosJubilados()
    {
        $limiteSuperiorJubilados = 65; // Jubilados mayores de 65 años (ejemplo)

        $sql = "SELECT COUNT(*) AS cantidad FROM usuarios 
            WHERE DATEDIFF(CURRENT_DATE, fecha_nacimiento) / 365.25 >= $limiteSuperiorJubilados";

        $result = $this->database->query($sql);
        return $result[0]['cantidad'];
    }

    public function obtenerListaUsuarios(){
        $sql  = "SELECT * FROM usuarios WHERE roll = 'jugador'";
        $result = $this->database->query($sql);

        return $result;
    }

    public function obtenerCantidadPreguntasRespondidasCorrectas($idUsuario){
        $sql  = "SELECT preguntasAcertadasTotales FROM usuarios WHERE id = $idUsuario";
        $result = $this->database->query($sql);
        return $result[0]['preguntasAcertadasTotales'];
    }
    public function buscarUsuario($idUsuario){
        $sql = "SELECT * FROM usuarios WHERE id = '$idUsuario'";
        $result = $this->database->query($sql);
        return $result[0];
    }


}