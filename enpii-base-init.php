<?php
// Only initiate the wp_app() when WordPress is fully loaded
// If the plugin is loaded via Composer before WordPress is ready, we need to ensure proper checks are in place.

use Enpii_Base\App\Support\Enpii_Base_Bootstrap;

Enpii_Base_Bootstrap::initialize( plugin_dir_url( __FILE__ ), __DIR__ );
