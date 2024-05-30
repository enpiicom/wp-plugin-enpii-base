<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\Foundation\WP;

use Enpii_Base\App\WP\WP_Application;
use Enpii_Base\Foundation\WP\WP_Plugin;
use Enpii_Base\Foundation\WP\WP_Plugin_Interface;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Mockery;
use WP_Mock;

class WP_Plugin_Test extends Unit_Test_Case {
	/** @var WP_Plugin_Tmp $mock_wp_plugin */
	protected $mock_wp_plugin;

	protected function setUp(): void {
		parent::setUp();

		$mock_wp_app = $this->getMockBuilder( WP_Application::class );
		$this->mock_wp_plugin = $this->getMockForAbstractClass(
			WP_Plugin_Tmp::class,
			[ $mock_wp_app ],
			'',
			false
		);
	}

	protected function tearDown(): void {
		$this->mock_wp_plugin = null;

		parent::tearDown();
	}

	public function test_wp_app_instance(): void {
		$mock_wp_plugin = $this->mock_wp_plugin;
		WP_Mock::userFunction( 'wp_app' )
			->times( 1 )
			->withAnyArgs()
			->andReturn( $mock_wp_plugin );
		$result = $mock_wp_plugin->wp_app_instance();
		$this->assertEquals( $mock_wp_plugin, $result );
	}

	public function test_init_with_wp_app_exists(): void {
		$mock_wp_plugin = $this->mock_wp_plugin;
		WP_Mock::userFunction( 'wp_app' )
			->times( 1 )
			->withAnyArgs()
			->andReturn( $mock_wp_plugin );
		$result = $mock_wp_plugin->wp_app_instance();
		$this->assertEquals( $mock_wp_plugin, $result );
	}

	public function test_init_with_wp_app() {
		$mock_wp_plugin = $this->getMockBuilder( WP_Plugin_Tmp::class )
			->disableOriginalConstructor()
			->onlyMethods(
				[
					'init_with_needed_params',
					'attach_to_wp_app',
					'manipulate_hooks',
				]
			)
			->getMock();
		// Mock the new instance creation
		$mock_class_name = get_class( $mock_wp_plugin );

		$slug = 'plugin-slug';
		$base_path = '/path/to/plugin';
		$base_url = 'https://example.com';

		WP_Mock::userFunction( 'wp_app' )
			->times( 2 )
			->withAnyArgs()
			->andReturnUsing(
				function ( $abstract = null ) use ( $mock_wp_plugin ) {
					if ( $abstract ) {
							return $mock_wp_plugin;
					} else {
						return new Tmp_Has_True();
					}
				}
			);
		$result = $mock_class_name::init_with_wp_app( $slug, $base_path, $base_url );
		$this->assertEquals( $mock_wp_plugin, $result );

		// Stub the wp_app function
		WP_Mock::userFunction( 'wp_app' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function ( $abstract = null ) use ( $mock_wp_plugin ) {
					if ( $abstract ) {
							return $mock_wp_plugin;
					} else {
						return new Tmp_Has_False();
					}
				}
			);

		$slug = 'plugin-slug';
		$base_path = '/path/to/plugin';
		$base_url = 'https://example.com';

		// Expected method calls
		$mock_wp_plugin->expects( $this->any() )->method( 'init_with_needed_params' );
		$mock_wp_plugin->expects( $this->any() )->method( 'attach_to_wp_app' );
		$mock_wp_plugin->expects( $this->any() )->method( 'manipulate_hooks' );

		$result = $mock_class_name::init_with_wp_app( $slug, $base_path, $base_url );
		// Assertions
		$this->assertInstanceOf( WP_Plugin_Interface::class, $result );
	}
}

class WP_Plugin_Tmp extends WP_Plugin {
	public function manipulate_hooks(): void {
	}

	public function get_name(): string {
		return 'tmp';
	}

	public function get_version(): string {
		return '1.0.1';
	}
}

class Tmp_Has_False {
	public function has( $abstract ) {
		return false;
	}
}

class Tmp_Has_True {
	public function has( $abstract ) {
		return true;
	}
}
