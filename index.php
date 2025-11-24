<?php
include_once(dirname(__FILE__) . "/cabecera.php");

// calcular visitas actuales
$visitas = isset($_COOKIE['visitas']) ? $_COOKIE['visitas'] + 1 : 1;

// guardar cookie para próximas visitas
setcookie('visitas', $visitas, time() + (86400 * 30), "/"); // 30 días

inicioCabecera("Relacion 8");
cabecera();
finCabecera();

inicioCuerpo("Relacion 8");
cuerpo($visitas);
finCuerpo();

function cabecera() {}

function cuerpo($visitas)
{
?>
    <h1>Relacion 8</h1>
    <p>Has visitado esta página <?php echo $visitas; ?> veces.</p>
<?php
}
