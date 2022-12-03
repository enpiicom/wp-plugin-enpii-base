<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\Base;

use Enpii\Wp_Plugin\Enpii_Base\App\Providers\Log_Service_Provider;
use Enpii\Wp_Plugin\Enpii_Base\App\Providers\Route_Service_Provider;
use Enpii\Wp_Plugin\Enpii_Base\App\Providers\View_Service_Provider;
use Enpii\Wp_Plugin\Enpii_Base\Base\Hook_Handlers\Wp_App_Hook_Handler;
use Enpii\Wp_Plugin\Enpii_Base\Libs\WP_Plugin;
use Enpii\Wp_Plugin\Enpii_Base\Support\Traits\Accessor_Set_Get_Has_Trait;

class Plugin extends WP_Plugin {
	use Accessor_Set_Get_Has_Trait;

	public function boot() {
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		/** @var  */
		$wp_app = $this->app;
		$wp_app->instance( __CLASS__, $this );

		$wp_app->register(Log_Service_Provider::class);
		$wp_app->register(Route_Service_Provider::class);
		$wp_app->register(View_Service_Provider::class);

		$this->manipulate_hooks();
	}

	public function manipulate_hooks(): void {
		// We want to start processing wp-app requests after all plugins and theme loaded
		add_action( 'init', [ $this, 'handle_wp_app_requests' ], 9999 );
	}

	public function handle_wp_app_requests(): void {
		// We want to check that if the uri prefix is for wp-app before invoke the handler
		// to keep the handler lazy-loading
		if ($this->is_wp_app_mode()) {
			(new Wp_App_Hook_Handler())->handle_wp_app_requests();
		}
	}

	/**
	 * For checking if the request uri is for 'wp-app'
	 *
	 * @return bool
	 */
	protected function is_wp_app_mode(): bool {
		$wp_app_prefix = 'wp-app';
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( $_SERVER['REQUEST_URI'] ) : '/';
		return ( strpos( $uri, '/' . $wp_app_prefix.'/' ) === 0 || $uri === '/' . $wp_app_prefix );
	}
}
