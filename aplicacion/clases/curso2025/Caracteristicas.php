<?php

class Caracteristicas implements IteratorAggregate // es ina interfaz de php que permite ser recorrida con foreach
{
    private array $caracteristicas = [
        'ancho' => 100,
        'alto' => 100,
        'largo' => 100
    ];

    private bool $BloqueaNuevas = false;

    public function __construct(array $iniciales = [])
    {
        foreach ($iniciales as $clave => $valor) {
            $this->set($clave, $valor);
        }
    }

    public function set(string $clave, $valor): void
    {
        if (!array_key_exists($clave, $this->caracteristicas) && $this->BloqueaNuevas) {
            throw new Exception("No se pueden añadir nuevas características después de 'ningunamas'.");
        }

        if ($clave === 'ningunamas') {
            $this->BloqueaNuevas = true;
        }

        $this->caracteristicas[$clave] = $valor;
    }

    public function get(string $clave)
    {
        return $this->caracteristicas[$clave] ?? null;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->caracteristicas);
    }

    public function existe(string $clave): bool
    {
        return array_key_exists($clave, $this->caracteristicas);
    }
}
