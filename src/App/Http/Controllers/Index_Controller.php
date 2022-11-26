<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\App\Http\Controllers;

class Index_Controller extends \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Routing\Controller
{
   	public function home() {
		return wp_app_view('index/home');
   	}
}
