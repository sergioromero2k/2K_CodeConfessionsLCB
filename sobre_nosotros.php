<?php
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once './includes/config.php'; // Configuración de la base de datos  
require_once './includes/functions.php'; // Funciones auxiliares
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina incial LCB</title>
    <meta name="author" content="Sergio Alejandro Romero López">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <nav>
        <div class="flex-container-nav ">
            <div class="flex-container-nav-right flex-container-border">
                <div><a href="home.php">LOGO LCB</a></div>
                <div><i class="fa-solid fa-user"></i>
                    <?php
                    nombre_usuario();
                    ?>
                </div>
            </div>
        </div>
    </nav>
    <div>
        <h1>Sobre nosotros</h1>
    </div>
    <section>
        <div>
            <div>
                <h3>Nuestra Misión</h3>
            </div>
            <div>Nuestra misión es fomentar la participación activa de los usuarios a traves de</div>
            <div>
                <div>
                    <b>Publicaciones de Interés</b>
                    Compartir noticias, eventos y temas relevantes que impacten a la comunidad universitaria.
                </div>
                <div>
                    <b>Discusión Académica y Social</b>
                    Facilitar el intercambio de ideas y opiniones sobre cuestiones académicas y sociales que afectan a los estudiantes.
                </div>
                <div>
                    <b>Confesiones Amorosas</b>
                    Un espacio donde los usuarios puedan expresar sus sentimientos y experiencias amorosas de manera anónima y respetuosa.
                </div>
            </div>
        </div>
        <div>
            <div>En LCB UNI, creemos que cada voz cuenta y que la comunicación abierta es fundamental para crear una comunidad universitaria más fuerte y unida. Nuestra plataforma digital ha sido diseñada con el objetivo de ofrecer un espacio seguro y accesible donde estudiantes universitarios y personas interesadas puedan compartir sus experiencias, inquietudes y conocimientos sobre la vida universitaria.</div>
            <div><img src="" alt="Logo LCB"></div>
        </div>
    </section>

    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>

</html>