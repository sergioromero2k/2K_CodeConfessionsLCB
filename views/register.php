<?php
require_once '../includes/config.php'; // Configuración de la base de datos
require_once '../includes/functions.php'; // Funciones auxiliares

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

# Como no usa composer necesitamos importar las clases de PHPMailer manualmente
require '../PHPMailer/Exception.php';
require '../PHPMailer//PHPMailer.php';
require '../PHPMailer/SMTP.php';

try {
    # Prevenir inyecciones SQL
    if (isset($_POST['registrarse'])) {
        $contraseña = $_POST['contrase'];
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/'; // Expresión regular para validar la contraseña
        if (!preg_match(pattern: $pattern, subject: $contraseña)) {
            header(header: "Location:register.php?errCrear=0");
            exit();
        } else {
            # Configuración global
            $contraseña = $conexion_bbdd->real_escape_string($_POST['contrase']);
            $password_hash = password_hash($contraseña, PASSWORD_ARGON2ID);
            $token_activacion = bin2hex(random_bytes(length: 32));
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                header(header: "Location:register.php?errCrear=4");
                exit();
            } else {
                $insertar = $conexion_bbdd->prepare(query: "INSERT INTO usuarios (email, password, nombre, apellido, fecha_nacimiento, profile_image,token_activacion,genero_id,universidad_id) VALUES (?, ?, ?, ?, ?,?, ?, ?,?)");
                $profile_image = 'profile-default.png'; // Imagen de perfil por defecto
                $insertar->bind_param("sssssssii", $_POST['email'], $password_hash, $_POST['nombres'], $_POST['apellidos'], $_POST['fecha_nacimiento'], $profile_image, $token_activacion, $_POST['genero'], $_POST['universidad']);
                $insertar->execute();
                $insertar->close();
                header(header: "Location: ../index.php");
                $conexion_bbdd->close();
                $email_enviar = $_POST['email'];
                $nombre_usuario = $_POST['nombres'];
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

                    $mail->Subject = 'Activa tu cuenta en Confensiones LCB';

                    $mail->Body = '
                                    <h2>¡Bienvenido ' . $nombre_usuario . '!</h2>
                                    <p>Gracias por registrarte. Estas a un paso de ser parte de nosotros!!</p>
                                    <p>Para activar tu cuenta, por favor haz clic en el siguiente enlace: <a href="http://localhost/CodeConfessionsLCB/controllers/activar_cuenta.php?token=' . $token_activacion . '">Activar cuenta</a></p>
                                    <p>Si no fuiste tú quien creó esta cuenta, puedes ignorar este mensaje.</p>
                                    <p>Salu2<br>El equipo de LCB</p>';
                    $mail->send();
                } catch (Exception $e) {
                    echo "No se ha podido enviar el mensaje. Error del remitente: {$mail->ErrorInfo}";
                }
            }
        }
    }
} catch (Throwable $t) {
    session_start();
    // Manejo de errores
    switch ($t->getCode()) {
        case 1062: // Error de clave duplicada
            $_SESSION['errCrear'] = 1; // Correo electrónico ya registrado
            header(header: "Location: register.php?");
            exit();
        case 1048: // Error de campo no nulo
            $_SESSION['errCrear'] = 2; // Campos requeridos no completados
            header(header: "Location: register.php?");
            exit();
        default:
            if ($t->getMessage()) {
                $_SESSION['errCrear'] = 3; // Error genérico
                header(header: "Location: register.php?");
                exit();
            }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina incial LCB</title>
    <meta name="author" content="Sergio Alejandro Romero López">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/register.css">
</head>

<body>
    <article class="h-100 d-flex justify-content-center align-items-center article-caja">
        <div>
            <img src="../assets/images/fonts/logo_LCB.png" alt="logo_LCB" class="img-fluid">
        </div>
        <div class="card-form">
            <form action="register.php" method="post" enctype="application/x-www-form-urlencoded">
                <h1>Crea una cuenta</h1>
                <p>Es rápido y sencillo</p>
                <hr>
                <div class="form-row" style="display: flex;justify-content: flex-start;">
                    <div class="form-group col-md-6" style="margin: 0.2rem;">
                        <input type="text" class="form-control" name="nombres" id="nombres" placeholder="Nombres" required>
                    </div>
                    <div class="form-group col-md-6" style="margin: 0.2rem;">
                        <input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Apellidos" required>
                    </div>
                </div>
                <div class="form-group col-md-9" style="text-align: left;">
                    <label for="fecha_nacimiento">Fecha Nacimiento</label><br>
                    <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" required>
                </div>
                <div class="custom-control custom-radio custom-control-inline" style="text-align: left;">
                    <label for="genero">Género</label><br>
                    <?php
                    $tabla_generos = $conexion_bbdd->query("SELECT * FROM generos");
                    while ($fila = $tabla_generos->fetch_assoc()) {
                        echo "&nbsp&nbsp<input type='radio'  class='custom-control-input' name='genero' class='genero' value='$fila[genero_id]' required>$fila[genero]</input>&nbsp&nbsp";
                    }
                    ?>
                </div>
                <div class="form-group" style="text-align: left;">
                    <label for="universidad">Universidad o Instituto</label>
                    <select name="universidad" id="universidad" class="custom-select custom-select-lg mb-3" required>
                        <?php
                        // Función para mostrar las universidades
                        universidades();
                        ?>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-9">
                        <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Correo Electrónico" required>
                    </div>
                    <div class="form-group col-md-9">
                        <input type="password" class="form-control" name="contrase" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" title="Debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo." id="password_usuario" placeholder="Contraseña" id="contrase" placeholder="Contraseña Nueva" required>
                    </div>
                </div>
                <input type="submit" value="Registrarse" name="registrarse" class="btn btn-success" name="Enviar">
                <input type="reset" value="Limpiar" class="btn btn-secondary">
                <hr>
                <a href="../index.php">¿Ya tienes una cuenta?</a> <br>
                <?php
                if (isset($_SESSION['errCrear'])) {
                    switch ($_SESSION['errCrear']) {
                        case 0:
                            echo "<br><div class='alert alert-danger'>La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.</div>";
                            break;
                        case 1:
                            echo "<br><div class='alert alert-danger'>El correo electrónico ya está registrado.</div>";
                            break;
                        case 2:
                            echo "<br><div class='alert alert-danger'>Por favor, rellena todos los campos requeridos.</div>";
                            break;
                        case 3:
                            echo "<br><div class='alert alert-danger'>Error, inténtelo más tarde.</div>";
                            break;
                        case 4:
                            echo "<br><div class='alert alert-danger'>Formato de email inválido.</div>";
                            break;
                    }
                    unset($_SESSION['errCrear']); // Limpiar la variable de sesión después de mostrar el mensaje
                }
                ?>
            </form>
        </div>
    </article>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>