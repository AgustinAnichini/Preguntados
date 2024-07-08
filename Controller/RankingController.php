<?php

class RankingController
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
        if(!isset($_SESSION["usuario"])){
            $this->presenter->render("login", []);
            exit();
        }
        $rankingMundial = $this->model->obtenerRanking();
        $rankingData = array();
        $rankingData["rankingMundial"] = $rankingMundial;
        $this->presenter->render("RankingMundial", $rankingData);
    }

}
