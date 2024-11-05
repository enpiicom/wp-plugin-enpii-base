<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\App\Actions;

use Enpii_Base\App\Actions\Get_WP_App_Info_Action;
use Enpii_Base\App\Actions\Put_Setup_Error_Message_To_Log_File_Action;
use Enpii_Base\App\WP\WP_Application;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Mockery;

class Put_Setup_Error_Message_To_Log_File_Action_Test extends Unit_Test_Case {


	protected function setUp(): void {
		parent::setUp();
	}

	protected function tearDown(): void {
		parent::tearDown();
		Mockery::close();
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_handle_logs_error_and_information() {
		$loggerMock = Mockery::mock( Logger::class );
		$loggerMock->shouldReceive()->times( 6 );

		/** @var WP_Application $app_mock */
		$app_mock = $this->createMock( WP_Application::class );

		// Mock the `make` method to always return the same instances
		$app_mock->method( 'make' )->willReturnMap(
			[
				[ Logger::class, [], $loggerMock ],
				[ 'path.config', [], DIRECTORY_SEPARATOR . 'config' ],
				[ 'path.storage', [], DIRECTORY_SEPARATOR . 'storage' ],
			]
		);

		// Set the mocked WP_Application instance globally
		WP_Application::setInstance( $app_mock );

		// Mock Get_WP_App_Info_Action::exec to return sample data
		$appInfoMock = Mockery::mock( 'alias:' . Get_WP_App_Info_Action::class );
		$appInfoMock->shouldReceive( 'exec' )->once()->andReturn( [ 'key' => 'value' ] );

		// Run the action with a test message
		$action = new Put_Setup_Error_Message_To_Log_File_Action( 'Test error message' );
		$action->handle();

		$this->assertTrue( true );
	}
}
