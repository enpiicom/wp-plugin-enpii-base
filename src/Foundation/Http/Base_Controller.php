<?php

declare(strict_types=1);

namespace Enpii_Base\Foundation\Http;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Base_Controller extends \Illuminate\Routing\Controller {
	use DispatchesJobs;
	use ValidatesRequests;
}
