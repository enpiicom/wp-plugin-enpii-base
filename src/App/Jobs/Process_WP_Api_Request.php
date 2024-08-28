<?php

namespace Enpii_Base\App\Jobs;

use Enpii_Base\Foundation\Support\Executable_Trait;

class Process_WP_Api_Request {
	use Executable_Trait;

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle(): void {
		/** @var \Enpii_Base\App\Http\Kernel $kernel */
		$kernel = app()->make( \Illuminate\Contracts\Http\Kernel::class );

		// We don't want to re-capture the request because we did that on WP_App_Bootstrap
		/** @var \Enpii_Base\App\Http\Request $request */
		$request = wp_app_request();
		$response = $kernel->handle( $request );
		$response->send();

		$kernel->terminate( $request, $response );
	}
}
