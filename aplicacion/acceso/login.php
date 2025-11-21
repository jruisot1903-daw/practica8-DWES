<?php
include_once("../../cabecera.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nick = $_POST["nick"] ?? "";
    $password = $_POST["password"] ?? "";

    if ($acl->validarUsuario($nick, $password)) {
        $nombre = $acl->getNombreUsuario($nick);
        $permisos = $acl->getPermisosUsuario($nick);
        $acceso->registrarUsuario($nick, $nombre, $permisos);
        header("Location: /index.php");
        exit;
    } else {
        echo "Usuario o contraseña incorrectos";
    }
}
?>
<form method="post">
    Nick: <input type="text" name="nick"><br>
    Contraseña: <input type="password" name="password"><br>
    <input type="submit" value="Entrar">
</form>
