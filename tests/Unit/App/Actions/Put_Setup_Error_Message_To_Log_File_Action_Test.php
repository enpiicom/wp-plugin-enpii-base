<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\App\Actions;

use Enpii_Base\App\Actions\Get_WP_App_Info_Action;
use Enpii_Base\App\Actions\Put_Setup_Error_Message_To_Log_File_Action;
use Enpii_Base\App\WP\WP_Application;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
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
	public function test_handle() {
		// Create an application mock
		$app_mock = $this->createMock( WP_Application::class );
		$app_mock->method( 'make' )->willReturnMap(
			[
				[ 'path.config', [], DIRECTORY_SEPARATOR . 'config' ],
				[ 'path.storage', [], DIRECTORY_SEPARATOR . 'storage' ],
			]
		);

		// Set the mocked WP_Application instance globally
		WP_Application::setInstance( $app_mock );

		// Mock Get_WP_App_Info_Action::exec to return sample data
		$wp_app_info = [ 'key' => 'value' ];
		$app_info_mock = Mockery::mock( 'alias:' . Get_WP_App_Info_Action::class );
		$app_info_mock->shouldReceive( 'exec' )->once()->andReturn( $wp_app_info );

		// Capture the output
		ob_start();
		$action = new Put_Setup_Error_Message_To_Log_File_Action( 'Test error message' );
		$action->handle();
		$output = ob_get_clean();

		// Get dynamic values for the expected output
		$wp_app_info_output = "array:1 [\n" . $this->format_array_for_output( $wp_app_info ) . "]\n";
		$extensions = get_loaded_extensions();
		$extensions_output = 'array:' . count( $extensions ) . " [\n" . $this->format_array_for_output( $extensions ) . "]\n";

		// Combine dynamic parts to form the expected output
		$expected_output = $wp_app_info_output . $extensions_output;

		// Assert that the captured output matches the expected output
		$this->assertEquals( $expected_output, $output );

		// Verify assertions
		$this->assertTrue( true );
	}

	private function format_array_for_output( array $data ): string {
		$output = '';
		foreach ( $data as $key => $value ) {
			// Format keys and values with double quotes to match the observed output
			$formatted_key = is_string( $key ) ? "\"$key\"" : $key;
			$formatted_value = is_string( $value ) ? "\"$value\"" : $value;
			$output .= "  $formatted_key => $formatted_value\n";
		}
		return $output;
	}
}
