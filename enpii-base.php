<?php
/**
 * Plugin Name: Enpii Base
 * Plugin URI:  https://enpii.com/wp-plugin-enpii-base/
 * Description: Base plugin for WP development using Laravel framework. Brings Laravel capabilities like Blade templates, Eloquent ORM, Service Providers, and modern development patterns to WordPress.
 * Author:      Enpii Team
 * Author URI:  https://enpii.com/enpii-team/
 * Version:     0.9.1
 * License:     MIT
 * License URI: https://mit-license.org/
 * Text Domain: enpii-base
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.3
 * Network: false
 *
 * @package Enpii_Base
 * @author  Enpii Team <dev@enpii.com>
 * @license MIT
 * @link    https://enpii.com/wp-plugin-enpii-base/
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// We want to split all the bootstrapping code to a separate file
//  for putting into composer autoload and
//  for easier including on other section e.g. unit test
require_once __DIR__ . DIRECTORY_SEPARATOR . 'enpii-base-bootstrap.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'enpii-base-init.php';
