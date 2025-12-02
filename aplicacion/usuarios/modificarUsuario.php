<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

// --- Control de permisos ---
if (!$ACCESO->puedePermiso(2) || !$ACCESO->puedePermiso(3)) {
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

// --- Comprobamos que se pasa cod_usuario ---
$cod_usuario = intval($_GET['id'] ?? 0);
if ($cod_usuario <= 0) {
    paginaError("Usuario no válido");
    exit;
}

// --- Cargamos datos actuales ---
$sentencia = "SELECT cod_usuario,nick,nombre,nif,direccion,poblacion,provincia,CP,fecha_nacimiento,foto 
              FROM usuarios WHERE cod_usuario=? AND borrado=0";
$fila = $bd->prepare($sentencia);
$fila->bind_param("i", $cod_usuario);
$fila->execute();
$result = $fila->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    paginaError("El usuario no existe");
    exit;
}

// --- Obtener rol actual desde ACL ---
$rolActual = $ACL->obtenerRolUsuario($usuario['nick']);

// --- Procesamiento del formulario ---
$errores = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recogemos datos
    $nombre     = trim($_POST['nombre'] ?? "");
    $nif        = trim($_POST['nif'] ?? "");
    $direccion  = trim($_POST['direccion'] ?? "");
    $poblacion  = trim($_POST['poblacion'] ?? "");
    $provincia  = trim($_POST['provincia'] ?? "");
    $CP         = trim($_POST['CP'] ?? "");
    $fechaNac   = trim($_POST['fecha_nacimiento'] ?? "");
    $fotoNombre = $usuario['foto']; // mantenemos la actual si no se sube nueva
    $idRol      = (int)($_POST['rol'] ?? $rolActual['cod_acl_role']);

    // Validaciones
    if ($nombre == "") $errores[] = "El nombre es obligatorio";
    if ($nif == "") $errores[] = "El NIF es obligatorio";
    if (!preg_match("/^[0-9]{5}$/", $CP)) $errores[] = "El código postal debe tener 5 dígitos";

    // --- Foto ---
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fotoNombre = bin2hex(random_bytes(10)) . "." . $ext;
        $destino = __DIR__ . "/../../imagenes/fotos/" . $fotoNombre;
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
            $errores[] = "Error al subir la foto";
        }
    }

    // Si no hay errores, actualizamos
    if (empty($errores)) {
        $stmt = $bd->prepare("UPDATE usuarios SET 
            nombre=?, nif=?, direccion=?, poblacion=?, provincia=?, CP=?, fecha_nacimiento=?, foto=? 
            WHERE cod_usuario=?");
        $stmt->bind_param("ssssssssi", $nombre,$nif,$direccion,$poblacion,$provincia,$CP,$fechaNac,$fotoNombre,$cod_usuario);
        if ($stmt->execute()) {
            // --- ACL: sincronizar nombre y rol ---
            $acl->modificarNombre($usuario['nick'], $nombre);
            $acl->asignarRol($usuario['nick'], $idRol);

            header("Location: verUsuario.php?id=" . $cod_usuario);
            exit;
        } else {
            $errores[] = "Fallo al actualizar en la BD: " . $bd->error;
        }
    }
}

// ------------------- VISTA -------------------
inicioCabecera("Modificar Usuario");
cabecera();
finCabecera();

inicioCuerpo("Modificar Usuario");
cuerpo($usuario, $rolActual, $ROLES, $errores);
finCuerpo();

// --- Vista ---
function cabecera() {}

function cuerpo($usuario, $rolActual, array $ROLES, $errores) {
    if (!empty($errores)) {
        echo "<ul style='color:red'>";
        foreach ($errores as $e) {
            echo "<li>$e</li>";
        }
        echo "</ul>";
    }
    ?>
    <form method="post" enctype="multipart/form-data">
        Nombre: <input type="text" name="nombre" value="<?=htmlspecialchars($_POST['nombre'] ?? $usuario['nombre'])?>"><br>
        NIF: <input type="text" name="nif" value="<?=htmlspecialchars($_POST['nif'] ?? $usuario['nif'])?>"><br>
        Dirección: <input type="text" name="direccion" value="<?=htmlspecialchars($_POST['direccion'] ?? $usuario['direccion'])?>"><br>
        Población: <input type="text" name="poblacion" value="<?=htmlspecialchars($_POST['poblacion'] ?? $usuario['poblacion'])?>"><br>
        Provincia: <input type="text" name="provincia" value="<?=htmlspecialchars($_POST['provincia'] ?? $usuario['provincia'])?>"><br>
        CP: <input type="text" name="CP" value="<?=htmlspecialchars($_POST['CP'] ?? $usuario['CP'])?>"><br>
        Fecha nacimiento: <input type="date" name="fecha_nacimiento" value="<?=htmlspecialchars($_POST['fecha_nacimiento'] ?? $usuario['fecha_nacimiento'])?>"><br>
        Nueva foto: <input type="file" name="foto"><br><br>

        Rol:
        <select name="rol">
            <?php
            foreach ($ROLES as $rol) {
                $selected = ($_POST['rol'] ?? $rolActual['cod_acl_role']) == $rol['cod_acl_role'] ? "selected" : "";
                echo "<option value='{$rol['cod_acl_role']}' $selected>{$rol['nombre']}</option>";
            }
            ?>
        </select><br><br>

        <input type="submit" value="Guardar cambios">
    </form>
    <?php
    echo "<br><a href='index.php'>Cancelar</a> | ";
    echo "<a href='verUsuario.php?id=" . urlencode($usuario['cod_usuario']) . "'>Ver Usuario</a>";
}
