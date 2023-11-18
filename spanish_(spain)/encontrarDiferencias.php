<?php
$archivoOriginal = file(__DIR__."/original.ini");
$archivoTraducido = file(__DIR__."/global.ini");

foreach($archivoOriginal as $numLinea => $contenidoLinea)
{
    $hayError = false;
    
    $partesOriginal = explode("=",$contenidoLinea);
    $partesTraducida = explode("=",$archivoTraducido[$numLinea]);

    if ($partesOriginal[0] != $partesTraducida[0])
    {
        $hayError = true;
        echo "\n------------------------------------------------------------------";
        echo "\n\033[0;33mLa línea ".($numLinea+1)." no coincide\033[0m\n";
        echo "TRADUCCIÓN   : \033[0;31m".$partesTraducida[0]."\033[0m";
        echo "\nORIGINAL     : \033[0;32m".$partesOriginal[0]."\033[0m";
        echo "\n------------------------------------------------------------------\n\n";
        break;
    }
}

if (!$hayError)
{
    echo "\n\n\033[0;32m¡Enhorabuena!, no hay más errores.\033[0m\n\n";
}
?>