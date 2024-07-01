<?php
// Incluir el cargador automático de Composer
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Cargar variables de entorno desde el archivo prueba.env
$dotenv = Dotenv::createImmutable(__DIR__, 'prueba.env');
$dotenv->load();

// Crear una nueva instancia de PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor de correo
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USERNAME'];
    $mail->Password = $_ENV['SMTP_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $_ENV['SMTP_PORT'];

    // Destinatario y remitente
    $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
    $mail->addAddress($_ENV['SMTP_TO_EMAIL']);

    // Generar un código de autenticación de 6 dígitos
    $authCode = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'IMPORTANTE!!';
    $mail->Body    = "Tu código de autenticación es: <b>$authCode</b>";
    $mail->AltBody = "Tu código de autenticación es: $authCode";

    // Enviar el correo
    $mail->send();
    echo 'El mensaje ha sido enviado con éxito.\n';
    echo "Ingrese el número de verificación: ";
    $numero = readline();

    if ($numero == $authCode) {
        echo "Autenticación válida con éxito.\n";
    } else {
        echo "Número de autenticación inválido. Por favor, vuelva a intentarlo.\n";
    }

} catch (Exception $e) {
    echo "No se pudo enviar el mensaje. Mailer Error: {$mail->ErrorInfo}";
}
?>

