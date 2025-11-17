<?php
require_once __DIR__ . '/MuebleBase.php';


final class MuebleTradicional extends MuebleBase {
    private float $Peso;
    private string $Serie;

     public function __construct(
        string $nombre,
        string $fabricante = '',
        string $pais = 'España',
        string $anio = '2020',
        string $fechaIniVenta = '01/01/2020',
        string $fechaFinVenta = '31/12/2040',
        int $materialPrincipal = 1,
        float $precio = 30,
        float $peso = 15.0,
        string $serie = "S01"
    ) {
        parent::__construct($nombre, $fabricante, $pais, $anio, $fechaIniVenta, $fechaFinVenta, $materialPrincipal, $precio);
        $this->setPeso($peso);
        $this->setSerie($serie);
    }

    public function setPeso(float $peso): void {
        if ($peso < 15 || $peso > 300) {
            throw new InvalidArgumentException("Peso debe estar entre 15 y 300.");
        }
        $this->Peso = $peso;
    }

    public function getPeso(): float {
        return $this->Peso;
    }

    public function setSerie(string $serie): void {
        $this->Serie = $serie;
    }

    public function getSerie(): string {
        return $this->Serie;
    }

    public function __toString(): string {
        return parent::__toString() . ", Peso: {$this->Peso}, Serie: {$this->Serie}";
    }
}