<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

if (!$ACCESO->puedePermiso(2)) {
    paginaError("No tienes permisos para personalizar la aplicación.");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['colorFondo']) || !isset($_POST['colorTexto'])) {
        die("Faltan datos en el formulario.");
    }

    setcookie('colorFondo', $_POST['colorFondo'], time() + (86400 * 30), "/"); // 30 días
    setcookie('colorTexto', $_POST['colorTexto'], time() + (86400 * 30), "/"); // 30 días

    // Actualizar la página para aplicar los cambios
    header("Location: /aplicacion/personalizar/personalizar.php");
    exit();
}

inicioCabecera("Personalizar");
cabecera();
finCabecera();

inicioCuerpo("Personalizar");
cuerpo();
finCuerpo();

function cabecera() {}

function cuerpo()
{
?>
    <h1>Personalizar la Aplicación</h1>
    <p>Aquí puedes personalizar la aplicación según tus preferencias.</p>
    <form action="" method="post">
        <label for="colorFondo">Color de Fondo:</label>
        <select name="colorFondo" id="colorFondo">
            <?php
            foreach (COLORESFONDO as $nombre => $valor) {
                $selected = ($nombre === $_COOKIE['colorFondo']) ? 'selected' : '';
                echo "<option value='$nombre' $selected>$nombre</option>";
            }
            ?>
        </select>
        <br>
        <label for="colorTexto">Color de Texto:</label>
        <select name="colorTexto" id="colorTexto">
            <?php
            foreach (COLORESTEXTO as $nombre => $valor) {
                $selected = ($nombre === $_COOKIE['colorTexto']) ? 'selected' : '';
                echo "<option value='$nombre' $selected>$nombre</option>";
            }
            ?>
        </select>
        <br>
        <input type="submit" value="Guardar">
    </form>
<?php
}
