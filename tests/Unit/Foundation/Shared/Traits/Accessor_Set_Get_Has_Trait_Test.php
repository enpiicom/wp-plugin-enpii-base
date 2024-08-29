<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\Foundation\Shared\Traits;

use Enpii_Base\Foundation\Shared\Traits\Accessor_Set_Get_Has_Trait;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use InvalidArgumentException;

class Accessor_Set_Get_Has_Trait_Test extends Unit_Test_Case {

	private $dummy_obj;

	protected function setUp(): void {
		// Create a dummy class that uses the Accessor_Set_Get_Has_Trait
		$this->dummy_obj = new class() {
			use Accessor_Set_Get_Has_Trait;

			public $property1;
			public $property2;
		};
	}

	protected function tearDown(): void {
		parent::tearDown();
	}

	public function test_set_property(): void {
		// Set a property using the set_property method
		$this->dummy_obj->set_property( 'property1', 'value' );

		// Assert that the property value is set correctly
		$this->assertEquals( 'value', $this->dummy_obj->get_property( 'property1' ) );
	}

	public function test_set_property_with_invalid_property(): void {
		// Expect an exception when setting an invalid property
		$this->expectException( InvalidArgumentException::class );
	
		try {
			// Attempt to set a property that does not exist
			$this->dummy_obj->set_property( 'invalid_property', 'some_value' );
		} catch ( InvalidArgumentException $e ) {
			// Assert that the exception message contains the expected core part
			$expected_message_part = "Property 'invalid_property' does not exist in";
			$this->assertStringContainsString( $expected_message_part, $e->getMessage() );
	
			// Rethrow the exception to fulfill the expectException requirement
			throw $e;
		}
	}

	public function test_get_property(): void {
		// Set new properties
		$this->dummy_obj->property1 = 'value1';
		$this->dummy_obj->property2 = 'value2';
		$result1 = $this->dummy_obj->get_property( 'property1' );
		$result2 = $this->dummy_obj->get_property( 'property2' );

		// Assert that the property value is get correctly
		$this->assertEquals( 'value1', $result1 );
		$this->assertEquals( 'value2', $result2 );
	}

	public function test_get_property_with_invalid_property(): void {
		// Expect an exception when setting an invalid property
		$this->expectException( InvalidArgumentException::class );
	
		try {
			// Attempt to get a property that does not exist
			$this->dummy_obj->get_property( 'invalid_property' );
		} catch ( InvalidArgumentException $e ) {
			// Assert that the exception message contains the expected core part
			$expected_message_part = "Property 'invalid_property' does not exist in";
			$this->assertStringContainsString( $expected_message_part, $e->getMessage() );
	
			// Rethrow the exception to fulfill the expectException requirement
			throw $e;
		}
	}

	public function test_has_property(): void {
		// Set new properties
		$this->dummy_obj->property1 = 'value1';
		$result1 = $this->dummy_obj->has_property( 'property1' );

		// Assert that the property exists
		$this->assertTrue( $result1 );
	}

	public function test_has_property_check_with_invalid_property(): void {
		// Expect an exception when setting an invalid property
		$this->expectException( InvalidArgumentException::class );
	
		try {
			// Attempt to get a property that does not exist
			$this->dummy_obj->has_property( 'invalid_property' );
		} catch ( InvalidArgumentException $e ) {
			// Assert that the exception message contains the expected core part
			$expected_message_part = "Property 'invalid_property' does not exist in";
			$this->assertStringContainsString( $expected_message_part, $e->getMessage() );
	
			// Rethrow the exception to fulfill the expectException requirement
			throw $e;
		}
	}

	public function test_non_existing_method_throws_exception() {
		$this->expectException( \BadMethodCallException::class );
		$this->expectExceptionMessage( "'undefined_method' does not exist in 'Enpii_Base\Foundation\Shared\Traits\Accessor_Set_Get_Has_Trait'." );
		$this->dummy_obj->undefined_method();
	}
}
