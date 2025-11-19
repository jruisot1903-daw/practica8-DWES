<?php
define("RUTABASE", dirname(__FILE__));
include_once(RUTABASE . "/aplicacion/clases/RegistroTexto.php");
session_start();
//define("MODO_TRABAJO","produccion"); //en "produccion o en desarrollo
define("MODO_TRABAJO", "desarrollo"); //en "produccion o en desarrollo

if (MODO_TRABAJO == "produccion")
    error_reporting(0);
else
    error_reporting(E_ALL);


spl_autoload_register(function ($clase) {
    $ruta = RUTABASE . "/scripts/clases/";
    $fichero = $ruta . "$clase.php";

    if (file_exists($fichero)) {
        require_once($fichero);
    } else {
        throw new Exception("La clase $clase no se ha encontrado.");
    }
});

include(RUTABASE . "/aplicacion/plantilla/plantilla.php");
//include(RUTABASE . "/aplicacion/config/acceso_bd.php");

 //creo todos los objetos que necesita mi aplicación

// Colores posibles para el texto
const COLORESTEXTO = [
    "Negro" => "black",
    "Azul"  => "blue",
    "Blanco"=> "white",
    "Rojo"  => "red"
];

// Colores posibles para el fondo
const COLORESFONDO = [
    "Blanco" => "white",
    "Rojo"   => "red",
    "Verde"  => "green",
    "Azul"   => "blue",
    "Cyan"   => "cyan"
];

// Inicializar cookies con valores por defecto si no existen
if (!isset($_COOKIE['color_fondo'])) {
    setcookie("color_fondo", "white", time() + 3600*24*30, "/");
}
if (!isset($_COOKIE['color_texto'])) {
    setcookie("color_texto", "black", time() + 3600*24*30, "/");
}

// Función para aplicar estilos desde cookies
function inicio_cuerpo() {
    $fondo = $_COOKIE['color_fondo'] ?? "white";
    $texto = $_COOKIE['color_texto'] ?? "black";
    echo "<body style='background-color:$fondo; color:$texto;'>";
}



