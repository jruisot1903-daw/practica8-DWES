<?php
function paginaError(string $mensaje)
{
    header("HTTP/1.0 404 $mensaje");
    inicioCabecera("PRACTICA");
    finCabecera();
    inicioCuerpo("ERROR");
    echo "<br />\n";
    echo $mensaje;
    echo "<br />\n";
    echo "<br />\n";
    echo "<br />\n";
    echo "<a href='/index.php'>Ir a la pagina principal</a>\n";

    finCuerpo();
}
function inicioCabecera(String $titulo)
{
?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="utf-8">
        <!-- Always force latest IE rendering engine (even inintranet) & Chrome Frame
        Remove this if you use the .htaccess -->
        <meta http-equiv="X-UA-Compatible"
            content="IE=edge,chrome=1">
        <title><?php echo $titulo ?></title>
        <meta name="description" content="">
        <meta name="author" content="Administrador">
        <meta name="viewport" content="width=device-width; initialscale=1.0">
        <!-- Replace favicon.ico & apple-touch-icon.png in the root
of your domain and delete these references -->
        <link rel="icon" type="image/png" href="/favicon.png">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="stylesheet" type="text/css" href="/estilos/base.css">
    <?php
}
function finCabecera()
{
    ?>
    </head>
<?php
}
function prettyLabel(string $seg): string
{
    $label = urldecode($seg);
    $label = str_replace(['-', '_'], ' ', $label);
    return htmlspecialchars(mb_convert_case($label, MB_CASE_TITLE, "UTF-8"), ENT_QUOTES, 'UTF-8');
}
function inicioCuerpo(String $cabecera)
{
    global $acceso;
    require_once(dirname(__FILE__) . "/../../cabecera.php");
    inicio_cuerpo();    
    global $acceso;
    $url = $_SERVER['REQUEST_URI'];
    $path = parse_url($url, PHP_URL_PATH);
    $partes = array_values(array_filter(explode("/", trim($path))));
?>

    <body>
        <div id="documento">

            <header>
                <h1 id="titulo"><?php echo $cabecera; ?></h1>

                <nav class="barraUbi" aria-label="Ruta de navegación">
                    <?php
                    $partes = array_filter($partes, fn($v) => $v !== 'index.php');
                    $total = count($partes);

                    if ($total == 0) {
                        echo '<span aria-current="page">Inicio</span>';
                    } else {
                        echo '<a href="/">Inicio</a>';
                    }

                    $acumulado = "";

                    foreach ($partes as $i => $seg) {
                        $acumulado .= '/' . $seg;
                        $href = htmlspecialchars($acumulado . '/', ENT_QUOTES, 'UTF-8');
                        $label = prettyLabel($seg);
                        $esUltimo = ($i === $total - 1);

                        echo '<span class="sep">››</span>';
                        if ($esUltimo) {
                            echo '<span aria-current="page">' . $label . '</span>';
                        } else {
                            echo '<a href="' . $href . '">' . $label . '</a>';
                        }
                    }
                    ?>
                </nav>
            </header>

            <div id="barraLogin">
        <?php if ($acceso->hayUsuario()): ?>
            Bienvenido, <?= htmlspecialchars($acceso->getNombre()); ?>
            | <a href="/aplicacion/acceso/logout.php">Cerrar sesión</a>
        <?php else: ?>
            <a href="/aplicacion/acceso/login.php">Login</a>
        <?php endif; ?>
            </div>
            <div id="barraMenu">
                <ul>
                    <li><a href="/">Inicio</a></li>
                </ul>
            </div>

            <div>
            <?php
        }
        function finCuerpo()
        {
            ?>
                <br />
                <br />
            </div>
            <footer>
                <hr width="90%" />
                <div>
                    &copy; Copyright by Javier Ruiz Soto - 2DAW
                </div>
            </footer>
        </div>
    </body>

    </html>
<?php
        }
