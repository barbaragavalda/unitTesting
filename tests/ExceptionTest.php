<?php

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException           InvalidArgumentException
     */
    public function testException()
    {
        throw new InvalidArgumentException('Some message', 10);
    }

    /**
     * @expectedException           InvalidArgumentException
     * @expectedExceptionMessage    Right Message
     */
    public function testExceptionMessage()
    {
        throw new InvalidArgumentException('Some message', 10);
    }

    /**
     * @expectedException           InvalidArgumentException
     * @expectedExceptionCode    10
     */
    public function testExceptionCode()
    {
        throw new InvalidArgumentException('Some message', 20);
    }
}