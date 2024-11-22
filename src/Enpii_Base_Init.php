<?php

declare(strict_types=1);

namespace Enpii_Base;

class Enpii_Base_Init {
	/**
	 * @var string
	 */
	protected $version;

	public function __construct() {
		$this->version = '0.8.6';
	}

	/**
	 * @return string
	 */
	public function get_version(): string {
		return $this->version;
	}
}
