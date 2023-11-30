<?php

declare(strict_types=1);

if ( ! function_exists( 'enpii_base_get_laravel_version' ) ) {
	function enpii_base_get_major_version($version): int {
		$parts = explode('.', $version);
		return (int)filter_var($parts[0], FILTER_SANITIZE_NUMBER_INT);
	}
}
