<?php
$rutaCarpeta = __DIR__."/../../data/Localization/english/";


$archivoOriginal = file($rutaCarpeta . "global.ini");
$archivoNuevo = file($rutaCarpeta . "global.new.ini");

sort($archivoOriginal);
sort($archivoNuevo);

file_put_contents($rutaCarpeta."/global.ini", $archivoOriginal);
file_put_contents($rutaCarpeta."/global.new.ini", $archivoNuevo);

?>