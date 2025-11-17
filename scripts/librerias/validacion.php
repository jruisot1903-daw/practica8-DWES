<?php   

/*Ejercicio 1 */

function validaEntero(int &$var, int $min, int $max, int $defecto): bool
{  // creamos un array con los valores maximos y los minimos , para comprobar posteriormente si el numero esta entre ellos 
    $option = array(
        'options' => array(
            'min_range' => $min,
            'max_range' => $max,
        )
    );

    if(filter_var($var, FILTER_VALIDATE_INT,$option) !== FALSE){
        return true;
    }else {
        $var = $defecto;
        return false;
    }
}

function validaReal(float &$var, float $min, float $max, float $defecto): bool
{
    $option = array(
        'options' => array(
            'min_range' => $min,
            'max_range' => $max,
        )
    );

    if(filter_var($var, FILTER_VALIDATE_FLOAT,$option) !== FALSE){
        return true;
    }else {
        $var = $defecto;
        return false;
    }

}

function validaFecha(string &$var, string $defecto): bool
{
    // Separar la fecha por "/"
    $fec = mb_split("/", $var);

    // Validar que tenga exactamente 3 partes
    if (count($fec) == 3) {
        $dia = (int)$fec[0];
        $mes = (int)$fec[1];
        $anio = (int)$fec[2];

        // Verificar si la fecha es válida
        if (checkdate($mes, $dia, $anio)) {
            // sprintf lo utilizamos para darle formato en la salida de la fecha
            $var = sprintf("%02d/%02d/%04d", $dia, $mes, $anio);
            return true;
        }
    }
    $var = $defecto;
    return false;
}

function validaHora(string &$var, string $defecto): bool
{
    $tiempo = mb_split(":", $var);

    if (count($tiempo) === 3) {
        $hora = (int) $tiempo[0];
        $min = (int) $tiempo[1];
        $seg = (int) $tiempo[2];

        if (
            $hora >= 0 && $hora <= 23 &&
            $min >= 0 && $min <= 59 &&
            $seg >= 0 && $seg <= 59
        ) {
            //sprintf lo utilizamos para formatear la salida de la hora 
            $var = sprintf('%02d:%02d:%02d', $hora, $min, $seg);
            return true;
        }
    }
    $var = $defecto;
    return false;
}

function validaEmail(string &$var, string $defecto): bool{

    if(filter_var($var,FILTER_VALIDATE_EMAIL)){
        return true;
    }else{
        $var = $defecto;
        return false;
    }
}

function validaCadena(string &$var, int $long, string $defecto): bool
{
    if (strlen($var) <= $long) {
        return true;
    } else {
        $var = $defecto;
        return false;
    }
}

function validaExpresion(string &$var, string $expresion, string $defecto) {
    
    $opciones = [
        "options" => [
            "regexp" => $expresion
        ]
    ];


    if(filter_var($var,FILTER_VALIDATE_REGEXP,$opciones)){
        return true;
    }else{
        $var = $defecto;
        return false;
    }
}

function validaRango(mixed $var, array $posibles, int $tipo = 1): bool {
    if ($tipo === 1) {
        // Compara con los valores del array
        foreach ($posibles as $valor) {
            if ($valor === $var) {
                return true;
            }
        }
    } elseif ($tipo === 2) {
        // Compara con las claves del array
        foreach (array_keys($posibles) as $clave) {
            if ($clave === $var) {
                return true;
            }
        }
    }

    return false;
}

