#!/bin/bash

VENDOR_DIR="$(dirname "$0")/../vendor"

# Ensure vendor directory exists
if [ ! -d "$VENDOR_DIR" ]; then
    echo "Vendor directory does not exist: $VENDOR_DIR"
    exit 0  # Exit without error
fi

echo "Starting cleanup of unnecessary vendor FILES..."

######################
# DELETE ".stub" FILES
######################
echo "Deleting all '.stub' files..."
find "$VENDOR_DIR" -type f -name "*.stub" -exec rm -rf {} + 2>/dev/null

######################
# DELETE ".whitesource" FILES
######################
echo "Deleting all '.whitesource' files..."
find "$VENDOR_DIR" -type f -name ".whitesource" -exec rm -rf {} + 2>/dev/null

######################
# DELETE "windows-ansi" FILES
######################
echo "Deleting all 'windows-ansi' files..."
find "$VENDOR_DIR" -type f -name "windows-ansi" -exec rm -rf {} + 2>/dev/null

######################
# DELETE "php-parse" FILES
######################
echo "Deleting all 'php-parse' files..."
find "$VENDOR_DIR" -type f -name "php-parse" -exec rm -rf {} + 2>/dev/null

######################
# RUN COMPOSER AUTOLOAD REBUILD
######################
echo "Rebuilding Composer autoload..."
composer dump-autoload

echo "File cleanup completed successfully!"
