<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Enpii_Base\App\Actions\WP_CLI\Prepare_WP_App_Folders_Action;

class Prepare_WP_App_Folders_Action_Test extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		WP_Mock::setUp();
	}

	protected function tearDown(): void {
		WP_Mock::tearDown();
		parent::tearDown();
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_handle() {
		// Mock Enpii_Base_Helper::prepare_wp_app_folders method
		$helper_mock = Mockery::mock( 'alias:Enpii_Base\App\Support\Enpii_Base_Helper' );
		$helper_mock->shouldReceive( 'prepare_wp_app_folders' )
			->once()
			->andReturn( true );

		// Mock WP_CLI::success using Mockery
		$wp_cli_mock = Mockery::mock( 'alias:WP_CLI' );
		$wp_cli_mock->shouldReceive( 'success' )
			->once()
			->with( 'Preparing needed folders for WP App done!' );

		// Execute the action
		$action = new Prepare_WP_App_Folders_Action();
		$action->handle();

		$this->assertTrue( true );
	}
}
