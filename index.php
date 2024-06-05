<?php 
session_start(); 
// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="lang" content="es-ES">
    <meta name="author" content="Alvaro Mateo Polit Guartatanga">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Página de venta de productos de domótica.">
    <meta name="keywords" content="palabra clave 1, palabra clave 2, palabra clave 3">
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles/styles.css">
    <style>
      .carousel-item {
          height: auto;
          width: 100%;
      }
      .carousel-item img {
          max-height: 100%;
          max-width: 100%;
      }
      @media (max-width: 576px) {
        .carousel-item {
          height: auto;
        }
        .carousel-item img {
          width: 100%;
          height: auto;
        }
      }
    </style>

    <title>Bienvenido!</title>
</head>
<body>
    <header class="py-3 mb-3 border-bottom">
        <?php include_once 'partials/header.php';?>
    </header>

    <main>
      <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-body-tertiary">
          <div class="col-md-12 p-lg-3 mx-auto">
            <h1 class="display-3 fw-bold">Donde nos preocupamos por tu hogar</h1>
            <h3 class="fw-normal text-muted mb-3">¡Te damos la bienvenida a Domosapiens!</h3>
          </div>
      </div>
      <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-body-tertiary">
          <div class="col-md-12 mx-auto">
            <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
              <div class="carousel-inner">

                <div class="carousel-item active" data-bs-interval="3500">
                <a href="result.php?categoria=1"><img class="imagen" src="img/Recursos/Control de iluminacion.png"></a>
                </div>

                <div class="carousel-item" data-bs-interval="3500">
                <a href="result.php?categoria=2"><img class="imagen" src="img/Recursos/Control de temperatura.png"></a>
                </div>

                <div class="carousel-item" data-bs-interval="3500">
                <a href="result.php?categoria=3"><img class="imagen" src="img/Recursos/Seguridad.png"></a>
                </div>

                <div class="carousel-item" data-bs-interval="3500">
                <a href="result.php?categoria=4"><img class="imagen" src="img/Recursos/Control de acceso.png"></a>
                </div>

                <div class="carousel-item" data-bs-interval="3500">
                <a href="result.php?categoria=5"><img class="imagen" src="img/Recursos/Automatización de persianas y cortinas.png"></a>
                </div>

                <div class="carousel-item" data-bs-interval="3500">
                <a href="result.php?categoria=6"><img class="imagen" src="img/Recursos/Control de electrodomesticos.jpg"></a>
                </div>
                
                <div class="carousel-item" data-bs-interval="3500">
                <a href="result.php?categoria=7"><img class="imagen" src="img/Recursos/Gestión de energía.png"></a>
                </div>

                <div class="carousel-item" data-bs-interval="3500">
                <a href="result.php?categoria=8"><img class="imagen" src="img/Recursos/Entretenimiento.png"></a>
                </div>

                <div class="carousel-item" data-bs-interval="3500">
                <a href="result.php?categoria=9"><img class="imagen" src="img/Recursos/Riego y jardinería.png"></a>
                </div>

                <div class="carousel-item" data-bs-interval="3500">
                  <a href="result.php?categoria=10"><img class="imagen" src="img/Recursos/Salud y bienestar.png"></a>
                </div>

              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>
      </div>
      <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-body-tertiary">
          <div class="col-md-12 p-lg-3 mx-auto">
            <h1 class="display-3 fw-bold">Imagen de "mira nuestros productos en oferta"</h1>
          </div>
      </div>
      <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-body-tertiary">
          <div class="col-md-12 p-lg-3 mx-auto">
            <h1 class="display-3 fw-bold"> Cards de Productos en oferta</h1>
          </div>
      </div>
      <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-body-tertiary">
          <div class="col-md-12 p-lg-3 mx-auto">
            <h1 class="display-3 fw-bold">Imagen de "Productos mas vendidos en estos momentos"</h1>
          </div>
      </div>
    </main>

    <footer class="footer mt-auto">
        <?php include_once 'partials/footer.php';?>
    </footer>

    <script src="scripts/bootstrap.bundle.min.js"></script>
</body>
</html>
