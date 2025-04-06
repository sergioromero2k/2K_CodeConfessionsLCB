<?php
if($_SERVER['REQUEST_METHOD'] == "POST"){
    echo "Hola";
}else{
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
    <article>Formularios Nuevo Usuario
        <form action="index.php" method="post" enctype="application/x-www-form-urlencoded">

            <input type="email" name="email_user" id="email_user" placeholder="Correo Electrónico"><br>
            <input type="password" name="password_user" id="password_user" placeholder="Contreseña"><br>
            <input type="submit" value="Enviar">

        </form>


    </article>
    <footer>reglas blabla</footer>
    
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
<?php    
}




?>
