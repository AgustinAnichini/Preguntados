<?php
include_once("./third-party/phpqrcode/qrlib.php");

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
        $rankingConQR= $this->generarQRporUsuario($rankingMundial);
        $posicion = 1;
        foreach ($rankingConQR as &$usuario) {
            $usuario['posicion'] = $posicion;
            $posicion++;
        }

       return $rankingConQR;
    }

    function traerUsuariosPorPuntajeRanking(){
        $ranking = $this->database->query("SELECT * FROM usuarios ORDER BY puntajeRanking DESC");
        return $ranking;
    }

    function generarQRporUsuario($rankingMundial){
        $usuarios = $rankingMundial;
        $rutaCarpetaQR = "./public/QR/";

        if (!file_exists($rutaCarpetaQR)) {
            mkdir($rutaCarpetaQR, 0777, true);
        }

        foreach ($usuarios as &$usuario) {
            $id = $usuario['id'];
            $nombreArchivoQR = "usuario_" . $id . ".png";
            $rutaCompletaQR = $rutaCarpetaQR . $nombreArchivoQR;
            $urlPerfil = "http://localhost:8080/Usuario/verPerfil?id=" . $id;

            // Genera el QR si no existe
            if (!file_exists($rutaCompletaQR)) {
                QRcode::png($urlPerfil, $rutaCompletaQR, QR_ECLEVEL_L, 5);
            }

            // Actualiza el usuario con la ruta del QR
            $usuario['qr_code_path'] = $nombreArchivoQR;
            $this->database->execute("UPDATE usuarios SET qr_code_path = '$nombreArchivoQR' WHERE id = $id");
        }

        return $usuarios; // Retorna usuarios con la ruta del QR generada
    }
}