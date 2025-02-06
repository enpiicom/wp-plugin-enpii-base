<?php

declare(strict_types=1);

namespace Enpii_Base\App\Actions\WP_CLI;

use Enpii_Base\App\Support\Enpii_Base_Helper;
use Enpii_Base\Foundation\Actions\Base_Action;
use Enpii_Base\Foundation\Support\Executable_Trait;
use WP_CLI;

/**
 * @method static void exec()
 */
class Prepare_WP_App_Folders_Action extends Base_Action {
	use Executable_Trait;

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle(): void {
		Enpii_Base_Helper::prepare_wp_app_folders();
		// Conditionally call WP_CLI::success if WP_CLI exists
		if ( class_exists( 'WP_CLI' ) ) {
			WP_CLI::success( 'Preparing needed folders for WP App done!' );
		}
	}
}
