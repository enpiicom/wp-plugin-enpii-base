<?php
/**
 * Plugin Name: Enpii Base
 * Plugin URI:  https://enpii.com/
 * Description: Base plugin for WP development
 * Author:      dev@enpii.com, nptrac@yahoo.com
 * Author URI:  https://enpii.com/
 * Version:     0.1.1
 * Text Domain: enpii
 */

// Update these constants whenever you bump the version
defined( 'ENPII_BASE_PLUGIN_VERSION' ) || define( 'ENPII_BASE_PLUGIN_VERSION', '0.1.1' );

// We set the slug for the plugin here.
// This slug will be used to identify the plugin instance from the WP_Applucation container
defined( 'ENPII_BASE_PLUGIN_SLUG' ) || define( 'ENPII_BASE_PLUGIN_SLUG', 'enpii-base' );

// General fixed constants
defined( 'DIR_SEP' ) || define( 'DIR_SEP', DIRECTORY_SEPARATOR );

// We include composer autoload here
if ( ! class_exists( \Enpii\WP_Plugin\Enpii_Base\App\WP\WP_Application::class ) ) {
	require_once __DIR__ . DIR_SEP . 'vendor' . DIR_SEP . 'autoload.php';
}

// The prefix for wp_app request
defined( 'ENPII_BASE_WP_APP_PREFIX' ) || define(
	'ENPII_BASE_WP_APP_PREFIX',
	env('ENPII_BASE_WP_APP_PREFIX', 'wp-app')
);

/**
 | Create a wp_app() instance to be used in the whole application
 */
$wp_app_base_path = enpii_base_wp_app_get_base_path();
$config = apply_filters( 'enpii_base_wp_app_prepare_config', [
	'app' => require_once __DIR__ . DIR_SEP . 'wp-app-config' . DIR_SEP . 'app.php',
] );
// We initiate the WP Application instance
$wp_app = \Enpii\WP_Plugin\Enpii_Base\App\WP\WP_Application::init_instance_with_config(
	$wp_app_base_path,
	$config
);

// We register Enpii_Base plugin as a Service Provider
$wp_app->register_plugin(
	\Enpii\WP_Plugin\Enpii_Base\App\WP\Enpii_Base_WP_Plugin::class,
	ENPII_BASE_PLUGIN_SLUG,
	__DIR__,
	plugin_dir_url( __FILE__ )
);
