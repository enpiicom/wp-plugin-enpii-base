#!/bin/bash

VENDOR_DIR="$(dirname "$0")/../vendor"

# Ensure vendor directory exists
if [ ! -d "$VENDOR_DIR" ]; then
    echo "Vendor directory does not exist: $VENDOR_DIR"
    exit 0  # Exit without error
fi

echo "Starting cleanup of unnecessary vendor files..."

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
# DELETE "stubs/" FILES
######################
echo "Deleting all '.stub' files..."
find "$VENDOR_DIR" -type f -name "*.stub" -exec rm -rf {} + 2>/dev/null

######################
# DELETE ".whitesource" FILES
######################
echo "Deleting all '.whitesource' files..."
find "$VENDOR_DIR" -type f -name ".whitesource" -exec rm -rf {} + 2>/dev/null

######################
# DELETE "windows-ansi" FILES/DIRECTORIES
######################
echo "Deleting all 'windows-ansi' files and directories..."
find "$VENDOR_DIR" -type d -name "windows-ansi" -exec rm -rf {} + 2>/dev/null
find "$VENDOR_DIR" -type f -name "windows-ansi" -exec rm -rf {} + 2>/dev/null

######################
# DELETE "commonmark" DIRECTORIES
######################
echo "Deleting all 'commonmark' directories..."
find "$VENDOR_DIR" -type d -name "commonmark" -exec rm -rf {} + 2>/dev/null

######################
# RUN COMPOSER AUTOLOAD REBUILD
######################
echo "Rebuilding Composer autoload..."
composer dump-autoload

echo "Cleanup completed successfully!"
