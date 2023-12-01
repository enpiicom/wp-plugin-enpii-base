<?php

namespace Enpii_Base\Tests\Unit\Foundation\Shared\Traits;

use Enpii_Base\Foundation\Shared\Traits\Accessor_Set_Get_Has_Trait;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;

class Accessor_Set_Get_Has_Trait_Test extends Unit_Test_Case {
	public function test_set_sroperty(): void {
		$sample_object = new class() {
			use Accessor_Set_Get_Has_Trait;
		};
		// Set a property using the set_property method
		$sample_object->set_property( 'property1', 'value' );

		// Assert that the property value is set correctly
		$this->assertEquals( 'value', $sample_object->get_property( 'property1' ) );
	}
}
