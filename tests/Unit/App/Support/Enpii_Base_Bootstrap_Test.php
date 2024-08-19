<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\App\Support;

use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Mockery;
use WP_Mock;

class Enpii_Base_Bootstrap_Test extends Unit_Test_Case {
	private $backup_SERVER = [];

	protected function setUp(): void {
		parent::setUp();
	}

	protected function tearDown(): void {
		global $_SERVER;
	}
}
