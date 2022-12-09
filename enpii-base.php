<?php
/**
 * Plugin Name: Enpii Base
 * Plugin URI:  https://enpii.com/
 * Description: Base plugin for WP development
 * Author:      dev@enpii.com, nptrac@yahoo.com
 * Author URI:  https://enpii.com/
 * Version:     0.0.1
 * Text Domain: enpii
 */

use Enpii\Wp_Plugin\Enpii_Base\Libs\Wp_Application;

defined( 'DIR_SEP' ) || define( 'DIR_SEP', DIRECTORY_SEPARATOR );
defined( 'WP_APP_FORCE_CREATE_WP_APP_FOLDER' ) || define( 'WP_APP_FORCE_CREATE_WP_APP_FOLDER', true );

defined( 'ENPII_BASE_SLUG' ) || define( 'ENPII_BASE_SLUG', 'enpii-base' );

// We want to have helper functions before loading the dependancies (for overridding purposes)
require_once __DIR__ . DIR_SEP . 'src' . DIR_SEP . 'Support' . DIR_SEP . 'helpers-wp-app.php';
require_once __DIR__ . DIR_SEP . 'src' . DIR_SEP . 'Support' . DIR_SEP . 'helpers.php';

// We include composer autoload here
require_once __DIR__ . DIR_SEP . 'vendor' . DIR_SEP . 'autoload.php';

$wp_app_base_path = enpii_base_get_wp_app_base_path();
if ( WP_APP_FORCE_CREATE_WP_APP_FOLDER ) {
	enpii_base_prepare_wp_app_folders( $wp_app_base_path );
}

$config = [
	'app' => require_once __DIR__ . DIR_SEP . 'wp-app-config' . DIR_SEP . 'app.php',
	// 'cache' => require_once __DIR__ . DIR_SEP . 'wp-app-config'. DIR_SEP .'cache.php',
	// 'logging'          => require_once __DIR__ . DIR_SEP . 'wp-app-config' . DIR_SEP . 'logging.php',
	// 'session' => require_once __DIR__ . DIR_SEP . 'wp-app-config'. DIR_SEP .'session.php',
	// 'view'             => require_once __DIR__ . DIR_SEP . 'wp-app-config' . DIR_SEP . 'view.php',
];

$config = apply_filters( 'enpii_base_wp_app_prepare_config', $config );
$wp_app = ( new \Enpii\Wp_Plugin\Enpii_Base\Libs\Wp_Application( $wp_app_base_path ) )->init_config( $config );

// We register Enpii_Base plugin as a Service Provider
$wp_app->register( \Enpii\Wp_Plugin\Enpii_Base\Base\Plugin::class );

// We want to have the wp_app working before other plugins start their operations
// add_action(
// 	'after_setup_theme',
// 	function () use ( $config ) {
// 		enpii_base_setup_wp_app( $config );
// 	},
// 	1000
// );
