<?php

declare(strict_types=1);

namespace Enpii_Base\Foundation\Support;

trait Executable_Trait {
	public static function exec( ...$arguments ) {
		$command = new static( ...$arguments );

		return app()->call( [ $command, 'execute' ] );
	}
}
