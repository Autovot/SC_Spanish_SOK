<?php

$rutaCarpetaEN = __DIR__ . "/../../data/Localization/english/";
$rutaCarpetaES = __DIR__ . "/../../data/Localization/spanish_(spain)/";

$archivoOriginal = file($rutaCarpetaEN . "global.ini");
$archivoNuevo = file($rutaCarpetaEN . "global.new.ini");
$archivoTraducido = file($rutaCarpetaES . "global.ini");

sort($archivoOriginal);
sort($archivoNuevo);
sort($archivoTraducido);

file_put_contents($rutaCarpetaEN . "/global.ini", $archivoOriginal);
file_put_contents($rutaCarpetaEN . "/global.new.ini", $archivoNuevo);
file_put_contents($rutaCarpetaES . "/global.ini", $archivoTraducido);