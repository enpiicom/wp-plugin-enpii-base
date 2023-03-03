<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\Libs\Interfaces;

interface Wp_Hook_Handler_Inferface {
	/**
	 * We want to use the config array for the constructor
	 * @return void
	 */
	public function init_by_config ( array $config = [], bool $strict = false) :void;

	public function handle() :void;
}
