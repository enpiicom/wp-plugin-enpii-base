<?php

declare(strict_types=1);

use Enpii\Wp_Plugin\Enpii_Base\Base\Plugin;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Filesystem\Filesystem;
use Enpii\Wp_Plugin\Enpii_Base\Libs\WP_Application;

if ( ! function_exists( 'enpii_base_get_wp_app_base_path' ) ) {
	function enpii_base_get_wp_app_base_path() {
		return rtrim( WP_CONTENT_DIR, '/' ) . DIR_SEP . 'uploads' . DIR_SEP . 'wp-app';
	}
}

if ( ! function_exists( 'enpii_base_get_wp_app_storage_path' ) ) {
	function enpii_base_get_wp_app_storage_path() {
		return enpii_base_get_wp_app_base_path() . DIR_SEP . 'storage';
	}
}

if ( ! function_exists( 'enpii_base_prepare_wp_app_folders' ) ) {
	function enpii_base_prepare_wp_app_folders( string $wp_app_base_path ): void {
		$file_system = new Filesystem();
		$file_system->ensureDirectoryExists( $wp_app_base_path, 0755 );
		$file_system->ensureDirectoryExists( $wp_app_base_path . DIR_SEP . 'bootstrap', 0755 );
		$file_system->ensureDirectoryExists( $wp_app_base_path . DIR_SEP . 'bootstrap' . DIR_SEP . 'cache', 0777 );

		$file_system->ensureDirectoryExists( $wp_app_base_path . DIR_SEP . 'lang', 0755 );
		$file_system->ensureDirectoryExists( $wp_app_base_path . DIR_SEP . 'resources', 0755 );

		$file_system->ensureDirectoryExists( $wp_app_base_path . DIR_SEP . 'storage', 0777 );
		$file_system->ensureDirectoryExists( $wp_app_base_path . DIR_SEP . 'storage' . DIR_SEP . 'logs', 0777 );
		$file_system->ensureDirectoryExists( $wp_app_base_path . DIR_SEP . 'storage' . DIR_SEP . 'framework', 0777 );
		$file_system->ensureDirectoryExists( $wp_app_base_path . DIR_SEP . 'storage' . DIR_SEP . 'framework' . DIR_SEP . 'views', 0777 );

		$file_system->chmod( $wp_app_base_path . DIR_SEP . 'bootstrap' . DIR_SEP . 'cache', 0777 );
		$file_system->chmod( $wp_app_base_path . DIR_SEP . 'storage', 0777 );
		$file_system->chmod( $wp_app_base_path . DIR_SEP . 'storage' . DIR_SEP . 'framework', 0777 );
		$file_system->chmod( $wp_app_base_path . DIR_SEP . 'storage' . DIR_SEP . 'logs', 0777 );
	}
}

if ( ! function_exists( 'enpii_base_prepare_config' ) ) {
	function enpii_base_prepare_config( array $config ): array {
		return apply_filters( 'enpii_base_prepare_config', $config );
	}
}

if ( ! function_exists( 'enpii_base_setup_wp_app' ) ) {
	function enpii_base_setup_wp_app( array $config ): void {
		$config = enpii_base_prepare_config( $config );
		$wp_app = ( new WP_Application( $config['wp_app_base_path'] ) )->initAppWithConfig( $config );
		$wp_app->registerConfiguredProviders();
	}
}

if ( ! function_exists( 'enpii_base_initialize_enpii_base_plugin' ) ) {
	function enpii_base_initialize_enpii_base_plugin(): void {
		wp_app()->register( Plugin::class );
	}
}
