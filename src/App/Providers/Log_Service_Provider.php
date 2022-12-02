<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\App\Providers;

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Log\LogServiceProvider;

class Log_Service_Provider extends LogServiceProvider {
	public function boot()
    {
        wp_app_config([
			'logging' => [
				/*
				|--------------------------------------------------------------------------
				| Default Log Channel
				|--------------------------------------------------------------------------
				|
				| This option defines the default log channel that gets used when writing
				| messages to the logs. The name specified in this option should match
				| one of the channels defined in the "channels" configuration array.
				|
				*/
				'default' => env('LOG_CHANNEL', 'stack'),
				/*
				|--------------------------------------------------------------------------
				| Log Channels
				|--------------------------------------------------------------------------
				|
				| Here you may configure the log channels for your application. Out of
				| the box, Laravel uses the Monolog PHP logging library. This gives
				| you a variety of powerful log handlers / formatters to utilize.
				|
				| Available Drivers: "single", "daily", "slack", "syslog",
				|                    "errorlog", "monolog",
				|                    "custom", "stack"
				|
				*/
				'channels' => [
					'stack' => [
						'driver' => 'stack',
						'channels' => ['daily'],
					],
					'single' => [
						'driver' => 'single',
						'path' => enpii_base_get_wp_app_storage_path().'/logs/laravel.log',
						'level' => 'debug',
					],
					'daily' => [
						'driver' => 'daily',
						'path' => enpii_base_get_wp_app_storage_path().'/logs/laravel.log',
						'level' => 'debug',
						'days' => 14,
					],
					'stderr' => [
						'driver' => 'monolog',
						'handler' => StreamHandler::class,
						'with' => [
							'stream' => 'php://stderr',
						],
					],
					'syslog' => [
						'driver' => 'syslog',
						'level' => 'debug',
					],
					'errorlog' => [
						'driver' => 'errorlog',
						'level' => 'debug',
					],
				],
			],
		]);
    }
}
