<?php

include_once ("controller/RegistroController.php");
include_once ("controller/HomeController.php");
include_once ("controller/PartidaController.php");
include_once ("controller/LobbyController.php");
include_once ("controller/PreguntaController.php");
include_once ("controller/UsuarioController.php");
include_once ("controller/RankingController.php");
include_once ("controller/ReporteController.php");
include_once ("controller/CrearPreguntaController.php");

include_once ("model/RegistroModel.php");
include_once ("model/HomeModel.php");
include_once ("model/PartidaModel.php");
include_once ("model/LobbyModel.php");
include_once ("model/PreguntaModel.php");
include_once ("model/UsuarioModel.php");
include_once ("model/RankingModel.php");
include_once ("model/ReporteModel.php");
include_once ("model/CrearPreguntaModel.php");


include_once ("helper/Router.php");
include_once("helper/Database.php");
include_once("helper/MustachePresenter.php");
include_once("helper/Presenter.php");
include_once('vendor/vendor/mustache/src/Mustache/Autoloader.php');// agregue esto

Class Configuration{

    //CONTROLLERS----------------------------------------------------------------------------------------------------------------------
    public static function getRegistroController()
    {
        return new RegistroController(self::getRegistroModel() ,self::getPresenter()); // le falta ser creado con el servicio
    }
    public static function getHomeController()
    {
        return new HomeController(self::getHomeModel() , self:: getPresenter(), self::getLobbyModel());
    }
    public static function getLobbyController(){
        return new LobbyController(self::getLobbyModel(),self::getPresenter(),self::getUsuarioModel());
    }
    public static function getPartidaController(){
        return new PartidaController(self::getPartidaModel(),self::getPresenter(), self::getPreguntaModel(), self::getUsuarioModel());
    }
    public static function getPreguntaController(){
        return new PreguntaController(self::getPreguntaModel(),self::getPresenter());
    }
    public static function getUsuarioController(){
        return new UsuarioController(self::getUsuarioModel(), self::getPresenter());
    }
    public static function getRankingController(){
        return new RankingController(self::getRankingModel(), self::getPresenter());
    }
    public static function getReporteController(){
        return new ReporteController(self::getReporteModel(), self::getPresenter(), self::getPartidaModel());
    }
    public static function getCrearPreguntaController(){
        return new CrearPreguntaController(self::getCrearPreguntaModel(), self::getPresenter(), self::getLobbyModel());
    }

    //MODELS---------------------------------------------------------------------------------------------------------------------------
    private static function getRegistroModel()
    {
        return new RegistroModel(self::getsabiondosDatabase());
    }

    private static function getPreguntaModel()
    {
        return new PreguntaModel(self::getsabiondosDatabase());
    }

    private static function getHomeModel()
    {
        return new HomeModel(self::getsabiondosDatabase());
    }

    private static  function getLobbyModel(){
        return new LobbyModel(self::getsabiondosDatabase());
    }
    private static  function getPartidaModel(){
        return new PartidaModel(self::getsabiondosDatabase());
    }

    private static function getUsuarioModel(){
        return new UsuarioModel(self::getsabiondosDatabase());
    }

    private static function getRankingModel(){
        return new RankingModel(self::getsabiondosDatabase());
    }
    private static function getReporteModel(){
        return new ReporteModel(self::getsabiondosDatabase());
    }
    private static function getCrearPreguntaModel(){
        return new CrearPreguntaModel(self::getsabiondosDatabase());
    }

    //HELPERS---------------------------------------------------------------------------------------------------------------------------
    public static function getsabiondosDatabase()
    {
        $config = self::getConfig();
        return new Database($config["servername"], $config["username"], $config["password"], $config["database"]);
    }
    private static function getConfig()
    {
        return parse_ini_file("config/config.ini",true);
    }

    public function getRouter()
    {
        return new Router(
            $this,
            "getHomeController",
            "home");
    }

    private static function getPresenter()
    {
        return new MustachePresenter("view/template");
    }
}
?>