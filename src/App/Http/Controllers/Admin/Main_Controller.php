<?php

declare(strict_types=1);

namespace Enpii_Base\App\Http\Controllers\Admin;

use Enpii_Base\Foundation\Http\Base_Controller;
use Illuminate\Support\Facades\Artisan;

class Main_Controller extends Base_Controller {
	public function home() {
		return 'Admin';
	}

	public function setup() {
		enpii_base_wp_app_prepare_folders(enpii_base_wp_app_get_base_path());
		// Artisan::call('vendor:publish', [
		// 	'--tag' => 'enpii-base-assets',
		// ]);
		// Artisan::call('vendor:publish', [
		// 	'--tag' => 'telescope-assets',
		// ]);
		// Artisan::call('migrate', [
		// ]);
		Artisan::call('vendor:publish', [
			'--tag' => 'telescope-migrations',
		]);
		$output = Artisan::output();
		echo 'Output of given command: ' . $output;
		return 'Setup';
	}
}
