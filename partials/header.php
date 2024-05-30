<div class="container-fluid d-grid align-items-center" style="grid-template-columns: 1fr 5fr;">

    <div class="text-center">
        <a href="index.php">
            <img src="img/ico.png" alt="Domo-Sapiens" id="logo">
        </a>
    </div>

    <div>
        <div class="d-flex align-items-center">

            <div class="navbar navbar-light m-1">
                <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample01" aria-controls="navbarsExample01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            
            <form class="w-100 m-1" role="search" action="result.php" method="get">
                <input type="search" class="form-control" name="keyword" placeholder="Search..." aria-label="Search">
            </form>


            <?php        
                if (isset($_SESSION['id'])) {

                echo' <div class="dropdown m-1">
                        <a href="#"class="d-flex align-items-center col-lg-4 mb-2 mb-lg-0 link-body-emphasis text-decoration-none dropdown-toggle"data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-fill-check" id="icono"></i>
                        </a>
                        <ul class="dropdown-menu text-small shadow" style="">
                            <li><p class="dropdown-item active">Bienvenido/a '.$_SESSION['nombre'].'</p></li>
                            <li><a class="dropdown-item" href="my_data.php">Mis Datos</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Log-Out</a></li>
                        </ul>
                    </div>';

                } else {
                echo'
                    <div class="dropdown m-1">
                        <a href="#"class="d-flex align-items-center col-lg-4 mb-2 mb-lg-0 link-body-emphasis text-decoration-none dropdown-toggle"data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-fill" id="icono"></i>
                        </a>
                        <ul class="dropdown-menu text-small shadow" style="">
                            <li><a class="dropdown-item " href="login.php" aria-current="page">Iniciar Sesion</a></li>
                            <li><a class="dropdown-item " href="create_acc.php" aria-current="page">Crear Cuenta</a></li>
                        </ul>
                    </div>
                    ';

                } 
            ?>
            <div class="m-1">
                <a class="d-flex align-items-center col-lg-4 mb-2 mb-lg-0 link-body-emphasis text-decoration-none" href="cart.php">
                    <i class="bi bi-cart-fill" id="icono"></i>
                    <!--<i class="bi bi-cart-check-fill id="icono""></i>-->
                </a>
            </div>

        </div>

        <div class="container-fluid">
            <div class="navbar-collapse collapse" id="navbarsExample01" style="">
                <?php require_once('includes/Search.php'); ?>
                <?php $search = new Search(); ?>
                <?php echo $search->show_categories(); ?>
            </div>
        </div>
    </div>

</div>