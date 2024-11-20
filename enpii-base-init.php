<?php
// Only initiate the app() when WordPress is fully loaded
// If the plugin is loaded via Composer before WordPress is ready, we need to ensure proper checks are in place.

use Enpii_Base\App\Support\Enpii_Base_Helper;

// Ensure WordPress core is loaded before proceeding
if ( ! Enpii_Base_Helper::is_wp_core_loaded() ) {
	return;
}

// Ensure Composer's ClassLoader is available
if ( ! class_exists( 'Composer\Autoload\ClassLoader' ) ) {
	throw new RuntimeException( "Composer's ClassLoader not found. Ensure Composer's autoloader is included." );
}

// Get the vendor directory dynamically
$reflection = new ReflectionClass( 'Composer\Autoload\ClassLoader' );
$class_loader = $reflection->getFileName();

if ( $class_loader === false ) {
	throw new RuntimeException( 'Failed to determine the file name for Composer\Autoload\ClassLoader.' );
}

$vendor_dir = dirname( $class_loader, 2 );

// Require the Laravel helpers.php file
$helpers_path = $vendor_dir . '/laravel/framework/src/Illuminate/Foundation/helpers.php';

if ( ! file_exists( $helpers_path ) ) {
	throw new RuntimeException( 'The Laravel helpers.php file is missing: ' . esc_html( $helpers_path ) );
}

require_once $helpers_path;

// Initialize the Enpii Base Helper
Enpii_Base_Helper::initialize( plugin_dir_url( __FILE__ ), __DIR__ );
