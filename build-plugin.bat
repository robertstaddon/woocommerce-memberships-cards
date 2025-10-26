@echo off
REM Build script for WooCommerce Memberships Cards (Windows)

echo Building WooCommerce Memberships Cards plugin package...

REM Install composer dependencies
echo Installing composer dependencies...
composer install --no-dev --optimize-autoloader

REM Create build directory
if exist "build\woocommerce-memberships-cards" rmdir /s /q "build\woocommerce-memberships-cards"
mkdir build\woocommerce-memberships-cards

REM Copy plugin files
echo Copying plugin files...
xcopy /E /I /Y admin build\woocommerce-memberships-cards\admin
xcopy /E /I /Y assets build\woocommerce-memberships-cards\assets
xcopy /E /I /Y includes build\woocommerce-memberships-cards\includes
xcopy /E /I /Y templates build\woocommerce-memberships-cards\templates
xcopy /E /I /Y vendor build\woocommerce-memberships-cards\vendor
copy LICENSE build\woocommerce-memberships-cards\
copy README.md build\woocommerce-memberships-cards\
copy readme.txt build\woocommerce-memberships-cards\
copy woocommerce-memberships-cards.php build\woocommerce-memberships-cards\
copy composer.json build\woocommerce-memberships-cards\

echo.
echo Build complete!
echo Package location: build\woocommerce-memberships-cards
echo.
echo This package includes all dependencies and is ready for distribution.
echo Zip the build\woocommerce-memberships-cards folder before uploading.

pause



