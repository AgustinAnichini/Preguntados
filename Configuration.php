<?php

include_once ("controller/RegistroController.php");
include_once ("controller/HomeController.php");

include_once ("model/RegistroModel.php");
include_once ("model/HomeModel.php");

include_once ("helper/Router.php");

Class Configuration{

    //CONTROLLERS----------------------------------------------------------------------------------------------------------------------
    public static function getRegistroController()
    {
        return new RegistroController(self::getRegistroModel() ,self::getPresenter()); // no se si debe recibir un presenter
    }
    public static function getHomeController()
    {
        return new HomeController(self::getHomeModel() , self:: getPresenter());
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