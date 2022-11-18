<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\Base;

use Enpii\Wp_Plugin\Enpii_Base\Libs\WP_Plugin;
use Enpii\Wp_Plugin\Enpii_Base\Support\Traits\Accessor_Set_Get_Has_Trait;

class Plugin extends WP_Plugin {
	use Accessor_Set_Get_Has_Trait;

	public function register_hooks(): void {
		add_action('after_setup_theme', [$this, 'handle_wp_app_requests']);
	}

	public function initialize(): self {
		$this->register_hooks();
		return $this;
	}

	public function run(): void {
	}

	public function handle_wp_app_requests(): void {
		dev_logger('handle_wp_app_requests', $this);
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->instance('enpii_base', $this);

		$this->initialize();
	}
}
