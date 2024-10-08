<?php
$archivoOriginal = file(__DIR__ . "/../../data/Localization/english/global.final.ini");
$archivoTraducido = file(__DIR__ . "/../../data/Localization/spanish_(spain)/global.ini");
$archivo = fopen(__DIR__ . "/diferencias.txt", "a");
foreach ($archivoOriginal as $numLinea => $contenidoLinea) {
    $partesOriginal = explode("=", $contenidoLinea);
    $partesTraducida = explode("=", $archivoTraducido[$numLinea]);

    if ($partesOriginal[0] != $partesTraducida[0]) {
        echo "\n---------------------------------------------";
        echo "\nLa línea " . ($numLinea + 1) . " no coincide\n";
        echo "ORIGINAL     : \033[0;31m" . $partesOriginal[0] . "\033[0m\n";
        echo "TRADUCCIÓN   : \033[0;31m" . $partesTraducida[0] . "\033[0m";
        echo "\n---------------------------------------------\n\n";

        // Guardammos (append) los cambios en un txt

        fwrite($archivo, "---------------------------------------------\n");
        fwrite($archivo, "La línea " . ($numLinea + 1) . " no coincide\n");
        fwrite($archivo, "ORIGINAL     : " . $partesOriginal[0] . "\n");
        fwrite($archivo, "TRADUCCIÓN   : " . $partesTraducida[0]);
        fwrite($archivo, "\n---------------------------------------------\n");

        break;
    }
}
