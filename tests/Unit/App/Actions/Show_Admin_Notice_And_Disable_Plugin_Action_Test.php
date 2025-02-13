<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\App\Actions;

use Enpii_Base\App\Actions\Show_Admin_Notice_And_Disable_Plugin_Action;
use Enpii_Base\Foundation\WP\WP_Plugin_Interface;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Mockery;
use WP_Mock;
use Illuminate\Support\Facades\Session;

class Show_Admin_Notice_And_Disable_Plugin_Action_Test extends Unit_Test_Case {

	protected function setUp(): void {
		parent::setUp();
		WP_Mock::setUp(); // Initialize WP_Mock
	}

	protected function tearDown(): void {
		WP_Mock::tearDown(); // Clean up WP_Mock
		Mockery::close();
		parent::tearDown();
	}

	public function test_construct() {
		$plugin_mock = $this->createMock( WP_Plugin_Interface::class );
		$extra_messages = [ 'Message 1', 'Message 2' ];

		// Instantiate the action class
		$action = new Show_Admin_Notice_And_Disable_Plugin_Action( $plugin_mock, $extra_messages );

		$plugin_property = $this->get_protected_property_value( $action, 'plugin' );
		$extra_messages_property = $this->get_protected_property_value( $action, 'extra_messages' );

		// Assert that the plugin and extra_messages properties are set correctly
		$this->assertSame( $plugin_mock, $plugin_property );
		$this->assertSame( $extra_messages, $extra_messages_property );
	}

	public function test_handle() {
		// Create plugin mock with expected method calls
		$plugin_mock = Mockery::mock( WP_Plugin_Interface::class );
		$plugin_mock->shouldReceive( 'get_name' )->andReturn( 'Test Plugin' )->once();
		$plugin_mock->shouldReceive( 'get_version' )->andReturn( '1.0.0' )->once();
		// $plugin_mock->shouldReceive('get_plugin_basename')->andReturn('test-plugin/test-plugin.php')->once();

		// Define extra messages
		$extra_messages = [ 'Custom message' ];

		// Mock session behavior for extra messages
		Session::shouldReceive( 'push' )
			->with( 'caution', 'Custom message' )
			->once();

		// Expected message based on the plugin details
		$expected_message = sprintf(
			__( 'Plugin <strong>%s</strong> is not working properly. Please recheck the mandatory prerequisites.', 'enpii-base' ),
			'Test Plugin 1.0.0'
		);

		// Mock session behavior for main plugin notice
		Session::shouldReceive( 'push' )
			->with( 'caution', $expected_message )
			->once();

		// Mock WordPress function to deactivate the plugin
		// WP_Mock::userFunction(
		//  'deactivate_plugins',
		//  [
		//      'args' => ['test-plugin/test-plugin.php'],
		//      'times' => 1,
		//  ]
		// );

		// Create the action instance
		$action = new Show_Admin_Notice_And_Disable_Plugin_Action( $plugin_mock, $extra_messages );

		// Call handle() method
		$action->handle();

		// Assert that everything executed without errors
		$this->assertTrue( true );
	}
}
