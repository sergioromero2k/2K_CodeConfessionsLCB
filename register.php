<?php
require_once './includes/config.php';
require_once './includes/functions.php'; // Funciones auxiliares
try {
    # Prevenir inyecciones SQL
    if (isset($_POST['registrarse'])) {
        // # Configuración global
        $contraseña = $conexion_bbdd->real_escape_string($_POST['contrase']);
        $password_hash = password_hash($contraseña, PASSWORD_ARGON2ID);
        $token_activacion = bin2hex(random_bytes(length: 32));
        $insertar = $conexion_bbdd->prepare(query: "INSERT INTO usuarios (email, password, nombre, apellido, fecha_nacimiento, token_activacion,genero_id,universidad_id) VALUES (?, ?, ?, ?, ?, ?, ?,?)");
        $insertar->bind_param("ssssssii", $_POST['email'], $password_hash, $_POST['nombres'], $_POST['apellidos'], $_POST['fecha_nacimiento'], $token_activacion, $_POST['genero'], $_POST['universidad']);
        $insertar->execute();
        $insertar->close();
        header(header: "Location: index.php");
        $conexion_bbdd->close();
    }
} catch (Throwable $t) {
    echo 'Error grave: ' . $t->getMessage();
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
            while($fila = $tabla_generos->fetch_assoc()) {
                echo "<input type='radio' name='genero' class='genero' value='$fila[genero_id]'>$fila[genero]</input>";
            }
            ?>
            <br>
            <label for="universidad">Universidad o Instituto</label><br>
            <select name="universidad" id="universidad">
                <?php
                universidades();
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