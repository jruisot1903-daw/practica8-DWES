<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");
require_once dirname(__FILE__) . "/../clases/curso2025/Muebles.php";
require_once dirname(__FILE__) . "/../clases/curso2025/MuebleBase.php";

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
    global $muebles;
    $id = $_GET['idMueble'] ?? null;

if (!is_numeric($id) || !isset($muebles[$id])) {
    echo "<p>Mueble no válido.</p>";
    echo '<a href="index.php">Volver</a>';
    exit;
}

$mueble = $muebles[$id];
$materiales = MuebleBase::MATERIALES_POSIBLES;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errores = [];
    $nuevo = clone $mueble;

    if (!$nuevo->setNombre($_POST['nombre'] ?? '')) {
        $errores[] = "Nombre inválido.";
    }
    if (!$nuevo->setFabricante($_POST['fabricante'] ?? '')) {
        $errores[] = "Fabricante inválido.";
    }

    $pais = $_POST['pais'] ?? '';
    if (strlen($pais) < 2 || strlen($pais) > 20) {
        $errores[] = "País inválido.";
    }

    $precio = $_POST['precio'] ?? 0;
    if (!is_numeric($precio) || $precio < 30) {
        $errores[] = "Precio inválido.";
    }

    $materialNombre = $_POST['material'] ?? '';
    $materialId = array_search($materialNombre, $materiales);
    if ($materialId === false) {
        $errores[] = "Material inválido.";
    }

    if (empty($errores)) {
        $nuevo->setPais($pais);
        $nuevo->setPrecio($precio);
        $nuevo->setMaterialPrincipal($materialId);

        echo "<h2>Mueble modificado</h2>";
        echo "<pre>" . htmlspecialchars((string)$nuevo) . "</pre>";
        echo '<a href="index.php">Volver</a>';
        exit;
    } else {
        echo "<p>Errores:</p><ul>";
        foreach ($errores as $e) echo "<li>$e</li>";
        echo "</ul>";
    }
}

// Mostrar formulario
$nombre = $fabricante = $pais = $precio = $material = null;
$mueble->damePropiedad("nombre", 1, $nombre);
$mueble->damePropiedad("fabricante", 1, $fabricante);
$mueble->damePropiedad("pais", 1, $pais);
$mueble->damePropiedad("precio", 1, $precio);
$mueble->damePropiedad("materialPrincipal", 1, $materialId);
$material = $materiales[$materialId] ?? '';

?>

<h2>Modificar mueble #<?= $id ?> (<?= get_class($mueble) ?>)</h2>
<form method="post">
    <label>Nombre: <input type="text" name="nombre" value="<?= htmlspecialchars((string)$nombre) ?>"></label><br>
    <label>Fabricante: <input type="text" name="fabricante" value="<?= htmlspecialchars((string)$fabricante) ?>"></label><br>
    <label>País: <input type="text" name="pais" value="<?= htmlspecialchars((string)$pais) ?>"></label><br>
    <label>Precio: <input type="number" name="precio" value="<?= htmlspecialchars((string)$precio) ?>"></label><br>
    <label>Material:
        <select name="material">
            <?php foreach ($materiales as $id => $nombreMat): ?>
                <option value="<?= htmlspecialchars((string)$nombreMat) ?>" <?= (string)$material === $nombreMat ? 'selected' : '' ?>><?= htmlspecialchars($nombreMat) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br><br>
    <button type="submit">Guardar cambios</button>
</form>

<?php
}

