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

defined( 'DIR_SEP' ) || define( 'DIR_SEP', DIRECTORY_SEPARATOR );

defined( 'ENPII_BASE_SLUG' ) || define( 'ENPII_BASE_SLUG', 'enpii-base' );
defined( 'ENPII_BASE_VERSION' ) || define( 'ENPII_BASE_SLUG', '0.0.1' );

// We include composer autoload here
require_once __DIR__ . DIR_SEP . 'vendor' . DIR_SEP . 'autoload.php';

/**
 | WP CLI handlers
 |
 */
add_action( 'cli_init', 'enpii_base_wp_cli_register_commands' );

/**
 | Create a wp_app() instance to be used in the whole application
 */
$wp_app_base_path = enpii_base_wp_app_get_base_path();
$config = apply_filters( 'enpii_base_wp_app_prepare_config', [
	'app' => require_once __DIR__ . DIR_SEP . 'wp-app-config' . DIR_SEP . 'app.php',
] );
$wp_app = ( new \Enpii\Wp_Plugin\Enpii_Base\Libs\Wp_Application( $wp_app_base_path ) )->init_config( $config );

// We register Enpii_Base plugin as a Service Provider
$wp_app->register( \Enpii\Wp_Plugin\Enpii_Base\Base\Plugin::class );
