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

if ( ! function_exists( 'wp_app_abort' ) ) {
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
	function wp_app_abort( $code, $message = '', array $headers = [] ) {
		if ( $code instanceof Response ) {
			throw new HttpResponseException( $code );
		} elseif ( $code instanceof Responsable ) {
			throw new HttpResponseException( $code->toResponse( request() ) );
		}

		wp_app()->abort( $code, $message, $headers );
	}
}

if ( ! function_exists( 'wp_app_abort_if' ) ) {
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
	function wp_app_abort_if( $boolean, $code, $message = '', array $headers = [] ) {
		if ( $boolean ) {
			wp_app_abort( $code, $message, $headers );
		}
	}
}

if ( ! function_exists( 'wp_app_abort_unless' ) ) {
	/**
	 * Throw an HttpException with the given data unless the given condition is true.
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
	function wp_app_abort_unless( $boolean, $code, $message = '', array $headers = [] ) {
		if ( ! $boolean ) {
			wp_app_abort( $code, $message, $headers );
		}
	}
}

if ( ! function_exists( 'wp_action' ) ) {
	/**
	 * Generate the URL to a controller action.
	 *
	 * @param  string|array  $name
	 * @param  mixed  $parameters
	 * @param  bool  $absolute
	 * @return string
	 */
	function wp_action( $name, $parameters = [], $absolute = true ) {
		return wp_app( 'url' )->action( $name, $parameters, $absolute );
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
	function wp_app( $abstract = null, array $parameters = [] ) {
		if ( is_null( $abstract ) ) {
			return Wp_Application::getInstance();
		}

		return Wp_Application::getInstance()->make( $abstract, $parameters );
	}
}

if ( ! function_exists( 'wp_app_path' ) ) {
	/**
	 * Get the path to the application folder.
	 *
	 * @param  string  $path
	 * @return string
	 */
	function wp_app_path( $path = '' ) {
		return wp_app()->path( $path );
	}
}

if ( ! function_exists( 'wp_asset' ) ) {
	/**
	 * Generate an asset path for the application.
	 *
	 * @param  string  $path
	 * @param  bool|null  $secure
	 * @return string
	 */
	function wp_asset( $path, $secure = null ) {
		return wp_app( 'url' )->asset( $path, $secure );
	}
}

if ( ! function_exists( 'wp_app_auth' ) ) {
	/**
	 * Get the available auth instance.
	 *
	 * @param  string|null  $guard
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Auth\Factory|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Auth\Guard|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Auth\StatefulGuard
	 */
	function wp_app_auth( $guard = null ) {
		if ( is_null( $guard ) ) {
			return wp_app( AuthFactory::class );
		}

		return wp_app( AuthFactory::class )->guard( $guard );
	}
}

if ( ! function_exists( 'wp_app_back' ) ) {
	/**
	 * Create a new redirect response to the previous location.
	 *
	 * @param  int  $status
	 * @param  array  $headers
	 * @param  mixed  $fallback
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Http\RedirectResponse
	 */
	function wp_app_back( $status = 302, $headers = [], $fallback = false ) {
		return wp_app( 'redirect' )->back( $status, $headers, $fallback );
	}
}

if ( ! function_exists( 'wp_app_base_path' ) ) {
	/**
	 * Get the path to the base of the install.
	 *
	 * @param  string  $path
	 * @return string
	 */
	function wp_app_base_path( $path = '' ) {
		return wp_app()->basePath( $path );
	}
}

if ( ! function_exists( 'wp_app_bcrypt' ) ) {
	/**
	 * Hash the given value against the bcrypt algorithm.
	 *
	 * @param  string  $value
	 * @param  array  $options
	 * @return string
	 */
	function wp_app_bcrypt( $value, $options = [] ) {
		return wp_app( 'hash' )->driver( 'bcrypt' )->make( $value, $options );
	}
}

if ( ! function_exists( 'wp_app_broadcast' ) ) {
	/**
	 * Begin broadcasting an event.
	 *
	 * @param  mixed|null  $event
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Broadcasting\PendingBroadcast
	 */
	function wp_app_broadcast( $event = null ) {
		return wp_app( BroadcastFactory::class )->event( $event );
	}
}

if ( ! function_exists( 'wp_app_cache' ) ) {
	/**
	 * Get / set the specified cache value.
	 *
	 * If an array is passed, we'll assume you want to put to the cache.
	 *
	 * @param  dynamic  key|key,default|data,expiration|null
	 * @return mixed|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Cache\CacheManager
	 *
	 * @throws \Exception
	 */
	function wp_app_cache() {
		$arguments = func_get_args();

		if ( empty( $arguments ) ) {
			return wp_app( 'cache' );
		}

		if ( is_string( $arguments[0] ) ) {
			return wp_app( 'cache' )->get( ...$arguments );
		}

		if ( ! is_array( $arguments[0] ) ) {
			throw new Exception(
				'When setting a value in the cache, you must pass an array of key / value pairs.'
			);
		}

		return wp_app( 'cache' )->put( key( $arguments[0] ), reset( $arguments[0] ), $arguments[1] ?? null );
	}
}

if ( ! function_exists( 'wp_app_config' ) ) {
	/**
	 * Get / set the specified configuration value.
	 *
	 * If an array is passed as the key, we will assume you want to set an array of values.
	 *
	 * @param  array|string|null  $key
	 * @param  mixed  $default
	 * @return mixed|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Config\Repository
	 */
	function wp_app_config( $key = null, $default = null ) {
		if ( is_null( $key ) ) {
			return wp_app( 'config' );
		}

		if ( is_array( $key ) ) {
			return wp_app( 'config' )->set( $key );
		}

		return wp_app( 'config' )->get( $key, $default );
	}
}

if ( ! function_exists( 'wp_app_config_path' ) ) {
	/**
	 * Get the configuration path.
	 *
	 * @param  string  $path
	 * @return string
	 */
	function wp_app_config_path( $path = '' ) {
		return wp_app()->configPath( $path );
	}
}

if ( ! function_exists( 'wp_app_cookie' ) ) {
	/**
	 * Create a new cookie instance.
	 *
	 * @param  string|null  $name
	 * @param  string|null  $value
	 * @param  int  $minutes
	 * @param  string|null  $path
	 * @param  string|null  $domain
	 * @param  bool|null  $secure
	 * @param  bool  $httpOnly
	 * @param  bool  $raw
	 * @param  string|null  $sameSite
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Cookie\CookieJar|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Symfony\Component\HttpFoundation\Cookie
	 */
	function wp_app_cookie( $name = null, $value = null, $minutes = 0, $path = null, $domain = null, $secure = null, $httpOnly = true, $raw = false, $sameSite = null ) {
		$cookie = wp_app( CookieFactory::class );

		if ( is_null( $name ) ) {
			return $cookie;
		}

		return $cookie->make( $name, $value, $minutes, $path, $domain, $secure, $httpOnly, $raw, $sameSite );
	}
}

if ( ! function_exists( 'wp_app_csrf_field' ) ) {
	/**
	 * Generate a CSRF token form field.
	 *
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Support\HtmlString
	 */
	function wp_app_csrf_field() {
		return new HtmlString( '<input type="hidden" name="_token" value="' . wp_app_csrf_token() . '">' );
	}
}

if ( ! function_exists( 'wp_app_csrf_token' ) ) {
	/**
	 * Get the CSRF token value.
	 *
	 * @return string
	 *
	 * @throws \RuntimeException
	 */
	function wp_app_csrf_token() {
		$session = wp_app( 'session' );

		if ( isset( $session ) ) {
			return $session->token();
		}

		throw new RuntimeException( 'Application session store not set.' );
	}
}

if ( ! function_exists( 'wp_app_database_path' ) ) {
	/**
	 * Get the database path.
	 *
	 * @param  string  $path
	 * @return string
	 */
	function wp_app_database_path( $path = '' ) {
		return wp_app()->databasePath( $path );
	}
}

if ( ! function_exists( 'decrypt' ) ) {
	/**
	 * Decrypt the given value.
	 *
	 * @param  string  $value
	 * @param  bool  $unserialize
	 * @return mixed
	 */
	function decrypt( $value, $unserialize = true ) {
		return wp_app( 'encrypter' )->decrypt( $value, $unserialize );
	}
}

if ( ! function_exists( 'dispatch' ) ) {
	/**
	 * Dispatch a job to its appropriate handler.
	 *
	 * @param  mixed  $job
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Foundation\Bus\PendingDispatch
	 */
	function dispatch( $job ) {
		if ( $job instanceof Closure ) {
			$job = CallQueuedClosure::create( $job );
		}

		return new PendingDispatch( $job );
	}
}

if ( ! function_exists( 'dispatch_now' ) ) {
	/**
	 * Dispatch a command to its appropriate handler in the current process.
	 *
	 * @param  mixed  $job
	 * @param  mixed  $handler
	 * @return mixed
	 */
	function dispatch_now( $job, $handler = null ) {
		return wp_app( Dispatcher::class )->dispatchNow( $job, $handler );
	}
}

if ( ! function_exists( 'elixir' ) ) {
	/**
	 * Get the path to a versioned Elixir file.
	 *
	 * @param  string  $file
	 * @param  string  $buildDirectory
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @deprecated Use Laravel Mix instead.
	 */
	function elixir( $file, $buildDirectory = 'build' ) {
		static $manifest = [];
		static $manifestPath;

		if ( empty( $manifest ) || $manifestPath !== $buildDirectory ) {
			$path = public_path( $buildDirectory . '/rev-manifest.json' );

			if ( file_exists( $path ) ) {
				$manifest = json_decode( file_get_contents( $path ), true );
				$manifestPath = $buildDirectory;
			}
		}

		$file = ltrim( $file, '/' );

		if ( isset( $manifest[ $file ] ) ) {
			return '/' . trim( $buildDirectory . '/' . $manifest[ $file ], '/' );
		}

		$unversioned = public_path( $file );

		if ( file_exists( $unversioned ) ) {
			return '/' . trim( $file, '/' );
		}

		throw new InvalidArgumentException( "File {$file} not defined in asset manifest." );
	}
}

if ( ! function_exists( 'encrypt' ) ) {
	/**
	 * Encrypt the given value.
	 *
	 * @param  mixed  $value
	 * @param  bool  $serialize
	 * @return string
	 */
	function encrypt( $value, $serialize = true ) {
		return wp_app( 'encrypter' )->encrypt( $value, $serialize );
	}
}

if ( ! function_exists( 'event' ) ) {
	/**
	 * Dispatch an event and call the listeners.
	 *
	 * @param  string|object  $event
	 * @param  mixed  $payload
	 * @param  bool  $halt
	 * @return array|null
	 */
	function event( ...$args ) {
		return wp_app( 'events' )->dispatch( ...$args );
	}
}

if ( ! function_exists( 'factory' ) ) {
	/**
	 * Create a model factory builder for a given class and amount.
	 *
	 * @param  string  $class
	 * @param  int  $amount
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Eloquent\FactoryBuilder
	 */
	function factory( $class, $amount = null ) {
		$factory = wp_app( EloquentFactory::class );

		if ( isset( $amount ) && is_int( $amount ) ) {
			return $factory->of( $class )->times( $amount );
		}

		return $factory->of( $class );
	}
}

if ( ! function_exists( 'info' ) ) {
	/**
	 * Write some information to the log.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return void
	 */
	function info( $message, $context = [] ) {
		wp_app( 'log' )->info( $message, $context );
	}
}

if ( ! function_exists( 'logger' ) ) {
	/**
	 * Log a debug message to the logs.
	 *
	 * @param  string|null  $message
	 * @param  array  $context
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Log\LogManager|null
	 */
	function logger( $message = null, array $context = [] ) {
		if ( is_null( $message ) ) {
			return wp_app( 'log' );
		}

		return wp_app( 'log' )->debug( $message, $context );
	}
}

if ( ! function_exists( 'logs' ) ) {
	/**
	 * Get a log driver instance.
	 *
	 * @param  string|null  $driver
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Log\LogManager|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Psr\Log\LoggerInterface
	 */
	function logs( $driver = null ) {
		return $driver ? wp_app( 'log' )->driver( $driver ) : wp_app( 'log' );
	}
}

if ( ! function_exists( 'method_field' ) ) {
	/**
	 * Generate a form field to spoof the HTTP verb used by forms.
	 *
	 * @param  string  $method
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Support\HtmlString
	 */
	function method_field( $method ) {
		return new HtmlString( '<input type="hidden" name="_method" value="' . $method . '">' );
	}
}

if ( ! function_exists( 'mix' ) ) {
	/**
	 * Get the path to a versioned Mix file.
	 *
	 * @param  string  $path
	 * @param  string  $manifestDirectory
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Support\HtmlString|string
	 *
	 * @throws \Exception
	 */
	function mix( $path, $manifestDirectory = '' ) {
		return wp_app( Mix::class )( ...func_get_args() );
	}
}

if ( ! function_exists( 'now' ) ) {
	/**
	 * Create a new Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon instance for the current time.
	 *
	 * @param  \DateTimeZone|string|null  $tz
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Support\Carbon
	 */
	function now( $tz = null ) {
		return Date::now( $tz );
	}
}

if ( ! function_exists( 'old' ) ) {
	/**
	 * Retrieve an old input item.
	 *
	 * @param  string|null  $key
	 * @param  mixed  $default
	 * @return mixed
	 */
	function old( $key = null, $default = null ) {
		return wp_app( 'request' )->old( $key, $default );
	}
}

if ( ! function_exists( 'policy' ) ) {
	/**
	 * Get a policy instance for a given class.
	 *
	 * @param  object|string  $class
	 * @return mixed
	 *
	 * @throws \InvalidArgumentException
	 */
	function policy( $class ) {
		return wp_app( Gate::class )->getPolicyFor( $class );
	}
}

if ( ! function_exists( 'public_path' ) ) {
	/**
	 * Get the path to the public folder.
	 *
	 * @param  string  $path
	 * @return string
	 */
	function public_path( $path = '' ) {
		return wp_app()->make( 'path.public' ) . ( $path ? DIRECTORY_SEPARATOR . ltrim( $path, DIRECTORY_SEPARATOR ) : $path );
	}
}

if ( ! function_exists( 'redirect' ) ) {
	/**
	 * Get an instance of the redirector.
	 *
	 * @param  string|null  $to
	 * @param  int  $status
	 * @param  array  $headers
	 * @param  bool|null  $secure
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Routing\Redirector|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Http\RedirectResponse
	 */
	function redirect( $to = null, $status = 302, $headers = [], $secure = null ) {
		if ( is_null( $to ) ) {
			return wp_app( 'redirect' );
		}

		return wp_app( 'redirect' )->to( $to, $status, $headers, $secure );
	}
}

if ( ! function_exists( 'report' ) ) {
	/**
	 * Report an exception.
	 *
	 * @param  \Throwable  $exception
	 * @return void
	 */
	function report( Throwable $exception ) {
		wp_app( ExceptionHandler::class )->report( $exception );
	}
}

if ( ! function_exists( 'request' ) ) {
	/**
	 * Get an instance of the current request or an input item from the request.
	 *
	 * @param  array|string|null  $key
	 * @param  mixed  $default
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Http\Request|string|array
	 */
	function request( $key = null, $default = null ) {
		if ( is_null( $key ) ) {
			return wp_app( 'request' );
		}

		if ( is_array( $key ) ) {
			return wp_app( 'request' )->only( $key );
		}

		$value = wp_app( 'request' )->__get( $key );

		return is_null( $value ) ? value( $default ) : $value;
	}
}

if ( ! function_exists( 'rescue' ) ) {
	/**
	 * Catch a potential exception and return a default value.
	 *
	 * @param  callable  $callback
	 * @param  mixed  $rescue
	 * @param  bool  $report
	 * @return mixed
	 */
	function rescue( callable $callback, $rescue = null, $report = true ) {
		try {
			return $callback();
		} catch ( Throwable $e ) {
			if ( $report ) {
				report( $e );
			}

			return $rescue instanceof Closure ? $rescue( $e ) : $rescue;
		}
	}
}

if ( ! function_exists( 'resolve' ) ) {
	/**
	 * Resolve a service from the container.
	 *
	 * @param  string  $name
	 * @param  array  $parameters
	 * @return mixed
	 */
	function resolve( $name, array $parameters = [] ) {
		return wp_app( $name, $parameters );
	}
}

if ( ! function_exists( 'resource_path' ) ) {
	/**
	 * Get the path to the resources folder.
	 *
	 * @param  string  $path
	 * @return string
	 */
	function resource_path( $path = '' ) {
		return wp_app()->resourcePath( $path );
	}
}

if ( ! function_exists( 'response' ) ) {
	/**
	 * Return a new response from the application.
	 *
	 * @param  \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\View\View|string|array|null  $content
	 * @param  int  $status
	 * @param  array  $headers
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Http\Response|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Routing\ResponseFactory
	 */
	function response( $content = '', $status = 200, array $headers = [] ) {
		$factory = wp_app( ResponseFactory::class );

		if ( func_num_args() === 0 ) {
			return $factory;
		}

		return $factory->make( $content, $status, $headers );
	}
}

if ( ! function_exists( 'route' ) ) {
	/**
	 * Generate the URL to a named route.
	 *
	 * @param  array|string  $name
	 * @param  mixed  $parameters
	 * @param  bool  $absolute
	 * @return string
	 */
	function route( $name, $parameters = [], $absolute = true ) {
		return wp_app( 'url' )->route( $name, $parameters, $absolute );
	}
}

if ( ! function_exists( 'secure_asset' ) ) {
	/**
	 * Generate an asset path for the application.
	 *
	 * @param  string  $path
	 * @return string
	 */
	function secure_asset( $path ) {
		return asset( $path, true );
	}
}

if ( ! function_exists( 'secure_url' ) ) {
	/**
	 * Generate a HTTPS url for the application.
	 *
	 * @param  string  $path
	 * @param  mixed  $parameters
	 * @return string
	 */
	function secure_url( $path, $parameters = [] ) {
		return url( $path, $parameters, true );
	}
}

if ( ! function_exists( 'session' ) ) {
	/**
	 * Get / set the specified session value.
	 *
	 * If an array is passed as the key, we will assume you want to set an array of values.
	 *
	 * @param  array|string|null  $key
	 * @param  mixed  $default
	 * @return mixed|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Session\Store|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Session\SessionManager
	 */
	function session( $key = null, $default = null ) {
		if ( is_null( $key ) ) {
			return wp_app( 'session' );
		}

		if ( is_array( $key ) ) {
			return wp_app( 'session' )->put( $key );
		}

		return wp_app( 'session' )->get( $key, $default );
	}
}

if ( ! function_exists( 'storage_path' ) ) {
	/**
	 * Get the path to the storage folder.
	 *
	 * @param  string  $path
	 * @return string
	 */
	function storage_path( $path = '' ) {
		return wp_app( 'path.storage' ) . ( $path ? DIRECTORY_SEPARATOR . $path : $path );
	}
}

if ( ! function_exists( 'today' ) ) {
	/**
	 * Create a new Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon instance for the current date.
	 *
	 * @param  \DateTimeZone|string|null  $tz
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Support\Carbon
	 */
	function today( $tz = null ) {
		return Date::today( $tz );
	}
}

if ( ! function_exists( 'trans' ) ) {
	/**
	 * Translate the given message.
	 *
	 * @param  string|null  $key
	 * @param  array  $replace
	 * @param  string|null  $locale
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Translation\Translator|string|array|null
	 */
	function trans( $key = null, $replace = [], $locale = null ) {
		if ( is_null( $key ) ) {
			return wp_app( 'translator' );
		}

		return wp_app( 'translator' )->get( $key, $replace, $locale );
	}
}

if ( ! function_exists( 'trans_choice' ) ) {
	/**
	 * Translates the given message based on a count.
	 *
	 * @param  string  $key
	 * @param  \Countable|int|array  $number
	 * @param  array  $replace
	 * @param  string|null  $locale
	 * @return string
	 */
	function trans_choice( $key, $number, array $replace = [], $locale = null ) {
		return wp_app( 'translator' )->choice( $key, $number, $replace, $locale );
	}
}

if ( ! function_exists( '__' ) ) {
	/**
	 * Translate the given message.
	 *
	 * @param  string|null  $key
	 * @param  array  $replace
	 * @param  string|null  $locale
	 * @return string|array|null
	 */
	function __( $key = null, $replace = [], $locale = null ) {
		if ( is_null( $key ) ) {
			return $key;
		}

		return trans( $key, $replace, $locale );
	}
}

if ( ! function_exists( 'url' ) ) {
	/**
	 * Generate a url for the application.
	 *
	 * @param  string|null  $path
	 * @param  mixed  $parameters
	 * @param  bool|null  $secure
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Routing\UrlGenerator|string
	 */
	function url( $path = null, $parameters = [], $secure = null ) {
		if ( is_null( $path ) ) {
			return wp_app( UrlGenerator::class );
		}

		return wp_app( UrlGenerator::class )->to( $path, $parameters, $secure );
	}
}

if ( ! function_exists( 'validator' ) ) {
	/**
	 * Create a new Validator instance.
	 *
	 * @param  array  $data
	 * @param  array  $rules
	 * @param  array  $messages
	 * @param  array  $customAttributes
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Validation\Validator|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Validation\Factory
	 */
	function validator( array $data = [], array $rules = [], array $messages = [], array $customAttributes = [] ) {
		$factory = wp_app( ValidationFactory::class );

		if ( func_num_args() === 0 ) {
			return $factory;
		}

		return $factory->make( $data, $rules, $messages, $customAttributes );
	}
}

if ( ! function_exists( 'view' ) ) {
	/**
	 * Get the evaluated view contents for the given view.
	 *
	 * @param  string|null  $view
	 * @param  \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Support\Arrayable|array  $data
	 * @param  array  $mergeData
	 * @return \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\View\View|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\View\Factory
	 */
	function view( $view = null, $data = [], $mergeData = [] ) {
		$factory = wp_app( ViewFactory::class );

		if ( func_num_args() === 0 ) {
			return $factory;
		}

		return $factory->make( $view, $data, $mergeData );
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
	function wp_app_view( $view = null, $data = [], $mergeData = [] ) {
		$factory = wp_app( \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\View\Factory::class );

		if ( func_num_args() === 0 ) {
			return $factory;
		}

		return $factory->make( $view, $data, $mergeData );
	}
}

if ( ! function_exists( 'wp_app_config' ) ) {
	/**
	 * Get / set the specified configuration value.
	 *
	 * If an array is passed as the key, we will assume you want to set an array of values.
	 *
	 * @param  array|string|null  $key
	 * @param  mixed  $default
	 * @return mixed|\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Config\Repository
	 */
	function wp_app_config( $key = null, $default = null ) {
		if ( is_null( $key ) ) {
			return wp_app( 'config' );
		}

		if ( is_array( $key ) ) {
			return wp_app( 'config' )->set( $key );
		}

		return wp_app( 'config' )->get( $key, $default );
	}
}
