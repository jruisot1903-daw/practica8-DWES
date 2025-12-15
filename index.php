<?php
include_once(dirname(__FILE__) . "/cabecera.php");

inicioCabecera("Examen 1");
cabecera();
finCabecera();

inicioCuerpo("Examen 1");
cuerpo($COLECCIONES);
finCuerpo();

function cabecera() {}

function cuerpo($COLECCIONES)
{
    
    foreach($COLECCIONES as $valor){
        echo "<p>".$valor."</p>";
    }

    echo"<br>";
    echo "<button class='boton'><a href='aplicacion/colecciones/modificar.php'>Modificar</a></button>";
    echo "<button class='boton'><a href='aplicacion/colecciones/enviar.php'>Enviar</a></button>";
   

}


function cargarColeccionDesdeFichero(string $nombre){
    $ruta  = $_SERVER["DOCUMENT_ROOT"]."/Examen1ºEvaluacion/fichero";
    $datos = [];

    if(!file_exists($ruta))
        mkdir($ruta);

    $ruta .=$nombre;
    $fic = fopen($ruta,"r");
    if(!$fic)
        return false;

    while($linea = fgetc($fic)){
        $linea = str_replace("\r","",$linea);
        $linea = str_replace("\n","",$linea);

        if($linea != ""){
            $linea = explode(";",$linea);
            $datos[]= $linea;
        }
        fclose($fic);
        return true;
    }
}
