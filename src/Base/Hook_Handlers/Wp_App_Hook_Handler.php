<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\Base\Hook_Handlers;

use Enpii\Wp_Plugin\Enpii_Base\App\Providers\Route_Service_Provider;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Support\Facades\Route;
use Enpii\Wp_Plugin\Enpii_Base\Libs\Wp_Base_Hook_Handler;

class Wp_App_Hook_Handler extends Wp_Base_Hook_Handler {
	public function handle_wp_app_requests(): void {
		$wp_app = $this->get_wp_app();
		$wp_app['env'] = config( 'app.env' );

		$wp_app->singleton(
			\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Http\Kernel::class,
			\Enpii\Wp_Plugin\Enpii_Base\App\Http\Kernel::class
		);

		$wp_app->singleton(
			\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Console\Kernel::class,
			\Enpii\Wp_Plugin\Enpii_Base\App\Console\Kernel::class
		);

		$wp_app->singleton(
			\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Debug\ExceptionHandler::class,
			\Enpii\Wp_Plugin\Enpii_Base\App\Exceptions\Handler::class
		);

		$wp_app->register(RouteServiceProvider::class);
		$wp_app->register(Route_Service_Provider::class);

		// Route::get('test', function() {
		// 	die(' test ');
		// });

		/** @var \Enpii\Wp_Plugin\Enpii_Base\App\Http\Kernel $kernel */
		$kernel = $wp_app->make( \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Http\Kernel::class );

		$request = \Enpii\Wp_Plugin\Enpii_Base\App\Http\Request::capture();
		$response = $kernel->handle( $request );

		$response->send();

		$kernel->terminate( $request, $response );
	}
}
