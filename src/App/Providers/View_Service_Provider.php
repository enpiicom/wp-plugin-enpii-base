<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\App\Providers;

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\View\ViewServiceProvider;

class View_Service_Provider extends ViewServiceProvider {

	public function boot()
    {
		wp_app_config([
			'view' => [

				/*
				|--------------------------------------------------------------------------
				| View Storage Paths
				|--------------------------------------------------------------------------
				|
				| Most templating systems load templates from disk. Here you may specify
				| an array of paths that should be checked for your views. Of course
				| the usual Laravel view path has already been registered for you.
				|
				*/

				'paths' => [
					wp_app_resource_path('views'),
				],

				/*
				|--------------------------------------------------------------------------
				| Compiled View Path
				|--------------------------------------------------------------------------
				|
				| This option determines where all the compiled Blade templates will be
				| stored for your application. Typically, this is within the storage
				| directory. However, as usual, you are free to change this value.
				|
				*/

				'compiled' => env(
					'VIEW_COMPILED_PATH',
					realpath(wp_app_storage_path('framework/views')),
				),

			],
		]);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->registerFactory();

		$this->registerViewFinder();

		$this->registerBladeCompiler();

		$this->registerEngineResolver();
	}
}
