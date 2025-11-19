<?php
include_once(dirname(__FILE__) . "/cabecera.php");


// Dibuja la plantilla de la vista 
inicioCabecera("2DAW Relacion8");
cabecera();
finCabecera();

inicioCuerpo("Relacion 8");
cuerpo(); // llamo a la vista
finCuerpo();

// **********************************************************
// Vista
function cabecera() {}

// Vista
function cuerpo()
{
  ?>
    <div id="barraMenu">
                <ul> 
                    <li>Opciones
                        <ul>
                            <li><a href="/aplicacion/personalizar/personalizar.php">Personalizar</a></li>
                            
                        </ul>
                
                </li>

                </ul>
            </div>
  <?php
}

