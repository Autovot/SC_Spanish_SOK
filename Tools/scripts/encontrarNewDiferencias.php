<?php
$archivoOriginal = file(__DIR__ . "/../../data/Localization/english/global.ini");
$archivoNuevo = file(__DIR__ . "/../../data/Localization/english/global.new.ini");
foreach ($archivoOriginal as $numLinea => $contenidoLinea) {
    if ($contenidoLinea != $archivoNuevo[$numLinea]) {
        echo "\n---------------------------------------------";
        echo "\nLa línea " . ($numLinea + 1) . " no coincide\n";
        echo "\033[0;33m[ORIGINAL]\033[0m : \033[0;31m" . $contenidoLinea . "\033[0m\n";
        echo "\033[0;36m[NUEVO]\033[0m    : \033[0;31m" . $archivoNuevo[$numLinea] . "\033[0m";
        echo "\n---------------------------------------------\n\n";
        break;
    }
}
