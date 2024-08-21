<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\App\Support;

use Closure;
use Enpii_Base\App\Support\App_Const;
use Enpii_Base\App\Support\Enpii_Base_Helper;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Enpii_Base\Tests\Unit\App\Support\Enpii_Base_Helper_Test\Enpii_Base_Helper_Test_Tmp_True;
use Enpii_Base\Tests\Unit\App\Support\Enpii_Base_Helper_Test\Enpii_Base_Helper_Test_Tmp_Is_Console_Mode_Apache;
use Enpii_Base\Tests\Unit\App\Support\Enpii_Base_Helper_Test\Enpii_Base_Helper_Test_Tmp_Is_Console_Mode_Cli;
use Enpii_Base\Tests\Unit\App\Support\Enpii_Base_Helper_Test\Enpii_Base_Helper_Test_Tmp_Is_Console_Mode_Cli_Server;
use Enpii_Base\Tests\Unit\App\Support\Enpii_Base_Helper_Test\Enpii_Base_Helper_Test_Tmp_Is_Console_Mode_Phpdbg;
use Enpii_Base\Tests\Unit\App\Support\Enpii_Base_Helper_Test\Enpii_Base_Helper_Test_Tmp_Setup_App_Not_Completed;
use Mockery;
use WP_Mock;

class Enpii_Base_Helper_Test extends Unit_Test_Case {
	private $backup_SERVER = [];
	
	public static $methods;

	protected function setUp(): void {
		parent::setUp();
		static::$methods = [];

		global $_SERVER;

		$this->backup_SERVER = $_SERVER;
	}

	protected function tearDown(): void {
		global $_SERVER;

		$_SERVER = $this->backup_SERVER;

		parent::tearDown();
		Mockery::close();
	}

	public function test_get_current_url(): void {
		global $_SERVER;
		$_SERVER['SERVER_NAME'] = '';

		$this->assertEquals( '', Enpii_Base_Helper::get_current_url() );
	}

	public function test_get_current_url_with_http_host(): void {
		global $_SERVER;
		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['REQUEST_URI'] = '/test';
		$expected_url = 'http://example.com/test';
		WP_Mock::userFunction( 'sanitize_text_field' )
			->twice()
			->withAnyArgs()
			->andReturnUsing(
				function ( $text ) {
					return $text === 'example.com' ? 'example.com' : '/test';
				}
			);

		$this->assertEquals( $expected_url, Enpii_Base_Helper::get_current_url() );
	}

	public function test_get_current_url_with_server_port(): void {
		global $_SERVER;
		$_SERVER['SERVER_NAME'] = 'localhost';
		$_SERVER['SERVER_PORT'] = '8080';
		$_SERVER['REQUEST_URI'] = '/page';

		WP_Mock::userFunction( 'sanitize_text_field' )
			->times( 3 )
			->withAnyArgs()
			->andReturnValues( [ 'localhost', '8080', '/page' ] );

		$this->assertEquals( '//localhost:8080/page', Enpii_Base_Helper::get_current_url() );

		$_SERVER['SERVER_PORT'] = null;

		WP_Mock::userFunction( 'sanitize_text_field' )
			->times( 2 )
			->withAnyArgs()
			->andReturnValues( [ 'localhost', '/page' ] );

		$this->assertEquals( '//localhost/page', Enpii_Base_Helper::get_current_url() );
	}

	public function test_get_current_url_with_https(): void {
		global $_SERVER;

		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['REQUEST_URI'] = '/secure';
		$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
		$expected_url = 'https://example.com/secure';

		WP_Mock::userFunction( 'sanitize_text_field' )
			->times( 2 )
			->withAnyArgs()
			->andReturnValues( [ 'example.com', '/secure' ] );

		$this->assertEquals( $expected_url, Enpii_Base_Helper::get_current_url() );
	}

	public function test_get_setup_app_uri(): void {
		$this->assertEquals( 'wp-app/setup-app/?force_app_running_in_console=1', Enpii_Base_Helper::get_setup_app_uri( false ) );

		WP_Mock::userFunction( 'site_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturn( 'example.com//' );
		$this->assertEquals( 'example.com/wp-app/setup-app/?force_app_running_in_console=1', Enpii_Base_Helper::get_setup_app_uri( true ) );
	}

	public function test_get_admin_setup_app_uri(): void {
		$this->assertEquals( 'wp-app/admin/setup-app/?force_app_running_in_console=1', Enpii_Base_Helper::get_admin_setup_app_uri( false ) );

		WP_Mock::userFunction( 'site_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturn( 'example.com' );
		$this->assertEquals( 'example.com/wp-app/admin/setup-app/?force_app_running_in_console=1', Enpii_Base_Helper::get_admin_setup_app_uri( true ) );
	}

	public function test_get_wp_login_url(): void {
		WP_Mock::userFunction( 'wp_login_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturn( 'wp-login.php' );
		$this->assertEquals( 'wp-login.php', Enpii_Base_Helper::get_wp_login_url() );
	}

	public function test_at_setup_app_url(): void {
		global $_SERVER;
		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['REQUEST_URI'] = '/test';
		WP_Mock::userFunction( 'sanitize_text_field' )
			->twice()
			->withAnyArgs()
			->andReturnUsing(
				function ( $text ) {
					return $text === 'example.com' ? 'example.com' : '/test';
				}
			);
		$this->assertFalse( Enpii_Base_Helper::at_setup_app_url() );

		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['REQUEST_URI'] = '/wp-app/setup-app/?force_app_running_in_console=1';
		WP_Mock::userFunction( 'sanitize_text_field' )
			->twice()
			->withAnyArgs()
			->andReturnUsing(
				function ( $text ) {
					return $text;
				}
			);

		$this->assertTrue( Enpii_Base_Helper::at_setup_app_url() );
	}

	public function test_at_admin_setup_app_url(): void {
		global $_SERVER;
		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['REQUEST_URI'] = '/test';
		WP_Mock::userFunction( 'sanitize_text_field' )
			->twice()
			->withAnyArgs()
			->andReturnUsing(
				function ( $text ) {
					return $text === 'example.com' ? 'example.com' : '/test';
				}
			);
		$this->assertFalse( Enpii_Base_Helper::at_admin_setup_app_url() );

		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['REQUEST_URI'] = '/wp-app/admin/setup-app/?force_app_running_in_console=1';
		WP_Mock::userFunction( 'sanitize_text_field' )
			->twice()
			->withAnyArgs()
			->andReturnUsing(
				function ( $text ) {
					return $text;
				}
			);

		$this->assertTrue( Enpii_Base_Helper::at_admin_setup_app_url() );
	}

	public function test_at_wp_login_url(): void {
		global $_SERVER;
		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['REQUEST_URI'] = '/test';
		WP_Mock::userFunction( 'sanitize_text_field' )
			->twice()
			->withAnyArgs()
			->andReturnUsing(
				function ( $text ) {
					return $text;
				}
			);
		WP_Mock::userFunction( 'wp_login_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturn( 'wp-login.php' );
		$this->assertFalse( Enpii_Base_Helper::at_wp_login_url() );

		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['REQUEST_URI'] = '/wp-login.php?abc=2';
		WP_Mock::userFunction( 'sanitize_text_field' )
			->twice()
			->withAnyArgs()
			->andReturnUsing(
				function ( $text ) {
					return $text;
				}
			);
		WP_Mock::userFunction( 'wp_login_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturn( 'wp-login.php' );
		$this->assertTrue( Enpii_Base_Helper::at_wp_login_url() );
	}

	public function test_redirect_to_setup_url(): void {
		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['REQUEST_URI'] = '/wp-app/setup-app/?force_app_running_in_console=1';
		WP_Mock::userFunction( 'sanitize_text_field' )
			->withAnyArgs()
			->andReturnUsing(
				function ( $text ) {
					return $text;
				}
			);

		$this->expectOutputString( '' );

		$this->assertNull( Enpii_Base_Helper::redirect_to_setup_url() );

		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['REQUEST_URI'] = '/page';
		WP_Mock::userFunction( 'sanitize_text_field' )
			->withAnyArgs()
			->andReturnUsing(
				function ( $text ) {
					return $text;
				}
			);
		WP_Mock::userFunction( 'add_query_arg' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function ( $args, $url ) {
					return $url . '&return_url=' . urlencode( Enpii_Base_Helper::get_current_url() );
				}
			);
		WP_Mock::userFunction( 'site_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function ( $args ) {
					return 'http://example.com/wp-app/setup-app/?force_app_running_in_console=1';
				}
			);
		// We expect exception here as the method would send a header then exit
		$this->expectException( \PHPUnit\Framework\Exception::class );

		$this->assertNull( Enpii_Base_Helper::redirect_to_setup_url() );
	}

	public function test_get_base_url_path(): void {
		WP_Mock::userFunction( 'site_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function () {
					return 'http://example.com';
				}
			);
		WP_Mock::userFunction( 'wp_parse_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function ( $url ) {
					return parse_url( $url );
				}
			);
		$this->assertEmpty( Enpii_Base_Helper::get_base_url_path() );

		WP_Mock::userFunction( 'site_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function () {
					return 'http://example.com/test';
				}
			);
		WP_Mock::userFunction( 'wp_parse_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function ( $url ) {
					return parse_url( $url );
				}
			);
		$this->assertEquals( '/test', Enpii_Base_Helper::get_base_url_path() );

		WP_Mock::userFunction( 'site_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function () {
					return 'http://example.com/test/test2';
				}
			);
		WP_Mock::userFunction( 'wp_parse_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function ( $url ) {
					return parse_url( $url );
				}
			);
		$this->assertEquals( '/test/test2', Enpii_Base_Helper::get_base_url_path() );
	}

	public function test_get_current_blog_path(): void {
		WP_Mock::userFunction( 'site_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function () {
					return 'http://example.com';
				}
			);
		WP_Mock::userFunction( 'network_site_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function () {
					return 'http://example.com';
				}
			);
		$this->assertEquals( null, Enpii_Base_Helper::get_current_blog_path() );

		WP_Mock::userFunction( 'site_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function () {
					return 'http://example.com/path1';
				}
			);
		WP_Mock::userFunction( 'network_site_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function () {
					return 'http://example.com';
				}
			);
		$this->assertEquals( 'path1', Enpii_Base_Helper::get_current_blog_path() );

		WP_Mock::userFunction( 'site_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function () {
					return 'http://example.com/path1/path2';
				}
			);
		WP_Mock::userFunction( 'network_site_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function () {
					return 'http://example.com';
				}
			);
		$this->assertEquals( 'path1/path2', Enpii_Base_Helper::get_current_blog_path() );

		WP_Mock::userFunction( 'site_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function () {
					return 'http://example.com/path1/path2';
				}
			);
		WP_Mock::userFunction( 'network_site_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function () {
					return 'http://not-an-example.com';
				}
			);
		$this->assertEquals( null, Enpii_Base_Helper::get_current_blog_path() );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_is_setup_app_completed_false() {
		// We need to run this on a separate process to have Enpii_Base_Helper::$version_option reset
		WP_Mock::userFunction( 'get_option' )
			->times( 1 )
			->with( App_Const::OPTION_VERSION, Mockery::any() )
			->andReturnUsing(
				function ( $option_key ) {
					return '0.6.0';
				}
			);
		$this->assertFalse( Enpii_Base_Helper::is_setup_app_completed() );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_is_setup_app_completed_true() {
		// We need to run this on a separate process to have Enpii_Base_Helper::$version_option reset
		WP_Mock::userFunction( 'get_option' )
			->times( 1 )
			->with( App_Const::OPTION_VERSION, Mockery::any() )
			->andReturnUsing(
				function ( $option_key ) {
					return '0.7.0';
				}
			);
		$this->assertTrue( Enpii_Base_Helper::is_setup_app_completed() );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_is_setup_app_failed() {
		// We need to run this on a separate process to have Enpii_Base_Helper::$setup_info reset
		WP_Mock::userFunction( 'get_option' )
			->times( 1 )
			->with( App_Const::OPTION_SETUP_INFO )
			->andReturnUsing(
				function ( $option_key ) {
					return 'failed';
				}
			);
		$this->assertTrue( Enpii_Base_Helper::is_setup_app_failed() );
	}

	public function test_perform_wp_app_check_true() {
		// We assert this return true as $wp_app_check is set to true in tmp class
		$this->assertTrue( Enpii_Base_Helper_Test_Tmp_True::perform_wp_app_check() );
	}

	public function test_put_messages_to_wp_admin_notice() {
		// Mock the add_action function
		// Create a sample array of error messages
		$error_messages = [ 'Error 1', 'Error 2' ];

		// Mock add_action to ensure it is called with 'admin_notices'
		WP_Mock::expectActionAdded(
			'admin_notices',
			function ( $callback ) {
				// Ensure that the callback is of type Closure
				$this->assertInstanceOf( Closure::class, $callback );
			}
		);

		// Call the method that adds the action
		Enpii_Base_Helper::put_messages_to_wp_admin_notice( $error_messages );

		// Verify that the action was added exactly once
		WP_Mock::assertHooksAdded();
	}

	public function test_add_wp_app_setup_errors() {
		// Clear the global variable for the test
		unset( $GLOBALS['wp_app_setup_errors'] );

		// Call the method
		Enpii_Base_Helper::add_wp_app_setup_errors( 'Test Error' );

		// Assert the global variable is initialized
		$this->assertIsArray( $GLOBALS['wp_app_setup_errors'] );
		$this->assertArrayHasKey( 'Test Error', $GLOBALS['wp_app_setup_errors'] );
		$this->assertFalse( $GLOBALS['wp_app_setup_errors']['Test Error'] );
	}

	public function test_get_wp_app_setup_errors_returns_empty_array_when_not_set() {
		// Ensure the global variable is not set
		unset( $GLOBALS['wp_app_setup_errors'] );

		// Call the method
		$result = Enpii_Base_Helper::get_wp_app_setup_errors();

		// Assert it returns an empty array
		$this->assertIsArray( $result );
		$this->assertEmpty( $result );
	}

	public function test_get_wp_app_setup_errors_returns_global_array_when_set() {
		// Set up the global variable with some errors
		$GLOBALS['wp_app_setup_errors'] = [
			'Error 1' => true,
			'Error 2' => false,
		];

		// Call the method
		$result = Enpii_Base_Helper::get_wp_app_setup_errors();

		// Assert it returns the global array
		$this->assertIsArray( $result );
		$this->assertCount( 2, $result );
		$this->assertArrayHasKey( 'Error 1', $result );
		$this->assertArrayHasKey( 'Error 2', $result );
		$this->assertTrue( $result['Error 1'] );
		$this->assertFalse( $result['Error 2'] );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_get_wp_app_base_path_returns_defined_constant_value() {
		// Define the constant
		define( 'ENPII_BASE_WP_APP_BASE_PATH', '/custom/path/to/wp-app' );

		// Call the method
		$result = Enpii_Base_Helper::get_wp_app_base_path();

		// Assert the result matches the defined constant value
		$this->assertEquals( '/custom/path/to/wp-app', $result );
	}

	public function test_get_wp_app_base_folders_paths() {
		// Define the base path and directory separator for the test
		$wp_app_base_path = '/var/www/wp-app';
		$dir_sep = '/'; // Typically '/' on Unix-based systems

		// Call the method
		$result = Enpii_Base_Helper::get_wp_app_base_folders_paths( $wp_app_base_path );

		// Expected paths array
		$expected = [
			'base_path' => $wp_app_base_path,
			'config_path' => $wp_app_base_path . $dir_sep . 'config',
			'database_path' => $wp_app_base_path . $dir_sep . 'database',
			'database_migrations_path' => $wp_app_base_path . $dir_sep . 'database' . $dir_sep . 'migrations',
			'bootstrap_path' => $wp_app_base_path . $dir_sep . 'bootstrap',
			'bootstrap_cache_path' => $wp_app_base_path . $dir_sep . 'bootstrap' . $dir_sep . 'cache',
			'lang_path' => $wp_app_base_path . $dir_sep . 'lang',
			'resources_path' => $wp_app_base_path . $dir_sep . 'resources',
			'storage_path' => $wp_app_base_path . $dir_sep . 'storage',
			'storage_logs_path' => $wp_app_base_path . $dir_sep . 'storage' . $dir_sep . 'logs',
			'storage_framework_path' => $wp_app_base_path . $dir_sep . 'storage' . $dir_sep . 'framework',
			'storage_framework_views_path' => $wp_app_base_path . $dir_sep . 'storage' . $dir_sep . 'framework' . $dir_sep . 'views',
			'storage_framework_cache_path' => $wp_app_base_path . $dir_sep . 'storage' . $dir_sep . 'framework' . $dir_sep . 'cache',
			'storage_framework_cache_data_path' => $wp_app_base_path . $dir_sep . 'storage' . $dir_sep . 'framework' . $dir_sep . 'cache' . $dir_sep . 'data',
			'storage_framework_sessions_path' => $wp_app_base_path . $dir_sep . 'storage' . $dir_sep . 'framework' . $dir_sep . 'sessions',
		];

		// Assert that the result matches the expected paths
		$this->assertEquals( $expected, $result );
	}

	public function test_wp_cli_init() {
		// Mock the WP_CLI class
		$mocked_wp_cli = Mockery::mock( 'alias:\WP_CLI' );
		
		// Expect that the add_command method will be called once with the expected parameters
		$mocked_wp_cli->shouldReceive( 'add_command' )
			->once()
			->with( 'enpii-base prepare', [ Enpii_Base_Helper::class, 'wp_cli_prepare' ] );

		// Call the method
		Enpii_Base_Helper::wp_cli_init();

		// The method doesn't return anything, so we assert that the mock expectations were met
		$this->assertTrue( true );
	}

	public function test_maybe_redirect_to_setup_app_when_setup_completed() {
		// Execute the method
		Enpii_Base_Helper_Test_Tmp_True::maybe_redirect_to_setup_app();
		$this->assertTrue( true );
	}

	public function test_maybe_redirect_to_setup_app_when_setup_not_completed() {
		// Execute the method
		Enpii_Base_Helper_Test_Tmp_Setup_App_Not_Completed::maybe_redirect_to_setup_app();
		$this->assertTrue( in_array( 'prepare_wp_app_folders', static::$methods ) );
		$this->assertTrue( in_array( 'redirect_to_setup_url', static::$methods ) );
	}

	public function test_wp_cli_prepare() {
		// Execute the method
		Enpii_Base_Helper_Test_Tmp_Setup_App_Not_Completed::wp_cli_prepare( [], [] );
		$this->assertTrue( in_array( 'prepare_wp_app_folders', static::$methods ) );
	}

	public function test_get_major_version_with_valid_version() {
		$version = '1.2.3';
		$expected_major_version = 1;

		$result = Enpii_Base_Helper::get_major_version( $version );

		$this->assertEquals( $expected_major_version, $result );
	}

	public function test_get_major_version_with_major_only_version() {
		$version = '2';
		$expected_major_version = 2;

		$result = Enpii_Base_Helper::get_major_version( $version );

		$this->assertEquals( $expected_major_version, $result );
	}

	public function test_get_major_version_with_extra_characters() {
		$version = 'v3.4.5-beta';
		$expected_major_version = 3;

		$result = Enpii_Base_Helper::get_major_version( $version );

		$this->assertEquals( $expected_major_version, $result );
	}

	public function test_get_major_version_with_non_numeric_major_version() {
		$version = 'v4.x.y';
		$expected_major_version = 4;

		$result = Enpii_Base_Helper::get_major_version( $version );

		$this->assertEquals( $expected_major_version, $result );
	}

	public function test_get_major_version_with_empty_version_string() {
		$version = '';
		$expected_major_version = 0;

		$result = Enpii_Base_Helper::get_major_version( $version );

		$this->assertEquals( $expected_major_version, $result );
	}

	public function test_get_major_version_with_no_major_version() {
		$version = '.2.3';
		$expected_major_version = 0;

		$result = Enpii_Base_Helper::get_major_version( $version );

		$this->assertEquals( $expected_major_version, $result );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_wp_app_web_page_title_with_wp_title() {
		// Mock wp_title to return a title
		WP_Mock::userFunction(
			'wp_title',
			[
				'args'   => [ '', false ],
				'return' => 'Test Page Title',
			]
		);

		// Call the method
		$result = Enpii_Base_Helper::wp_app_web_page_title();

		// Assert that the title is correctly returned
		$this->assertEquals( 'Test Page Title', $result );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_wp_app_web_page_title_with_empty_wp_title() {
		// Mock wp_title to return an empty string
		WP_Mock::userFunction(
			'wp_title',
			[
				'args'   => [ '', false ],
				'return' => '',
			]
		);

		// Mock get_bloginfo to return the site name and description
		WP_Mock::userFunction(
			'get_bloginfo',
			[
				'args'   => 'name',
				'return' => 'My Blog',
			]
		);

		WP_Mock::userFunction(
			'get_bloginfo',
			[
				'args'   => 'description',
				'return' => 'Just another WordPress site',
			]
		);

		// Mock apply_filters to return the expected title
		WP_Mock::onFilter( App_Const::FILTER_WP_APP_WEB_PAGE_TITLE )
			->with( 'My Blog | Just another WordPress site' )
			->reply( 'My Blog | Just another WordPress site' );

		// Call the method
		$result = Enpii_Base_Helper::wp_app_web_page_title();

		// Assert that the title is correctly constructed
		$this->assertEquals( 'My Blog | Just another WordPress site', $result );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_wp_app_web_page_title_with_empty_wp_title_and_description() {
		// Mock wp_title to return an empty string
		WP_Mock::userFunction(
			'wp_title',
			[
				'args'   => [ '', false ],
				'return' => '',
			]
		);

		// Mock get_bloginfo to return the site name and an empty description
		WP_Mock::userFunction(
			'get_bloginfo',
			[
				'args'   => 'name',
				'return' => 'My Blog',
			]
		);

		WP_Mock::userFunction(
			'get_bloginfo',
			[
				'args'   => 'description',
				'return' => '',
			]
		);

		// Mock apply_filters to return the expected title
		WP_Mock::onFilter( App_Const::FILTER_WP_APP_WEB_PAGE_TITLE )
			->with( 'My Blog | WP App' )
			->reply( 'My Blog | WP App' );

		// Call the method
		$result = Enpii_Base_Helper::wp_app_web_page_title();

		// Assert that the title is correctly constructed
		$this->assertEquals( 'My Blog | WP App', $result );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_wp_app_get_asset_url_defined_constant_full_url() {
		if ( ! defined( 'ENPII_BASE_WP_APP_ASSET_URL' ) ) {
			define( 'ENPII_BASE_WP_APP_ASSET_URL', 'http://example.com' );
		}

		// Call the method with full_url = false
		$result = Enpii_Base_Helper_Test_Tmp_True::wp_app_get_asset_url( true );

		// Assert that the method returns the correct slug path
		$this->assertEquals( ENPII_BASE_WP_APP_ASSET_URL, $result );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_is_wp_content_loaded_when_constant_defined() {
		// Ensure the constant WP_CONTENT_DIR is defined
		if ( ! defined( 'WP_CONTENT_DIR' ) ) {
			define( 'WP_CONTENT_DIR', '/path/to/wp-content' );
		}

		// Mock the get_site_url function
		WP_Mock::userFunction(
			'get_site_url',
			[
				'return' => 'https://example.com',
			]
		);

		// Call the method and check the result
		$result = Enpii_Base_Helper::is_wp_content_loaded();

		// Assert that the method returns true
		$this->assertTrue( $result );
	}

	public function test_is_console_mode_true_cli() {
		// Call the is_console_mode method statically
		$result = Enpii_Base_Helper_Test_Tmp_Is_Console_Mode_Cli::is_console_mode();

		// Assert that it returns true
		$this->assertTrue( $result );
	}

	public function test_is_console_mode_true_phpdbg() {
		// Call the is_console_mode method statically
		$result = Enpii_Base_Helper_Test_Tmp_Is_Console_Mode_Phpdbg::is_console_mode();

		// Assert that it returns true
		$this->assertTrue( $result );
	}

	public function test_is_console_mode_true_cli_server() {
		// Call the is_console_mode method statically
		$result = Enpii_Base_Helper_Test_Tmp_Is_Console_Mode_Cli_Server::is_console_mode();

		// Assert that it returns true
		$this->assertTrue( $result );
	}

	public function test_is_console_mode_false() {
		// Call the is_console_mode method statically
		$result = Enpii_Base_Helper_Test_Tmp_Is_Console_Mode_Apache::is_console_mode();

		// Assert that it returns true
		$this->assertFalse( $result );
	}

	public function test_get_php_sapi_name() {
		// Get the expected SAPI name directly using php_sapi_name
		$expected_sapi_name = php_sapi_name();

		// Call the method
		$result = Enpii_Base_Helper::get_php_sapi_name();

		// Assert that the method returns the correct SAPI name
		$this->assertEquals( $expected_sapi_name, $result );
	}

	public function test_use_enpii_base_error_handler() {
		// Mock the apply_filters function to ensure it processes the value
		WP_Mock::onFilter( 'enpii_base_use_error_handler' )
		->with( true )
		->reply( true );

		// Call the method
		$result = Enpii_Base_Helper_Test_Tmp_True::use_enpii_base_error_handler();

		// Assert that it returns true
		$this->assertTrue( $result );
	}

	public function test_get_use_error_handler_setting_returns_true_when_constant_defined() {
		// Define the constant if not already defined
		if ( ! defined( 'ENPII_BASE_USE_ERROR_HANDLER' ) ) {
			define( 'ENPII_BASE_USE_ERROR_HANDLER', true );
		}

		// Call the method and check the result
		$result = Enpii_Base_Helper::get_use_error_handler_setting();

		// Assert that it returns true
		$this->assertTrue( $result );
	}

	public function test_use_blade_for_wp_template() {
		// Mock the apply_filters function to ensure it processes the value
		WP_Mock::onFilter( 'enpii_base_use_blade_for_wp_template' )
		->with( true )
		->reply( true );

		// Call the method
		$result = Enpii_Base_Helper_Test_Tmp_True::use_blade_for_wp_template();

		// Assert that it returns true
		$this->assertTrue( $result );
	}

	public function test_get_blade_for_wp_template_setting_returns_true_when_constant_defined() {
		// Define the constant if not already defined
		if ( ! defined( 'ENPII_BASE_USE_BLADE_FOR_WP_TEMPLATE' ) ) {
			define( 'ENPII_BASE_USE_BLADE_FOR_WP_TEMPLATE', true );
		}

		// Call the method and check the result
		$result = Enpii_Base_Helper::get_blade_for_wp_template_setting();

		// Assert that it returns true
		$this->assertTrue( $result );
	}

	public function test_disable_web_worker() {
		// Mock the apply_filters function to ensure it processes the value
		WP_Mock::onFilter( 'enpii_base_disable_web_worker' )
		->with( true )
		->reply( true );

		// Call the method
		$result = Enpii_Base_Helper_Test_Tmp_True::disable_web_worker();

		// Assert that it returns true
		$this->assertTrue( $result );
	}
}

namespace Enpii_Base\Tests\Unit\App\Support\Enpii_Base_Helper_Test;

use Enpii_Base\App\Support\Enpii_Base_Helper;
use Enpii_Base\Tests\Unit\App\Support\Enpii_Base_Helper_Test;

class Enpii_Base_Helper_Test_Tmp_True extends Enpii_Base_Helper {

	public static $wp_app_check = true;

	public static function add_wp_app_setup_errors( $error_message ) {
		Enpii_Base_Helper_Test::$methods[] = 'add_wp_app_setup_errors'; 
	}

	public static function is_setup_app_completed() {
		return true;
	}

	public static function get_use_error_handler_setting(): bool {
		return true;
	}

	public static function get_blade_for_wp_template_setting(): bool {
		return true;
	}

	public static function get_disable_web_worker_status(): bool {
		return true;
	}
}

class Enpii_Base_Helper_Test_Tmp_False extends Enpii_Base_Helper {

	public static function is_setup_app_completed() {
		return false;
	}
}

class Enpii_Base_Helper_Test_Tmp_Setup_App_Not_Completed extends Enpii_Base_Helper {
	public static function is_setup_app_completed() {
		return false;
	}

	public static function prepare_wp_app_folders( $chmod = 0777, string $wp_app_base_path = '' ): void {
		Enpii_Base_Helper_Test::$methods[] = 'prepare_wp_app_folders'; 
	}

	public static function is_setup_app_failed() {
		return false;
	}

	public static function redirect_to_setup_url(): void {
		Enpii_Base_Helper_Test::$methods[] = 'redirect_to_setup_url'; 
	}
}

class Enpii_Base_Helper_Test_Tmp_Is_Console_Mode_Cli extends Enpii_Base_Helper {
	public static function get_php_sapi_name(): string {
		return 'cli';
	}
}

class Enpii_Base_Helper_Test_Tmp_Is_Console_Mode_Phpdbg extends Enpii_Base_Helper {
	public static function get_php_sapi_name(): string {
		return 'phpdbg';
	}
}

class Enpii_Base_Helper_Test_Tmp_Is_Console_Mode_Cli_Server extends Enpii_Base_Helper {
	public static function get_php_sapi_name(): string {
		return 'cli-server';
	}
}

class Enpii_Base_Helper_Test_Tmp_Is_Console_Mode_Apache extends Enpii_Base_Helper {
	public static function get_php_sapi_name(): string {
		return 'apache2handler';
	}
}
