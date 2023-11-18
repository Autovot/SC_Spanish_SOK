<?php
$archivoOriginal = file(__DIR__."/original.ini");
$archivoTraducido = file(__DIR__."/global.ini");

foreach($archivoOriginal as $numLinea => $contenidoLinea)
{
    $partesOriginal = explode("=",$contenidoLinea);
    $partesTraducida = explode("=",$archivoTraducido[$numLinea]);

    if ($partesOriginal[0] != $partesTraducida[0])
    {
        echo "\n---------------------------------------------";
        echo "\nLa línea ".($numLinea+1)." no coincide\n";
        echo "ORIGINAL     : \033[0;31m".$partesOriginal[0]."\033[0m\n";
        echo "TRADUCCIÓN   : \033[0;31m".$partesTraducida[0]."\033[0m";
        echo "\n---------------------------------------------\n\n";
        break;
    }
}
?>