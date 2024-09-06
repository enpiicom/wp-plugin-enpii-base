<?php

namespace Enpii_Base\App\Actions;

use Enpii_Base\Foundation\Actions\Base_Action;
use Enpii_Base\Foundation\Support\Executable_Trait;

/**
 * @method static function exec(): void
 */
class Init_WP_App_Kernels_Action extends Base_Action {
	use Executable_Trait;

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle(): void {
		$wp_app = app();
		$wp_app['env'] = config( 'app.env' );

		$wp_app->singleton(
			\Illuminate\Contracts\Http\Kernel::class,
			\Enpii_Base\App\Http\Kernel::class
		);

		$wp_app->singleton(
			\Illuminate\Contracts\Console\Kernel::class,
			\Enpii_Base\App\Console\Kernel::class
		);

		$wp_app->singleton(
			\Illuminate\Contracts\Debug\ExceptionHandler::class,
			\Enpii_Base\App\Exceptions\Handler::class
		);
	}
}
