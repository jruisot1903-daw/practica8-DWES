<?php
require_once '../clases/curso2025/MuebleBase.php';
require_once '../clases/curso2025/MuebleReciclado.php';
require_once '../clases/curso2025/MuebleTradicional.php';
require_once '../clases/curso2025/Caracteristicas.php';
require_once '../clases/curso2025/muebles.php';

echo "<pre>";

// Crear un mueble reciclado
try {
    $reciclado = new MuebleReciclado(
        "Silla Eco",
        "EcoFabrica",
        "España",
        "2022",
        "01/01/2022",
        "31/12/2030",
        2,
        45.5,
        80
    );

    $reciclado->anadir("color", "verde", "plegable", true);
    //$reciclado->anadir("ningunamas", true);
    // Esto lanzará excepción
    try {
        $reciclado->anadir("nuevoExtra", "valor");
    } catch (Exception $e) {
        echo "Excepción esperada: " . $e->getMessage() . "\n";
    }

    echo "Mueble Reciclado:\n";
    echo $reciclado;
    echo "\nExportar características:\n" . $reciclado->exportarCaracteristicas();

    $res = null;
    if ($reciclado->damePropiedad("PorcentajeReciclado", 1, $res)) {
        echo "\nPorcentaje reciclado (modo 1): $res\n";
    }

} catch (Exception $e) {
    echo "Error al crear MuebleReciclado: " . $e->getMessage();
}

// Crear un mueble tradicional
try {
    $tradicional = new MuebleTradicional(
        "Mesa Clásica",
        "MueblesAntiguos",
        "Italia",
        "2021",
        "01/06/2021",
        "31/12/2035",
        1,
        150.0,
        80.5,
        "S02"
    );

    $tradicional->anadir("barnizado", true, "pesoExtra", 5);
    echo "\n\nMueble Tradicional:\n";
    echo $tradicional;
    echo "\nExportar características:\n" . $tradicional->exportarCaracteristicas();

    $res = null;
    if ($tradicional->damePropiedad("Peso", 2, $res)) {
        echo "\nPeso (modo 2): $res\n";
    }

} catch (Exception $e) {
    echo "Error al crear MuebleTradicional: " . $e->getMessage();
}

echo "\n\nTotal de muebles creados: " . MuebleBase::getMueblesCreados();

echo "</pre>";

//Mostramos todos los muebles creados en el array definido

foreach ($muebles as $indice => $mueble) {
    echo "[$indice] " . $mueble . "\n\n";
}