<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

//Controlador

// Validar permisos
if (!$ACCESO->puedePermiso(2)) {
    paginaError("No tienes permiso para acceder a esta página de la base de datos.");
    exit;
}

// Conectamos a la BD
$bd = new mysqli($servidor, $usuario, $contrasenia, $baseDatos);

if ($bd->connect_errno) {
    paginaError("Fallo al conectar con la Base de Datos");
    exit;
}

$bd->set_charset("utf8");

// Sentencia SQL
$sentSelect = "*";
$sentFrom   = "usuarios";
$sentWhere  = "";
$sentOrder  = "";


if (!empty($_POST['filtro']) && !empty($_POST['campo'])) {
    $filtro = $bd->real_escape_string($_POST['filtro']);
    $campo  = $bd->real_escape_string($_POST['campo']);
    $sentWhere = "WHERE $campo LIKE '%$filtro%'";
}

if (!empty($_POST['orden'])) {
    $orden = $bd->real_escape_string($_POST['orden']);
    $sentOrder = "ORDER BY $orden";
}

// Construimos la sentencia
$sentencia = "SELECT $sentSelect FROM $sentFrom $sentWhere $sentOrder";

// Ejecutamos
$consulta = $bd->query($sentencia);

if (!$consulta) {
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
function cabecera() {}

//Vista
function cuerpo(array $filas, Acceso $ACCESO)
{
    if (empty($filas)) {
        echo "<p>No hay usuarios registrados.</p>";
        return;
    }
    echo "<h2>Filtrar/Ordenar usuarios</h2>";

    echo '<form id="formBuscar" method="post" action="">';

    echo '<label for="filtro">Palabra a filtrar:</label>';
    echo '<input type="text" name="filtro" id="filtro" placeholder="Escribe la palabra">';

    echo '<label for="campo">Filtrar por:</label>';
    echo '<select name="campo" id="campo">';
    echo '  <option value="nombre">Nombre</option>';
    echo '  <option value="provincia">Provincia</option>';
    echo '  <option value="borrado">Borrado</option>';
    echo '</select>';

    echo '<label for="orden">Ordenar por:</label>';
    echo '<select name="orden" id="orden">';
    echo '  <option value="">-- Selecciona --</option>';
    echo '  <option value="nombre">Nombre</option>';
    echo '  <option value="provincia">Provincia</option>';
    echo '  <option value="borrado">Borrado</option>';
    echo '</select>';

    echo '<button type="submit">Buscar</button>';
    echo '<button type="reset">Borrar filtros</button>';

    echo '</form>';
    echo '<br><br>';

    echo "<h2>Listado de usuarios</h2>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr>
            <th>Nick</th>
            <th>Nombre</th>
            <th>NIF</th>
            <th>Dirección</th>
            <th>Población</th>
            <th>Provincia</th>
            <th>CP</th>
            <th>FechaNacimiento</th>
            <th>Borrado</th>
            <th>Foto</th>";

    // Si el usuario tiene permiso de edición (3), añadimos columna de acciones
    if ($ACCESO->puedePermiso(3)) {
        echo "<th>Acciones</th>";
    }
    echo "</tr>";

    foreach ($filas as $fila) {
        echo "<tr>";
        echo "<td>{$fila['nick']}</td>";
        echo "<td>{$fila['nombre']}</td>";
        echo "<td>{$fila['nif']}</td>";
        echo "<td>{$fila['direccion']}</td>";
        echo "<td>{$fila['poblacion']}</td>";
        echo "<td>{$fila['provincia']}</td>";
        echo "<td>{$fila['CP']}</td>";
        echo "<td>{$fila['fecha_nacimiento']}</td>";
        echo "<td>{$fila['borrado']}</td>";
        echo "<td>" . (($fila['foto'] === "" || $fila['foto'] === null) ? "default.png" : $fila['foto']) . "</td>";



        if ($ACCESO->puedePermiso(3)) {
            echo "<td>
                <a href='verUsuario.php?id={$fila['cod_usuario']}'>Ver</a> 
                <a href='modificarUsuario.php?id={$fila['cod_usuario']}'>Editar</a> 
                <a href='borrarUsuario.php?id={$fila['cod_usuario']}'>Borrar</a>
              </td>";
        }

        echo "</tr>";
    }

    echo "</table>";

    if ($ACCESO->puedePermiso(3)) {
        echo "<p><a href='nuevoUsuario.php'>Nuevo usuario</a></p>";
    }
}
