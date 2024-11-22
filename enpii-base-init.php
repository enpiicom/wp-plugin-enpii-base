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

$vendor_dir = '';

// Ensure spl_autoload_functions() returns a valid iterable
$autoload_functions = spl_autoload_functions();
if ( is_array( $autoload_functions ) ) {
	foreach ( $autoload_functions as $autoload_function ) {
		// Check if the autoload function is an instance of Composer's ClassLoader
		if ( is_array( $autoload_function ) && $autoload_function[0] instanceof Composer\Autoload\ClassLoader ) {
			$class_loader = $autoload_function[0];

			// Get the class map from the Composer ClassLoader
			$class_map = $class_loader->getClassMap();

			// Check if the class "Enpii_Base\Enpii_Base_Init" exists in the class map
			if ( isset( $class_map['Enpii_Base\Enpii_Base_Init'] ) ) {
				$reflection = new ReflectionClass( $class_loader );
				$file_name = $reflection->getFileName();

				// Ensure $fileName is not false before calling dirname
				if ( $file_name !== false ) {
					$vendor_dir = dirname( $file_name, 2 );
				}
				break;
			}
		}
	}
}

// Require the Laravel helpers.php file
$helpers_path = $vendor_dir . '/laravel/framework/src/Illuminate/Foundation/helpers.php';

if ( ! file_exists( $helpers_path ) ) {
	throw new RuntimeException( 'The Laravel helpers.php file is missing: ' . esc_html( $helpers_path ) );
}

require_once $helpers_path;

// Initialize the Enpii Base Helper
Enpii_Base_Helper::initialize( plugin_dir_url( __FILE__ ), __DIR__ );
