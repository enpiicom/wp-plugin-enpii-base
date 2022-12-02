<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\Libs;

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Cache\CacheManager;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Config\Repository;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Foundation\Application;

class Wp_Application extends Application {
	public function initAppWithConfig( $config = null ): self {
		$this->singleton(
			'config',
			function ( $app ) use ( $config ) {
				return new Repository( $config );
			}
		);

		return $this;
	}

	public function register_cache_service_provider(): void {
		$this->register(\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Cache\CacheServiceProvider::class);
	}
}
