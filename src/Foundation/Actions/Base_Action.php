<?php

declare(strict_types=1);

namespace Enpii_Base\Foundation\Actions;

/**
 * The class must have the method handle() 
 */
abstract class Base_Action {

	final public function execute() {
		return app()->call( [ $this, 'handle' ] );
	}
}
