<?php

declare(strict_types=1);

namespace Enpii_Base\App\WP;

use Enpii_Base\App\Support\Enpii_Base_Helper;

class Enpii_Base_Bootstrap {
	public static function initialize() {
		if ( self::is_wp_content_loaded() ) {
			self::register_cli_init_action();
			self::handle_non_console_mode();
			self::register_wp_app_setup_hooks();
			self::register_wp_app_loaded_action();
		}
	}

	protected static function is_wp_content_loaded(): bool {
		return Enpii_Base_Helper::is_wp_content_loaded();
	}

	protected static function register_cli_init_action() {
		add_action( 'cli_init', [ Enpii_Base_Helper::class, 'wp_cli_init' ] );
	}

	protected static function handle_non_console_mode() {
		if ( ! self::is_console_mode() ) {
			if ( ! self::perform_wp_app_check() ) {
				// WordPress app check failed, return early but keep the plugin active
				return;
			}
			self::register_setup_app_redirect();
		} elseif ( self::is_cli_prepare_command_present() ) {
			self::prepare_wp_app_folders();
		}
	}

	protected static function is_console_mode(): bool {
		return Enpii_Base_Helper::is_console_mode();
	}

	protected static function perform_wp_app_check(): bool {
		return Enpii_Base_Helper::perform_wp_app_check();
	}

	protected static function register_setup_app_redirect() {
		add_action(
			ENPII_BASE_SETUP_HOOK_NAME,
			[ Enpii_Base_Helper::class, 'enpii_base_maybe_redirect_to_setup_app' ],
			-200
		);
	}

	protected static function prepare_wp_app_folders() {
		Enpii_Base_Helper::prepare_wp_app_folders();
	}

	protected static function is_cli_prepare_command_present(): bool {
		return ! empty( $_SERVER['argv'] ) && array_intersect( (array) $_SERVER['argv'], [ 'enpii-base', 'prepare' ] );
	}

	protected static function register_wp_app_setup_hooks() {
		add_action(
			ENPII_BASE_SETUP_HOOK_NAME,
			[ \Enpii_Base\App\WP\WP_Application::class, 'load_instance' ],
			-100
		);
	}

	protected static function register_wp_app_loaded_action() {
		add_action(
			\Enpii_Base\App\Support\App_Const::ACTION_WP_APP_LOADED,
			function () {
				\Enpii_Base\App\WP\Enpii_Base_WP_Plugin::init_with_wp_app(
					ENPII_BASE_PLUGIN_SLUG,
					__DIR__,
					plugin_dir_url( __FILE__ )
				);
			}
		);
	}
}
