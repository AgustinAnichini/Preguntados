<?php

include_once ("controller/RegistroController.php");
include_once ("controller/HomeController.php");
include_once ("controller/PartidaController.php");
include_once ("controller/LobbyController.php");


include_once ("model/RegistroModel.php");
include_once ("model/HomeModel.php");
include_once ("model/PartidaModel.php");
include_once ("model/LobbyModel.php");


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
        return new HomeController(self::getHomeModel() , self:: getPresenter());
    }
    public static function getLobbyController(){
        return new LobbyController(self::getLobbyModel(),self::getPresenter());
    }
    public static function getPartidaController(){
        return new PartidaController(self::getPartidaModel(),self::getPresenter());
    }

    //MODELS---------------------------------------------------------------------------------------------------------------------------
    private static function getRegistroModel()
    {
        return new RegistroModel(self::getsabiondosDatabase());
    }

    private static function getHomeModel()
    {
        return new HomeModel(self::getsabiondosDatabase());
    }

    private static  function getLobbyModel(){
        return new LobbyModel(self::getsabiondosDatabase());
    }
    private static  function getPartidaModel(){
        return new LobbyModel(self::getsabiondosDatabase());
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