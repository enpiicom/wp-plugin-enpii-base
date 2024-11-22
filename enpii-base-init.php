<?php

use Enpii_Base\App\Support\Enpii_Base_Helper;
use Composer\Autoload\ClassLoader;

// Ensure WordPress core is loaded before proceeding
if ( ! Enpii_Base_Helper::is_wp_core_loaded() ) {
	return;
}

// Ensure Composer's ClassLoader is available
if ( ! class_exists( ClassLoader::class ) ) {
	throw new RuntimeException( "Composer's ClassLoader not found. Ensure Composer's autoloader is included." );
}

$vendor_dir = null;

// Iterate through autoload functions to find the Composer ClassLoader
$autoload_functions = spl_autoload_functions();
if ( is_array( $autoload_functions ) ) {
	foreach ( $autoload_functions as $autoload_function ) {
		if ( is_array( $autoload_function ) && $autoload_function[0] instanceof ClassLoader ) {
			$class_loader = $autoload_function[0];
			$class_map = $class_loader->getClassMap();

			// Check if the target class exists in the class map
			if ( isset( $class_map['Enpii_Base\Enpii_Base_Init'] ) ) {
				$normalized_path = realpath( $class_map['Composer\InstalledVersions'] );
				if ( $normalized_path ) {
					// Extract the vendor directory using regex
					$vendor_dir = preg_replace( '~(vendor)/.*~', '$1', $normalized_path );

					if ( $vendor_dir ) {
						// Ensure the path ends with '/vendor'
						$vendor_dir = rtrim( $vendor_dir, '/' );
					}
				}
				break;
			}
		}
	}
}

// Validate and require Laravel helpers.php file
if ( ! $vendor_dir ) {
	throw new RuntimeException( 'Unable to locate the vendor directory.' );
}

$helpers_path = $vendor_dir . '/laravel/framework/src/Illuminate/Foundation/helpers.php';
if ( ! file_exists( $helpers_path ) ) {
	throw new RuntimeException( 'The Laravel helpers.php file is missing: ' . htmlspecialchars( $helpers_path ) );
}

require_once $helpers_path;

// Initialize the Enpii Base Helper
Enpii_Base_Helper::initialize( plugin_dir_url( __FILE__ ), __DIR__ );
