<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

// --- Control de permisos ---
if (!$ACCESO->puedePermiso(2)) {
    paginaError("No tienes permiso para acceder a esta página de la base de datos.");
    exit;
}

// --- Conexión BD ---
$bd = new mysqli($servidor, $usuario, $contrasenia, $baseDatos);
if ($bd->connect_errno) {
    paginaError("Fallo al conectar con la Base de Datos");
    exit;
}
$bd->set_charset("utf8");

// --- Recoger parámetro cod_usuario ---
$cod_usuario = $_GET['id'] ?? null;
if (!$cod_usuario) {
    paginaError("No se ha indicado usuario");
    exit;
}

// --- Obtener datos del usuario ---
$sentencia = "SELECT nick, nombre, nif, direccion, poblacion, provincia, CP, 
                     fecha_nacimiento, foto
              FROM usuarios 
              WHERE cod_usuario=? AND borrado=0";
$fila = $bd->prepare($sentencia);
$fila->bind_param("i", $cod_usuario);
$fila->execute();
$result = $fila->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    paginaError("El usuario no existe");
    exit;
}

// --- Obtener rol desde ACL (usando objeto global $ACL) ---
$rol = $acl->obtenerRolUsuario($usuario['nick']);

// ------------------- VISTA -------------------
inicioCabecera("Ver Usuario");
cabecera();
finCabecera();

inicioCuerpo("Ver Usuario");
cuerpo($usuario, $rol, $ACCESO);
finCuerpo();

// --- Vista ---
function cabecera() {}

function cuerpo($usuario, $rol, Acceso $ACCESO) {
    ?>
    <form>
        Nick: <input type="text" value="<?=htmlspecialchars($usuario['nick'])?>" readonly><br>
        Nombre: <input type="text" value="<?=htmlspecialchars($usuario['nombre'])?>" readonly><br>
        NIF: <input type="text" value="<?=htmlspecialchars($usuario['nif'])?>" readonly><br>
        Dirección: <input type="text" value="<?=htmlspecialchars($usuario['direccion'])?>" readonly><br>
        Población: <input type="text" value="<?=htmlspecialchars($usuario['poblacion'])?>" readonly><br>
        Provincia: <input type="text" value="<?=htmlspecialchars($usuario['provincia'])?>" readonly><br>
        CP: <input type="text" value="<?=htmlspecialchars($usuario['CP'])?>" readonly><br>
        Fecha nacimiento: <input type="date" value="<?=htmlspecialchars($usuario['fecha_nacimiento'])?>" readonly><br>
        Foto: 
        <img src="../../imagenes/fotos/<?=empty($usuario['foto']) ? 'default.jpg' : htmlspecialchars($usuario['foto'])?>" 
             alt="foto" width="100"><br>
        Rol: <input type="text" value="<?=htmlspecialchars($rol['nombre'] ?? 'Sin rol')?>" readonly><br>
    </form>
    <br>
    <?php
    // Enlaces según permisos
    if ($ACCESO->puedePermiso(3)) {
        echo "<a href='modificarUsuario.php?id=" . urlencode($_GET['id']) . "'>Modificar</a> | ";
        echo "<a href='borrarUsuario.php?id=" . urlencode($_GET['id']) . "'>Borrar</a> | ";
    }
    echo "<a href='index.php'>Cancelar</a>";
}
