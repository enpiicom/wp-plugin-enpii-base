<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\App\Actions;

use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Mockery;

class Login_WP_App_User_Action_Test extends Unit_Test_Case {

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
