<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\Libs\Interfaces;

interface Plugin_Interface {
	/**
	 * We want to use this method to initialize all hooks and related things to be used in the plugin
	 * @return void
	 */
	public function initialize(): self;

	/**
	 * Start to run the plugin
	 * @return void
	 */
	public function run(): void;
}
