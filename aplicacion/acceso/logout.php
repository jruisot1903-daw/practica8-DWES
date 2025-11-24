<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

$ACCESO->quitarRegistroUsuario();
header("Location: /");
exit();
