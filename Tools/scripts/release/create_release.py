import os
import zipfile
import shutil

####
# Crear un release de la versión actual
#
# Pasos:
# 1. Crear un zip llamado "SC_Spanish.zip" con:
#   - El archivo de configuración, "user.cfg"
#   - El archivo de traducción,
#     incluida su estructura de carpetas, "data/Localization/spanish_(spain)/global.ini"
# 2. Copiar el "data/Localization/spanish_(spain)/global.ini" a "Tools/scripts/release/" como "global.ini.es_ES"

fileUser = "user.cfg"
fileSpanish = "data/Localization/spanish_(spain)/global.ini"
toolsPath = "Tools\\scripts\\release"
zipName = "SC_Spanish.zip"
zipPath = os.path.join(os.getcwd(), f"{toolsPath}\\{zipName}")
zipFiles = [fileUser, fileSpanish]

print(zipPath)

# 1. Crear un zip llamado "SC_Spanish.zip" en "Tools/scripts/release/"
print("Creating zip file...")
with zipfile.ZipFile(zipPath, "w", zipfile.ZIP_DEFLATED) as zip:
    for file in zipFiles:
        zip.write(file)
print("Zip file created!")

# 2. Copiar el "data/Localization/spanish_(spain)/global.ini" a "Tools/scripts/release/" como "global.ini.es_ES"
print("Copying global.ini...")
shutil.copyfile(fileSpanish, f"{toolsPath}\\global.ini.es_ES")
print("global.ini copied!")
