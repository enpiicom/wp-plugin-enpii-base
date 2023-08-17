<?php

declare(strict_types=1);

namespace Enpii_Base\App\Http\Controllers;

use Enpii_Base\App\WP\Enpii_Base_WP_Plugin;
use Enpii_Base\Foundation\Http\Base_Controller;

class Index_Controller extends Base_Controller {
	public function index() {
		// This will render the index.php template in the theme
		return wp_app_view( 'index' );
	}

	public function enpii_base() {
		return wp_app(Enpii_Base_WP_Plugin::class)->view('index/home');
	}
}
