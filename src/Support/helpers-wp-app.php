<?php

declare(strict_types=1);

use Enpii\Wp_Plugin\Enpii_Base\Libs\Wp_Application;

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
	 * @return mixed|\Enpii\Wp_Plugin\Enpii_Base\Libs\Wp_Application
	 */
	function wp_app( string $abstract = null, array $parameters = [] ) {
		if ( is_null( $abstract ) ) {
			return Wp_Application::getInstance();
		}

		return Wp_Application::getInstance()->make( $abstract, $parameters );
	}
}

if ( ! function_exists( 'dev_logger' ) ) {
	function dev_logger( ...$messages ): void {
		foreach ( $messages as $index => $message ) {
			logger( "message $index:" );
			ob_start();
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_dump
			var_dump( $message );
			$debug_message = ob_get_clean();
			logger( $debug_message );
		}
		logger( "\n\n\n" );
	}
}

if ( ! function_exists( 'wp_app_view' ) ) {
	function wp_app_view($view = null, $data = [], $mergeData = [])
    {
        $factory = wp_app(\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\View\Factory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData);
    }
}

if (! function_exists('wp_app_config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Config\Repository
     */
    function wp_app_config($key = null, $default = null)
    {
        if (is_null($key)) {
            return wp_app('config');
        }

        if (is_array($key)) {
            return wp_app('config')->set($key);
        }

        return wp_app('config')->get($key, $default);
    }
}
