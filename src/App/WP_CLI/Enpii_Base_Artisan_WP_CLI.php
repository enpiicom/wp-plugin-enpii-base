<?php

declare(strict_types=1);

namespace Enpii_Base\App\WP_CLI;

use Enpii_Base\App\Actions\WP_CLI\Process_Artisan_Action;

class Enpii_Base_Artisan_WP_CLI {
	// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsedl, Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	public function __invoke( $args, $options ) {
		Process_Artisan_Action::exec();
	}
}
