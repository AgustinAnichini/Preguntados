<?php
include_once ("third-party/PHPMailer-master/src/PHPMailer.php");
include_once ("third-party/PHPMailer-master/src/SMTP.php");
include_once ("third-party/PHPMailer-master/src/Exception.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class RegistroController
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
        $this->presenter->render("Registro", []);
    }

    public function registrar()
    {  // todo esto hay que llevarlo al servicio

      if(  isset($_POST["nombreCompleto"],$_POST["AnioNacimiento"],$_POST["sexo"],
          $_POST["pais"],$_POST["ciudad"],$_POST["email"],$_POST["password"],$_POST["confirmPassword"],$_POST["username"])){
          $formData = $_POST;

          if (isset($_POST["fotoPerfil"]) && isset($_FILES["fotoPerfil"]['name'])){
              $formData['fotoPerfil']['name'] =  $_FILES['foto_perfil']['name'];
          }
          $email = $_POST["email"];
          $password = $_POST['password'];

          // Crear el hash MD5 de la contraseña
          $passwordHash = md5($password);
          $formData["passwordHash"] = $passwordHash;

          $this->presenter->render("login", []);
          $mail = new PHPMailer(true);

          try {
                  // Configuración del servidor de correo
              $mail->isSMTP();
              $mail->Host = 'smtp.gmail.com'; // Host del servidor SMTP
              $mail->SMTPAuth = true;
              $mail->Username = 'agustinanichini277@gmail.com'; // Tu correo
              $mail->Password = 'okmmaeerwqyghuxs'; // Tu contraseña de correo
              $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
              $mail->Port = 587;

              // Remitente y destinatario
              $mail->setFrom('aanichini@alumno.unlam.edu.ar', 'Agustin Anichini');
              $mail->addAddress($email); // Añadir destinatario

              // Contenido del correo
              $mail->isHTML(true);
              $mail->Subject = 'Si no sale me MATO';
              $mail->Body    = 'El hash MD5 de tu contraseña es: ' . $passwordHash. "<br>".
                  'Tu email es ' . $formData["email"]."<br>".
                  'Tu nombre de usuario es ' . $formData["username"];
              $mail->send();
          } catch (Exception $e) {
              echo "El mensaje no pudo ser enviado.".'<br>'." Error de PHPMailer: {$mail->ErrorInfo}";
          }
      }
    }
}