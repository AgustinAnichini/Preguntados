<?php

class AdminModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    function obtenerJugadoresTotales()
    {
        $sql ="SELECT COUNT(*) AS total_usuarios FROM usuarios";
        $result = $this->database->query($sql);
        return $result[0]['total_usuarios'];
    }
    function obtenerPartidasJugadas()
    {
        $sql ="SELECT COUNT(*) AS total_partidas FROM partida";
        $result = $this->database->query($sql);
        return $result[0]['total_partidas'];
    }
    function obtenerCantidadPreguntasDelJuego()
    {
        $sql ="SELECT COUNT(*) AS total_preguntas FROM pregunta";
        $result = $this->database->query($sql);
        return $result[0]['total_preguntas'];
    }
    function obtenerCantidadUsuariosPorPais()
    {
        $sql ="SELECT pais, COUNT(*) AS cantidad_pais FROM usuarios GROUP BY pais";
        $result = $this->database->query($sql);
        return $result;
    }
    function obtenerCantidadUsuariosPorSexo()
    {
        $sql ="SELECT sexo, COUNT(*) AS cantidad_sexo FROM usuarios GROUP BY sexo";
        $result = $this->database->query($sql);
        return $result;
    }

    function cantidadPreguntasCreadas(){
        $sql ="SELECT  COUNT(*) AS cantidad_creadas FROM preguntaSugerida";
        $result = $this->database->query($sql);
        return $result[0]['cantidad_creadas'];
    }

    function obtenerCantidadDeUsuariosMenores(){

        $sql ="SELECT COUNT(*) AS cantidad_usuarios_menores FROM usuarios WHERE fecha_nacimiento > DATE_SUB(CURDATE(), INTERVAL 18 YEAR)";
        $result = $this->database->query($sql);
         return $result [0]['cantidad_usuarios_menores'];

    }
    function obtenerCantidadDeUsuariosAdultos(){

        $sql = "SELECT COUNT(*) AS cantidad_usuarios_mayores
            FROM usuarios 
            WHERE fecha_nacimiento <= DATE_SUB(CURDATE(), INTERVAL 18 YEAR)
            AND fecha_nacimiento > DATE_SUB(CURDATE(), INTERVAL 65 YEAR)";
        $result = $this->database->query($sql);
        return $result [0] ['cantidad_usuarios_mayores'];
    }

    public function obtenerCantidadUsuariosJubilados()
    {
        $sql = "SELECT COUNT(*) AS cantidad_usuarios_jubilados  FROM usuarios WHERE fecha_nacimiento <= DATE_SUB(CURDATE(), INTERVAL 65 YEAR)";
        $result = $this->database->query($sql);
        return $result [0]['cantidad_usuarios_jubilados'];
    }
}
