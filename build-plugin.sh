#!/bin/bash
# Build script for WooCommerce Memberships Cards

echo "Building WooCommerce Memberships Cards plugin package..."

# Install composer dependencies (no dev dependencies for production)
echo "Installing composer dependencies..."
composer install --no-dev --optimize-autoloader

# Create build directory
BUILD_DIR="build/woocommerce-memberships-cards"
rm -rf $BUILD_DIR
mkdir -p $BUILD_DIR

# Copy plugin files
echo "Copying plugin files..."
cp -r admin $BUILD_DIR/
cp -r assets $BUILD_DIR/
cp -r includes $BUILD_DIR/
cp -r templates $BUILD_DIR/
cp -r vendor $BUILD_DIR/
cp LICENSE $BUILD_DIR/
cp README.md $BUILD_DIR/
cp readme.txt $BUILD_DIR/
cp woocommerce-memberships-cards.php $BUILD_DIR/
cp composer.json $BUILD_DIR/

# Create zip file
echo "Creating zip package..."
cd build
zip -r woocommerce-memberships-cards.zip woocommerce-memberships-cards/
cd ..

echo ""
echo "✅ Build complete!"
echo "📦 Package: build/woocommerce-memberships-cards.zip"
echo ""
echo "This package includes all dependencies and is ready for distribution."
echo "Upload it directly to your client's WordPress site."



