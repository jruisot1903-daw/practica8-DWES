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

// --- Procesamiento del formulario ---
$errores = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recogemos datos
    $nick       = trim($_POST['nick'] ?? "");
    $nombre     = trim($_POST['nombre'] ?? "");
    $nif        = trim($_POST['nif'] ?? "");
    $direccion  = trim($_POST['direccion'] ?? "");
    $poblacion  = trim($_POST['poblacion'] ?? "");
    $provincia  = trim($_POST['provincia'] ?? "");
    $CP         = trim($_POST['CP'] ?? "");
    $fechaNac   = trim($_POST['fecha_nacimiento'] ?? "");
    $borrado    = 0; // por defecto no borrado

    // --- ACL: contraseña y rol ---
    $password        = trim($_POST['contrasenia'] ?? "");
    $passwordConfirm = trim($_POST['password_confirm'] ?? "");
    $idRol           = (int)($_POST['rol'] ?? 0);

    // Validaciones básicas
    if ($nick == "") $errores[] = "El nick es obligatorio";
    if ($nombre == "") $errores[] = "El nombre es obligatorio";
    if ($nif == "") $errores[] = "El NIF es obligatorio";
    if (!preg_match("/^[0-9]{5}$/", $CP)) $errores[] = "El código postal debe tener 5 dígitos";

    if ($password == "" || $passwordConfirm == "") {
        $errores[] = "La contraseña es obligatoria";
    } elseif ($password !== $passwordConfirm) {
        $errores[] = "Las contraseñas no coinciden";
    }

    // --- Foto ---
    $fotoNombre = "default.jpg"; // foto por defecto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fotoNombre = bin2hex(random_bytes(10)) . "." . $ext;
        $destino = __DIR__ . "/../../imagenes/fotos/" . $fotoNombre;
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
            $errores[] = "Error al subir la foto";
        }
    }

    // Si no hay errores, insertamos
    if (empty($errores)) {
        // --- Primero comprobamos en ACL ---
        if ($acl->existeUsuario($nick)) {
            $errores[] = "El Nick ya existe en la ACL, no se puede crear.";
        } else {
            // Insertar en usuarios
            $stmt = $bd->prepare("INSERT INTO usuarios 
                (nick,nombre,nif,direccion,poblacion,provincia,CP,fecha_nacimiento,borrado,foto) 
                VALUES (?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("ssssssssss", $nick,$nombre,$nif,$direccion,$poblacion,$provincia,$CP,$fechaNac,$borrado,$fotoNombre);
            
            if ($stmt->execute()) {
                // Insertar en ACL
                if (!$acl->insertarUsuario($nick, $nombre, $password, $idRol)) {
                    $errores[] = "Fallo al insertar en la ACL.";
                } else {
                    $idInsertado = $stmt->insert_id;
                    header("Location: verUsuario.php?id=" . $idInsertado);
                    exit;
                }
            } else {
                $errores[] = "Fallo al insertar en la BD: " . $bd->error;
            }
        }
    }
}

// ------------------- VISTA -------------------
inicioCabecera("Nuevo Usuario");
cabecera();
finCabecera();

inicioCuerpo("Nuevo Usuario");
cuerpo($errores, $ROLES);
finCuerpo();

// --- Vista ---
function cabecera() {}

function cuerpo($errores, array $ROLES) {
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

        <!-- ACL: contraseña y rol -->
        Contraseña: <input type="password" name="contrasenia"><br>
        Confirmar contraseña: <input type="password" name="password_confirm"><br>
        Rol:
        <select name="rol">
            <?php
            foreach ($ROLES as $rol) {
                $selected = ($_POST['rol'] ?? '') == $rol['cod_acl_role'] ? "selected" : "";
                echo "<option value='{$rol['cod_acl_role']}' $selected>{$rol['nombre']}</option>";
            }
            ?>
        </select><br>

        <input type="submit" value="Guardar">
    </form>
    <?php
    echo "<br><a href='index.php'>Volver al inicio</a>";
}
