<?php

declare(strict_types=1);

namespace Enpii_Base\App\Support;

class Enpii_Base_Helper {
	public static $version_option;
	public static $setup_info;

	public static function get_current_url(): string {
		if ( empty( $_SERVER['SERVER_NAME'] ) && empty( $_SERVER['HTTP_HOST'] ) ) {
			return '';
		}

		if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
			$_SERVER['HTTPS'] = 'on';
		}

		if ( isset( $_SERVER['HTTP_HOST'] ) ) {
			$http_protocol = isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
		}

		$current_url = $http_protocol ?? '';
		$current_url .= $current_url ? '://' : '//';

		if ( ! empty( $_SERVER['HTTP_HOST'] ) ) {
			$current_url .= sanitize_text_field( $_SERVER['HTTP_HOST'] ) . ( isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( $_SERVER['REQUEST_URI'] ) : '' );

			return $current_url;
		}

		if ( isset( $_SERVER['SERVER_PORT'] ) && $_SERVER['SERVER_PORT'] != '80' ) {
			$current_url .= sanitize_text_field( $_SERVER['SERVER_NAME'] ) . ':' . sanitize_text_field( $_SERVER['SERVER_PORT'] ) . ( isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( $_SERVER['REQUEST_URI'] ) : '' );
		} else {
			$current_url .= sanitize_text_field( $_SERVER['SERVER_NAME'] ) . ( isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( $_SERVER['REQUEST_URI'] ) : '' );
		}

		return $current_url;
	}

	public static function get_setup_app_uri( $full_url = false ): string {
		$uri = 'wp-app/setup-app/?force_app_running_in_console=1';

		return $full_url ? trim( site_url(), '/' ) . '/' . $uri : $uri;
	}

	public static function get_admin_setup_app_uri( $full_url = false ): string {
		$uri = 'wp-app/admin/setup-app/?force_app_running_in_console=1';

		return $full_url ? trim( site_url(), '/' ) . '/' . $uri : $uri;
	}

	public static function get_wp_login_url( $return_url = '', $force_reauth = false ): string {
		return wp_login_url( $return_url, $force_reauth );
	}

	public static function at_setup_app_url(): bool {
		$current_url = static::get_current_url();
		$setup_app_uri = static::get_setup_app_uri();

		return ( strpos( $current_url, $setup_app_uri ) !== false );
	}

	public static function at_admin_setup_app_url(): bool {
		$current_url = static::get_current_url();
		$redirect_uri = static::get_admin_setup_app_uri();

		return ( strpos( $current_url, $redirect_uri ) !== false );
	}

	public static function at_wp_login_url(): bool {
		$current_url = static::get_current_url();
		$login_url = wp_login_url();

		return ( strpos( $current_url, $login_url ) !== false );
	}

	public static function redirect_to_setup_url(): void {
		$redirect_uri = static::get_setup_app_uri();
		if ( ! static::at_setup_app_url() && ! static::at_admin_setup_app_url() ) {
			$redirect_url = add_query_arg(
				[
					'return_url' => urlencode( static::get_current_url() ),
				],
				site_url( $redirect_uri )
			);
			header( 'Location: ' . $redirect_url );
			exit( 0 );
		}
	}

	public static function get_base_url_path(): string {
		$site_url_parts = wp_parse_url( site_url() );

		return empty( $site_url_parts['path'] ) ? '' : $site_url_parts['path'];
	}

	public static function get_current_blog_path() {
		$site_url = site_url();
		$network_site_url = network_site_url();

		if ( $site_url === $network_site_url ) {
			return false;
		}

		$reverse_pos = strpos( strrev( $site_url ), strrev( $network_site_url ) );
		if ( $reverse_pos === false ) {
			return false;
		}

		return trim( substr( $site_url, $reverse_pos * ( -1 ) ), '/' );
	}

	public static function get_version_option() {
		if ( empty( static::$version_option ) ) {
			static::$version_option = (string) get_option( App_Const::OPTION_VERSION, '0.0.0' );
		}

		return static::$version_option;
	}

	public static function get_setup_info() {
		if ( empty( static::$setup_info ) ) {
			static::$setup_info = (string) get_option( App_Const::OPTION_SETUP_INFO );
		}

		return static::$setup_info;
	}

	public static function is_setup_app_completed() {
		// We have migration for session with db from '0.7.0'
		return apply_filters( 'enpii_base_is_setup_app_completed', version_compare( static::get_version_option(), '0.7.0', '>=' ) );
	}

	public static function is_setup_app_failed() {
		return static::get_setup_info() === 'failed';
	}

	/**
	 * We want to check if the wp_app setup has been done correctly
	 *  If the setup process failed, we should return false and raise the notice in the Admin
	 */
	public static function perform_wp_app_check(): bool {
		if ( ! isset( $GLOBALS['wp_app_setup_errors'] ) ) {
			$GLOBALS['wp_app_setup_errors'] = [];
		}

		// We only want to check if it's not in the setup url
		if ( static::is_setup_app_failed() && ! static::at_setup_app_url() && ! static::at_admin_setup_app_url() ) {
			$error_message = sprintf(
				// translators: %1$s is replaced by a string, url
				__( 'The setup has not been done correctly. Please go to this URL <a href="%1$s">%1$s</a> to complete the setup', 'enpii' ),
				static::get_admin_setup_app_uri( true )
			);
			if ( ! isset( $GLOBALS['wp_app_setup_errors'][ $error_message ] ) ) {
				$GLOBALS['wp_app_setup_errors'][ $error_message ] = false;
			}
		}

		if ( ! empty( $GLOBALS['wp_app_setup_errors'] ) ) {
			add_action(
				'admin_notices',
				function () {
					$error_content = '';
					foreach ( (array) $GLOBALS['wp_app_setup_errors'] as $error_message => $displayed ) {
						if ( ! $displayed && $error_message ) {
							$error_content .= '<p>' . $error_message . '</p>';
							$GLOBALS['wp_app_setup_errors'][ $error_message ] = true;
						}
					}
					if ( $error_content ) {
						echo '<div class="notice notice-error">' . wp_kses_post( $error_content ) . '</div>';
					}
				}
			);

			return apply_filters( App_Const::FILTER_WP_APP_CHECK, false );
		}

		return apply_filters( App_Const::FILTER_WP_APP_CHECK, true );
	}
}
