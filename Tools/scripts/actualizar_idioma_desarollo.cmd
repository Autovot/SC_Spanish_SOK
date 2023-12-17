@echo off
setlocal enabledelayedexpansion

REM Preparar variables
set BATCH_PATH=%~dp0
set BATCH_PATH=%BATCH_PATH:~0,-1%

REM Verificar que estamos en LIVE, PTU, EPTU, TECH-PREWIEV

echo %BATCH_PATH% | findstr /I /C:"\StarCitizen\LIVE\data" >nul
if errorlevel 1 (
    echo %BATCH_PATH% | findstr /I /C:"\StarCitizen\PTU\data" >nul
    if errorlevel 1 (
        echo %BATCH_PATH% | findstr /I /C:"\StarCitizen\EPTU\data" >nul
        if errorlevel 1 (
            echo %BATCH_PATH% | findstr /I /C:"\StarCitizen\TECH-PREVIEW\data" >nul
            if errorlevel 1 (
                echo:
                echo El script tiene que ejecutarse desde el directorio: "\StarCitizen\[LIVE, PTU, EPTU, TECH-PREVIEW]\data"
                pause
                exit /b
            )
        )
    )
)

REM Descargar la ultima version
echo "Descargando la ultima version de desarollo de la traduccion..."
curl -L -s -o "global.ini" "https://raw.githubusercontent.com/Autovot/SC_Spanish_SOK/master/data/Localization/spanish_(spain)/global.ini"
move /y global.ini ".\Localization\spanish_(spain)\global.ini" > nul

REM Elimitar el archivo user.cfg
IF EXIST user.cfg.new DEL /F user.cfg.new

REM Se crea el archivo user.cfg con el idioma
echo g_language=spanish_(spain) > ../user.cfg
echo g_languageAudio=english >> ../user.cfg

echo:
echo Traduccion en desarollo actualizada. Disfruta de la traduccion.
pause
