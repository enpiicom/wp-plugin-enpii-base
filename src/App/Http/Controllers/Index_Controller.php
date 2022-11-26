<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Index_Controller extends \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Routing\Controller
{
   	public function home() {
		return 'here we are home';
   	}
}
