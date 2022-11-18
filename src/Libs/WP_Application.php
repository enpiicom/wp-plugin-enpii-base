<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\Libs;

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Cache\CacheManager;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Config\Repository;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Foundation\Application;

class WP_Application extends Application {
	public function initAppWithConfig( $config = null ): self {
		$this->singleton(
			'config',
			function ( $app ) use ( $config ) {
				return new Repository( $config );
			}
		);

		return $this;
	}

	public function getCacheManager(): CacheManager {
		return $this->make( 'cache' );
	}
}
