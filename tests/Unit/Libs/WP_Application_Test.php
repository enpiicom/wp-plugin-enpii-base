<?php

namespace Enpii\WP_Plugin\Enpii_Base\Tests\Unit\Base\Libs;

use Codeception\Stub;
use Enpii\WP_Plugin\Enpii_Base\Base\Plugin;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Cache\CacheServiceProvider;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Foundation\Application;
use Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Support\ServiceProvider;
use Enpii\Wp_Plugin\Enpii_Base\Libs\WP_Application;

class WP_Application_Test extends \Codeception\Test\Unit {
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

	public function test_register_plugin_service_provider(): void {
		$config = [
			'env' => 'local',
		];
		$wp_app = $this->wp_app->init_config($config);
		$plugin = new Plugin( $wp_app );
		$plugin->set_base_path( __DIR__ );
		$plugin->set_base_url( $this->wp_app_base_path );
		$wp_app->register( $plugin );

		// We need to ensure all the Cache service provider is registered
		$this->assertEquals($config['env'], $this->wp_app['config']['env'], 'Config is not correct');
		$this->assertEquals(__DIR__ , $plugin->get_base_path() , 'Base path is not correct');
		$this->assertEquals($this->wp_app_base_path , $plugin->get_base_url() , 'Base url is not correct');
		$this->assertNotEmpty($this->wp_app->getProviders(Plugin::class), 'Plugin is not registered');
	}

	public function test_resource_path() {
		$wp_app = \Codeception\Stub::make( $this->wp_app, ['resourcePath' => function () { return $this->wp_app_base_path; }]);
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
