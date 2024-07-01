<?php

class LobbyModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    function actualizarUsuario(){
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];
        $usuarioActualizado = $this->database->query("SELECT * FROM usuarios WHERE id = $idUsuario");
        $_SESSION["usuario"] = $usuarioActualizado[0];
    }

   function partidasActualizadas(){
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];

        $partidasActualizadas = $this->database->query("SELECT * FROM partida WHERE idUsuario = $idUsuario LIMIT 5");
        return $partidasActualizadas;
    }

    function obtenerRankingDeUsuario(){
        $usuario = $_SESSION["usuario"];
        $idUsuario = $usuario["id"];
        $puntajeTotal = $usuario["puntajeTotal"];
        $partidasJugadas = $usuario["partidasJugadas"];
        // ranking --> ahora es --> puntajeRanking --> calculado de PT/PJ

        $puntajeRanking = $this->calcularPuntajeRanking($puntajeTotal,$partidasJugadas);
        $this->database->execute("UPDATE usuarios SET puntajeRanking = '$puntajeRanking' WHERE id = '$idUsuario'");

        // obtener una lista de todos los usuarios ordenada por puntajeRanking

        $listaUsuariosRanking = $this->traerUsuariosPorPuntajeRanking(); // Trae una lista
        // con la lista debemos mostrar la posicion en la que se encuentra el usurio
        $posicionUsuarioEnElRanking = $this->bucarPosicionUsuarioEnRanking($listaUsuariosRanking, $idUsuario);
        // ranking --> posicion del usuario en la lista ordenda por puntaje ranking

        $this->database->execute("UPDATE usuarios SET ranking = '$posicionUsuarioEnElRanking' WHERE id = '$idUsuario'");
    }
    function traerUsuariosPorPuntajeRanking(){
        $ranking = $this->database->query("SELECT * FROM usuarios ORDER BY puntajeRanking DESC");
        return $ranking;
    }

    function bucarPosicionUsuarioEnRanking($listaUsuariosRanking, $idUsuarioBuscado){
        $posicion = 1;

        foreach ($listaUsuariosRanking as $usuario) {
            if ($usuario['id'] == $idUsuarioBuscado) {
                break;
            }
            $posicion++;
        }
        return $posicion;
    }

    function calcularPuntajeRanking($puntajeTotal,$partidasJugadas){
        $puntajeRanking = 0;
        if($partidasJugadas != 0){
            $puntajeRanking = $puntajeTotal/$partidasJugadas;
        }
        return $puntajeRanking;
    }
}