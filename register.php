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
                $insertar = $conexion_bbdd->prepare(query: "INSERT INTO usuarios (email, password, nombre, apellido, fecha_nacimiento, token_activacion,genero_id,universidad_id) VALUES (?, ?, ?, ?, ?, ?, ?,?)");
                $insertar->bind_param("ssssssii", $_POST['email'], $password_hash, $_POST['nombres'], $_POST['apellidos'], $_POST['fecha_nacimiento'], $token_activacion, $_POST['genero'], $_POST['universidad']);
                $insertar->execute();
                $insertar->close();
                header(header: "Location: index.php");
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
                                    <p>Para activar tu cuenta, por favor haz clic en el siguiente enlace: <a href="http://localhost/CodeConfessionsLCB/activar_cuenta.php?token=' . $token_activacion . '">Activar cuenta</a></p>
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
    // Manejo de errores
    switch ($t->getCode()) {
        case 1062: // Error de clave duplicada
            header(header: "Location: register.php?errCrear=1");
            exit();
        case 1048: // Error de campo no nulo
            header(header: "Location: register.php?errCrear=2");
            exit();
        default:
            if ($t->getMessage()) {
                header(header: "Location: register.php?errCrear=3");
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
</head>

<body>
    <header>Anuncio</header>
    <article>

    </article>
    <nav>informacion sobre LCB</nav>

    <article style="border: 2px solid black;text-align:center">
        <h1>Crea una cuenta</h1>
        <p>Es tu rápido y sencillo</p>
        <form action="register.php" method="post" enctype="application/x-www-form-urlencoded">
            <input type="text" name="nombres" id="nombres" placeholder="Nombres" required>
            <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos"><br>
            <label for="fecha_nacimiento">Fecha Nacimiento</label><br>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"><br>
            <label for="genero">Género</label><br>
            <?php
            $tabla_generos = $conexion_bbdd->query("SELECT * FROM generos");
            while ($fila = $tabla_generos->fetch_assoc()) {
                echo "<input type='radio' name='genero' class='genero' value='$fila[genero_id]'>$fila[genero]</input>";
            }
            ?>
            <br>
            <label for="universidad">Universidad o Instituto</label><br>
            <select name="universidad" id="universidad">
                <?php
                // Función para mostrar las universidades
                universidades();
                ?>
            </select><br>
            <input type="email" name="email" id="email" placeholder="Correo Electrónico"><br>
            <input type="password" name="contrase" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" title="Debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo." id="password_usuario" placeholder="Contreseña" id="contrase" placeholder="Contraseña Nueva"><br>
            <input type="submit" value="Registrarse" name="registrarse"><br>
            <a href="./index.php">¿Ya tienes una cuenta?</a>
        </form>
        <?php
        if (isset($_GET['errCrear'])) {
            switch ($_GET['errCrear']) {
                case 0:
                    echo "<div class='alert alert-danger'>La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.</div>";
                    break;
                case 1:
                    echo "<div class='alert alert-danger'>El correo electrónico ya está registrado.</div>";
                    break;
                case 2:
                    echo "<div class='alert alert-danger'>Por favor, rellena todos los campos requeridos.</div>";
                    break;
                case 3:
                    echo "<div class='alert alert-danger'>Error, inténtelo más tarde.</div>";
                    break;
                case 4:
                    echo "<div class='alert alert-danger'>Formato de email inválido.</div>";
                    break;
            }
        }
        ?>
    </article>
    <footer>reglas blabla</footer>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>