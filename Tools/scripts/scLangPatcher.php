<?php

$yourTranslatedPatchFileName = "global_es.ini";     // THE NAME OF YOUR TRANSLATED FILE (Example: global_spanish.ini)
$newOriginalPatchFileName = "global.new.ini";       // THE NAME OF YOUR NEW PATCH FILE (Example: global_3.23.ini)
$oldOriginalPatchFileName = "global_en.ini";        // THE NAME OF YOUR OLD PATCH FILE (Example: global_3.22.ini)
$finalResultFileName = "global.final.ini";          // THE NAME OF FINAL FILE (Example: global_final.ini)
$logFileName = "log.txt";                           // THE NAME OF LOGS FILE (Example: logs.txt)

$runTests = true;                                   // RUN FINAL FILE INTEGRITY TESTS?  ******STRONGLY RECOMENDED****** (true = RUN TESTS / false = DONT RUN TESTS)

$debug = false;                                      // SET TO TRUE IF WANT CONSOLE LOGS

// -------------------------------------------
// --      DONT TOUCH THE CODE BELOW        --
// --   UNLESS YOU KNOW WHAT YOU ARE DOING  --
// -------------------------------------------

$time_start = microtime(true);

$translatedPatchArray = file(__DIR__ . "/" . $yourTranslatedPatchFileName);
$oldOriginalPatchArray = file(__DIR__ . "/" . $oldOriginalPatchFileName);
$newOriginalPatchArray = file(__DIR__ . "/" . $newOriginalPatchFileName);

$translatedPatchAsociativeArray;
$oldOriginalPatchAsociativeArray;
$newOriginalPatchAsociativeArray;
$finalAsociativeArray;

$logContent = "";

// -----------------------------------
// --   CREATE ASOCIATIVE ARRAYS    --
// -----------------------------------

if ($debug == true) echo "\nINICIANDO...";

// CREATE TRANSLATED PATCH ASOCIATIVE ARRAY

if ($debug == true) echo "\nCREANDO ARRAY ASOCIATIVO DEL ARCHIVO TRADUCIDO";

foreach ($translatedPatchArray as $lineNum => $lineContent)
{
    if (strpos($lineContent , "=") === false)
    {
        echo "Line ".($lineNum+1)." error (NO VARIABLE FOUND)";
        die();
    }
    else
    {
        $content = explode("=",$lineContent);
        if (sizeof($content) == 2)
        {
            $key = $content[0];
            $value = $content[1];

            $translatedPatchAsociativeArray[$key] = $value;
        }
        elseif (sizeof($content) > 2)
        {
            $key = $content[0];
            unset($content[0]);

            $value = implode("=",$content);

            $translatedPatchAsociativeArray[$key] = $value;
        }
    }
}

if ($debug == true) echo "\nARRAY ASOCIATIVO DEL ARCHIVO TRADUCIDO CREADO";

// CREATE OLD PATCH ASOCIATIVE ARRAY

if ($debug == true) echo "\nCREANDO ARRAY ASOCIATIVO DEL PARCHE ANTIGUO";

foreach ($oldOriginalPatchArray as $lineNum => $lineContent)
{
    if (strpos($lineContent , "=") === false)
    {
        echo "Line ".($lineNum+1)." error (NO VARIABLE FOUND)";
        die();
    }
    else
    {
        $content = explode("=",$lineContent);
        if (sizeof($content) == 2)
        {
            $key = $content[0];
            $value = $content[1];

            $oldOriginalPatchAsociativeArray[$key] = $value;
        }
        elseif (sizeof($content) > 2)
        {
            $key = $content[0];
            unset($content[0]);

            $value = implode("=",$content);

            $oldOriginalPatchAsociativeArray[$key] = $value;
        }
    }
}

if ($debug == true) echo "\nARRAY ASOCIATIVO DEL PARCHE ANTIGUO CREADO";

// CREATE NEW PATCH ASOCIATIVE ARRAY

if ($debug == true) echo "\nCREANDO ARRAY ASOCIATIVO DEL NUEVO PARCHE";

foreach ($newOriginalPatchArray as $lineNum => $lineContent)
{
    if (strpos($lineContent , "=") === false)
    {
        echo "Line ".($lineNum+1)." error (NO VARIABLE FOUND)";
        die();
    }
    else
    {
        $content = explode("=",$lineContent);
        if (sizeof($content) == 2)
        {
            $key = $content[0];
            $value = $content[1];

            $newOriginalPatchAsociativeArray[$key] = $value;
        }
        elseif (sizeof($content) > 2)
        {
            $key = $content[0];
            unset($content[0]);

            $value = implode("=",$content);

            $newOriginalPatchAsociativeArray[$key] = $value;
        }
    }
}

if ($debug == true) echo "\nARRAY ASOCIATIVO DEL NUEVO PARCHE CREADO";

$finalAsociativeArray = $newOriginalPatchAsociativeArray;

if ($debug == true) echo "\nARRAY ASOCIATIVO DEL ARCHIVO FINAL CREADO";

// ---------------------
// --   LOG CONTENT   --
// ---------------------

if ($debug == true) echo "\nGENERANDO LOGS";

// NEW LINES

if ($debug == true) echo "\nGENERANDO LOGS DE NUEVAS LÍNEAS";

$logContent .= "\n\n\n--------------------";
$logContent .= "\n--    NEW LINES   --";
$logContent .= "\n--------------------";

$tempKeys = array_keys($newOriginalPatchAsociativeArray);

foreach ($newOriginalPatchAsociativeArray as $key => $value)
{
    if (!isset($oldOriginalPatchAsociativeArray[$key]))
    {
        $logContent .= ("\n[".$key."] => ".$value."(LINE: ".(array_search($key,$tempKeys)+1).")");
    }
}

if ($debug == true) echo "\nLOGS DE NUEVAS LÍNEAS GENERADOS";

// DELETED LINES

if ($debug == true) echo "\nGENERANDO LOGS DE LÍNEAS ELIMINADAS";

$logContent .= "\n\n\n------------------------";
$logContent .= "\n--    DELETED LINES   --";
$logContent .= "\n------------------------";

$tempKeys = array_keys($oldOriginalPatchAsociativeArray);

foreach ($oldOriginalPatchAsociativeArray as $key => $value)
{
    if (!isset($newOriginalPatchAsociativeArray[$key]))
    {
        $logContent .= ("\n[".$key."] => ".$value."(LINE: ".(array_search($key,$tempKeys)+1).")");
    }
}

if ($debug == true) echo "\nLOGS DE LÍNEAS ELIMINADAS GENERADOS";

// MODIFIED LINES

if ($debug == true) echo "\nGENERANDO LOGS DE LÍNEAS MODIFICADAS";

$logContent .= "\n\n\n-------------------------";
$logContent .= "\n--    MODIFIED LINES   --";
$logContent .= "\n-------------------------";

$tempKeys = array_keys($newOriginalPatchAsociativeArray);

foreach ($newOriginalPatchAsociativeArray as $key => $value)
{
    if ( isset($oldOriginalPatchAsociativeArray[$key]) && (strcmp(trim($oldOriginalPatchAsociativeArray[$key]),trim($value)) == true) )
    {
        $logContent .= ("\nNEW: [".$key."] => ".$value);
        $logContent .= ("OLD: [".$key."] => ".$oldOriginalPatchAsociativeArray[$key]);
        $logContent .= ("(LINE: ".(array_search($key,$tempKeys)+1).")\n");
    }
}

if ($debug == true) echo "\nLOGS DE LÍNEAS MODIFICADAS GENERADOS";


file_put_contents((__DIR__ . "/" . $logFileName) , $logContent);

if ($debug == true) echo "\nARCHIVO DE LOGS CREADO";



// -----------------------------
// --   GENERATE FINAL FILE   --
// -----------------------------

if ($debug == true) echo "\nGENERANDO ARCHIVO FINAL";

// REPLACE NEW VALUES FOR OLD VALUES IN EXISTING KEYS
// SO FINAL FILE WILL HAVE YOUR TRANSLATION IN OLD VARIABLES
// AND NEW VARIABLES WILL BE BY DEFAULT
// WATCH LOGS FOR TRANSLATING IT

foreach ($finalAsociativeArray as $key => $value)
{
    if (isset($translatedPatchAsociativeArray[$key]))
    {
        $finalAsociativeArray[$key] = $translatedPatchAsociativeArray[$key];
    }
}

$finalContent = "";

foreach ($finalAsociativeArray as $key => $value)
{
    $finalContent .= $key."=".$value;
}

file_put_contents((__DIR__ . "/" . $finalResultFileName) , $finalContent);

if ($debug == true) echo "\nARCHIVO FINAL CREADO";



// ---------------------------------
// --   RUN FINAL COMPROBATIONS   --
// --       ONLY IF WANTED        --
// ---------------------------------

if ($debug == true) echo "\nEJECUTANDO TEST DE INTEGRIDAD";

if ($runTests == true)
{
    $finalExistingFile = file(__DIR__ . "/" . $finalResultFileName);
    $newPatchFile = file(__DIR__ . "/" . $newOriginalPatchFileName);

    // CHECK TOTAL LENGTH (TOTAL LINE NUMBER)
    if (sizeof($finalExistingFile) == sizeof($newPatchFile))
    {
        foreach ($finalExistingFile as $lineNum => $lineContent)
        {
            // CHECK IF VARIABLE IS MISSING
            if (strpos($lineContent, "=") === false)
            {
                echo "\nERROR EN TEST DE INTEGRIDAD DE LINEA";
                die();
            }
            else
            {
                $finalVariable = (explode("=" , $finalExistingFile[$lineNum])[0]);
                $newPatchVariable = (explode("=" , $newPatchFile[$lineNum])[0]);

                // COMPARE FINAL FILE VARIABLE TO NEW PATCH FILE VARIABLE AT SAME LINE
                if (strcmp($finalVariable, $newPatchVariable) == true)
                {
                    echo "\nERROR EN TEST DE VARIABLES";
                    die();
                }
            }
        }
    }
    else
    {
        echo "\nERROR EN TEST DE LOGITUD";
        die();
    }
}

$time_end = microtime(true);

$time_total = $time_end - $time_start;

echo ("\n\n---------------------------------");
echo ("\nFINALIZADO - ".getTime());
echo ("\n---------------------------------");

function getTime()
{
    global $time_total;

    if ($time_total > 60)
    {
        $minutes = floor($time_total / 60);
        $seconds = round($time_total % 60);
        return ($minutes."min ".$seconds." sec");
    }
    else return (round($time_total, 2)." sec");
}