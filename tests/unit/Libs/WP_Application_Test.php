<?php

namespace Enpii_Base\Tests\Unit\Base\Libs;

use Codeception\Stub;
use Enpii_Base\App\WP\Enpii_Base_WP_Plugin;
use Enpii_Base\Base\Plugin;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Cache\CacheServiceProvider;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Foundation\Application;
use Enpii_Base\Deps\Illuminate\Support\ServiceProvider;
use Enpii_Base\App\Providers\Bus_Service_Provider;
use Enpii_Base\App\Providers\Events_Service_Provider;
use Enpii_Base\App\Providers\Log_Service_Provider;
use Enpii_Base\App\Providers\Routing_Service_Provider;
use Enpii_Base\App\WP\WP_Application;
use Enpii_Base\Tests\Helper\Unit;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;

class WP_Application_Test extends Unit_Test_Case {
	public function test_init_config(): void {
		$config = [
			'env' => 'local',
			'wp_app_slug' => 'app_slug_local',
			'wp_app_api_slug' => 'api_slug_local',
		];
		$this->wp_app = WP_Application::init_instance_with_config(
			$this->wp_app_base_path,
			$config
		);

		// We need to ensure all the configs are binded
		$this->assertEquals($config['env'], $this->wp_app['config']['env'], 'Config env is not correct');
		$this->assertEquals($config['wp_app_slug'], $this->wp_app['config']['wp_app_slug'], 'Config app slug is not correct');
		$this->assertEquals($config['wp_app_api_slug'], $this->wp_app['config']['wp_app_api_slug'], 'Config api slug is not correct');
	}

	public function test_register_providers(): void {
		// We need to ensure all the Cache service provider is registered
		$this->assertNotEmpty($this->wp_app->getProviders(Events_Service_Provider::class), 'Events_Service_Provider is not registered');
		$this->assertNotEmpty($this->wp_app->getProviders(Log_Service_Provider::class), 'Log_Service_Provider is not registered');
		$this->assertNotEmpty($this->wp_app->getProviders(Routing_Service_Provider::class), 'Routing_Service_Provider is not registered');
		$this->assertNotEmpty($this->wp_app->getProviders(Bus_Service_Provider::class), 'Bus_Service_Provider is not registered');
	}

	public function test_register_plugin(): void {
		$plugin_base_url = 'enpii_base_wp_plugin';
		$plugin_slug = 'enpii-base-wp-plugin';
		$this->wp_app->register_plugin(Enpii_Base_WP_Plugin::class, $plugin_slug, $this->wp_app_base_path, $plugin_base_url);
		$plugin = $this->wp_app->getProvider(Enpii_Base_WP_Plugin::class);

		// We need to ensure the plugin is registered as a service provider with all correct configs set
		$this->assertEquals($plugin_slug, Unit::get_property_class(Enpii_Base_WP_Plugin::class, "plugin_slug", $plugin), 'Slug is not correct');
		$this->assertEquals($plugin_base_url, Unit::get_property_class(Enpii_Base_WP_Plugin::class, "base_url", $plugin), 'Base url is not correct');
		$this->assertEquals($this->wp_app_base_path, Unit::get_property_class(Enpii_Base_WP_Plugin::class, "base_path", $plugin), 'Base path is not correct');
		$this->assertNotEmpty($this->wp_app->getProvider(Enpii_Base_WP_Plugin::class), 'Plugin is not registered');
	}

	public function test_resource_path() {
		$wp_app = Stub::make( $this->wp_app, ['resourcePath' => function () { return $this->wp_app_base_path; }]);
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
