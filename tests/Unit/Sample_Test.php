<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit;

use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Mockery;

class Sample_Test extends Unit_Test_Case
{
    protected function setUp(): void {
	}

	protected function tearDown(): void {
		Mockery::close();
	}

	public function test_something() {
		// Assetions go here
		$this->assertTrue(true);
	}
}
