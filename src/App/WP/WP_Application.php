<?php

declare(strict_types=1);

namespace Enpii_Base\App\WP;

use Enpii_Base\App\Support\App_Const;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Mix;
use Enpii_Base\Foundation\WP\WP_Plugin_Interface;
use Enpii_Base\Foundation\WP\WP_Theme_Interface;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Foundation\ProviderRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * @package Enpii_Base\App\WP
 */
class WP_Application extends Application {
	/**
	 * We override the parent instance for not messing up with other application
	 *
	 * @var static
	 */
	protected static $instance;

	/**
	 * Config array needed for the initialization process
	 * @var array
	 */
	protected static $config;

	protected $wp_app_slug = 'wp-app';
	protected $wp_api_slug = 'wp-api';

	/**
	 * Should contains WP headers, we will merge them with Laravel headers to send later
	 * @var mixed
	 */
	protected $wp_headers;

	public static function isset(): bool {
		return ! is_null( static::$instance );
	}

	public static function instance_base_path(): string {
		return defined( 'WP_APP_BASE_PATH' ) && realpath( WP_APP_BASE_PATH ) ?
			WP_APP_BASE_PATH :
			rtrim( WP_CONTENT_DIR, '/' ) . DIR_SEP . 'uploads' . DIR_SEP . 'wp-app';
	}

	public static function load_instance() {
		add_action(
			ENPII_BASE_SETUP_HOOK_NAME,
			function () {
				// We only want to run the setup once
				if ( static::isset() ) {
					return;
				}

				/**
				| Create a wp_app() instance to be used in the whole application
				*/
				$wp_app_base_path = static::instance_base_path();
				$config = apply_filters(
					App_Const::FILTER_WP_APP_PREPARE_CONFIG,
					[
						'app'         => require_once dirname(
							dirname( dirname( __DIR__ ) )
						) . DIR_SEP . 'wp-app-config' . DIR_SEP . 'app.php',
						'wp_app_slug' => ENPII_BASE_WP_APP_PREFIX,
						'wp_api_slug' => ENPII_BASE_WP_API_PREFIX,
					]
				);
				if ( empty( $config['app']['key'] ) ) {
					$auth_key = md5( uniqid() );
					$config['app']['key'] = $auth_key;
					add_option( 'wp_app_auth_key', $auth_key );
				}

				// We initiate the WP Application instance
				static::init_instance_with_config(
					$wp_app_base_path,
					$config
				);

				do_action( App_Const::ACTION_WP_APP_LOADED );
			},
			-100
		);
	}

	/**
	 * We don't want to have this class publicly initialized
	 *
	 * @param  string|null  $basePath
	 * @return void
	 */
	protected function __construct( $basePath = null ) {
		if ( $basePath ) {
			$this->setBasePath( $basePath );
		}

		$this->registerBaseBindings();
		$this->registerBaseServiceProviders();
		$this->registerCoreContainerAliases();
	}

	/**
	 * @inheritedDoc
	 *
	 * @param  string  $path
	 * @return string
	 */
	public function resourcePath( $path = '' ): string {
		// Todo: refactor this using constant
		return dirname( dirname( dirname( __DIR__ ) ) ) . DIRECTORY_SEPARATOR . 'resources' . ( $path ? DIRECTORY_SEPARATOR . $path : $path );
	}

	/**
	 * @inheritedDoc
	 *
	 * @return string
	 *
	 * @throws \RuntimeException
	 */
	public function getNamespace(): string {
		if ( ! is_null( $this->namespace ) ) {
			return $this->namespace;
		}
		$this->namespace = 'Enpii_Base\\';
		return $this->namespace;
	}

	/**
	 * @inheritDoc
	 */
	public function runningInConsole(): ?bool {
		if ( $this->isRunningInConsole === null ) {
			if (
				strpos( wp_app_request()->getPathInfo(), 'wp-admin' ) !== false && wp_app_request()->get( 'force_app_running_in_console' ) ||
				strpos( wp_app_request()->getPathInfo(), 'queue-work' ) !== false && wp_app_request()->get( 'force_app_running_in_console' )
			) {
				$this->isRunningInConsole = true;
			}
		}

		return parent::runningInConsole();
	}

	/**
	 * @inheritDoc
	 */
	public function registerConfiguredProviders() {
		$providers_list = apply_filters(
			App_Const::FILTER_WP_APP_MAIN_SERVICE_PROVIDERS,
			$this->config['app.providers']
		);
		$providers = Collection::make( $providers_list )
						->partition(
							function ( $provider ) {
								return ( strpos( $provider, 'Enpii_Base\\' ) === 0 ) ||
								( strpos( $provider, 'Illuminate\\' ) === 0 );
							}
						);

		$providers->splice( 1, 0, [ $this->make( PackageManifest::class )->providers() ] );

		( new ProviderRepository( $this, new Filesystem(), $this->getCachedServicesPath() ) )
			->load( $providers->collapse()->toArray() );

		// We trigger the action when wp_app (with providers) is registered
		do_action( App_Const::ACTION_WP_APP_REGISTERED, $this );
	}

	/**
	 * @inheritDoc
	 */
	public function boot() {
		parent::boot();

		// We trigger the action when wp_app (with providers) are booted
		do_action( App_Const::ACTION_WP_APP_BOOTED );
	}

	/**
	 * We want to use the array to load the config
	 *
	 * @param  null  $basePath
	 * @param  mixed  $config
	 *
	 * @return WP_Application
	 */
	public static function init_instance_with_config( $basePath = null, $config = null ): self {
		$instance = static::$instance;
		if ( ! empty( $instance ) ) {
			return $instance;
		}

		static::$config = $config;
		$instance = new static( $basePath );
		$instance->wp_app_slug = $config['wp_app_slug'];
		$instance->wp_api_slug = $config['wp_api_slug'];

		static::$instance = $instance;

		return $instance;
	}

	/**
	 * @throws \Exception
	 */
	public function register_plugin(
		$plugin_classsname,
		$plugin_slug,
		$plugin_base_path,
		$plugin_base_url
	): void {
		if ( $this->has( $plugin_classsname ) ) {
			return;
		}

		$plugin = new $plugin_classsname( $this );
		if ( ! ( $plugin instanceof WP_Plugin_Interface ) ) {
			throw new InvalidArgumentException(
				sprintf(
					'The target classname %s must implement %s',
					esc_html( $plugin_classsname ),
					WP_Plugin_Interface::class
				)
			);
		}

		/** @var \Enpii_Base\Foundation\WP\WP_Plugin $plugin  */
		$plugin->bind_base_params(
			[
				WP_Plugin_Interface::PARAM_KEY_PLUGIN_SLUG => $plugin_slug,
				WP_Plugin_Interface::PARAM_KEY_PLUGIN_BASE_PATH => $plugin_base_path,
				WP_Plugin_Interface::PARAM_KEY_PLUGIN_BASE_URL => $plugin_base_url,
			]
		);
		$this->singleton(
			$plugin_classsname,
			// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
			function ( $app ) use ( &$plugin ) {
				return $plugin;
			}
		);
		$this->alias( $plugin_classsname, 'plugin-' . $plugin_slug );
		$this->register( $plugin );
	}

	/**
	 * @throws \Exception
	 */
	public function register_theme(
		$theme_classsname,
		$theme_slug
	): void {
		if ( $this->has( $theme_classsname ) ) {
			return;
		}

		$theme = new $theme_classsname( $this );
		if ( ! ( $theme instanceof WP_Theme_Interface ) ) {
			throw new InvalidArgumentException(
				sprintf(
					'The target classname %s must implement %s',
					esc_html( $theme_classsname ),
					WP_Theme_Interface::class
				)
			);
		}

		if ( get_template_directory() !== get_stylesheet_directory() ) {
			$theme_base_path = get_stylesheet_directory();
			$theme_base_url = get_stylesheet_directory_uri();
			$parent_theme_base_path = get_template_directory();
			$parent_theme_base_url = get_template_directory_uri();
		} else {
			$theme_base_path = get_template_directory();
			$theme_base_url = get_template_directory_uri();
			$parent_theme_base_path = null;
			$parent_theme_base_url = null;
		}

		/** @var \Enpii_Base\Foundation\WP\WP_Theme $theme  */
		$theme->bind_base_params(
			[
				WP_Theme_Interface::PARAM_KEY_THEME_SLUG => $theme_slug,
				WP_Theme_Interface::PARAM_KEY_THEME_BASE_PATH => $theme_base_path,
				WP_Theme_Interface::PARAM_KEY_THEME_BASE_URL => $theme_base_url,
				WP_Theme_Interface::PARAM_KEY_PARENT_THEME_BASE_PATH => $parent_theme_base_path,
				WP_Theme_Interface::PARAM_KEY_PARENT_THEME_BASE_URL => $parent_theme_base_url,
			]
		);
		$this->singleton(
			$theme_classsname,
			// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
			function ( $app ) use ( $theme ) {
				return $theme;
			}
		);
		$this->alias( $theme_classsname, 'theme-' . $theme_slug );
		$this->register( $theme );
	}

	/**
	 * A shortcut to register actions for enpii_base_wp_app_register_routes
	 * @param mixed $callback
	 * @param int $priority
	 * @param int $accepted_args
	 * @return void
	 */
	public function register_routes( $callback, $priority = 10, $accepted_args = 1 ): void {
		add_action( App_Const::ACTION_WP_APP_REGISTER_ROUTES, $callback, $priority, $accepted_args );
	}

	/**
	 * A shortcut to register actions for enpii_base_wp_api_register_routes
	 * @param mixed $callback
	 * @param int $priority
	 * @param int $accepted_args
	 * @return void
	 */
	public function register_api_routes( $callback, $priority = 10, $accepted_args = 1 ): void {
		add_action( App_Const::ACTION_WP_API_REGISTER_ROUTES, $callback, $priority, $accepted_args );
	}

	/**
	 * Get the slug for wp-app mode
	 * @return string
	 */
	public function get_wp_app_slug(): string {
		return $this->wp_app_slug;
	}

	/**
	 * Get the slug for wp-api mode
	 * @return string
	 */
	public function get_wp_api_slug(): string {
		return $this->wp_api_slug;
	}

	public function is_debug_mode(): bool {
		return wp_app_config( 'app.debug' );
	}

	/**
	 * For checking if the request uri is for 'wp-app'
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function is_wp_app_mode(): bool {
		$wp_app_prefix = $this->wp_app_slug;
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( $_SERVER['REQUEST_URI'] ) : '/';

		return ( strpos( $uri, '/' . $wp_app_prefix . '/' ) === 0 || $uri === '/' . $wp_app_prefix );
	}

	/**
	 * For checking if the request uri is for 'wp-app'
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function is_wp_api_mode(): bool {
		$wp_site_prefix = $this->wp_api_slug;
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( $_SERVER['REQUEST_URI'] ) : '/';

		return ( strpos( $uri, '/' . $wp_site_prefix . '/' ) === 0 || $uri === '/' . $wp_site_prefix );
	}

	/**
	 * For checking if the request uri is for 'wp-app'
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function is_wp_site_mode(): bool {
		$wp_site_prefix = 'site';
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( $_SERVER['REQUEST_URI'] ) : '/';

		return ( strpos( $uri, '/' . $wp_site_prefix . '/' ) === 0 || $uri === '/' . $wp_site_prefix );
	}

	public function get_laravel_major_version(): int {
		return (int) enpii_base_get_major_version( Application::VERSION );
	}

	public function get_composer_folder_name(): string {
		// We only want to have these Watches on Laravel 8+
		if ( version_compare( '9.0.0', Application::VERSION, '>' ) ) {
			return 'vendor-legacy';
		}

		return 'vendor';
	}

	public function get_composer_path(): string {
		return defined( 'COMPOSER_VENDOR_DIR' ) ? COMPOSER_VENDOR_DIR : dirname( $this->resourcePath() ) . DIR_SEP . $this->get_composer_folder_name();
	}

	public function set_wp_headers( $headers ) {
		$this->wp_headers = $headers;
	}

	public function get_wp_headers() {
		return $this->wp_headers;
	}

	public function set_request( Request $request ) {
		$this->instance( 'request', $request );
	}

	public function set_response( Response $response ) {
		$this->bind(
			ResponseFactory::class,
			// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
			function ( $app ) use ( $response ) {
				return $response;
			}
		);
	}

	/**
	 * @inheritedDoc
	 *
	 * @return void
	 */
	protected function registerBaseBindings() {
		parent::registerBaseBindings();

		// We want to have the `config` service ready to use later on
		$config = static::$config;
		$this->singleton(
			'config',
			// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
			function ( $app ) use ( $config ) {
				return new Repository( $config );
			}
		);

		$this->instance( self::class, $this );
		$this->singleton( Mix::class );
	}

	/**
	 * @inheritedDoc
	 *
	 * @return void
	 */
	protected function registerBaseServiceProviders() {
		$providers = [
			\Enpii_Base\App\Providers\Event_Service_Provider::class,
			\Enpii_Base\App\Providers\Log_Service_Provider::class,
			\Enpii_Base\App\Providers\Routing_Service_Provider::class,
			\Enpii_Base\App\Providers\Bus_Service_Provider::class,
		];

		foreach ( $providers as $provider_classname ) {
			$this->register( $provider_classname );
		}
	}
}
