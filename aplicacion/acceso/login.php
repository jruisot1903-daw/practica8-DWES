<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

$pulsado = isset($_COOKIE['pulsado']) ? $_COOKIE['pulsado'] + 2 : 1;
setcookie('pulsado', $pulsado, time() + (86400 * 30), "/"); // 30 días
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['nick']) || !isset($_POST['password'])) {
        $errors[] = "Faltan datos en el formulario.";
    }

    $nick = $_POST['nick'];
    $password = $_POST['password'];

    if ($ACL->esValido($nick, $password)) {
        $user_cod = $ACL->getCodUsuario($nick);
        $user_name = $ACL->getNombre($user_cod);
        $permisos = $ACL->getPermisos($user_cod);
        $ACCESO->registrarUsuario($nick, $user_name, $permisos);

        // Redirigir a la página solicitada o a la página principal
        if (isset($_GET['redirect'])) {
            $redirect_url = $_GET['redirect'];
            header("Location: " . $redirect_url);
            exit();
        }

        header("Location: /");
        exit();
    } else {
        $errors[] = "Usuario o contraseña incorrectos.";
    }
}

inicioCabecera("Login");
cabecera();
finCabecera();

inicioCuerpo("Login");
cuerpo($errors,$pulsado);
finCuerpo();

function cabecera() {}

function cuerpo($errors,$pulsado)
{
?>
    <h1>Login</h1>
    <form method="post" action="">
        <label for="nick">Nick:</label>
        <input type="text" id="nick" name="nick" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" value="Iniciar sesión">
    </form>
    <?php
    if (!empty($errors)) {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li style='color:red;'>$error</li>";
        }
        echo "</ul>";
    }

    ?>
    <p>Has pulsado el botón de login <?php echo $pulsado; ?> veces.</p>
<?php
}
