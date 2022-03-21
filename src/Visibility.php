<?php

namespace Fastbolt\TestHelpers;

use ReflectionClass;

/**
 * Class Visibility
 */
class Visibility
{
    /**
     * Call protected/private method of a class.
     *
     * @param object $object     Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public static function invokeMethod(object $object, string $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method     = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        $return = $method->invokeArgs($object, $parameters);
        $method->setAccessible(false);

        return $return;
    }

    /**
     * Get protected/private property of a class.
     *
     * @param object $object       Instantiated object that we will run method on.
     * @param string $propertyName Property name to fetch
     *
     * @return mixed property value.
     */
    public static function getProperty(object $object, string $propertyName)
    {
        $reflection = new ReflectionClass(get_class($object));
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $return = null;
        if (!$property->hasType() || $property->isInitialized($object)) {
            $return = $property->getValue($object);
        }

        $property->setAccessible(false);

        return $return;
    }

    /**
     * Set protected/private property of a class.
     *
     * @param object $object        Instantiated object that we will set property on.
     * @param string $propertyName  Property name to update
     * @param mixed  $propertyValue Property value
     */
    public static function setProperty(object $object, string $propertyName, $propertyValue): void
    {
        $reflection = new ReflectionClass(get_class($object));
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $propertyValue);
        $property->setAccessible(false);
    }
}
