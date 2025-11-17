<?php
require_once __DIR__ . '/MuebleBase.php';


final class MuebleReciclado extends MuebleBase {
    private int $PorcentajeReciclado;

    public function __construct(
    string $nombre,
    string $fabricante = '',
    string $pais = 'España',
    string $anio = '2020',
    string $fechaIniVenta = '01/01/2020',
    string $fechaFinVenta = '31/12/2040',
    int $materialPrincipal = 1,
    float $precio = 30,
    int $porcentaje = 10
) {
    parent::__construct($nombre, $fabricante, $pais, $anio, $fechaIniVenta, $fechaFinVenta, $materialPrincipal, $precio);
    $this->setPorcentajeReciclado($porcentaje);
}

    public function setPorcentajeReciclado(int $porcentaje): void {
        if ($porcentaje < 0 || $porcentaje > 100) {
            throw new InvalidArgumentException("PorcentajeReciclado debe estar entre 0 y 100.");
        }else{
            $this->PorcentajeReciclado = $porcentaje;
        }
        
    }

    public function getPorcentajeReciclado(): int {
        return $this->PorcentajeReciclado;
    }

    public function __toString(): string {
        return parent::__toString() . ", PorcentajeReciclado: {$this->PorcentajeReciclado}";
    }
}