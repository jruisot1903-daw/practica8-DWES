<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

$textos = $_SESSION['textos'] ?? [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nuevoTexto'])) {
        $texto = trim($_POST['nuevoTexto']);

        if (empty($texto)) {
            $errors[] = "El texto no puede estar vacío.";
        } else {
            $registro = new RegistroTexto($texto);
            $textos[] = $registro;
            $_SESSION['textos'] = $textos;
        }
    } elseif (isset($_POST['limpiar'])) {
        unset($_SESSION['textos']);
        $textos = [];
    }
} else {
    if (isset($_SESSION['textos'])) {
        $textos = $_SESSION['textos'];
    }
}

inicioCabecera("Registro de Textos");
cabecera();
finCabecera();

inicioCuerpo("Registro de Textos");
cuerpo($textos, $errors);
finCuerpo();

function cabecera() {}

function cuerpo($textos, $errors)
{
?>
    <h1>Registro de Textos</h1>
    <p>Aquí puedes ver los textos registrados en la aplicación.</p>
    <textarea rows="10" cols="50" readonly>
    <?php
    foreach ($textos as $texto) {
        echo $texto . "\n";
    }
    ?>
    </textarea>
    <form action="" method="post">
        <label for="nuevoTexto">Nuevo Texto:</label>
        <input type="text" id="nuevoTexto" name="nuevoTexto" required>
        <button type="submit">Agregar Texto</button>
    </form>
    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
    ?>
    <form action="" method="post" style="margin-top:10px;">
        <button type="submit" name="limpiar" value="1">Limpiar Textos</button>
    </form>
<?php
}
