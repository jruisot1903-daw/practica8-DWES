<?php
define("RUTABASE", dirname(__FILE__));
//define("MODO_TRABAJO","produccion"); //en "produccion o en desarrollo
define("MODO_TRABAJO", "desarrollo"); //en "produccion o en desarrollo

if (MODO_TRABAJO == "produccion")
    error_reporting(0);
else
    error_reporting(E_ALL);

spl_autoload_register(function ($clase) {
    $ruta = RUTABASE . "/aplicacion/clases/";
    $fichero = $ruta . "$clase.php";

    if (file_exists($fichero)) {
        require_once($fichero);
        return true;
    }
});

spl_autoload_register(function ($clase) {
    $ruta = RUTABASE . "/scripts/clases/";
    $fichero = $ruta . "$clase.php";

    if (file_exists($fichero)) {
        require_once($fichero);
        return true;
    }
});

session_start();
$ACL = new ACLArray();
$ACCESO = new Acceso();

const FONDO_DEFECTO = "blanco";
const TEXTO_DEFECTO = "negro";

const COLORESTEXTO = [
    "negro" => "black",
    "blanco" => "white",
    "azul" => "blue",
    "rojo" => "red"
];

const COLORESFONDO = [
    "blanco" => "white",
    "negro" => "black",
    "rojo" => "red",
    "azul" => "blue",
    "cyan" => "cyan"
];

$PATH = dirname($_SERVER['PHP_SELF']);
$PATH = str_replace('\\', '/', $PATH);
$FILE = basename($_SERVER['PHP_SELF']);

if ($FILE != "index.php") {
    $PATH .= "/" . $FILE;
}

$PUBLIC_PATHS = [
    "/",
    "/aplicacion/acceso/login.php",
    "/aplicacion/acceso/logout.php",
];

if (!$ACCESO->hayUsuario() && !in_array($PATH, $PUBLIC_PATHS)) {
    // Redirigir al usuario a la página de login con la URL de redirección
    header("Location: /aplicacion/acceso/login.php?redirect=" . urlencode($PATH));
    exit();
}

include(RUTABASE . "/aplicacion/plantilla/plantilla.php");
include(RUTABASE . "/aplicacion/config/acceso_bd.php");

// Verificar permisos de acceso a la página
if ($ACCESO->hayUsuario() && !in_array($PATH, $PUBLIC_PATHS) && !$ACCESO->puedePermiso(1)) {
    paginaError("No tienes permisos para acceder a esta página.");
    exit();
}
