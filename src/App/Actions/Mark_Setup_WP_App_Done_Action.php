<?php

declare(strict_types=1);

namespace Enpii_Base\App\Actions;

use Enpii_Base\App\Support\App_Const;
use Enpii_Base\Foundation\Actions\Base_Action;
use Enpii_Base\Foundation\Support\Executable_Trait;

/**
 * @method static function exec(): void
 */
class Mark_Setup_WP_App_Done_Action extends Base_Action {
	use Executable_Trait;

	public function handle() {
		update_option( App_Const::OPTION_VERSION, ENPII_BASE_PLUGIN_VERSION, false );
		delete_option( App_Const::OPTION_SETUP_INFO );
		
		do_action( App_Const::ACTION_WP_APP_MARK_SETUP_APP_DONE );
	}
}
