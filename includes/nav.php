<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="home.php"><img src="./assets/images/fonts/logo_LCB_LETRAS.png" alt="Logo LCB" class="logo-pic"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="home.php">Inicio <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="sobre_nosotros.php">Sobre nosotros</a>
            </li>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </ul>

        <a class="nav-link" href="sobre_nosotros.php">Notificaciones</a>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="<?php $ruta_defecto = './public/uploads/profile_pics/profile-default.png';
                                echo mostrar_foto_perfil(user_id: $_SESSION['user_id'], imagen_defecto: $ruta_defecto) ?>"
                        alt="Foto de perfil" class="profile-pic me-2">&nbsp
                    <?php nombre_usuario(); ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="mi_perfil.php">Mi Perfil</a>
                    <a class="dropdown-item" href="editar_perfil.php">Editar perfil</a>
                    <a class="dropdown-item" href="cambiar_password.php">Cambiar Contraseña</a>
                    <a class="dropdown-item" href="eliminar_cuenta.php">Eliminar Cuenta</a>
                    <a class="dropdown-item" href="https://linktr.ee/sergioromero2k">Sobre el creador</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="./auth/logout.php">Cerrar sesión</a>
                </div>
            </li>
        </ul>


    </div>
</nav>