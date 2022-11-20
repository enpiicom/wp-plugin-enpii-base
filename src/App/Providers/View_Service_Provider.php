<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\App\Providers;

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\View\ViewServiceProvider;

class View_Service_Provider extends ViewServiceProvider {

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
