<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\App\Jobs;

use Enpii_Base\App\Jobs\Init_WP_App_Bootstrap_Job;
use Enpii_Base\App\WP\WP_Application;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Mockery;

class Init_WP_App_Bootstrap_Job_Test extends Unit_Test_Case
{
	public function test_handle(): void {
		$this->setup_wp_app();
		$wp_app_hook_handler = $this->getMockBuilder(Init_WP_App_Bootstrap_Job::class)->getMock();

		// Create a mock of the WPApp class
		$wp_app = $this->getMockBuilder(WP_Application::class)
						->disableOriginalConstructor()
		               ->getMock();

		// Expect the singleton method to be called with HttpKernel classes
		$wp_app->expects($this->once())
		       ->method('singleton')
		       ->with(\Illuminate\Contracts\Http\Kernel::class,
			       \Enpii_Base\App\Http\Kernel::class);

		// Replace the original instance with the mock instance
		$this->wp_app->instance( WP_Application::class, $wp_app );

		$wp_app_hook_handler->handle();
	}
}
