<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\App\Actions;

use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Mockery;

class Show_Basic_Info_Action_Test extends Unit_Test_Case {

	protected function setUp(): void {
		parent::setUp();
	}

	protected function tearDown(): void {
		parent::tearDown();
		Mockery::close();
	}

	public function test_handle() {
		$this->assertTrue( true );
	}
}
