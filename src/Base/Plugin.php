<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\Base;

use Enpii\Wp_Plugin\Enpii_Base\App\Console\Kernel as AppConsoleKernel;
use Enpii\Wp_Plugin\Enpii_Base\App\Http\Kernel as AppHttpKernel;
use Enpii\Wp_Plugin\Enpii_Base\App\Http\Request;
use Enpii\Wp_Plugin\Enpii_Base\Libs\WP_Plugin;
use Enpii\Wp_Plugin\Enpii_Base\Support\Traits\Accessor_Set_Get_Has_Trait;

class Plugin extends WP_Plugin {
	use Accessor_Set_Get_Has_Trait;

	public function register_hooks(): void {
		add_action( 'after_setup_theme', [ $this, 'handle_wp_app_requests' ] );
	}

	public function initialize(): self {
		$this->handle_wp_app_requests();
		$this->register_hooks();
		return $this;
	}

	public function run(): void {
	}

	public function handle_wp_app_requests(): void {
		$wp_app_prefix = 'wp-app';
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( $_SERVER['REQUEST_URI'] ) : '/';
		if ( strpos( $uri, '/' . $wp_app_prefix ) === 0 ) {
			$wp_app = $this->app;
			$wp_app['env'] = config( 'app.env' );

			$wp_app->singleton(
				\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Http\Kernel::class,
				AppHttpKernel::class
			);

			$wp_app->singleton(
				\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Console\Kernel::class,
				AppConsoleKernel::class
			);

			$wp_app->singleton(
				\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Debug\ExceptionHandler::class,
				\Enpii\Wp_Plugin\Enpii_Base\App\Exceptions\Handler::class
			);

			/** @var AppHttpKernel $kernel */
			$kernel = $wp_app->make( \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Http\Kernel::class );

			$request = Request::capture();
			$response = $kernel->handle( $request );

			$response->send();

			$kernel->terminate( $request, $response );

			die( ' wp-app ' );
		}
		die( ' asdf ' );
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->instance( 'enpii_base', $this );

		$this->initialize();
	}
}
