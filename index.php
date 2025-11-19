<?php
include_once(dirname(__FILE__) . "/cabecera.php");
//Controlador
if (!isset($_COOKIE['contador'])) {
    $contador = 1;
} else {
    $contador = $_COOKIE['contador'] + 1;
}
setcookie("contador", $contador, time() + 3600*24*30, "/");

// Dibuja la plantilla de la vista 
inicioCabecera("2DAW Relacion8");
cabecera();
finCabecera();

inicioCuerpo("Relacion 8");
cuerpo($contador); 
finCuerpo();

// **********************************************************
// Vista
function cabecera() {}

// Vista
function cuerpo($contador)
{
  ?>
    <div id="barraMenu">
        <ul> 
            <li>Opciones
                <ul>
                    <li><a href="/aplicacion/personalizar/personalizar.php">Personalizar</a></li>
                    <li><a href="/aplicacion/texto/verTextos.php">Ver Textos</a></li>
                </ul>
            </li>
        </ul>
    </div>

    <div id="contenido">
        <p>Has visitado esta página <strong><?= $contador ?></strong> veces.</p>
    </div>
  <?php
}
