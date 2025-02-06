<?php

declare(strict_types=1);

use Enpii_Base\App\Support\Enpii_Base_Helper;

if ( ! function_exists( 'enpii_base_wp_app_web_page_title' ) ) {
	/**
	 * Return the WP App web page title.
	 *
	 * @return mixed|void|null
	 */
	function enpii_base_wp_app_web_page_title() {
		return Enpii_Base_Helper::wp_app_web_page_title();
	}
}
