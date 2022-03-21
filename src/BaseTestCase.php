<?php

namespace Fastbolt\TestHelpers;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class BaseTestCase extends TestCase
{

    /**
     *
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

        $mockObject->method('count')
                   ->willReturnCallback(
                       function () use ($internalData) {
                           return sizeof($internalData->array);
                       }
                   );

        return $mockObject;
    }
}