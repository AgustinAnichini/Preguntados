<?php

class RankingModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    function obtenerRanking()
    {
        $rankingMundial = $this->traerUsuariosPorPuntajeRanking(); // Trae una lista

        $posicion = 1;
        foreach ($rankingMundial as &$usuario) {
            $usuario['posicion'] = $posicion;
            $posicion++;
        }

       return $rankingMundial;
    }

    function traerUsuariosPorPuntajeRanking(){
        $ranking = $this->database->query("SELECT * FROM usuarios ORDER BY puntajeRanking DESC");
        return $ranking;
    }
}