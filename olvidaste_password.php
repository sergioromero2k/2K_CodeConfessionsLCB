<?php
require_once './includes/config.php';
require_once './includes/functions.php'; // Funciones auxiliares
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

# Como no usa composer necesitamos importar las clases de PHPMailer manualmente
require './PHPMailer/Exception.php';
require './PHPMailer//PHPMailer.php';
require './PHPMailer/SMTP.php';
//     $token_activacion = bin2hex(random_bytes(length: 32));

try {
    session_start();
    if (isset($_POST['continuar'])) {
        if (!filter_var($_POST['email_recuperacion'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['olvidar'] = 0; // Error de formato de email  
            header(header: "Location:olvidaste_password.php");
            exit();
        } else {
            $consulta_email = $conexion_bbdd->prepare(query: "SELECT * FROM usuarios WHERE email=? AND verificado=1");
            $consulta_email->bind_param("s", $_POST['email_recuperacion']);
            $consulta_email->execute();
            $resultado_email = $consulta_email->get_result();
            if ($resultado_email->num_rows == 0) {
                $_SESSION['olvidar'] = 1; // No existe el email
                header(header: "Location:olvidaste_password.php");
                exit();
            } else {
                $insertar_email = $conexion_bbdd->prepare(query: "INSERT INTO password_resets (email,token,created_at, used) VALUES (?, ?, NOW(), 0)");
                $token_activacion = bin2hex(random_bytes(length: 32));
                $insertar_email->bind_param("ss", $_POST['email_recuperacion'], $token_activacion);
                $insertar_email->execute();
                $insertar_email->close();
                $email_enviar = $_POST['email_recuperacion'];
                // Codigo sacado de https://github.com/PHPMailer/PHPMailer
                $mail = new PHPMailer(exceptions: true);
                try {
                    //Server settings
                    $mail->SMTPDebug = 0;                                       //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'mr.sergioromero2k@gmail.com';                     //SMTP username
                    $mail->Password   = 'cdttknsfsovenupu';                               //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                    //Recipients
                    $mail->setFrom('mr.sergioromero2k@gmail.com', 'Confesiones LCB');
                    $mail->addAddress($email_enviar);                     //Add a recipient
                    //Content
                    $mail->isHTML(true);  // Establecer formato HTML

                    $mail->Subject = 'Restablecimiento de Password - Confesiones LCB';

                    $mail->Body = '
                                        <h2>Hola!</h2>
                                        <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en Confesiones LCB. Si no has solicitado este cambio, puedes ignorar este correo.</p>
                                        <p>Para restablecer tu contraseña, por favor haz clic en el siguiente enlace: <a href="http://localhost/CodeConfessionsLCB/recuperar_cuenta.php?token=' . $token_activacion . '">Restablecer mi contraseña</a></p>
                                        <p>Este enlace es válido por 24 horas. Si no lo usas dentro de ese tiempo, tendrás que solicitar un nuevo restablecimiento.</p>
                                        <p>Gracias<br>El equipo de LCB</p>';
                    $mail->send();
                    $_SESSION['olvidar'] = 2; // Email enviado correctamente
                    header(header: "Location:olvidaste_password.php");
                    exit();
                } catch (Exception $e) {
                    session_start();
                    $_SESSION['olvidar'] = 3; // Error al enviar el correo electrónico
                    header(header: "Location:olvidaste_password.php");
                    exit();
                }
            }
        }
    }
} catch (Throwable $t) {
    // Manejo de errores
    session_start();
    // Registrar el error en un archivo de log o mostrar un mensaje de error
    $_SESSION['olvidar'] = 4; // Error genérico
    $error = $t->getMessage();
    header(header: "Location:olvidaste_password.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina recuperación Cuenta LCB</title>
    <meta name="author" content="Sergio Alejandro Romero López">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/olvidaste_pass.css">
</head>

<body>
    <article class="h-100 d-flex justify-content-center align-items-center article-caja">
        <div class="card-form">
            <form action="olvidaste_password.php" method="post" enctype="application/x-www-form-urlencoded">
                <h1>Recuperar tu cuenta</h1>
                <hr>
                <div class="form-group" style="text-align: left;">
                    <label for="email_recuperacion">Ingresa tu correo electrónico para buscar tu cuenta.</label>
                    <input type="email" class="form-control" name="email_recuperacion" id="email_recuperacion" aria-describedby="emailHelp" placeholder="Correo Electrónico" required>
                </div>
                <hr>
                <input type="button" value="Cancelar" class="btn btn-secondary" onclick="location.href='index.php'">
                <input type="submit" value="Continuar" class="btn btn-primary" name="continuar">
                <?php
                if (isset($_SESSION['olvidar'])) {
                    switch ($_SESSION['olvidar']) {
                        case 0:
                            echo "<br> <div class='alert alert-danger'>Error de formato de email</div>";
                            break;
                        case 1:
                            echo "<br> <div class='alert alert-danger'>El correo electrónico que ingresaste no está conectado a una cuenta o no está verificado.</div>";
                            break;
                        case 2:
                            echo "<br> <div class='alert alert-success'>Revisa tu bandeja de entrada.</div>";
                            break;
                        case 3:
                            echo "<br> <div class='alert alert-danger'>No se ha podido enviar el mensaje.</div>";
                            break;
                        case 4:
                            echo "<br> <div class='alert alert-danger'>Error al enviar el correo electrónico: " . $error . "</div>";
                            break;
                    }
                    unset($_SESSION['olvidar']); // Limpiar la variable de sesión
                }
                ?>
            </form>
        </div>
    </article>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>