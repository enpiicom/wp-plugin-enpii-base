<?php

declare(strict_types=1);

use Enpii\Wp_Plugin\Enpii_Base\Libs\WP_Application;

/**
| We want to define helper functions for the app here
| We don't need to use the prefix for these functions
|
*/

if ( ! function_exists( 'wp_app' ) ) {
	/**
	 * Get the available container instance.
	 *
	 * @param  string|null  $abstract
	 * @param  array  $parameters
	 * @return mixed|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Foundation\Application
	 */
	function wp_app( string $abstract = null, array $parameters = [] ): WP_Application {
		if ( is_null( $abstract ) ) {
			return WP_Application::getInstance();
		}

		return WP_Application::getInstance()->make( $abstract, $parameters );
	}
}
