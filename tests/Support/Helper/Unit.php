<?php
namespace Enpii_Base\Tests\Helper;

use ReflectionClass;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Unit extends \Codeception\Module
{
	static public function get_property_class($classname, $property_name, $object) {
        // Use reflection to access the protected property
        $reflectionClass = new ReflectionClass($classname);
        $property = $reflectionClass->getProperty($property_name);
        $property->setAccessible(true); // Set the property as accessible
        $value = $property->getValue($object); // Get the value of the protected property

		return $value;
	}
}
