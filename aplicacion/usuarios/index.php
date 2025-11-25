<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

//Controlador

// Validar permisos
if (!$ACCESO->puedePermiso(2)) {
    paginaError("No tienes permiso para acceder a esta página de la base de datos.");
    exit;
}

// Conectamos a la BD
$bd = new mysqli($servidor,$usuario,$contrasenia,$baseDatos);

if($bd->connect_errno){
    paginaError("Fallo al conectar con la Base de Datos");
    exit;
}

$bd->set_charset("utf8");

// Sentencia SQL
$sentSelect = "*";
$sentFrom   = "usuarios";
$sentWhere  = "";
$sentOrder  = "";

// Ejemplo: recogemos criterios de filtrado desde GET
if (!empty($_GET['nombre'])) {
    $nombre = $bd->real_escape_string($_GET['nombre']);
    $sentWhere = "WHERE nombre LIKE '%$nombre%'";
}

// Ejemplo: recogemos ordenación desde GET
if (!empty($_GET['orden'])) {
    $orden = $bd->real_escape_string($_GET['orden']);
    $sentOrder = "ORDER BY $orden";
}

// Construimos la sentencia
$sentencia = "SELECT $sentSelect FROM $sentFrom $sentWhere $sentOrder";

// Ejecutamos
$consulta = $bd->query($sentencia);

if(!$consulta){
    paginaError("Fallo al ejecutar la sentencia ");
    exit;
}

// Guardamos las filas en un array
$filas = [];
while ($fila = $consulta->fetch_assoc()) {
    $filas[] = $fila;
}

// ------------------- VISTA -------------------
inicioCabecera("Usuarios");
cabecera();
finCabecera();

inicioCuerpo("Usuarios");
cuerpo($filas, $ACCESO);
finCuerpo();

//Vista
function cabecera() {
    echo "<h2>Listado de usuarios</h2>";
}

//Vista
function cuerpo(array $filas, Acceso $ACCESO) {
    if (empty($filas)) {
        echo "<p>No hay usuarios registrados.</p>";
        return;
    }

    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Email</th>";

    // Si el usuario tiene permiso de edición (3), añadimos columna de acciones
    if ($ACCESO->puedePermiso(3)) {
        echo "<th>Acciones</th>";
    }
    echo "</tr>";

    foreach ($filas as $fila) {
        echo "<tr>";
        echo "<td>{$fila['id']}</td>";
        echo "<td>{$fila['nombre']}</td>";
        echo "<td>{$fila['email']}</td>";

        if ($ACCESO->puedePermiso(3)) {
            echo "<td><a href='editar.php?id={$fila['id']}'>Editar</a> | ";
            echo "<a href='borrar.php?id={$fila['id']}'>Borrar</a></td>";
        }

        echo "</tr>";
    }

    echo "</table>";
}
