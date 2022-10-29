<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base;

class Plugin
{

    public function __construct($config = [])
    {
        
    }

    public function setup_wp_hooks()
    {
        
    }

    public function bootstrap()
    {
        error_log(print_r(__DIR__, true));
    }
}
