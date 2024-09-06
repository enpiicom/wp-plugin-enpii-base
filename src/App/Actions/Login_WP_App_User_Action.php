<?php

namespace Enpii_Base\App\Actions;

use Enpii_Base\App\Models\User;
use Enpii_Base\Foundation\Actions\Base_Action;
use Enpii_Base\Foundation\Support\Executable_Trait;
use Illuminate\Support\Facades\Auth;

/**
 * @method static function exec(): void
 */
class Login_WP_App_User_Action extends Base_Action {
	use Executable_Trait;

	protected $user;

	public function __construct( $user_id ) {
		$this->user = User::findOrFail( $user_id );
	}

	public function handle() {
		Auth::login( $this->user );
	}
}
