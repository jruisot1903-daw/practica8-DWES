<?php
include_once(dirname(__FILE__) . "/cabecera.php");

require_once dirname(__FILE__) . "/aplicacion/clases/curso2025/Muebles.php"; 
require_once dirname(__FILE__) . "/aplicacion/clases/curso2025/MuebleBase.php";

// Dibuja la plantilla de la vista 
inicioCabecera("2DAW Tienda");
cabecera();
finCabecera();

inicioCuerpo("Tienda");
cuerpo(); // llamo a la vista
finCuerpo();

// **********************************************************
// Vista
function cabecera() {}

// Vista
function cuerpo()
{
    global $muebles; // usamos el array de muebles definido en Muebles.php
?>
    <h2>Listado de muebles</h2>
    <table border="1" cellpadding="6">
        <tr>
            <th>Índice</th>
            <th>Tipo</th>
            <th>Nombre</th>
            <th>Fabricante</th>
            <th>Origen</th>
            <th>Año</th>
        </tr>
        <?php foreach ($muebles as $indice => $mueble): ?>
            <tr>
                <td><?= $indice ?></td>
                <td><?= get_class($mueble) ?></td>
                <?php
                    $nombre = $fabricante = $origen = $anio = null;
                    $mueble->damePropiedad("nombre", 1, $nombre);
                    $mueble->damePropiedad("fabricante", 1, $fabricante);
                    $mueble->damePropiedad("pais", 1, $origen);
                    $mueble->damePropiedad("anio", 1, $anio);
                ?>
                <td><?= htmlspecialchars($nombre ?? "", ENT_QUOTES, "UTF-8") ?></td>
                <td><?= htmlspecialchars($fabricante ?? "", ENT_QUOTES, "UTF-8") ?></td>
                <td><?= htmlspecialchars($origen ?? "", ENT_QUOTES, "UTF-8") ?></td>
                <td><?= htmlspecialchars($anio instanceof DateTime ? $anio->format('Y') : $anio, ENT_QUOTES, "UTF-8") ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <hr>

    <form method="get">
        <label for="idMueble">Selecciona mueble:</label>
        <select name="idMueble" id="idMueble">
            <?php foreach ($muebles as $indice => $mueble): ?>
                <?php $nombre = null; $mueble->damePropiedad("nombre", 1, $nombre); ?>
                <option value="<?= $indice ?>">
                    <?= $indice ?> - <?= htmlspecialchars($nombre ?? "", ENT_QUOTES, "UTF-8") ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <button type="submit" formaction="aplicacion/principal/mostrarMueble.php">Mostrar Mueble</button>
        <button type="submit" formaction="aplicacion/principal/modificarMueble.php">Modificar Mueble</button>
    </form>
<?php
}
?>
