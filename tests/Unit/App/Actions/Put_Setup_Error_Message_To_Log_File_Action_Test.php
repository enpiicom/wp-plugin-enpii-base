<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\App\Actions;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Enpii_Base\App\Actions\Put_Setup_Error_Message_To_Log_File_Action;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Mockery;
use WP_Mock;

class Put_Setup_Error_Message_To_Log_File_Action_Test extends Unit_Test_Case {

	protected function setUp(): void {
		parent::setUp();
	}

	protected function tearDown(): void {
		parent::tearDown();
		Mockery::close();
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_handle() {        
	}
}
