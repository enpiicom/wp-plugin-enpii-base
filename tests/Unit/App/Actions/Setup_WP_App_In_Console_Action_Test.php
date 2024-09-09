<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\App\Actions;

use Enpii_Base\App\Actions\Setup_WP_App_In_Console_Action;
use Illuminate\Console\Command;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use InvalidArgumentException;
use Mockery;

class Setup_WP_App_In_Console_Action_Test extends Unit_Test_Case {
	protected $console_command;

	protected function setUp(): void {
		parent::setUp();

		// Mock the Console Command
		$this->console_command = Mockery::mock( Command::class );
	}

	protected function tearDown(): void {
		parent::tearDown();
		Mockery::close();
	}

	public function test_constructor_with_valid_command(): void {
		// Test that the constructor correctly initializes with a valid Command instance
		$action = new Setup_WP_App_In_Console_Action( $this->console_command );
		$console_command = $this->get_protected_property_value( $action, 'console_command' );

		// Assert that the console_command property is correctly assigned
		$this->assertSame( $this->console_command, $console_command );
	}

	public function test_constructor_throws_exception_if_invalid_command(): void {
		$this->expectException( InvalidArgumentException::class );
		new Setup_WP_App_In_Console_Action( 'invalid_command' );
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_handle() {
		
		$this->assertTrue( true );
	}
}
