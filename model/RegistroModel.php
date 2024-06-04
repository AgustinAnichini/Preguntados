<?php
include_once ("third-party/PHPMailer-master/src/PHPMailer.php");
include_once ("third-party/PHPMailer-master/src/SMTP.php");
include_once ("third-party/PHPMailer-master/src/Exception.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
class RegistroModel
{
    private $database;
    public function __construct($database)
    {
        $this->database = $database;
    }
    public function crearUsuario($formData)
    {
        $nombreComleto = $formData["nombreCompleto"];
        $fechaNac = $formData["fechaNacimiento"];
        $sexo = $formData["sexo"];
        $pais = $formData["pais"];
        $ciudad = $formData["ciudad"];
        $email = $formData["email"];
        $password = $formData["password"];
        $username= $formData["username"];
        $tokenValidacion = $formData["passwordHash"];
        $fechaRegistro = date('Y-m-d H:i:s');

        $this->database->execute(" INSERT INTO usuarios (
                                        nombre_completo,
                                        fecha_nacimiento,
                                        sexo,
                                        contrasenia,
                                        token_validacion,
                                        mail,
                                        pais,
                                        ciudad,
                                        foto_perfil,
                                        nombre_usuario,
                                        fecha_registro,
                                        cuenta_activa,
                                        ranking,
                                        puntajeMasAlto
                                    ) VALUES (
                                        '$nombreComleto',
                                        '$fechaNac',
                                        '$sexo',
                                        '$password',
                                        '$tokenValidacion',
                                        '$email',
                                        '$pais',
                                        '$ciudad',
                                        'null',
                                        '$username',
                                        '$fechaRegistro',
                                        false,
                                        0,
                                        0);"
        );
    }
    public function sendEmail ($formData)
    {
        // Crear el hash MD5 de la contraseña
        $passwordHash = md5($formData["password"]);
        $formData["passwordHash"] = $passwordHash;
        $this->crearUsuario($formData);

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
            $mail->setFrom('agustinanichini277@gmail.com', 'Agustin Anichini');
            $mail->addAddress($formData["email"]); // Añadir destinatario

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Si no sale me MATO';
            $mail->Body    = 'El hash MD5 de tu contraseña es: ' . $passwordHash. "<br>".
                'Tu email es ' . $formData["email"]."<br>".
                'Tu nombre de usuario es ' . $formData["username"] .
                'haz click <a href="http://localhost:8080/home/validarHash?hash=' . $passwordHash . '">aquí</a> para ir al home de nuestra aplicación.';
            $mail->send();
        } catch (Exception $e) {
            echo "El mensaje no pudo ser enviado.'.  'Error de PHPMailer:' . {$mail->ErrorInfo}";
        }
    }
}

