<?php

declare(strict_types=1);

namespace Enpii\WP_Plugin\Enpii_Base\Libs\Interfaces;

use Enpii\WP_Plugin\Enpii_Base\Libs\WP_Application;

interface WP_Hook_Handler_Inferface {
	/**
	 * We want to use the config array for the constructor
	 * @return void
	 */
	public function init_by_config ( array $config = [], bool $strict = false) :void;

	public function get_wp_app(): WP_Application;
}
