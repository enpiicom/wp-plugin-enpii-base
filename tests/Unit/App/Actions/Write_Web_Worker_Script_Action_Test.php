<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\App\Actions;

use Enpii_Base\App\Actions\Write_Web_Worker_Script_Action;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Mockery;

class Write_Web_Worker_Script_Action_Test extends Unit_Test_Case {

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
	public function test_handle_outputs_web_worker_script_when_enabled() {
		// Mock Enpii_Base_Helper::disable_web_worker to return false
		$helper_mock = Mockery::mock( 'alias:Enpii_Base\App\Support\Enpii_Base_Helper' );
		$helper_mock->shouldReceive( 'disable_web_worker' )
			->andReturn( false )
			->once();

		// Mock the route_with_wp_url to return a known URL
		$web_worker_url = 'https://example.com/wp-api/web-worker/';
		$helper_mock->shouldReceive( 'route_with_wp_url' )
			->with( 'wp-api::web-worker' )
			->andReturn( $web_worker_url )
			->once();

		// Capture the output
		ob_start();

		// Create the action instance and run the handle method
		$action = new Write_Web_Worker_Script_Action();
		$action->handle();

		$output = ob_get_clean();

		// Assert the expected output contains the correct script
		$expected_script = <<<SCRIPT
		<script type="text/javascript">
			var enpii_base_web_worker_url = '$web_worker_url';
			function ajax_request_to_web_worker() {
				if (typeof(jQuery) !== 'undefined') {
					jQuery.ajax({
						url: enpii_base_web_worker_url,
						method: "POST"
					});
				} else {
					const response = fetch(enpii_base_web_worker_url);
				}
			}
			var ajax_request_to_web_worker_interval = window.setInterval(function(){
				ajax_request_to_web_worker();
			}, 7*7*60*1000);
			window.setTimeout(function() {
				ajax_request_to_web_worker();
			}, 1000);
		</script>
SCRIPT;

		$this->assertStringContainsString( $expected_script, $output );
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_handle_does_not_output_script_when_disabled() {
		// Mock Enpii_Base_Helper::disable_web_worker to return true
		$helper_mock = Mockery::mock( 'alias:Enpii_Base\App\Support\Enpii_Base_Helper' );
		$helper_mock->shouldReceive( 'disable_web_worker' )
			->andReturn( true )
			->once();

		// Capture the output
		ob_start();

		// Create the action instance and run the handle method
		$action = new Write_Web_Worker_Script_Action();
		$action->handle();

		$output = ob_get_clean();

		// Assert that no script is outputted
		$this->assertEmpty( $output );
	}
}
