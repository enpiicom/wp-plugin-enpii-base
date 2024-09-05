<?php

namespace Enpii_Base\App\Actions;

use Enpii_Base\App\Support\Enpii_Base_Helper;
use Enpii_Base\Foundation\Actions\Base_Action;
use Enpii_Base\Foundation\Support\Executable_Trait;

class Bootstrap_WP_App_Action extends Base_Action {
	use Executable_Trait;

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {
		/** @var \Enpii_Base\App\WP\WP_Application $wp_app  */
		$wp_app = app();
		$wp_app['env'] = config( 'app.env' );
		$config = config();

		if ( Enpii_Base_Helper::is_console_mode() ) {
			/** @var \Enpii_Base\App\Console\Kernel $console_kernel */
			$console_kernel = $wp_app->make( \Illuminate\Contracts\Console\Kernel::class );
			$console_kernel->bootstrap();
		} else {
			// As we may not use Contracts\Kernel::handle(), we need to call bootstrap method
			//  to iinitialize all boostrappers
			/** @var \Enpii_Base\App\Http\Kernel $http_kernel */
			$http_kernel = $wp_app->make( \Illuminate\Contracts\Http\Kernel::class );
			$http_kernel->capture_request();
			$http_kernel->bootstrap();
		}

		// As we don't use the LoadConfiguration boostrapper, we need the below snippets
		//  taken from Illuminate\Foundation\Bootstrap\LoadConfiguration
		$wp_app->detectEnvironment(
			function () use ( $config ) {
				return $config->get( 'app.env', 'production' );
			}
		);
	}
}
