<?php
require_once __DIR__ . '/MuebleReciclado.php';
require_once __DIR__ . '/MuebleTradicional.php';

$muebles = [];


$muebles[1] = new MuebleReciclado("Silla Eco", "EcoFabrica", "España", 2021);
$muebles[1]->anadir("reciclado", true, "peso", 5);


$muebles[2] = new MuebleTradicional("Mesa Roble", "MueblesSA", "Portugal", 2020);
$muebles[2]->anadir("color", "marrón", "peso", 20);


$muebles[3] = new MuebleReciclado("Banco Verde", "VerdeVida", "España", 2022);
$muebles[3]->anadir("reciclado", true, "resistente", true);


$muebles[4] = new MuebleTradicional("Armario Clásico", "ClásicosSL", "Italia", 2019);
$muebles[4]->anadir("color", "caoba", "altura", 180);


$muebles[5] = new MuebleReciclado("Estantería Eco", "EcoFabrica", "España", 2023);
$muebles[5]->anadir("reciclado", true, "capacidad", "30 libros");