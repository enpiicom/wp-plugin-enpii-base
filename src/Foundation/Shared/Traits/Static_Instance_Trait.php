<?php

declare(strict_types=1);

namespace Enpii_Base\Foundation\Shared\Traits;

use InvalidArgumentException;

/**
 * This trait allow the class to have a singleton static object
 */
trait Static_Instance_Trait {
	protected static $instance = null;

	/**
	 * Initialize the singleton instance then return it or the existing one
	 * @param mixed $args
	 * @return static
	 */
	public static function instance(...$args) {
		if (empty(static::$instance)) {
			static::$instance = new static(...$args);
		}

		return static::$instance;
	}

	public static function init_instance($init_object): void {
		if (empty(static::$instance)) {
			static::$instance = $init_object;
		} else {
			throw new InvalidArgumentException(__('Instance not empty'));
		}
	}
}
