<?php

//---------------------------------------------------------------------------------------------------------------------
//--     _____  _____        _               _   _  _____         _____     _______ _____ _    _ ______ _____        --
//--    / ____|/ ____|      | |        /\   | \ | |/ ____|       |  __ \ /\|__   __/ ____| |  | |  ____|  __ \       --
//--    | (___| |           | |       /  \  |  \| | |  __        | |__) /  \  | | | |    | |__| | |__  | |__) |      --
//--    \___ \| |           | |      / /\ \ | . ` | | |_ |       |  ___/ /\ \ | | | |    |  __  |  __| |  _  /       --
//--    ____) | |____       | |____ / ____ \| |\  | |__| |       | |  / ____ \| | | |____| |  | | |____| | \ \       --
//--    |_____/ \_____|     |______/_/    \_\_| \_|\_____|       |_| /_/    \_\_|  \_____|_|  |_|______|_|  \_\      --
//--                                                                                                                 --
//--                                            By ZZETTAZZ & AUTOVOT                                                --
//---------------------------------------------------------------------------------------------------------------------


// COMMAND : php scLangPatcher.php <LogCopyPasteFormat? true|false> <Generate Logs File Only? true|false>


// CONFIG (ONLY TOUCH THIS)

// ES (Spanish) || EN (English)
$language = "en";

$crearLogs = true;                  // CREATE LOGS FILE ¿?

$archivoOriginal = "../../data/Localization/english/global.ini";    // ORIGINAL / OLD INI FILE
$archivoNuevo = "../../data/Localization/english/global.new.ini";   // NEW PATCH INI FILE
$archivoFinal = "../../data/Localization/english/global.final.ini"; // FINAL INI FILE (WITH ALL CHANGES)

$archivoNewLinesLog = "newLines.txt";
$archivoDeletedLinesLog = "deletedLines.txt";
$archivoModifiedLinesLog = "modifiedLines.txt";
$onlyLogs = false;                  // GENERATE ONLY LOG FILE? (LESS PROCESSING TIME)
$logCopyPasteFormat = false;         // TRUE IF WANT "VARIABLE=VALUE", FALSE IF WANT "[VARIABLE] => VALUE"
// YOU CAN LEAVE IT BLANK IF $crearLogs = false


$debugLevel = 2;                    // DEBUG LEVEL = 1,2,3 (2 RECOMENDED FOR NORMAL USE)
// LEVEL 1 = ONLY NECESARY CONSOLE LINES
// LEVEL 2 = MORE LINES (ONLY USE IF SOMETHING NOT WORKING)
// LEVEL 3 = CRAZY (LOT OF LOGS (RECOMENDED FOR DEVELOPING))
// LEVEL 4 = SICK DEBUG (USE DONT RECOMENDED)

// VARIABLE DECLARATION
// -----------------------------
// --  DONT TOUCH BELOW HERE  --
// -----------------------------
// UNLES YOU KNOW WHAT YOU DOING
// (VARIABLE LIST)

$originalFile;
$newFile;
$finalFileAsociativeArray;

$newLines;
$deletedLines;
$changedLines;
$logNewFile;
$logDeleteFile;
$logModifiedFile;

$logNewContent;
$logDeletedContent;
$logModifiedContent;

$newFileAsociativeArray;
$originalFileAsociativeArray;

$finalFile;
$finalContent;
$counter;

// DECLARATIONS

if (isset($argv[1]))
{
    if (strtoupper(trim($argv[1])) == "TRUE") $logCopyPasteFormat = true;
    elseif (strtoupper((trim($argv[1])) == "FALSE")) $logCopyPasteFormat = false;
}

if (isset($argv[2]))
{
    if (strtoupper(trim($argv[2])) == "TRUE") $onlyLogs = true;
    elseif (strtoupper((trim($argv[2])) == "FALSE")) $onlyLogs = false;
}

if ($language != "es" && $language != "en") {
    echo ("Invalid language selected");
    die();
}

$json_string = file_get_contents(__DIR__ . "/lang/" . $language . ".json");
$translate = json_decode($json_string, true);

// if (
//     ($archivoOriginal == $archivoNuevo || $archivoOriginal == $archivoLog || $archivoOriginal == $archivoFinal) ||
//     ($archivoNuevo == $archivoOriginal || $archivoNuevo == $archivoLog || $archivoNuevo == $archivoFinal) ||
//     ($archivoLog == $archivoNuevo || $archivoLog == $archivoOriginal || $archivoLog == $archivoFinal) ||
//     ($archivoFinal == $archivoNuevo || $archivoFinal == $archivoOriginal || $archivoFinal == $archivoLog)
// ) {
//     echo (getTranslation("name.cant.be.same"));
//     die();
// }

if (trim($archivoOriginal) == "") {
    echo (getTranslation("original.file.name.blank"));
    die();
}
if (trim($archivoNuevo) == "") {
    echo (getTranslation("new.file.name.blank"));
    die();
}
if (trim($archivoFinal) == "") {
    echo (getTranslation("final.file.name.blank"));
    die();
}
// if ($crearLogs == true && trim($archivoLog) == "") {
//     echo (getTranslation("log.file.name.blank"));
//     die();
// }
if ($debugLevel != 1 && $debugLevel != 2 && $debugLevel != 3 && $debugLevel != 4) {
    echo (getTranslation("debug.level.invalid"));
    die();
}

try {
    if (!file_exists(__DIR__ . "/" . $archivoOriginal))
        throw new Exception;
    $originalFile = file(__DIR__ . "/" . $archivoOriginal);
    sort($originalFile);
} catch (Exception) {
    echo (getTranslation("original.file.not.detected"));
    die();
}

try {
    if (!file_exists(__DIR__ . "/" . $archivoNuevo))
        throw new Exception;
    $newFile = file(__DIR__ . "/" . $archivoNuevo);
    sort($newFile);
} catch (Exception) {
    echo (getTranslation("new.file.not.detected"));
    die();
}

if ($crearLogs == true)
{
    $dir = (__DIR__ . "/logs");
    try {
        if (!file_exists($dir))
        {
            mkdir($dir);
        }
    }
    catch (Exception) {
        echo "Error Creando directorio LOG";
    }
}

if ($crearLogs == true)
{
    try {
        $logNewFile = (__DIR__ . "/logs/" . $archivoNewLinesLog);
        $logDeleteFile = (__DIR__ . "/logs/" . $archivoDeletedLinesLog);
        $logModifiedFile = (__DIR__ . "/logs/" . $archivoModifiedLinesLog);
    } catch (Exception) {
        echo (getTranslation("log.file.not.detected"));
        if ($crearLogs == true)
            die();
    }
}

try {
    $finalFile = (__DIR__ . "/" . $archivoFinal);
} catch (Exception) {
    echo (getTranslation("final.file.not.detected"));
    die();
}

$logNewContent = date("d-m-Y H:i:s");
$logDeletedContent = date("d-m-Y H:i:s");
$logModifiedContent = date("d-m-Y H:i:s");
$counter = 0;
$finalContent = "";
$finalFileAsociativeArray = [];
$newLines = [];
$deletedLines = [];
$changedLines = [];
$newFileAsociativeArray = [];
$originalFileAsociativeArray = [];

// ----------------------
// -  REORGANIZE ARRAY  -
// ----------------------
if ($debugLevel == 3)
    echo ("\n" . getTranslation("organizating.array.into.asociatives"));

foreach ($originalFile as $numLinea => $linea) {
    $contentArray = explode("=", $linea);
    $key = $contentArray[0];
    $value = "";
    if (sizeof($contentArray) > 2) {
        unset($contentArray[0]);
        $value = implode("=", $contentArray);
    } else
        $value = $contentArray[1];

    $originalFileAsociativeArray[$key] = $value;
}

foreach ($newFile as $numLinea => $linea) {
    $contentArray = explode("=", $linea);
    $key = $contentArray[0];
    $value = "";
    if (sizeof($contentArray) > 2) {
        unset($contentArray[0]);
        $value = implode("=", $contentArray);
    } else
        $value = $contentArray[1];

    $newFileAsociativeArray[$key] = $value;
}

if ($debugLevel == 3)
    echo ("\n" . getTranslation("organization.completed"));

$finalFileAsociativeArray = $originalFileAsociativeArray;

if ($debugLevel == 3)
    echo ("\n" . getTranslation("final.array.clone.completed"));

// --------------------------
// -  SEARCH FOR NEW LINES  -
// --------------------------

if ($debugLevel == 1 || $debugLevel == 2 || $debugLevel == 3 || $debugLevel == 4)
    echo "\n" . getTranslation("searching.new.lines") . "\n";

if ($debugLevel == 3)
    echo ("\n" . getTranslation("introducing.new.lines.into.array"));

foreach ($newFileAsociativeArray as $key => $value) {
    $existe = isset($originalFileAsociativeArray[$key]);
    if ($existe == false)
        $newLines[$key] = $value;
}

if ($debugLevel == 3)
    echo ("\n" . getTranslation("new.lines.array.completed"));
if ($debugLevel == 3 && $crearLogs == true)
    echo ("\n" . getTranslation("creating.new.line.logs"));

if ($crearLogs) {
    $logNewContent = $logNewContent . "\n\n---------------------\n";
    $logNewContent = $logNewContent . "--  " . getTranslation("new.lines.logs.title") . "  --\n";
    $logNewContent = $logNewContent . "---------------------\n\n";
    foreach ($newLines as $key => $value) {
        if ($logCopyPasteFormat == false)
            $logNewContent = $logNewContent . "[" . $key . "] => " . $value . "\n";
        elseif ($logCopyPasteFormat == true)
            $logNewContent = $logNewContent . $key . "=" . $value;
    }
}

if ($debugLevel == 3 && $crearLogs == true)
    echo ("\n" . getTranslation("new.lines.logs.completed"));

if ($debugLevel == 1 || $debugLevel == 2 || $debugLevel == 3 || $debugLevel == 4)
    echo "\n" . getTranslation("new.lines.search.completed") . "\n";

// ------------------------------
// -  SEARCH FOR DELETED LINES  -
// ------------------------------

if ($debugLevel == 1 || $debugLevel == 2 || $debugLevel == 3 || $debugLevel == 4)
    echo "\n" . getTranslation("searching.deleted.lines") . "\n";

if ($debugLevel == 3)
    echo ("\n" . getTranslation("introducing.deleted.lines.into.array"));

foreach ($originalFileAsociativeArray as $key => $value) {
    $existe = isset($newFileAsociativeArray[$key]);
    if ($existe == false)
        $deletedLines[$key] = $value;
}

if ($debugLevel == 3)
    echo ("\n" . getTranslation("deleted.lines.array.completed"));
if ($debugLevel == 3 && $crearLogs == true)
    echo ("\n" . getTranslation("creating.deleted.line.logs"));

if ($crearLogs) {
    $logDeletedContent = $logDeletedContent . "\n\n-----------------------\n";
    $logDeletedContent = $logDeletedContent . "-- " . getTranslation("deleted.lines.logs.title") . " --\n";
    $logDeletedContent = $logDeletedContent . "-----------------------\n\n";
    foreach ($deletedLines as $key => $value) {
        if ($logCopyPasteFormat == false)
            $logDeletedContent = $logDeletedContent . "[" . $key . "] => " . $value . "\n";
        elseif ($logCopyPasteFormat == true)
            $logDeletedContent = $logDeletedContent . $key . "=" . $value;
    }
}

if ($debugLevel == 3)
    echo ("\n" . getTranslation("deleted.lines.logs.completed"));

if ($debugLevel == 1 || $debugLevel == 2 || $debugLevel == 3 || $debugLevel == 4)
    echo "\n" . getTranslation("deleted.lines.search.completed") . "\n";

// ---------------------------------
// --  SEARCH FOR MODIFIED LINES  --
// ---------------------------------

if ($debugLevel == 1 || $debugLevel == 2 || $debugLevel == 3 || $debugLevel == 4)
    echo "\n" . getTranslation("searching.modified.lines") . "\n";

if ($debugLevel == 3)
    echo ("\n" . getTranslation("introducing.modified.lines.into.array"));

foreach ($newFileAsociativeArray as $key => $value) {
    $existe = isset($originalFileAsociativeArray[$key]);

    $mismoContenido = false;

    if ($existe == true) {
        $newContent = trim($newFileAsociativeArray[$key]);
        $originalContent = trim($originalFileAsociativeArray[$key]);

        if (strcmp($newContent, $originalContent) == true) {
            $changedLines[$key] = $newFileAsociativeArray[$key];
        }
    }
}

if ($debugLevel == 3)
    echo ("\n" . getTranslation("modified.lines.array.completed"));
if ($debugLevel == 3 && $crearLogs == true)
    echo ("\n" . getTranslation("creating.modified.lines.logs"));

if ($crearLogs) {
    $logModifiedContent = $logModifiedContent . "\n\n------------------------\n";
    $logModifiedContent = $logModifiedContent . "-- " . getTranslation("modified.lines.logs.title") . " --\n";
    $logModifiedContent = $logModifiedContent . "------------------------\n\n";
    foreach ($changedLines as $key => $value) {
        $tempValue = trim($value);
        if ($logCopyPasteFormat == false)
            $logModifiedContent = $logModifiedContent . "[" . $key . "] => [" . $tempValue . "]\n";
        elseif ($logCopyPasteFormat == true)
            $logModifiedContent = $logModifiedContent . $key . "=" . $tempValue . "\n";
    }
}

if ($debugLevel == 3)
    echo ("\n" . getTranslation("modified.lines.logs.completed"));

if ($debugLevel == 1 || $debugLevel == 2 || $debugLevel == 3 || $debugLevel == 4)
    echo "\n" . getTranslation("modified.lines.search.completed") . "\n";

if ($crearLogs)
{
    file_put_contents($logNewFile, $logNewContent);
    file_put_contents($logDeleteFile, $logDeletedContent);
    file_put_contents($logModifiedFile, $logModifiedContent);
}

if ($debugLevel == 3)
    echo ("\n" . getTranslation("logs.file.generated"));

// ONLY CREATE FINAL FILE IF onlyLogs = FALSE
if ($onlyLogs == false) {
    // ---------------------
    // --  APPLY CHANGES  --
    // ---------------------

    if ($debugLevel == 3)
        echo ("\n" . getTranslation("applying.changes"));

    // NEW LINES
    foreach ($newLines as $key => $value) {
        $finalFileAsociativeArray[$key] = $value;
    }

    if ($debugLevel == 3)
        echo ("\n" . getTranslation("new.line.changes.log.generated"));

    // DELETED LINES
    foreach ($deletedLines as $key => $value) {
        unset($finalFileAsociativeArray[$key]);
    }

    if ($debugLevel == 3)
        echo ("\n" . getTranslation("deleted.line.changes.log.generated"));

    // MODIFIED LINES
    foreach ($changedLines as $key => $value) {
        $finalFileAsociativeArray[$key] = $changedLines[$key];
    }

    // FINAL FILE GENERATION ONLY IF ONLYLOGS = FALSE

    if ($debugLevel == 3)
        echo ("\n" . getTranslation("modified.line.changes.log.generated"));

    if ($debugLevel == 1 || $debugLevel == 2 || $debugLevel == 3 || $debugLevel == 4)
        echo ("\n" . getTranslation("generating.final.file") . " (" . $archivoFinal . ")");


    foreach ($finalFileAsociativeArray as $key => $value) {
        $counter++;
        if ($debugLevel == 2 || $debugLevel == 3)
            getLineInterval();
        elseif ($debugLevel == 4)
            echo ("\n" . getTranslation("generating.line") . ": " . $counter);
        // $tempValue = str_replace("\n", "\\n", $value);
        $finalContent = $finalContent . $key . "=" . $value;
    }

    file_put_contents($finalFile, $finalContent);

    if ($debugLevel == 1 || $debugLevel == 2 || $debugLevel == 3 || $debugLevel == 4)
        echo ("\n" . getTranslation("final.file.generated") . " (" . $archivoFinal . ")");
}

// FUNCTIONS

function getLineInterval()
{
    global $counter;

    $firstNumber = (strval($counter)[0]);

    if ($counter == 1) {
        echo ("\n" . getTranslation("generating.lines") . " (   1   - 10.000)");
    } elseif (sizeof(str_split(strval($counter))) == 5) {
        $numbers = str_split(strval($counter));
        unset($numbers[0]);
        if (strval(implode("", $numbers)) == "0000")
            echo ("\n" . getTranslation("generating.lines") . " (" . (str_split(strval($counter))[0]) . "0.001 - " . (intval(str_split(strval($counter))[0]) + 1) . "0.000)");
    }

}

function getTranslation($key)
{
    global $translate;

    $translation = "";
    try {
        $translation = $translate[$key];
    } catch (Exception) {
        $translation = ("ERR - KEY [" . $key . "] NOT DETECTED IN LANG FILE SELECTED");
    }

    return $translation;
}

?>