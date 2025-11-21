<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");
include_once(dirname(__FILE__) . "/../clases/RegistroTexto.php");

if (!$acceso->hayUsuario()) {
    header("Location: /aplicacion/acceso/login.php");
    exit;
}
if (!$acceso->puedePermiso(1)) {
    paginaError("No tienes permiso para acceder a esta página");
    exit;
}



// Recuperar textos de la sesión
$textos = $_SESSION['textos'] ?? [];

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['accion']) && $_POST['accion'] == "guardar") {
        $nuevoTexto = trim($_POST['texto'] ?? "");
        if ($nuevoTexto !== "") {
            $registro = new RegistroTexto($nuevoTexto);
            $textos[] = $registro;
        }
    } elseif (isset($_POST['accion']) && $_POST['accion'] == "limpiar") {
        $textos = []; 
    }

    // Actualizar sesión
    $_SESSION['textos'] = $textos;

    // Redirigir para evitar reenvío de formulario
    header("Location: verTextos.php");
    exit;
}

// Dibuja la plantilla
inicioCabecera("2DAW Relacion8 - Textos");
cabecera();
finCabecera();

inicioCuerpo("Textos");
cuerpo($textos);
finCuerpo();

// **********************************************************
// Vista
function cabecera() {
}

function cuerpo($textos) {
    ?>
    <form method="post">
        <label>Texto a registrar:</label>
        <input type="text" name="texto">
        <button type="submit" name="accion" value="guardar">Guardar</button>
        <button type="submit" name="accion" value="limpiar">Limpiar</button>
    </form>

    <br>
    <label>Textos registrados:</label><br>
    <textarea rows="10" cols="60" readonly>
<?php
    foreach ($textos as $registro) {
        echo $registro->getFechaHora() . " - " . $registro->getTexto() . "\n";
    }
?>
    </textarea>
    <?php
}
