<?php

declare(strict_types=1);

namespace Enpii_Base\App\Support;

use Enpii_Base\App\Support\Enpii_Base_Helper;

class Enpii_Base_Bootstrap {
	public static function initialize() {
		// Check if the WordPress content directory is loaded.
		if ( Enpii_Base_Helper::is_wp_content_loaded() ) {
	
			// If the current mode is the command-line interface (CLI) mode.
			if ( Enpii_Base_Helper::is_console_mode() ) {
				// Register the CLI initialization action.
				static::register_cli_init_action();
	
				// If the command 'enpii-base prepare' is present, prepare the WP App folders.
				if ( static::is_enpii_base_prepare_command() ) {
					Enpii_Base_Helper::prepare_wp_app_folders();
	
					// Return early after preparing the folders since no further actions are needed.
					return;
				}
			} 
	
			// Check if the wp_app setup has been done correctly
			// If the check fails, return early and do not proceed with further setup.
			if ( ! Enpii_Base_Helper::perform_wp_app_check() ) {
				return;
			}
	
			// Register a redirect to the application setup page.
			static::register_setup_app_redirect();
	
			// Register the WP App setup hooks.
			static::register_wp_app_setup_hooks();
	
			// Register the action to signal that the WP App has fully loaded.
			static::register_wp_app_loaded_action();
		}
	}

	public static function register_cli_init_action() {
		add_action( 'cli_init', [ Enpii_Base_Helper::class, 'wp_cli_init' ] );
	}

	public static function register_setup_app_redirect() {
		add_action(
			ENPII_BASE_SETUP_HOOK_NAME,
			[ Enpii_Base_Helper::class, 'maybe_redirect_to_setup_app' ],
			-200
		);
	}

	public static function is_enpii_base_prepare_command(): bool {
		return ! empty( $_SERVER['argv'] ) && array_intersect( (array) $_SERVER['argv'], [ 'enpii-base', 'prepare' ] );
	}

	public static function register_wp_app_setup_hooks() {
		add_action(
			ENPII_BASE_SETUP_HOOK_NAME,
			[ \Enpii_Base\App\WP\WP_Application::class, 'load_instance' ],
			-100
		);
	}

	public static function register_wp_app_loaded_action() {
		add_action(
			\Enpii_Base\App\Support\App_Const::ACTION_WP_APP_LOADED,
			[ static::class, 'handle_wp_app_loaded_action' ]
		);
	}
	
	public static function handle_wp_app_loaded_action() {
		\Enpii_Base\App\WP\Enpii_Base_WP_Plugin::init_with_wp_app(
			ENPII_BASE_PLUGIN_SLUG,
			__DIR__,
			plugin_dir_url( __FILE__ )
		);
	}
}
