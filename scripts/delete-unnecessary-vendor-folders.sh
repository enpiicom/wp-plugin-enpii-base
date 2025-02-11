#!/bin/bash

VENDOR_DIR="$(dirname "$0")/../vendor"

# Ensure vendor directory exists
if [ ! -d "$VENDOR_DIR" ]; then
    echo "Vendor directory does not exist: $VENDOR_DIR"
    exit 0  # Exit without error
fi

echo "Starting cleanup of unnecessary vendor FOLDERS..."

######################
# DELETE "bin/" DIRECTORIES
######################
echo "Deleting all 'bin/' directories..."
find "$VENDOR_DIR" -type d -name "bin" -exec rm -rf {} + 2>/dev/null

######################
# DELETE "vendor/bin/" DIRECTORIES
######################
echo "Deleting all 'vendor/bin/' directories..."
find "$VENDOR_DIR/vendor/bin" -type d -exec rm -rf {} + 2>/dev/null

######################
# DELETE "tests/" DIRECTORIES
######################
echo "Deleting all 'tests/' directories..."
find "$VENDOR_DIR" -type d -name "tests" -exec rm -rf {} + 2>/dev/null

######################
# DELETE "demos/" DIRECTORIES
######################
echo "Deleting all 'demos/' directories..."
find "$VENDOR_DIR" -type d -name "demos" -exec rm -rf {} + 2>/dev/null

######################
# DELETE "development tools" (e.g., node_modules, bower_components)
######################
echo "Deleting development tool directories..."
find "$VENDOR_DIR" -type d \( -name "bower_components" -o -name "node_modules" -o -name "grunt" \) -exec rm -rf {} + 2>/dev/null

######################
# DELETE "php-parser" and "Doctrine Deprecations" (From WordPress Plugin Review)
######################
echo "Deleting specific vendor folders..."
find "$VENDOR_DIR/nikic/php-parser" -type d -exec rm -rf {} + 2>/dev/null
find "$VENDOR_DIR/doctrine/deprecations/lib/Doctrine/Deprecations/PHPUnit" -type d -exec rm -rf {} + 2>/dev/null

echo "Folder cleanup completed successfully!"
