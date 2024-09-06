<?php

namespace Enpii_Base\App\Actions;

use Enpii_Base\Foundation\Actions\Base_Action;
use Enpii_Base\Foundation\Support\Executable_Trait;
use Illuminate\Support\Facades\Auth;

/**
 * @method static function exec(): void
 */
class Logout_WP_App_User_Action extends Base_Action {
	use Executable_Trait;

	public function handle() {
		Auth::logoutCurrentDevice();
		session()->save();
	}
}
