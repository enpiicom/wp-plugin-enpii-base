<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\Base\Wp_Cli;

use WP_CLI;

class Enpii_Base_Wp_Cli {

	public function prepare_wp_app() {
		$wp_app_base_path = enpii_base_wp_app_get_base_path();
		enpii_base_wp_app_prepare_folders($wp_app_base_path);

		WP_CLI::success( 'Preparing wp_app() done!' );
	}

}
