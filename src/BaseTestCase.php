<?php

namespace Fastbolt\TestHelpers;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class BaseTestCase extends TestCase
{
    /**
     * Helper method to mock iterator object.
     * Modified code taken from {@see https://stackoverflow.com/a/15907250/309163}
     */
    public function mockIterator(MockObject $mockObject, array $data): MockObject
    {
        $internalData           = new stdClass();
        $internalData->array    = $data;
        $internalData->position = 0;

        $mockObject->method('rewind')
                   ->willReturnCallback(
                       function () use ($internalData) {
                           $internalData->position = 0;
                       }
                   );

        $mockObject->method('current')
                   ->willReturnCallback(
                       function () use ($internalData) {
                           return $internalData->array[$internalData->position];
                       }
                   );

        $mockObject->method('key')
                   ->willReturnCallback(
                       function () use ($internalData) {
                           return $internalData->position;
                       }
                   );

        $mockObject->method('next')
                   ->willReturnCallback(
                       function () use ($internalData) {
                           $internalData->position++;
                       }
                   );

        $mockObject->method('valid')
                   ->willReturnCallback(
                       function () use ($internalData) {
                           return isset($internalData->array[$internalData->position]);
                       }
                   );

        if ($mockObject instanceof Countable) {
            $mockObject->method('count')
                       ->willReturnCallback(
                           function () use ($internalData) {
                               return sizeof($internalData->array);
                           }
                       );
        }

        return $mockObject;
    }

    /**
     * @return MockObject|stdClass|callable
     */
    protected function getCallable()
    {
        return $this->getMockBuilder(stdClass::class)
                    ->addMethods(['__invoke'])
                    ->getMock();
    }

    /**
     * @param string                                $className
     * @param string[]                              $onlyMethods
     * @param string[]                              $addMethods
     *
     * @template MockedType
     * @psalm-param class-string<MockedType>|string $className
     *
     * @return MockObject
     * @psalm-return MockObject&MockedType
     */
    protected function getMock(string $className, array $onlyMethods = [], array $addMethods = []): MockObject
    {
        $builder = $this->getMockBuilder($className)
                        ->disableOriginalConstructor();
        if ($onlyMethods) {
            $builder->onlyMethods($onlyMethods);
        }
        if ($addMethods) {
            $builder->addMethods($addMethods);
        }

        return $builder->getMock();
    }
}