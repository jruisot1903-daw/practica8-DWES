<?php
class RegistroTexto
{
    private string $_cadena;
    private DateTime $_fecha;

    public function __construct(string $cadena)
    {
        $this->_cadena = $cadena;
        $this->_fecha = new DateTime();
    }

    public function getCadena(): string
    {
        return $this->_cadena;
    }

    public function getFecha(): DateTime
    {
        return $this->_fecha;
    }

    public function __toString(): string
    {
        return $this->_cadena . " (registrado el " . $this->_fecha->format('Y-m-d H:i:s') . ")";
    }

    public function __set(string $nombre, $valor)
    {
        throw new Exception("No se pueden modificar los atributos de esta clase.");
    }

    public function __get(string $nombre)
    {
        throw new Exception("No se pueden acceder a los atributos de esta clase.");
    }

    public function __isset($name)
    {
        throw new Exception('No implementado');
    }

    public function __unset($name)
    {
        throw new Exception('No implementado');
    }
}
