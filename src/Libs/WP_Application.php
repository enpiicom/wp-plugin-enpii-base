<?php

declare(strict_types=1);

namespace Enpii\WP_Plugin\Enpii_Base\Libs;

use Enpii\WP_Plugin\Enpii_Base\Base\Plugin;
use Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Config\Repository;
use Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Foundation\Application;
use TypeError;

class WP_Application extends Application {

	/**
	 * We want to use the array to load the config
	 *
	 * @param mixed $config
	 * @return WP_Application
	 * @throws TypeError
	 * @throws TypeError
	 * @throws TypeError
	 * @throws TypeError
	 * @throws TypeError
	 * @throws \Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Container\BindingResolutionException
	 */
	public function init_config( $config = null ): self {
		$this->singleton(
			'config',
			function ( $app ) use ( $config ) {
				return new Repository( $config );
			}
		);

		return $this;
	}

	public function register_cache_service_provider(): void {
		$this->register( \Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Cache\CacheServiceProvider::class );
	}

	public function register_plugin_service_provider( $config = null ): void {
		$wp_app = $this->init_config( $config );
		$plugin = new Plugin( $wp_app );
		$plugin->set_base_path( __DIR__ );
		$plugin->set_base_url( plugin_dir_url( __FILE__ ) );
		$wp_app->register( $plugin );
	}

	/**
	 * Get the path to the resources directory.
	 *
	 * @param  string  $path
	 * @return string
	 */
	public function resourcePath( $path = '' ) {
		return dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'resources' . ( $path ? DIRECTORY_SEPARATOR . $path : $path );
	}

	public static function getInstance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
		}

		return static::$instance;
	}
}
