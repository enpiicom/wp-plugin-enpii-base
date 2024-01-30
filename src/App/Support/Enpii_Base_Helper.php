<?php

declare(strict_types=1);

namespace Enpii_Base\App\Support;

class Enpii_Base_Helper {
	const VERSION_OPTION_FIELD = 'enpii_base_version';
	const TEXT_DOMAIN = 'enpii';

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

		$current_url = $http_protocol;
		$current_url .= '://';

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

	public static function get_setup_app_uri(): string {
		return 'wp-app/wp-admin/admin/setup-app?force_app_running_in_console=1';
	}

	public static function redirect_to_setup_url(): void {
		$current_url = static::get_current_url();
		$redirect_uri = static::get_setup_app_uri();
		if ( strpos( $current_url, $redirect_uri ) === false ) {
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
}
