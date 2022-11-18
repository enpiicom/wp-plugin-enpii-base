<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\Base;

use Enpii\Wp_Plugin\Enpii_Base\Libs\WP_Plugin;
use Enpii\Wp_Plugin\Enpii_Base\Support\Traits\Accessor_Set_Get_Has_Trait;

class Plugin extends WP_Plugin {
	use Accessor_Set_Get_Has_Trait;

	public function initialize(): void {
		die( __CLASS__ . ' bootstrap' );
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		die( __CLASS__ . ' register ' );
	}
}
