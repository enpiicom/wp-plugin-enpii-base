<?php

declare(strict_types=1);

namespace Enpii_Base\App\Actions;

use Enpii_Base\Foundation\Actions\Base_Action;
use Enpii_Base\Foundation\Support\Executable_Trait;
use Illuminate\Foundation\Application;

/**
 * @method static function exec(): void
 */
class Get_WP_App_Info_Action extends Base_Action {
	use Executable_Trait;

	public function handle(): array {
		$info = [];
		$info['php_version'] = $this->get_php_version();
		$info['wp_version'] = $this->get_wp_version();
		$info['laravel_version'] = Application::VERSION;
		$info['enpii_base_version'] = ENPII_BASE_PLUGIN_VERSION;

		return $info;
	}

	protected function get_php_version(): string {
		return phpversion();
	}

	protected function get_wp_version(): string {
		return get_bloginfo( 'version' );
	}
}
