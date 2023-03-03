<?php

namespace Tests\Unit;

use Codeception\Stub;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Cache\CacheServiceProvider;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Foundation\Application;
use Enpii\Wp_Plugin\Enpii_Base\Libs\Wp_Application;

class Wp_App_HWP_Application_Testook_Handler_Test extends \Codeception\Test\Unit {
	protected Wp_Application $wp_app;
	protected $wp_app_base_path;

	protected function _before() {
		$this->wp_app_base_path = codecept_root_dir();
		$this->wp_app = (new Wp_Application($this->wp_app_base_path));
	}

	public function test_init_config(): void {
		$config = [
			'env' => 'local',
		];
		$this->wp_app->init_config($config);

		// We need to ensure all the configs are binded
		$this->assertEquals($config['env'], $this->wp_app['config']['env'], 'Config is not correct');
	}

	public function test_register_cache_service_provider(): void {
		$this->wp_app->register_cache_service_provider();

		// We need to ensure all the Cache service provider is registered
		$this->assertNotEmpty($this->wp_app->getProviders(CacheServiceProvider::class), 'Cache Service is not registered');
	}

	public function test_resource_path() {
		$wp_app = Stub::make(Wp_Application::class, ['resourcePath' => function () { return $this->wp_app_base_path; }]);
		$resourcePath = $wp_app->resourcePath($this->wp_app_base_path);

		// We need to ensure the resource path is correct
		$this->assertEquals($this->wp_app_base_path, $resourcePath, 'Resource path is not correct');
	}

	public function test_get_instance() {
		$wp_app = $this->wp_app;

		// We need to ensure the same wp_app object is returned when we get its instance
		$this->assertEquals($wp_app, $wp_app->getInstance(), 'Instance is not correct');
	}
}
