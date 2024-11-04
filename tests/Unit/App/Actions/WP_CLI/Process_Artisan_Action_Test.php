<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\App\Actions;

use Enpii_Base\App\Actions\WP_CLI\Process_Artisan_Action;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use InvalidArgumentException;
use Mockery;

class Process_Artisan_Action_Test extends Unit_Test_Case {

	protected function setUp(): void {
		parent::setUp();
	}

	protected function tearDown(): void {
		parent::tearDown();
		Mockery::close();
	}

	public function test_handle_throws_exception_if_artisan_not_in_args() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Not an artisan command' );

		$kernelMock = Mockery::mock( \Illuminate\Contracts\Console\Kernel::class );
		app()->instance( \Illuminate\Contracts\Console\Kernel::class, $kernelMock );

		// Simulate $_SERVER['argv'] without 'artisan'
		$_SERVER['argv'] = [ 'some_command', 'another_command' ];

		$action = new Process_Artisan_Action();
		$action->handle();
	}

	public function test_handle() {
		// Simulate $_SERVER['argv'] with 'artisan'
		$_SERVER['argv'] = [ 'php', 'artisan', 'make:command', 'TestCommand' ];

		// Mock the Kernel
		$kernelMock = Mockery::mock( \Illuminate\Contracts\Console\Kernel::class );
		$kernelMock->shouldReceive( 'handle' )
		->once()
			->andReturn( 0 );

		app()->instance( \Illuminate\Contracts\Console\Kernel::class, $kernelMock );

		$action = new Process_Artisan_Action_Test_Tmp();
		$status = $action->handle();

		$this->assertEquals( 'test', $status );
	}
}

namespace Enpii_Base\Tests\Unit\App\Actions;

use Enpii_Base\App\Actions\WP_CLI\Process_Artisan_Action;

class Process_Artisan_Action_Test_Tmp extends Process_Artisan_Action {

	protected function exit_with_status( int $status ) {
		return 'test';
	}
}
