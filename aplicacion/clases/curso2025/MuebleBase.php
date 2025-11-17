<?php
require_once(__DIR__ . '/../../../scripts/librerias/validacion.php');
require_once(__DIR__ . '/Caracteristicas.php');

abstract class MuebleBase
{
    public const MATERIALES_POSIBLES = [1 => "Madera", 2 => "Plastico", 3 => "Metal", 4 => "Roca"];
    public const MAXIMO_MUEBLES = 100;
    private static int $mueblesCreados = 0;

    private string $nombre;
    private string $fabricante;
    private string $pais;
    private DateTime $anio;
    private ?DateTime $fechaIniVenta = null;
    private ?DateTime $fechaFinVenta = null;
    private int $materialPrincipal;
    private float $precio;

    private Caracteristicas $carecteristicas;

    protected static function incrementarContador(): void
    {
        if (self::$mueblesCreados < self::MAXIMO_MUEBLES) {
            self::$mueblesCreados++;
        } else {
            throw new Exception("Se ha alcanzado el número máximo de muebles permitidos.");
        }
    }

    public static function getMueblesCreados(): int
    {
        return self::$mueblesCreados;
    }

    public static function puedeCrear(&$numero): bool
    {
        $numero = self::MAXIMO_MUEBLES - self::$mueblesCreados;
        return $numero > 0;
    }

    public function __construct($nombre, $fabricante = '', $pais = 'España', $anio = '2020', $fechaIniVenta = '01/01/2020', $fechaFinVenta = '31/12/2040', $materialPrincipal = 1, $precio = 30)
    {
        if (!self::puedeCrear($restantes)) {
            throw new Exception("No se pueden crear más muebles. Límite alcanzado.");
        }

        if (!$this->setNombre($nombre)) {
            throw new Exception("Nombre inválido.");
        }

        $this->setFabricante($fabricante) ?: $this->setFabricante('FMu:');
        $this->setPais($pais) ?: $this->setPais('España');
        $this->setAnio($anio) ?: $this->setAnio('2020');
        $this->setFechaIniVenta($fechaIniVenta) ?: $this->setFechaIniVenta('01/01/2020');
        $this->setFechaFinVenta($fechaFinVenta) ?: $this->setFechaFinVenta('31/12/2040');
        $this->setMaterialPrincipal($materialPrincipal) ?: $this->setMaterialPrincipal(1);
        $this->setPrecio($precio) ?: $this->setPrecio(30);
        $this->carecteristicas = new Caracteristicas();

        self::incrementarContador();
    }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre)
    {
        if (validaCadena($nombre, 40, "")) {
            $this->nombre = strtoupper($nombre);
            return true;
        }
        return false;
    }

    public function getFabricante() { return $this->fabricante; }
    public function setFabricante($fabricante)
    {
        if (mb_strpos($fabricante, 'FMu:') !== 0) {
            $fabricante = 'FMu:' . $fabricante;
        }
        if (validaCadena($fabricante, 30, "FMu:")) {
            $this->fabricante = $fabricante;
            return true;
        }
        return false;
    }

    public function getPais() { return $this->pais; }
    public function setPais($pais)
    {
        if (validaCadena($pais, 20, "España")) {
            $this->pais = $pais;
            return true;
        }
        return false;
    }

    public function getAnio() { return $this->anio; }
    public function setAnio($anio)
    {
        $anioActual = (int)date('Y');
        if (is_numeric($anio) && $anio >= 2020 && $anio <= $anioActual) {
            $this->anio = DateTime::createFromFormat('d/m/Y', "01/01/$anio");
            return true;
        }
        return false;
    }

    public function getFechaIniVenta()
    {
        return $this->fechaIniVenta;
    }

    public function setFechaIniVenta($fechaIniVenta)
    {
        $fecha = DateTime::createFromFormat('d/m/Y', $fechaIniVenta);
        if ($fecha && $fecha >= $this->anio) {
            $this->fechaIniVenta = $fecha;
            return true;
        }
        return false;
    }

    public function getFechaFinVenta()
    {
        return $this->fechaFinVenta;
    }

    public function setFechaFinVenta($fechaFinVenta)
    {
        $fecha = DateTime::createFromFormat('d/m/Y', $fechaFinVenta);
        if ($fecha && $this->fechaIniVenta && $fecha >= $this->fechaIniVenta) {
            $this->fechaFinVenta = $fecha;
            return true;
        }
        return false;
    }

    public function getMaterialPrincipal() { return $this->materialPrincipal; }
    public function setMaterialPrincipal($materialPrincipal)
    {
        if (array_key_exists($materialPrincipal, self::MATERIALES_POSIBLES)) {
            $this->materialPrincipal = $materialPrincipal;
            return true;
        }
        return false;
    }

    public function getPrecio() { return $this->precio; }
    public function setPrecio($precio)
    {
        if (is_numeric($precio) && $precio >= 30) {
            $this->precio = (float)$precio;
            return true;
        }
        return false;
    }

    public function getMaterialDescripcion(): string
    {
        return self::MATERIALES_POSIBLES[$this->materialPrincipal] ?? "Desconocido";
    }

    public function dameListaPropiedades(): array
    {
        return [
            'nombre', 'fabricante', 'pais', 'anio',
            'fechaIniVenta', 'fechaFinVenta',
            'materialPrincipal', 'precio'
        ];
    }

    public function damePropiedad(string $propiedad, int $modo, mixed &$res): bool
    {
        if (!in_array($propiedad, $this->dameListaPropiedades())) {
            return false;
        }

        if ($modo === 1) {
            $metodo = 'get' . ucfirst($propiedad);
            if (method_exists($this, $metodo)) {
                try {
                    $res = $this->$metodo();
                    return true;
                } catch (Error $e) {
                    $res = null;
                    return false;
                }
            }
        } elseif ($modo === 2) {
            if (property_exists($this, $propiedad)) {
                try {
                    $res = $this->$propiedad;
                    return true;
                } catch (Error $e) {
                    $res = null;
                    return false;
                }
            } else {
                $metodo = 'get' . ucfirst($propiedad);
                if (method_exists($this, $metodo)) {
                    try {
                        $res = $this->$metodo();
                        return true;
                    } catch (Error $e) {
                        $res = null;
                        return false;
                    }
                }
            }
        }

        return false;
    }

    public function anadir(...$args): void
    {
        $total = count($args);
        if ($total < 2) return;

        if ($total % 2 != 0) {
            array_pop($args);
        }

        for ($i = 0; $i < count($args); $i += 2) {
            $clave = $args[$i];
            $valor = $args[$i + 1];
            $this->carecteristicas->set($clave, $valor);
        }
    }

    public function exportarCaracteristicas(): string
    {
        $result = "";
        foreach ($this->carecteristicas as $clave => $valor) {
            $result .= "$clave:$valor ";
        }
        return trim($result);
    }

    public function __toString(): string
    {
        $base = "MUEBLE de clase " . get_class($this) .
            " con nombre {$this->getNombre()}, fabricante {$this->getFabricante()}, fabricado en {$this->getPais()} a partir del año {$this->getAnio()->format('Y')}";

        if ($this->fechaIniVenta && $this->fechaFinVenta) {
            $base .= ", vendido desde {$this->fechaIniVenta->format('d/m/Y')} hasta {$this->fechaFinVenta->format('d/m/Y')}";
        }

        $base .= ", precio {$this->getPrecio()} de material {$this->getMaterialDescripcion()}";

        $caracs = $this->exportarCaracteristicas();
        return $base . "\nCaracterísticas:\n" . $caracs;
    }

}
