<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

if (!$acceso->hayUsuario()) {
    header("Location: /aplicacion/acceso/login.php");
    exit;
}
if (!$acceso->puedePermiso(1)) {
    paginaError("No tienes permiso para acceder a esta página");
    exit;
}
if (!$acceso->puedePermiso(2)) {
    paginaError("No tienes permiso para configurar los colores");
    exit;
}

// Si el formulario se envía, actualizar cookies
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["color_fondo"])) {
        setcookie("color_fondo", $_POST["color_fondo"], time() + 3600*24*30, "/");
        $_COOKIE["color_fondo"] = $_POST["color_fondo"]; // actualizar en esta carga
    }
    if (isset($_POST["color_texto"])) {
        setcookie("color_texto", $_POST["color_texto"], time() + 3600*24*30, "/");
        $_COOKIE["color_texto"] = $_POST["color_texto"];
    }
}


// Plantilla
inicioCabecera("Personalizar");
cabecera();
finCabecera();

inicioCuerpo("Personalizar");
cuerpo();
finCuerpo();

// Vista
function cabecera() {}

function cuerpo() {
    ?>
    <form method="post" action="personalizar.php">
        <label>Color de fondo:</label>
        <select name="color_fondo">
            <?php foreach(COLORESFONDO as $nombre=>$valor){ ?>
                <option value="<?= $valor ?>" <?= ($_COOKIE['color_fondo'] ?? "white") == $valor ? "selected" : "" ?>>
                    <?= $nombre ?>
                </option>
            <?php } ?>
        </select>
        <br><br>

        <label>Color de texto:</label>
        <select name="color_texto">
            <?php foreach(COLORESTEXTO as $nombre=>$valor){ ?>
                <option value="<?= $valor ?>" <?= ($_COOKIE['color_texto'] ?? "black") == $valor ? "selected" : "" ?>>
                    <?= $nombre ?>
                </option>
            <?php } ?>
        </select>
        <br><br>

        <input type="submit" value="Guardar preferencias">
    </form>
    <?php
}
