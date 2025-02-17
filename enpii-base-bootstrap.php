<?php
$enpii_base_existed = defined( 'ENPII_BASE_PLUGIN_VERSION' );

// General fixed constants
defined( 'DIR_SEP' ) || define( 'DIR_SEP', DIRECTORY_SEPARATOR );

// Update these constants whenever you bump the version
defined( 'ENPII_BASE_PLUGIN_VERSION' ) || define( 'ENPII_BASE_PLUGIN_VERSION', '0.9.1' );

// We set the slug for the plugin here.
// This slug will be used to identify the plugin instance from the WP_Applucation container
defined( 'ENPII_BASE_PLUGIN_SLUG' ) || define( 'ENPII_BASE_PLUGIN_SLUG', 'enpii-base' );

// The prefix for wp_app request
defined( 'ENPII_BASE_WP_APP_PREFIX' ) || define(
	'ENPII_BASE_WP_APP_PREFIX',
	! empty( getenv( 'ENPII_BASE_WP_APP_PREFIX' ) ) ? getenv( 'ENPII_BASE_WP_APP_PREFIX' ) : 'wp-app'
);

// The prefix for wp_api request
defined( 'ENPII_BASE_WP_API_PREFIX' ) || define(
	'ENPII_BASE_WP_API_PREFIX',
	! empty( getenv( 'ENPII_BASE_WP_API_PREFIX' ) ) ? getenv( 'ENPII_BASE_WP_API_PREFIX' ) : 'wp-api'
);

defined( 'ENPII_BASE_SETUP_HOOK_NAME' ) || define(
	'ENPII_BASE_SETUP_HOOK_NAME',
	! empty( getenv( 'ENPII_BASE_SETUP_HOOK_NAME' ) ) ? getenv( 'ENPII_BASE_SETUP_HOOK_NAME' ) : 'plugins_loaded'
);

require_once __DIR__ . DIR_SEP . 'src' . DIR_SEP . 'helpers.php';

$autoload_file = __DIR__ . DIR_SEP . 'vendor' . DIR_SEP . 'autoload.php';

if ( file_exists( $autoload_file ) && ! $enpii_base_existed ) {
	require_once $autoload_file;
}
