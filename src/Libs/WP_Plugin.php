<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\Libs;

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Support\ServiceProvider;
use Enpii\Wp_Plugin\Enpii_Base\Libs\Interfaces\Plugin_Interface;

/**
 * This is the base class for plugin to be inherited from
 * We consider each plugin a Laravel Service provider
 * @package Enpii\Wp_Plugin\Enpii_Base\Libs
 */
abstract class WP_Plugin extends ServiceProvider implements Plugin_Interface {
	protected $base_path;
	protected $base_url;
}
