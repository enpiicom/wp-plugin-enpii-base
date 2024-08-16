<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\App\Support;

use Enpii_Base\App\Support\App_Const;
use Enpii_Base\App\Support\Enpii_Base_Helper;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Mockery;
use WP_Mock;

class Enpii_Base_Helper_Test extends Unit_Test_Case {
	private $backup_SERVER = [];
	private $backup_setup_info = '';

	protected function setUp(): void {
		parent::setUp();

		global $_SERVER;

		$this->backup_SERVER = $_SERVER;
	}

	protected function tearDown(): void {
		global $_SERVER;

		$_SERVER = $this->backup_SERVER;

		parent::tearDown();
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

	public function test_get_setup_info_when_empty() {
        // Reset static property for testing
        Enpii_Base_Helper::$setup_info = null;

        // Mock the get_option function to return a setup info string
        WP_Mock::userFunction('get_option', [
            'times' => 1,
            'args' => [App_Const::OPTION_SETUP_INFO],
            'return' => 'mocked_setup_info',
        ]);

        // Call the function and assert that the returned setup info matches the mocked value
        $this->assertEquals('mocked_setup_info', Enpii_Base_Helper::get_setup_info());

        // Assert that the static property was set correctly
        $this->assertEquals('mocked_setup_info', Enpii_Base_Helper::$setup_info);
    }

    public function test_get_setup_info_when_not_empty() {
		// Set the static property to a predefined value
		Enpii_Base_Helper::$setup_info = 'existing_setup_info';

		// Ensure that get_option is not called by setting expectations
		WP_Mock::userFunction('get_option', [
			'times' => 0, // get_option should not be called
		]);

		// Call the function and assert that the returned setup info matches the static property
		$this->assertEquals('existing_setup_info', Enpii_Base_Helper::get_setup_info());

		// Assert that the static property has not changed
		$this->assertEquals('existing_setup_info', Enpii_Base_Helper::$setup_info);
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

	/**
	 * @runInSeparateProcess
	 */
	public function test_perform_wp_app_check_true() {
		// We need to run this on a separate process to have Enpii_Base_Helper::$setup_info reset
		WP_Mock::userFunction( 'get_option' )
			->times( 1 )
			->with( App_Const::OPTION_SETUP_INFO )
			->andReturnUsing(
				function ( $option_key ) {
					return true;
				}
			);
		$this->assertTrue( Enpii_Base_Helper::perform_wp_app_check() );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_perform_wp_app_check_false() {
		// We need to run this on a separate process to have Enpii_Base_Helper::$setup_info reset
		WP_Mock::userFunction( 'get_option' )
			->times( 1 )
			->with( App_Const::OPTION_SETUP_INFO )
			->andReturnUsing(
				function ( $option_key ) {
					return 'failed';
				}
			);
		WP_Mock::userFunction( 'add_action' )
			->between( 0, 1 )
			->with( 'admin_notices', Mockery::any() )
			->andReturnUsing(
				function ( $action_name, $callback ) {
					return;
				}
			);

		global $_SERVER;
		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['REQUEST_URI'] = '/test';
		WP_Mock::userFunction( 'sanitize_text_field' )
			->between( 0, 10 )
			->withAnyArgs()
			->andReturnUsing(
				function ( $text ) {
					return $text;
				}
			);
		WP_Mock::userFunction( 'site_url' )
			->times( 1 )
			->withAnyArgs()
			->andReturnUsing(
				function () {
					return 'example.com';
				}
			);
		$this->assertFalse( Enpii_Base_Helper::perform_wp_app_check() );
	}

	public function test_put_messages_to_wp_admin_notice() {
		// Initialize the error messages array
		$error_messages = [
			'Error 1' => false,
			'Error 2' => false,
			'' => false, // This one should be ignored
		];

		// Mock the add_action function to ensure it's called with 'admin_notices'
		WP_Mock::userFunction('add_action')
			->with('admin_notices', Mockery::any())
			->andReturnUsing(
				function ($action_name, $callback) {
					return;
				}
			);

		WP_Mock::userFunction('wp_kses_post')
			->withAnyArgs()
			->andReturnUsing(
				function ($text) {
					return $text;
				}
			);

		// Call the function that should trigger the add_action
		Enpii_Base_Helper::put_messages_to_wp_admin_notice($error_messages);

		// Verify the expectations
		WP_Mock::assertHooksAdded();
	}
}
