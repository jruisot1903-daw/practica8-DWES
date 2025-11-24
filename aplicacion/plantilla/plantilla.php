<?php
if (!isset($_COOKIE['colorFondo'])) {
    setcookie('colorFondo', FONDO_DEFECTO, time() + (86400 * 30), "/"); // 30 días
}

if (!isset($_COOKIE['colorTexto'])) {
    setcookie('colorTexto', TEXTO_DEFECTO, time() + (86400 * 30), "/"); // 30 días
}

function paginaError($mensaje)
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
    echo "<a href='/'>Ir a la pagina principal</a>\n";

    finCuerpo();
}
function inicioCabecera($titulo)
{
?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="utf-8">
        <!-- Always force latest IE rendering engine (even in
intranet) & Chrome Frame
 Remove this if you use the .htaccess -->
        <meta http-equiv="X-UA-Compatible"
            content="IE=edge,chrome=1">
        <title><?php echo $titulo; ?></title>
        <meta name="description" content="">
        <meta name="author" content="Administrador">
        <meta name="viewport" content="width=device-width; initialscale=1.0">
        <link rel="icon" type="image/png" href="/favicon.png">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="stylesheet" type="text/css"
            href="/estilos/base.css">
    <?php
}
function finCabecera()
{
    ?>
    </head>
<?php
}

// Convierte un segmento de URL en una etiqueta legible
function prettyLabel(string $seg): string
{
    $label = urldecode($seg);
    $label = str_replace(['-', '_'], ' ', $label);
    return htmlspecialchars(mb_convert_case($label, MB_CASE_TITLE, "UTF-8"), ENT_QUOTES, 'UTF-8');
}

function inicioCuerpo($cabecera)
{
    global $ACCESO, $PATH;

    $cookie_colorFondo = $_COOKIE['colorFondo'];
    $cookie_colorTexto = $_COOKIE['colorTexto'];

    // Dividir el path en partes y filtrar las vacías
    $partes = array_values(array_filter(explode('/', trim($PATH, '/'))));

    // Eliminar 'index.php' si está presente al final
    $partes = array_filter($partes, fn($value) => $value !== 'index.php');
?>

    <body style="
        background-color:<?php echo COLORESFONDO[$cookie_colorFondo]; ?>;
        color: <?php echo COLORESTEXTO[$cookie_colorTexto]; ?>;
    ">
        <div id="documento">

            <header>
                <h1 id="titulo"><?php echo $cabecera; ?></h1>
            </header>

            <div id="barraLogin">
                <?php
                if ($ACCESO->hayUsuario()) {
                    $name = $ACCESO->getNombre();
                    echo "¡Hola!, $name | <a href='/aplicacion/acceso/logout.php'>Logout</a>";
                } else {
                    echo "<a href='/aplicacion/acceso/login.php'>Login</a>";
                }
                ?>
            </div>
            <div id="barraMenu">
                <ul>
                    <li><a href="/">Inicio</a></li>
                    <li><a href="/aplicacion/personalizar/personalizar.php">Personalizar</a></li>
                    <li><a href="/aplicacion/texto/verTextos.php">Ver Textos</a></li>
                </ul>
            </div>

            <nav class="breadcrumbs">
                <?php
                $total = count($partes);

                if ($total == 0) {
                    echo '<span aria-current="page">Inicio</span>';
                } else {
                    echo '<a href="/">Inicio</a>';
                }

                $acumulado = "";

                foreach ($partes as $i => $seg) {
                    $acumulado .= '/' . $seg;
                    // Escapar la URL y la etiqueta
                    $href = htmlspecialchars($acumulado . '/', ENT_QUOTES, 'UTF-8');
                    $label = prettyLabel($seg);
                    $esUltimo = ($i === $total - 1);

                    // Separador
                    echo '<span class="sep">›</span>';

                    // Elemento del breadcrumb
                    if ($esUltimo) {
                        echo '<span aria-current="page">' . $label . '</span>';
                    } else {
                        echo '<a href="' . $href . '">' . $label . '</a>';
                    }
                }
                ?>
            </nav>

            <br />
            <div>
            <?php
        }

        function finCuerpo()
        {
            ?>
                <br />
                <br />
            </div>
        </div>
    </body>

    </html>
<?php
        }
