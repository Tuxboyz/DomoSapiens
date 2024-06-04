<?php


function crearCarpeta($ruta, $inicio = 1, $cantidad = 10) {
    for ($i = $inicio; $i <= $inicio + $cantidad - 1; $i++) {
        $rutaCarpeta = $ruta . DIRECTORY_SEPARATOR . $i;
        if (mkdir($rutaCarpeta)) {
            if (mkdir($rutaCarpeta . DIRECTORY_SEPARATOR . 'principal')) {
                echo "Se creó la subcarpeta 'principal' dentro de la carpeta $i\n";
            } else {
                echo "Error al crear la subcarpeta 'principal' dentro de la carpeta $i\n";
            }
        } else {
            echo "Error al crear la carpeta $i\n";
        }
    }
    echo "Se crearon $cantidad carpetas numeradas a partir de $inicio\n";
}

// Ejemplo de uso
crearCarpeta('img/', 1, 20); // Crear 50 carpetas numeradas desde 100