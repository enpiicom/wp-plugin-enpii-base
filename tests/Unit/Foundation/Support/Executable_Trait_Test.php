<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\Foundation\Support;

use Illuminate\Container\Container;

use Enpii_Base\Foundation\Support\Executable_Trait;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;

class Executable_Trait_Test extends Unit_Test_Case {
	private $app;
	private $mock_container;

	protected function setUp(): void {
		parent::setUp();

		// Mock the application container and bind the mock handler
		$this->mock_container = $this->createMock( Container::class );
		$this->mock_container->method( 'call' )->willReturn( 'handled' );

		// Bind the mock container to the app() function
		$this->app = $this->mock_container;
	}

	protected function tearDown(): void {
		parent::tearDown();
	}

	public function test_execute_now_creates_instance_and_calls_handle(): void {
		// Mock the command class
		$mock_command = $this->createMock( Executable_Trait_Test_Tmp::class );

		// Bind the mocked command class to the container
		$this->mock_container->method( 'call' )
							->with(
								$this->callback(
									function ( $args ) use ( $mock_command ) {
										return $args[0] === $mock_command && $args[1] === 'handle';
									}
								)
							)
							->willReturn( 'handled' );

		// Call the execute_now method
		$result = Executable_Trait_Test_Tmp::execute_now( 'arg1', 'arg2' );

		// Assert that the result is as expected
		$this->assertEquals( 'handled', $result );
	}
}


class Executable_Trait_Test_Tmp {
	use Executable_Trait;

	public function __construct( $arg1 = null, $arg2 = null ) {
		// Initialize with arguments if necessary
	}

	public function handle() {
		return 'handled'; // Simulate handling
	}
}
