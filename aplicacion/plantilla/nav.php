<?php
function navigator($items)
{
    echo "<ul>";
    foreach ($items as $texto => $enlace) {
        echo "<li><a href='$enlace'>$texto</a></li>";
    }
    echo "</ul>";
}
