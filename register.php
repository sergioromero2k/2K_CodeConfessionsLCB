<?php
require_once './includes/config.php';



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
            <input type="radio" name="genero" class="genero" value="Mujer">Mujer
            <input type="radio" name="genero" class="genero" value="Hombre">Hombre
            <input type="radio" name="genero" class="genero" value="Otro">Otro
            <br>
            <label for="universidad">Universidad o Instituto</label><br>
            <select name="universidad" id="universidad">
                <?php
                $tabla_universidades = $conexion_bbdd->query("SELECT * FROM universidades");
                while ($fila = $tabla_universidades->fetch_assoc()) {
                    echo "<option value='$fila[universidad]'>$fila[universidad]</option>";
                }
                ?>
            </select><br>
            <input type="email" name="email" id="email" placeholder="Correo Electrónico"><br>
            <input type="password" name="contrase" id="contrase" placeholder="Contraseña Nueva"><br>
            <input type="submit" value="Registrarse" name="registrarse"><br>
            <a href="./index.php">¿Ya tienes una cuenta?</a>
        </form>
    </article>
    <footer>reglas blabla</footer>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>