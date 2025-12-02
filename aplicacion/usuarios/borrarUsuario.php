<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

// Validar permisos (necesita 2 y 3)
if (!$ACCESO->puedePermiso(2) || !$ACCESO->puedePermiso(3)) {
    paginaError("No tienes permiso para borrar usuarios.");
    exit;
}

// Conexión a la BD
$bd = new mysqli($servidor, $usuario, $contrasenia, $baseDatos);
if ($bd->connect_errno) {
    paginaError("Fallo al conectar con la Base de Datos");
    exit;
}
$bd->set_charset("utf8");

// Recogemos el cod_usuario por GET
if (empty($_GET['id'])) {
    paginaError("No se ha indicado ningún usuario.");
    exit;
}
$id = (int)$_GET['id'];

// Comprobamos que existe el usuario y no está ya borrado
$consulta = $bd->query("SELECT * FROM usuarios WHERE cod_usuario = $id AND borrado = 0");
if (!$consulta || $consulta->num_rows == 0) {
    paginaError("El usuario con id '$id' no existe o ya está borrado.");
    exit;
}
$usuario = $consulta->fetch_assoc();

// Si se confirma el borrado (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
    // Borrado lógico en tabla usuarios
    $bd->query("UPDATE usuarios SET borrado = 1 WHERE cod_usuario = $id");

    // Borrado también en ACL (usando objeto global $ACL)
    $acl->borrarUsuario($usuario['nick']);

    echo "<p>El usuario <strong>{$usuario['nick']}</strong> ha sido marcado como borrado en la tabla de usuarios y en la ACL.</p>";
    echo "<p><a href='index.php'>Volver al listado</a></p>";
    exit;
}

// ------------------- VISTA -------------------
inicioCabecera("Borrar usuario");
cabecera();
finCabecera();

inicioCuerpo("Borrar usuario");
cuerpo($usuario);
finCuerpo();

// --- Vista ---
function cabecera() {
    echo "<h2>Borrar usuario</h2>";
}

function cuerpo(array $usuario) {
    echo "<p>¿Seguro que deseas borrar al usuario <strong>{$usuario['nick']}</strong>?</p>";

    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Nick</th><th>Nombre</th><th>Provincia</th><th>Borrado</th></tr>";
    echo "<tr>";
    echo "<td>{$usuario['nick']}</td>";
    echo "<td>{$usuario['nombre']}</td>";
    echo "<td>{$usuario['provincia']}</td>";
    echo "<td>{$usuario['borrado']}</td>";
    echo "</tr>";
    echo "</table>";

    // Formulario de confirmación (POST)
    echo '<form method="post">';
    echo '<button type="submit" name="confirmar" value="1">Confirmar borrado</button>';
    echo '</form>';

    // Enlaces para cancelar
    echo "<p><a href='index.php'>Cancelar y volver al listado</a></p>";
    echo "<p><a href='verUsuario.php?id={$usuario['cod_usuario']}'>Cancelar y ver usuario</a></p>";
}
