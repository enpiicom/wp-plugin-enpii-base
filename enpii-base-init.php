<?php
// Only initiate the app() when WordPress is fully loaded
// If the plugin is loaded via Composer before WordPress is ready, we need to ensure proper checks are in place.

use Enpii_Base\App\Support\Enpii_Base_Helper;

// Check if WordPress core is loaded, if not, exit the method.
if ( ! Enpii_Base_Helper::is_wp_core_loaded() ) {
	return;
} else {
	require_once __DIR__ . '/vendor/laravel/framework/src/Illuminate/Foundation/helpers.php';
	// If the WordPress core is loaded, we can initialize the app.
	if ( function_exists( 'plugin_dir_url' ) ) {
		Enpii_Base_Helper::initialize( plugin_dir_url( __FILE__ ), __DIR__ );
	}
}
