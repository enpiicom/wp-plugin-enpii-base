<?php

declare(strict_types=1);

namespace Enpii_Base\Foundation\Actions;

abstract class Base_Action {

	final public function execute() {
		return static::handle();
	}

	abstract protected function handle();
}
