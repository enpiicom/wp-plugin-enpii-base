<?php

declare(strict_types=1);

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Container\Container;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Auth\Access\Gate;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Auth\Factory as AuthFactory;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Broadcasting\Factory as BroadcastFactory;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Bus\Dispatcher;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Cookie\Factory as CookieFactory;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Debug\ExceptionHandler;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Routing\ResponseFactory;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Routing\UrlGenerator;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Support\Responsable;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\View\Factory as ViewFactory;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Foundation\Bus\PendingDispatch;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Foundation\Mix;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Http\Exceptions\HttpResponseException;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Queue\CallQueuedClosure;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Support\Facades\Date;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Support\HtmlString;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Symfony\Component\HttpFoundation\Response;
use Enpii\Wp_Plugin\Enpii_Base\Libs\Wp_Application;

/**
| We want to define helper functions for the app here
| We don't need to use the prefix for these functions
|
*/

if (! function_exists('wp_app_abort')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param  \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Symfony\Component\HttpFoundation\Response|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Support\Responsable|int  $code
     * @param  string  $message
     * @param  array  $headers
     * @return void
     *
     * @throws \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function wp_app_abort($code, $message = '', array $headers = [])
    {
        if ($code instanceof Response) {
            throw new HttpResponseException($code);
        } elseif ($code instanceof Responsable) {
            throw new HttpResponseException($code->toResponse(request()));
        }

        wp_app()->abort($code, $message, $headers);
    }
}

if (! function_exists('wp_app_abort_if')) {
    /**
     * Throw an HttpException with the given data if the given condition is true.
     *
     * @param  bool  $boolean
     * @param  \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Symfony\Component\HttpFoundation\Response|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Support\Responsable|int  $code
     * @param  string  $message
     * @param  array  $headers
     * @return void
     *
     * @throws \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function wp_app_abort_if($boolean, $code, $message = '', array $headers = [])
    {
        if ($boolean) {
            wp_app_abort($code, $message, $headers);
        }
    }
}

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
