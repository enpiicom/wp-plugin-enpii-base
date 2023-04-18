<?php

declare(strict_types=1);

namespace Enpii\WP_Plugin\Enpii_Base\App\Commands;

use Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Foundation\Application;
use Enpii\WP_Plugin\Enpii_Base\Foundation\Shared\Interfaces\Command_Interface;
use Enpii\WP_Plugin\Enpii_Base\Foundation\Shared\Traits\Accessor_Set_Get_Has_Trait;
use Enpii\WP_Plugin\Enpii_Base\Foundation\Shared\Traits\Config_Trait;

class Process_WP_App_Request_Command implements Command_Interface {
	private Application $wp_app;

	use Config_Trait;
	use Accessor_Set_Get_Has_Trait;
}