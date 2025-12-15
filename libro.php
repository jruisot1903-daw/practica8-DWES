<?php
class Libro implements Iterator {
    private $_nombre;
    private $_autor;
    private $_otras = [];

    private $posicion = 0;
    private $claves = [];

    public function __construct(string $nombre, string $autor, ...$propiedades) {
        if (is_string($nombre)) {
            $this->_nombre = $nombre;
        }
        if (is_string($autor)) {
            $this->_autor = $autor;
        }

        for ($i = 0; $i < count($propiedades); $i += 2) {
            if (isset($propiedades[$i + 1])) {
                $clave = $this->normalizarClave($propiedades[$i]);
                $valor = $propiedades[$i + 1];
                $this->_otras[$clave] = $valor;
            }
        }

        $this->claves = array_merge(['nombre', 'autor'], array_keys($this->_otras));
    }

    private function normalizarClave($clave) {
        $clave = strtolower($clave);
        $ultimaMayus = strtoupper(substr($clave, -1));
        return substr($clave, 0, -1) . $ultimaMayus;
    }

    public function __get($prop) {
        $propNormalizada = strtolower($prop);
        if ($propNormalizada === 'nombre') return $this->_nombre;
        if ($propNormalizada === 'autor') return $this->_autor;

        $clave = $this->normalizarClave($propNormalizada);
        return $this->_otras[$clave] ?? null;
    }

    // Métodos de Iterator
    public function current() {
        $clave = $this->claves[$this->posicion];
        return $this->$clave;
    }

    public function key() {
        return strtolower($this->claves[$this->posicion]);
    }

    public function next() {
        ++$this->posicion;
    }

    public function rewind() {
        $this->posicion = 0;
    }

    public function valid() {
        return isset($this->claves[$this->posicion]);
    }
}
