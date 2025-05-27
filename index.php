<?php   
    session_start();
    if(isset($_SESSION['user_id'])) {
        header(header: "Location:./home.php");
        exit();
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
        <h1>Iniciar sesión</h1>
        <p>¿Es tu primera vez?<a href="register.php">Registrarse</a></p>
        <form action="./auth/validar.php" method="post" enctype="application/x-www-form-urlencoded">
            <input type="email" name="email_usuario" id="email_usuario" placeholder="Correo Electrónico" required><br>
            <input type="password" name="password_usuario" id="password_usuario" placeholder="Contreseña" required><br>
            <a href="olvidaste_password.php">¿Olvidó la contraseña?</a>
            <hr>
            <input type="submit" value="Iniciar Sesión" name="Enviar">
            <?php
            if(isset($_GET['errAuth'])){
                switch($_GET['errAuth']){
                    case 0:
                        echo "<div class='alert alert-danger'>Contraseña incorrecta</div>";
                        break;
                    case 1:
                        echo "<div class='alert alert-danger'>Usuario no verificado</div>";
                        break;
                    case 2:
                        echo "<div class='alert alert-danger'>El correo electrónico que ingresaste no está conectado a una cuenta.</div>";
                        break;  
                    case 3: 
                        echo "<div class='alert alert-danger'>Rellenar los campos solicitados</div>";
                        break;

                }
            }
            ?>
        </form>
    </article>
    <footer>reglas blabla</footer>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
<!--echo "<div class='alert alert-danger'>Rellenar los campos</div>";-->