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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/sobre_nosotros.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>

<body>
    <?php
    require_once './includes/nav.php'; // Incluye el encabezado
    ?>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="./assets/images/fonts/estudiantesSombrero.png" alt="First slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="./assets/images/fonts/logo_LCB_fondo.png" alt="Second slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="./assets/images/fonts/lcb_frase.png" alt="Third slide">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <div class="section-admin">
        <div class="container-admin">
            <div class="row">
                <div class="col-md-12 texto-plus">
                    <p class="texto-admin1">Confesiones LCB: La cara "B" de la UNI</p>
                    <br>
                    <p class="texto-admin2">
                        En LCB UNI, creemos que cada voz cuenta y que la comunicación abierta es fundamental para crear una comunidad universitaria más fuerte y unida. Nuestra plataforma digital ha sido diseñada con el objetivo de ofrecer un espacio seguro y accesible donde estudiantes universitarios y personas interesadas puedan compartir sus experiencias, inquietudes y conocimientos sobre la vida universitaria.
                    </p>
                    <p class="texto-admin1">Por qué creamos LCB UNI?</p>
                    <br>
                    <p class="texto-admin2">
                        Creamos LCB UNI porque no existe una aplicación web dedicada donde los estudiantes puedan expresarse libremente. Esto para poder fomentar la comunicación entre los estudiantes y ofrecer un espacio donde se pueda interactuar libremente, compartir experiencia, inquietudes y dudas sobre la universidad, sabiendo que durante un cierto tiempo aquellas publicaciones serán eliminadas.</p>

                </div>
            </div>
        </div>
    </div>
    <div class="section-empresa">
        <div class="container-empresa">
            <h3 style="text-align: center; color: #333;">Nuestra Misión</h3>
            <p style="text-align: center; color: #555;">Nuestra misión es fomentar la participación activa de los usuarios a través de</p>
            <hr>
            <div class="row">
                <div class="col-md-4 texto-plus">
                    <div class="cuadro">
                        
                        <p class="texto-empresa1">Publicaciones de Interés</p>
                        <p class="texto-empresa2">
                            Compartir noticias, eventos y temas relevantes que impacten a la comunidad universitaria.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 texto-plus">
                    <div class="cuadro">
                        <p class="texto-empresa1">Discusión Académica y Social</p>
                        <p class="texto-empresa2">
                            Facilitar el intercambio de ideas y opiniones sobre cuestiones académicas y sociales que afectan a los estudiantes.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 texto-plus">
                    <div class="cuadro">
                        <p class="texto-empresa1">Confesiones Amorosas</p>
                        <p class="texto-empresa2">
                            Un espacio donde los usuarios puedan expresar sus sentimientos y experiencias amorosas de manera anónima y respetuosa.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>

</html>