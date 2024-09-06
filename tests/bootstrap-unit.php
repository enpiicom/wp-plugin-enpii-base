<?php
/**
 * Now we include any plugin files that we need to be able to run the tests. This
 * should be files that define the functions and classes you're going to test.
 */

use Illuminate\Filesystem\Filesystem;

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

function output_debug( $debug_string ) {
	// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r, WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_fwrite
	fwrite( STDERR, print_r( $debug_string, true ) );
}

function get_output_tmp_folder_path() {
	$output_tmp_folder_path = __DIR__ . DIRECTORY_SEPARATOR . '_output' . DIRECTORY_SEPARATOR . '_tmp';

	$filesystem = new Filesystem();
	$filesystem->ensureDirectoryExists( $output_tmp_folder_path, 0777 );

	return $output_tmp_folder_path;
}

defined( 'DIR_SEP' ) || define( 'DIR_SEP', DIRECTORY_SEPARATOR );
defined( 'ENPII_BASE_PLUGIN_SLUG' ) || define( 'ENPII_BASE_PLUGIN_SLUG', 'enpii-base' );
defined( 'ENPII_BASE_PLUGIN_VERSION' ) || define( 'ENPII_BASE_PLUGIN_VERSION', '0.7.0' );
defined( 'ENPII_BASE_SETUP_HOOK_NAME' ) || define(
	'ENPII_BASE_SETUP_HOOK_NAME',
	! empty( getenv( 'ENPII_BASE_SETUP_HOOK_NAME' ) ) ? getenv( 'ENPII_BASE_SETUP_HOOK_NAME' ) : 'plugins_loaded'
);


// Bootstrap WP_Mock to initialize built-in features
WP_Mock::setUsePatchwork( true );
WP_Mock::bootstrap();


require_once dirname( __DIR__ ) . '/src/Foundation/Support/helpers-utils.php';
require_once dirname( __DIR__ ) . '/vendor/laravel/framework/src/Illuminate/Foundation/helpers.php';
