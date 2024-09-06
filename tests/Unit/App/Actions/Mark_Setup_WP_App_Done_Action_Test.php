<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\App\Actions;

use Enpii_Base\App\Actions\Mark_Setup_WP_App_Done_Action;
use Enpii_Base\App\Support\App_Const;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Mockery;
use WP_Mock;


class Mark_Setup_WP_App_Done_Action_Test extends Unit_Test_Case {

	protected function setUp(): void {
		parent::setUp();
	}

	protected function tearDown(): void {
		parent::tearDown();
		Mockery::close();
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_handle() {
		// Arrange: Mock the WordPress functions
		WP_Mock::userFunction(
			'update_option',
			[
				'times' => 1,
				'args' => [ App_Const::OPTION_VERSION, ENPII_BASE_PLUGIN_VERSION, false ],
				'return' => true,
			]
		);

		WP_Mock::userFunction(
			'delete_option',
			[
				'times' => 1,
				'args' => [ App_Const::OPTION_SETUP_INFO ],
				'return' => true,
			]
		);

		WP_Mock::expectAction( App_Const::ACTION_WP_APP_MARK_SETUP_APP_DONE );

		// Act: Execute the handle method
		$action = new Mark_Setup_WP_App_Done_Action();
		$action->handle();

		// Assert: Ensure that the functions are called as expected
		WP_Mock::assertActionsCalled();
	}
}
