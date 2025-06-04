<?php
function nav($ruta_home = "./home.php", $ruta_sobreNoso = "./sobre_nosotros.php", $ruta_buscar="./buscar.php",$ruta_notificaciones, $ruta_perfil, $editar_perfil, $cambiar_password, $eliminar_cuenta): void
{
?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="<?php echo $ruta_home ?>">
            <img src="../assets/images/fonts/logo_LCB_LETRAS.png" alt="Logo LCB" class="logo-pic">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo $ruta_home ?>"><i class="fa-solid fa-house"></i>&nbsp; Inicio <span
                            class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $ruta_sobreNoso ?>">Sobre nosotros</a>
                </li>
                <form class="form-inline my-2 my-lg-0" action="<?php echo $ruta_buscar ?>" method="POST">
                    <input class="form-control mr-sm-2" type="search" name="q" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </ul>

            <ul class="navbar-nav align-items-center">

                <!-- Notificaciones -->
                <a class="nav-link" href="<?php  echo $ruta_notificaciones ?>"> <i class="fa-solid fa-bell"></i>&nbsp; Notificaciones</a>
                <!-- Usuario -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="<?php echo mostrar_foto_perfil(user_id: $_SESSION['user_id'], ruta_imagen: '../public/uploads/profile_pics/', imagen_defecto: 'profile-default.png') ?>"
                            alt="Foto de perfil" class="profile-pic me-2">
                        &nbsp;<?php nombre_usuario(); ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo $ruta_perfil ?>">Mi Perfil</a>
                        <a class="dropdown-item" href="<?php echo $editar_perfil ?>">Editar perfil</a>
                        <a class="dropdown-item" href="<?php echo $cambiar_password ?>">Cambiar Contraseña</a>
                        <a class="dropdown-item" href="<?php echo $eliminar_cuenta ?>">Eliminar Cuenta</a>
                        <a class="dropdown-item" href="https://linktr.ee/sergioromero2k">Sobre el creador</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../auth/logout.php">Cerrar sesión</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
<?php
}
