<?php

namespace Enpii_Base\Tests\Support\Unit\Libs;

use Enpii_Base\App\WP\WP_Application;

class Unit_Test_Case extends \Codeception\Test\Unit {
	protected WP_Application $wp_app;
	protected $wp_app_base_path;
	protected $tester;

	protected function setUp(): void {
		$this->wp_app_base_path = codecept_root_dir();
		$config = [
			'app' => '../../../../wp-app-config/app.php',
			'env' => 'local',
			'view' => [
				'paths' => [$this->wp_app_base_path],
				'compiled' => [codecept_output_dir()],
			],
			'wp_app_slug' => 'wp-app',
			'wp_api_slug' => 'wp-api',
		];
		$this->wp_app = WP_Application::init_instance_with_config(
			$this->wp_app_base_path,
			$config
		);
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
	}
}
