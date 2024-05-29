<?php 
    session_start();
    if ($_SERVER["REQUEST_METHOD"] !== "GET" || !isset($_GET['keyword'])) {
        header('Location: index.php');
        exit();
    } 
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
        .filter-section {
            background-color: #f8f9fa;
            padding: 1rem;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }
        @media (max-width: 768px) {
            .product-grid {
            display: grid;
            gap: 1rem;
            }
        }
        .product-card {
            background-color: #dee2e6;
            padding: 1rem;
            border: 1px solid #ced4da;
            text-align: center;
        }
    </style>
    <title>Bienvenido!</title>
</head>
<body>
    <header class="py-3 mb-3 border-bottom">
        <?php include_once 'partials/header.php'; include_once 'includes/Search.php'; ?>
    </header>

    <main class="container-fluid">
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['keyword'])) {
                $keyword = htmlspecialchars($_GET['keyword']); // Asegúrate de que el nombre coincida con el del formulario
                $search = new Search();

                if (empty($keyword)) {
                    // Si está vacío
        ?>
                    <div class='container'>
                        <h2 class="mb-3">No se proporcionó una palabra clave para la búsqueda.</h2>
                        <div class="row">
                            <div class="col-12">
                                <div class="container my-5">
                                    <div class="bg-body-tertiary p-5 rounded">
                                        <div class="col-sm-8 py-5 mx-auto text-center">
                                            <p class="fs-5">No se proporcionó una palabra clave para la búsqueda.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check text-center my-5">
                                    Quieres volver a la paguina principal cliquea <a href="index.php">aquí</a>!
                                </div>
                                <div class="form-check text-center my-5">
                                    ¿Tienes algún problema? Podemos ayudarte <a href="faq.php">aquí</a>!
                                </div>
                            </div>
                        </div>
                    </div>
        <?php
                } else {
                    // Si hay resultados
                    $results = $search->searcher($keyword);
                    if ($results) {
        ?>
                        <h2 class="mb-3">Resultados de "<?php echo $keyword ?>"</h2>
                        <div class="row">
                            <div class="col-md-3 filter-section">
                                <h5>Filtra al gusto</h5>
                                <button class="btn btn-secondary mb-2 w-100">Stock</button><!--boton para que filtre solo los que tengan stock-->
                                <h6>Categoría</h6>
                                <button class="btn btn-outline-secondary mb-2 w-100">Categoría 1</button><!--filtrar por categorias-->
                                <button class="btn btn-outline-secondary mb-2 w-100">Categoría 2</button>
                                <button class="btn btn-outline-secondary mb-2 w-100">Categoría 3</button>
                                <h6>Marca</h6>
                                <button class="btn btn-outline-secondary mb-2 w-100">Marca 1</button><!--filtrar por marcas-->
                                <button class="btn btn-outline-secondary mb-2 w-100">Marca 2</button>
                                <button class="btn btn-outline-secondary mb-2 w-100">Marca 3</button>
                            </div>
                            <div class="col-md-9">
                                <div class="d-flex justify-content-between mb-3">
                                    <button class="btn btn-outline-primary">Más vendido</button><!--filtrar por mas vendido-->
                                    <button class="btn btn-outline-primary">Precio más bajo</button><!--filtrar por precio mas bajo-->
                                    <button class="btn btn-outline-primary">Precio más alto</button><!--filtrar por precio mas alto-->
                                    <button class="btn btn-outline-primary">Orden alfabético</button><!--filtrar por orden alfabetico-->
                                </div>
                                <div class="product-grid">
        <?php 
                                    foreach ($results as $r) { $mini = new Search();$info = $search->show_mini_prod($r);
                                        echo '<a href="product.php?id_product='. $r .'">
                                                <div class="product-card">
                                                    '.$info.'
                                                </div>
                                             </a>'; } 
        ?>
                                </div>
                            </div>
                        </div>
        <?php
                    } else {
                        // Si no hay resultados con esa palabra
        ?>
                            <div class='container'>
                                <h2 class="mb-3">No se encontraron resultados de "<?php echo $keyword; ?>"</h2>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="container my-5">
                                            <div class="bg-body-tertiary p-5 rounded">
                                                <div class="col-sm-8 py-5 mx-auto text-center">
                                                    <p class="fs-5">No se encontraron resultados de "<?php echo $keyword; ?>".</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-check text-center my-5">
                                            Quieres volver a la paguina principal cliquea <a href="index.php">aquí</a>!
                                        </div>
                                        <div class="form-check text-center my-5">
                                            ¿Tienes algún problema? Podemos ayudarte <a href="faq.php">aquí</a>!
                                        </div>
                                    </div>
                                </div>
                            </div>
        <?php
                    }
                }
            }
        ?>
    </main>

    <footer class="footer mt-auto">
        <?php include_once 'partials/footer.php'; ?>
    </footer>

    <script src="scripts/bootstrap.bundle.min.js"></script>
</body>
</html>
