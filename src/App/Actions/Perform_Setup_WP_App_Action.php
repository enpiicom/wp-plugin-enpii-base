<?php

declare(strict_types=1);

namespace Enpii_Base\App\Actions;

use Enpii_Base\App\Support\Enpii_Base_Helper;
use Enpii_Base\Foundation\Actions\Base_Action;
use Enpii_Base\Foundation\Support\Executable_Trait;
use Illuminate\Support\Facades\Artisan;

/**
 * @method static function exec(): void
 */
class Perform_Setup_WP_App_Action extends Base_Action {
	use Executable_Trait;

	public function handle() {
		Enpii_Base_Helper::prepare_wp_app_folders();

		Artisan::call(
			'wp-app:setup',
			[]
		);

		$output = Artisan::output();
		echo( esc_html( $output ) );
	}
}
