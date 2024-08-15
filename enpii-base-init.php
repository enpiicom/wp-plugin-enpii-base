<?php
// Only initiate the wp_app() when WordPress is fully loaded
// If the plugin is loaded via Composer before WordPress is ready, we need to ensure proper checks are in place.

use Enpii_Base\App\Support\Enpii_Base_Helper;

if ( defined( 'WP_CONTENT_DIR' ) ) {
	add_action( 'cli_init', [ Enpii_Base_Helper::class, 'wp_cli_init' ] );

	if ( ! Enpii_Base_Helper::is_console_mode() ) {
		if ( ! Enpii_Base_Helper::perform_wp_app_check() ) {
			// WordPress app check failed, return early but keep the plugin active
			return;
		}
		// Redirect to setup app before WP App initialization if not in console mode
		add_action(
			ENPII_BASE_SETUP_HOOK_NAME,
			[ Enpii_Base_Helper::class, 'enpii_base_maybe_redirect_to_setup_app' ],
			-200
		);
	} elseif ( ! empty( $_SERVER['argv'] ) && array_intersect( (array) $_SERVER['argv'], [ 'enpii-base', 'prepare' ] ) ) {
		// Prepare WP app folders if specific CLI commands are present
		Enpii_Base_Helper::prepare_wp_app_folders();
	}

	// Initialize wp_app() at the appropriate hook point
	add_action(
		ENPII_BASE_SETUP_HOOK_NAME,
		[ \Enpii_Base\App\WP\WP_Application::class, 'load_instance' ],
		-100
	);

	// Initialize the Enpii Base plugin only when WP App is successfully loaded
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
