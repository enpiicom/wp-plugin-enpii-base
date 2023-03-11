<?php

declare(strict_types=1);

namespace Enpii\WP_Plugin\Enpii_Base\Libs;

use Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Config\Repository;
use Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Foundation\Application;
use Enpii\WP_Plugin\Enpii_Base\Libs\Interfaces\WP_Plugin_Interface;
use InvalidArgumentException;
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

	public function register_plugin( $plugin_classsname, $plugin_base_path, $plugin_base_url ): void {
		$plugin = new $plugin_classsname( $this );
		if ( !($plugin instanceof WP_Plugin_Interface) ) {
			throw new InvalidArgumentException( sprintf( 'The target classname %s must implement %s', $plugin_classsname, WP_Plugin_Interface::class ) );
		}

		/** @var \Enpii\WP_Plugin\Enpii_Base\Libs\WP_Plugin $plugin  */
		$plugin->bind_base_params(
			[
				WP_Plugin::PARAM_KEY_PLUGIN_BASE_PATH => $plugin_base_path,
				WP_Plugin::PARAM_KEY_PLUGIN_BASE_URL => $plugin_base_url,
			]
		);
		$this->register( $plugin );
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
