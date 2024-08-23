<?php

declare(strict_types=1);

namespace Enpii_Base\App\Support;

use Enpii_Base\App\Support\Enpii_Base_Helper;

class Enpii_Base_Bootstrap {
	public static function initialize( $plugin_url, $dirname ) {
		if ( Enpii_Base_Helper::is_wp_content_loaded() ) {
			static::register_cli_init_action();
		
			if ( ! Enpii_Base_Helper::is_console_mode() && ! Enpii_Base_Helper::perform_wp_app_check() ) {
				// We do nothing but still keep the plugin enabled
				return;
			}
		
			if ( ! Enpii_Base_Helper::is_console_mode() ) {
				// We want to redirect to setup app before the WP App init
				static::register_setup_app_redirect();
			} elseif ( static::is_enpii_base_prepare_command() ) {
					Enpii_Base_Helper::prepare_wp_app_folders();
			}
		
			// We init wp_app() here
			static::init_wp_app_instance();

			// We init the Enpii Base plugin only when the WP App is loaded correctly
			static::init_enpii_base_wp_plugin_instance( $plugin_url, $dirname );
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
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		return ! empty( $_SERVER['argv'] ) && array_intersect( (array) $_SERVER['argv'], [ 'enpii-base', 'prepare' ] );
	}

	public static function init_wp_app_instance() {
		add_action(
			ENPII_BASE_SETUP_HOOK_NAME,
			[ \Enpii_Base\App\WP\WP_Application::class, 'load_instance' ],
			-100
		);
	}

	public static function init_enpii_base_wp_plugin_instance( $plugin_url, $dirname ) {
		add_action(
			\Enpii_Base\App\Support\App_Const::ACTION_WP_APP_LOADED,
			function () use ( $plugin_url, $dirname ) {
				\Enpii_Base\App\WP\Enpii_Base_WP_Plugin::init_with_wp_app(
					ENPII_BASE_PLUGIN_SLUG,
					$dirname,
					$plugin_url
				);
			}
		);
	}
}
