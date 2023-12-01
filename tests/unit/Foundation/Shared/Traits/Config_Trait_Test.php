<?php

namespace Enpii_Base\Tests\Unit\Foundation\Shared\Traits;

use Enpii_Base\Foundation\Shared\Traits\Config_Trait;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use InvalidArgumentException;

class Config_Trait_Test extends Unit_Test_Case {
	public function test_bind_config(): void {
		// Create a dummy class that uses the Config_Trait
		$dummyObject = new class() {
			use Config_Trait;

			public $property1;
			public $property2;
			public $property3;
		};

		// Create a test configuration array
		$config = [
			'property1' => 'value1',
			'property2' => 'value2',
			'property3' => 'value3',
		];

		// Call the bind_config method
		$result = $dummyObject->bind_config( $config );

		// Assert that the properties are correctly assigned
		$this->assertEquals( 'value1', $dummyObject->property1 );
		$this->assertEquals( 'value2', $dummyObject->property2 );
		$this->assertEquals( 'value3', $dummyObject->property3 );
		$this->assertSame( $dummyObject, $result );
	}

	public function test_bind_config_strict_mode(): void {
		// Create a dummy class that uses the Config_Trait
		$dummyObject = new class() {
			use Config_Trait;

			public $property1;
			public $property2;
			public $property3;

		};

		// Create a test configuration array with an unknown property
		$config = [
			'property1' => 'value1',
			'unknown_property' => 'value2',
			'property3' => 'value3',
		];

		// Call the bind_config method with strict mode enabled
		$this->expectException( InvalidArgumentException::class );
		$dummyObject->bind_config( $config, true );
	}
}
