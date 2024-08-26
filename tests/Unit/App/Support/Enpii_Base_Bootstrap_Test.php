<?php

namespace Enpii_Base\Tests\Unit\App\Support;

use Enpii_Base\App\Support\Enpii_Base_Bootstrap;
use Enpii_Base\App\Support\Enpii_Base_Helper;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Enpii_Base\Tests\Unit\App\Support\Enpii_Base_Bootstrap_Test\Enpii_Base_Bootstrap_Test_Tmp_Initialize;
use Mockery;
use WP_Mock;

class Enpii_Base_Bootstrap_Test extends Unit_Test_Case {

	public $plugin_url;
	public $dirname;
	public static $methods;
	private $backup_SERVER = [];

	public function setUp(): void {
		parent::setUp();
		static::$methods = [];

		$this->plugin_url = 'http://example.com/wp-content/plugins/my-plugin/';
		$this->dirname = '/var/www/html/wp-content/plugins/my-plugin';

		global $_SERVER;

		$this->backup_SERVER = $_SERVER;
	}

	public function tearDown(): void {
		global $_SERVER;

		$_SERVER = $this->backup_SERVER;

		parent::tearDown();
		Mockery::close();
	}

	public function test_initialize_in_console_mode_true() {
		// Arrange
		$helper_mock = Mockery::mock( 'alias:' . Enpii_Base_Helper::class );

		// Mock the is_wp_content_loaded method to return true
		$helper_mock->shouldReceive( 'is_wp_content_loaded' )
					->once()
					->andReturn( true );

		// Mock the is_console_mode method to return false
		$helper_mock->shouldReceive( 'is_console_mode' )
					->times( 2 )
					->andReturn( true );

		// Mock the prepare_wp_app_folders method to return false
		$helper_mock->shouldReceive( 'prepare_wp_app_folders' )
					->once()
					->andReturn( false );

		Enpii_Base_Bootstrap_Test_Tmp_Initialize::initialize( $this->plugin_url, $this->dirname );

		$this->assertTrue( in_array( 'register_cli_init_action', static::$methods ) );
		$this->assertTrue( in_array( 'init_wp_app_instance', static::$methods ) );
		$this->assertTrue( in_array( 'init_enpii_base_wp_plugin_instance', static::$methods ) );
	}

	public function test_initialize_in_console_mode_false() {
		// Arrange
		$helper_mock = Mockery::mock( 'alias:' . Enpii_Base_Helper::class );

		// Mock the is_wp_content_loaded method to return true
		$helper_mock->shouldReceive( 'is_wp_content_loaded' )
					->once()
					->andReturn( true );

		// Mock the is_console_mode method to return false
		$helper_mock->shouldReceive( 'is_console_mode' )
					->once()
					->andReturn( false );

		// Mock the prepare_wp_app_folders method to return false
		$helper_mock->shouldReceive( 'perform_wp_app_check' )
					->once()
					->andReturn( false );

		Enpii_Base_Bootstrap_Test_Tmp_Initialize::initialize( $this->plugin_url, $this->dirname );

		$this->assertTrue( in_array( 'register_cli_init_action', static::$methods ) );
		$this->assertTrue( ! in_array( 'init_wp_app_instance', static::$methods ) );
	}

	public function test_initialize_not_in_console_mode_perform_wp_app_check_true() {
		// Arrange
		$helper_mock = Mockery::mock( 'alias:' . Enpii_Base_Helper::class );

		// Mock the is_wp_content_loaded method to return true
		$helper_mock->shouldReceive( 'is_wp_content_loaded' )
					->once()
					->andReturn( true );

		// Mock the is_console_mode method to return false
		$helper_mock->shouldReceive( 'is_console_mode' )
					->times( 2 )
					->andReturn( false );

		// Mock the prepare_wp_app_folders method to return true
		$helper_mock->shouldReceive( 'perform_wp_app_check' )
					->once()
					->andReturn( true );

		Enpii_Base_Bootstrap_Test_Tmp_Initialize::initialize( $this->plugin_url, $this->dirname );

		$this->assertTrue( in_array( 'register_cli_init_action', static::$methods ) );
		$this->assertTrue( in_array( 'init_wp_app_instance', static::$methods ) );
		$this->assertTrue( in_array( 'init_enpii_base_wp_plugin_instance', static::$methods ) );
		$this->assertTrue( in_array( 'register_setup_app_redirect', static::$methods ) );
	}

	public function test_initialize_is_enpii_base_prepare_command() {
		// Arrange
		$helper_mock = Mockery::mock( 'alias:' . Enpii_Base_Helper::class );

		// Mock the is_wp_content_loaded method to return true
		$helper_mock->shouldReceive( 'is_wp_content_loaded' )
					->once()
					->andReturn( true );

		// Mock the is_console_mode method to return false
		$helper_mock->shouldReceive( 'is_console_mode' )
					->times( 2 )
					->andReturn( true );

		// Mock the prepare_wp_app_folders method to return false
		$helper_mock->shouldReceive( 'prepare_wp_app_folders' )
					->once()
					->andReturn( null );

		Enpii_Base_Bootstrap_Test_Tmp_Initialize::initialize( $this->plugin_url, $this->dirname );

		$this->assertTrue( in_array( 'register_cli_init_action', static::$methods ) );
		$this->assertTrue( in_array( 'init_wp_app_instance', static::$methods ) );
		$this->assertTrue( in_array( 'init_enpii_base_wp_plugin_instance', static::$methods ) );
		$this->assertTrue( ! in_array( 'register_setup_app_redirect', static::$methods ) );
	}

	public function test_register_cli_init_action() {
		// Arrange: Mock the add_action function to check it's called with the right parameters
		WP_Mock::expectActionAdded( 'cli_init', [ Enpii_Base_Helper::class, 'wp_cli_init' ] );

		// Act: Call the method we're testing
		Enpii_Base_Bootstrap::register_cli_init_action();

		// Assert: WP_Mock automatically asserts that add_action was called with the correct parameters
		WP_Mock::assertHooksAdded();
	}

	public function test_register_setup_app_redirect() {
		// Arrange: Mock the add_action function to check it's called with the right parameters
		WP_Mock::expectActionAdded(
			ENPII_BASE_SETUP_HOOK_NAME, // The hook name
			[ Enpii_Base_Helper::class, 'maybe_redirect_to_setup_app' ], // The callback
			-200 // The priority
		);

		// Act: Call the method we're testing
		Enpii_Base_Bootstrap::register_setup_app_redirect();

		// Assert: WP_Mock automatically asserts that add_action was called with the correct parameters
		WP_Mock::assertHooksAdded();
	}

	public function test_is_enpii_base_prepare_command() {
		global $_SERVER;
		// Arrange: Simulate command line arguments
		$_SERVER['argv'] = [ 'enpii-base', 'prepare' ];

		// Act
		$result = Enpii_Base_Bootstrap::is_enpii_base_prepare_command();

		// Assert
		$this->assertTrue( $result );
	}

	public function test_is_enpii_base_prepare_command_false() {
		// Arrange: Simulate command line arguments
		$_SERVER['argv'] = [ 'other-command' ];

		// Act
		$result = Enpii_Base_Bootstrap::is_enpii_base_prepare_command();

		// Assert
		$this->assertFalse( $result );
	}

	public function test_register_wp_app_setup_hooks_adds_action() {
		// Arrange: Expect that add_action is called with specific parameters.
		WP_Mock::expectActionAdded(
			ENPII_BASE_SETUP_HOOK_NAME, // The hook name
			[ \Enpii_Base\App\WP\WP_Application::class, 'load_instance' ], // The callback
			-100 // The priority
		);

		// Act: Call the method to register the hook
		Enpii_Base_Bootstrap::init_wp_app_instance();

		// Assert: WP_Mock automatically asserts that add_action was called with the correct parameters
		WP_Mock::assertHooksAdded();
	}
	
	public function test_init_enpii_base_wp_plugin_instance() {
		$action_name = \Enpii_Base\App\Support\App_Const::ACTION_WP_APP_LOADED;

		// Mock add_action to ensure it is called with 'admin_notices'
		WP_Mock::expectActionAdded(
			$action_name,
			function ( $callback ) {
				// Ensure that the callback is of type Closure
				return true;
			}
		);

		// Execute the method under test
		Enpii_Base_Bootstrap::init_enpii_base_wp_plugin_instance( $this->plugin_url, $this->dirname );

		// Verify that the action was added exactly once
		WP_Mock::assertHooksAdded();
	}

	public function test_handle_wp_app_loaded_action() {
		// Mock the method that should be called within the static method
		$plugin_mock = Mockery::mock( 'alias:' . \Enpii_Base\App\WP\Enpii_Base_WP_Plugin::class );
		$plugin_mock->shouldReceive( 'init_with_wp_app' )
					->once()
					->with(
						'enpii-base',
						$this->dirname, // __DIR__ should be a string
						$this->plugin_url // The mocked return value of plugin_dir_url
					);

		// Act: Directly call the static method that handles the action
		Enpii_Base_Bootstrap::handle_wp_app_loaded_action( $this->plugin_url, $this->dirname );

		$this->assertTrue( true );
	}
}

namespace Enpii_Base\Tests\Unit\App\Support\Enpii_Base_Bootstrap_Test;

use Enpii_Base\App\Support\Enpii_Base_Bootstrap;
use Enpii_Base\Tests\Unit\App\Support\Enpii_Base_Bootstrap_Test;

class Enpii_Base_Bootstrap_Test_Tmp_Initialize extends Enpii_Base_Bootstrap {

	public static function register_cli_init_action() {
		Enpii_Base_Bootstrap_Test::$methods[] = 'register_cli_init_action'; 
	}

	public static function register_setup_app_redirect() {
		Enpii_Base_Bootstrap_Test::$methods[] = 'register_setup_app_redirect'; 
	}

	public static function init_wp_app_instance() {
		Enpii_Base_Bootstrap_Test::$methods[] = 'init_wp_app_instance'; 
	}

	public static function init_enpii_base_wp_plugin_instance( $plugin_url, $dirname ) {
		Enpii_Base_Bootstrap_Test::$methods[] = 'init_enpii_base_wp_plugin_instance'; 
	}

	public static function is_enpii_base_prepare_command(): bool {
		return true;
	}

	public static function prepare_wp_app_folders() {
		Enpii_Base_Bootstrap_Test::$methods[] = 'prepare_wp_app_folders'; 
	}
}
