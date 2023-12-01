<?php

namespace Enpii_Base\Tests\Support\Helpers;

use ReflectionClass;

trait Test_Utils
{
	/**
	 * @description: For testing protected, private method in class
	 * @throws \ReflectionException
	 */
	protected function invoke_method(&$object, $methodName, array $parameters = [])
	{
		$reflection = new ReflectionClass(get_class($object));
		$method     = $reflection->getMethod($methodName);
		$method->setAccessible(true);

		return $method->invokeArgs($object, $parameters);
	}

	/**
	 * Get protected/private property value of a class.
	 *
	 * @param  object  $object  Instantiated object that we will get value from.
	 * @param  string  $propertyName  Property name to call
	 *
	 * @throws \ReflectionException
	 */
	protected function get_class_property_value(object $object, string $propertyName)
	{
		$reflection = new \ReflectionClass(get_class($object));
		$property   = $reflection->getProperty($propertyName);
		$property->setAccessible(true);

		return $property->getValue($object);
	}

	/**
	 * Set value to a property of an object class.
	 *
	 * @param  object  $object  Instantiated object that we will get value from.
	 * @param  string  $property  Property name to set value to.
	 * @param  mixed  $value  Value to set to property
	 *
	 * @throws \ReflectionException
	 */
	protected function set_class_property_value(object $object, string $property, mixed $value) : void
	{
		$reflection = new \ReflectionClass(get_class($object));
		$property   = $reflection->getProperty($property);
		$property->setAccessible(true);
		$property->setValue($object, $value);
	}
}
