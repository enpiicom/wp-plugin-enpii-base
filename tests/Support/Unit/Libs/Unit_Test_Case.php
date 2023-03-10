<?php

namespace Enpii\WP_Plugin\Enpii_Base\Tests\Support\Unit\Libs;

use Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Foundation\Application;
use Enpii\WP_Plugin\Enpii_Base\Libs\WP_Application;

class Unit_Test_Case extends \Codeception\Test\Unit {
	protected WP_Application $wp_app;
	protected $wp_app_base_path;

	protected function setUp(): void {
		\WP_Mock::setUp();
		$this->wp_app_base_path = codecept_root_dir();
		$this->wp_app = (new WP_Application($this->wp_app_base_path));
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
	}
}
