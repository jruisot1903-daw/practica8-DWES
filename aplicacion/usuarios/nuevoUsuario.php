<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

// --- Control de permisos ---
if (!$ACCESO->puedePermiso(2)) {
    paginaError("No tienes permiso para acceder a esta página de la base de datos.");
    exit;
}else if(!$ACCESO->puedePermiso(3)){
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

// --- Procesamiento del formulario ---
$errores = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recogemos datos
    $nick   = trim($_POST['nick'] ?? "");
    $nombre = trim($_POST['nombre'] ?? "");
    $nif    = trim($_POST['nif'] ?? "");
    $direccion = trim($_POST['direccion'] ?? "");
    $poblacion = trim($_POST['poblacion'] ?? "");
    $provincia = trim($_POST['provincia'] ?? "");
    $CP     = trim($_POST['CP'] ?? "");
    $fechaNac = trim($_POST['fecha_nacimiento'] ?? "");
    $borrado = 0; // por defecto no borrado

    // Validaciones básicas
    if ($nick == "") $errores[] = "El nick es obligatorio";
    if ($nombre == "") $errores[] = "El nombre es obligatorio";
    if ($nif == "") $errores[] = "El NIF es obligatorio";
    if (!preg_match("/^[0-9]{5}$/", $CP)) $errores[] = "El código postal debe tener 5 dígitos";

    // --- Foto ---
    $fotoNombre = "default.jpg"; // foto por defecto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fotoNombre = bin2hex(random_bytes(10)) . "." . $ext; // nombre aleatorio de 20 caracteres
        $destino = __DIR__ . "/../../imagenes/fotos/" . $fotoNombre;
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
            $errores[] = "Error al subir la foto";
        }
    }

    // Si no hay errores, insertamos
    if (empty($errores)) {
        $stmt = $bd->prepare("INSERT INTO usuarios 
            (nick,nombre,nif,direccion,poblacion,provincia,CP,fecha_nacimiento,borrado,foto) 
            VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssssss", $nick,$nombre,$nif,$direccion,$poblacion,$provincia,$CP,$fechaNac,$borrado,$fotoNombre);
        if ($stmt->execute()) {
            $idInsertado = $stmt->insert_id;
            header("Location: verUsuario.php?id=" . $idInsertado);
            exit;
        } else {
            $errores[] = "Fallo al insertar en la BD: " . $bd->error;
        }
    }
}

// ------------------- VISTA -------------------
inicioCabecera("Nuevo Usuario");
cabecera();
finCabecera();

inicioCuerpo("Nuevo Usuario");
cuerpo($errores);
finCuerpo();

// --- Vista ---
function cabecera() {
}

function cuerpo($errores) {
    if (!empty($errores)) {
        echo "<ul style='color:red'>";
        foreach ($errores as $e) {
            echo "<li>$e</li>";
        }
        echo "</ul>";
    }
    ?>
    <form method="post" enctype="multipart/form-data">
        Nick: <input type="text" name="nick" value="<?=htmlspecialchars($_POST['nick'] ?? '')?>"><br>
        Nombre: <input type="text" name="nombre" value="<?=htmlspecialchars($_POST['nombre'] ?? '')?>"><br>
        NIF: <input type="text" name="nif" value="<?=htmlspecialchars($_POST['nif'] ?? '')?>"><br>
        Dirección: <input type="text" name="direccion" value="<?=htmlspecialchars($_POST['direccion'] ?? '')?>"><br>
        Población: <input type="text" name="poblacion" value="<?=htmlspecialchars($_POST['poblacion'] ?? '')?>"><br>
        Provincia: <input type="text" name="provincia" value="<?=htmlspecialchars($_POST['provincia'] ?? '')?>"><br>
        CP: <input type="text" name="CP" value="<?=htmlspecialchars($_POST['CP'] ?? '')?>"><br>
        Fecha nacimiento: <input type="date" name="fecha_nacimiento" value="<?=htmlspecialchars($_POST['fecha_nacimiento'] ?? '')?>"><br>
        Foto: <input type="file" name="foto"><br>
        <input type="submit" value="Guardar">
    </form>
    <?php
     echo "<br><a href='index.php'>Volver al inicio</a>";

}
