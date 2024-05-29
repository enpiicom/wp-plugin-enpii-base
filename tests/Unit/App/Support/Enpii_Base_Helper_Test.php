<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit;

use Enpii_Base\App\Support\Enpii_Base_Helper;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use WP_Mock;

class Enpii_Base_Helper_Test extends Unit_Test_Case {
	private $backup_SERVER = [];

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

        $this->assertEquals('', Enpii_Base_Helper::get_current_url());
    }

	public function test_get_current_url_with_http_host(): void {
        global $_SERVER;
        $_SERVER['HTTP_HOST'] = 'example.com';
        $_SERVER['REQUEST_URI'] = '/test';
        $expected_url = 'http://example.com/test';
		WP_Mock::userFunction('sanitize_text_field')
			->twice()
            ->withAnyArgs()
			->andReturnValues(['example.com', '/test']);

        $this->assertEquals($expected_url, Enpii_Base_Helper::get_current_url());
    }

    public function test_get_current_url_with_server_port(): void {
        global $_SERVER;
        $_SERVER['SERVER_NAME'] = 'localhost';
        $_SERVER['SERVER_PORT'] = '8080';
		$_SERVER['REQUEST_URI'] = '/page';
        $expected_url = '//localhost:8080/page';

		WP_Mock::userFunction('sanitize_text_field')
			->times(3)
            ->withAnyArgs()
			->andReturnValues(['localhost', '8080', '/page']);

        $this->assertEquals($expected_url, Enpii_Base_Helper::get_current_url());
    }

    public function test_get_current_url_with_https(): void {
        global $_SERVER;

        $_SERVER['HTTP_HOST'] = 'example.com';
        $_SERVER['REQUEST_URI'] = '/secure';
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
        $expected_url = 'https://example.com/secure';

		WP_Mock::userFunction('sanitize_text_field')
			->times(2)
            ->withAnyArgs()
			->andReturnValues(['example.com', '/secure']);

        $this->assertEquals($expected_url, Enpii_Base_Helper::get_current_url());
    }
}
