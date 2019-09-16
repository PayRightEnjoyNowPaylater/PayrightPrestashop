#!/bin/bash

helpFunction()
{
   echo ""
   echo "Usage: $0 -v <VERSION_NO>"
   echo -e "\t-v The version number to publish, i.e. 1.0.5"
   exit 1 # Exit script after printing help
}

while getopts "v:" opt
do
   case "$opt" in
      v ) VERSION_NO="$OPTARG" ;;
      ? ) helpFunction ;; # Print helpFunction in case parameter is non-existent
   esac
done

# Print helpFunction in case parameters are empty
if [[ -z "${VERSION_NO}" ]]; then
   echo "Some or all of the parameters are empty";
   helpFunction
fi

# Begin script in case all parameters are correct
echo "Building PrestaShop module version ${VERSION_NO}"

TEMP_FOLDER="./dist/payright"
ZIP_FILENAME="payright-${VERSION_NO}.zip"

# Remove previously built files and folder if it already exists
rm -rf ${TEMP_FOLDER}
rm -f ./dist/${ZIP_FILENAME}

# Copy source files to temp folder
rsync -rP --exclude "/dist" --exclude "/README.md" --exclude "/build.sh" --exclude ".git" ./ ${TEMP_FOLDER}

# Clean up folder (i.e. remove .DS_Store)
find ${TEMP_FOLDER} -name '.DS_Store' -type f -delete

# Zip up folder, ready to upload to addons.prestashop.com
cd ./dist
zip -r ${ZIP_FILENAME} payright
cd -

# Clean up
rm -rf ${TEMP_FOLDER}