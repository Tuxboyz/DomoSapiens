<?php 
    session_start();
    if ($_SERVER["REQUEST_METHOD"] !== "GET" || (!isset($_GET['keyword']) && !isset($_GET['categoria']))) {
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
        /* Adjust the number of columns based on screen size */
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        }

        .imagen{
            max-height: 275px;
            max-width: 275px;
        }

        .product-card {
        background-color: #dee2e6;
        padding: 1rem;
        border: 1px solid #ced4da;
        text-align: center;
        max-height: 350px;
        max-width: 350px;
        }

        @media (min-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(4, 1fr);
        }
        }
    </style>
    <title>Bienvenido!</title>
</head>
<body>
    <header class="py-3 mb-3 border-bottom">
        <?php include_once 'partials/header.php'; include_once 'includes/Search.php'; ?>
    </header>

    <main class="container-fluid">
    <div class='container'>
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
                    $results = $search->searcher($keyword);
                    if ($results) {
        ?>
                        <h2 class="mb-3">Resultados de "<?php echo $keyword ?>"</h2>
                        <div class="row p-3 m-3">
                            <div class="col-md-12">
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
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['categoria'])) {
                $categoria = htmlspecialchars($_GET['categoria']); // Asegúrate de que el nombre coincida con el del formulario
                $search = new Search();

                if (empty($categoria)) {
                    // Si está vacío
        ?>
                    <div class='container'>
                        <h2 class="mb-3">No se proporcionó una categoría para la búsqueda.</h2>
                        <div class="row">
                            <div class="col-12">
                                <div class="container my-5">
                                    <div class="bg-body-tertiary p-5 rounded">
                                        <div class="col-sm-8 py-5 mx-auto text-center">
                                            <p class="fs-5">No se proporcionó una categoría para la búsqueda.</p>
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
                    $results = $search->categories_searcher($categoria);
                    if ($results) {
        ?>
                        <div class="row p-3 m-3">
                            <div class="col-md-12">
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
        ?>
                            <div class='container'>
                                <h2 class="mb-3">No se encontraron resultados en la categoría "<?php echo $categoria; ?>"</h2>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="container my-5">
                                            <div class="bg-body-tertiary p-5 rounded">
                                                <div class="col-sm-8 py-5 mx-auto text-center">
                                                    <p class="fs-5">No se encontraron resultados en la categoría "<?php echo $categoria; ?>".</p>
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
    </div>
    </main>

    <footer class="footer mt-auto">
        <?php include_once 'partials/footer.php'; ?>
    </footer>

    <script src="scripts/bootstrap.bundle.min.js"></script>
</body>
</html>
