<div class="container-fluid d-grid align-items-center" style="grid-template-columns: 1fr 5fr;">

    <div class="text-center">
        <a href="index.php">
            <img src="img/ico.png" alt="Domo-Sapiens" id="logo">
        </a>
    </div>

    <div>
        <div class="d-flex align-items-center">

            <div class="navbar navbar-light">
                <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample01" aria-controls="navbarsExample01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            
            <form class="w-100 me-3" role="search">
                <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
            </form>

            <div class="dropdown">
                <a href="#"class="d-flex align-items-center col-lg-4 mb-2 mb-lg-0 link-body-emphasis text-decoration-none dropdown-toggle"data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-fill" id="icono"></i>
                    <!--<i class="bi bi-person-fill-check" id="icono"></i>-->
                </a>
                <ul class="dropdown-menu text-small shadow" style="">
                    <li><a class="dropdown-item active" href="#" aria-current="page">Login</a></li>
                    <li><a class="dropdown-item" href="#">Bienvenido Mateo</a></li>
                    <li><a class="dropdown-item" href="#">Mis Datos</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Log-Out</a></li>
                </ul>
            </div>
            <div>
                <a class="d-flex align-items-center col-lg-4 mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
                    <i class="bi bi-cart-fill" id="icono"></i>
                    <!--<i class="bi bi-cart-check-fill id="icono""></i>-->
                </a>
            </div>

        </div>

        <div class="container-fluid">
            <div class="navbar-collapse collapse" id="navbarsExample01" style="">
            <ul class="navbar-nav me-auto mb-2">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Categorias</a>
                <a class="nav-link active" aria-current="page" href="#">Categorias</a>
                <a class="nav-link active" aria-current="page" href="#">Categorias</a>
                <a class="nav-link active" aria-current="page" href="#">Categorias</a>
                <a class="nav-link active" aria-current="page" href="#">Categorias</a>
                <a class="nav-link active" aria-current="page" href="#">Categorias</a>
                <a class="nav-link active" aria-current="page" href="#">Categorias</a>
                <a class="nav-link active" aria-current="page" href="#">Categorias</a>
                <a class="nav-link active" aria-current="page" href="#">Categorias</a>
                <a class="nav-link active" aria-current="page" href="#">Categorias</a>
                <a class="nav-link active" aria-current="page" href="#">Categorias</a>
                </li>
                <?php echo'<li class="nav-item"><a class="nav-link active" aria-current="page" href="#" >Esto sera generado con PHP</a></li>'?>
            </ul>
            </div>
        </div>
    </div>

</div>