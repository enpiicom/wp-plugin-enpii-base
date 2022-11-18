<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\Libs\Interfaces;

interface Plugin_Interface {
	/**
	 * We want to use thie method to initialize all hooks and related things to be used in the plugin
	 * @return void
	 */
	public function initialize(): void;
}
