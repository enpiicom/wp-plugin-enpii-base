<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit;

use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Mockery;

class Helpers_Test extends Unit_Test_Case {

	protected function setUp(): void {
		parent::setUp();
	}

	protected function tearDown(): void {
		parent::tearDown();
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_enpii_base_wp_app_web_page_title_returns_expected_value() {
		// Mock the Enpii_Base_Helper class and the static method wp_app_web_page_title
		$expected_title = 'Mocked Web Page Title';
		$helper_mock = Mockery::mock( 'alias:Enpii_Base\App\Support\Enpii_Base_Helper' );
		$helper_mock->shouldReceive( 'wp_app_web_page_title' )->once()->andReturn( $expected_title );

		// Call the function and assert the result
		$result = enpii_base_wp_app_web_page_title();
		$this->assertEquals( $expected_title, $result );
	}
}
