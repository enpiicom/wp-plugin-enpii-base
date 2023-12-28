<?php

namespace Enpii_Base\App\Jobs;

use Enpii_Base\App\Http\Controllers\Api\Main_Controller;
use Enpii_Base\App\Http\Controllers\Api\User_Controller;
use Illuminate\Support\Facades\Route;
use Enpii_Base\Foundation\Shared\Base_Job;
use Enpii_Base\Foundation\Support\Executable_Trait;

class Register_Base_WP_Api_Routes_Job extends Base_Job {
	use Executable_Trait;

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle(): void {
		// For API
		Route::get( '/', [ Main_Controller::class, 'home' ] );
		Route::post( 'queue-work', [ Main_Controller::class, 'queue_work' ] )->name( 'queue-work' );
		Route::post( 'user-login', [ Main_Controller::class, 'user_login' ] );

		Route::get( 'users/info', [ User_Controller::class, 'info' ] )->middleware( 'auth:api' );

		// For API with session validation
		Route::group(
			[
				'prefix' => '/wp-admin',
				'middleware' => [
					'wp_user_session_validation',
				],
			],
			function () {
				Route::get( '/', [ Main_Controller::class, 'wp_admin' ] );
			}
		);
	}
}
