<?php
session_start();
include_once ("Configuration.php");
//$router = Configuration::getRouter();
$configuration = new Configuration();
$router = $configuration->getRouter();


$controller = isset($_GET["controller"]) ? $_GET["controller"]: "";
$action = isset($_GET["action"]) ? $_GET["action"]: "";

$router->route($controller,$action);
?>


