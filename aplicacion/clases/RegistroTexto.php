<?php
class RegistroTexto {
    private string $texto;
    private DateTime $fechaHora;

    public function __construct(string $texto) {
        $this->texto = $texto;
        $this->fechaHora = new DateTime(); // hora del sistema
    }

    public function getTexto(): string {
        return $this->texto;
    }

    public function getFechaHora(): string {
        return $this->fechaHora->format("Y-m-d H:i:s");
    }
}
