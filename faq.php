<?php    session_start();?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="lang" content="es-ES">
    <meta name="author" content="Alvaro Mateo Polit Guartatanga">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Paguina de venta de productos de domotica.">
    <meta name="keywords" content="palabra clave 1, palabra clave 2, palabra clave 3">
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1 0 auto;
        }
        .footer {
            flex-shrink: 0;
        }
        .filter-section {
            background-color: #f8f9fa;
            padding: 1rem;
        }
    </style>

    <title>Bienvenido!</title>
</head>

<body>
    <header class="py-3 mb-3 border-bottom">
        <?php include_once 'partials/header.php';?>
    </header>

    <main>

    </main>

    <footer class="footer mt-auto">
        <?php include_once 'partials/footer.php';?>
    </footer>

    <script src="scripts/bootstrap.bundle.min.js"></script>

</body>

</html>