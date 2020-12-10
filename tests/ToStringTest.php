<?php

namespace theodorejb\polycast;

use PHPUnit\Framework\TestCase;

class ToStringTest extends TestCase
{
    public function shouldPass()
    {
        return [
            ["foobar", "foobar"],
            ["123", 123],
            ["123.45", 123.45],
            ["INF", INF],
            ["-INF", -INF],
            ["NAN", NAN],
            ["", ""],
            ["foobar", new Stringable()],
        ];
    }

    /**
     * @dataProvider shouldPass
     */
    public function testShouldPass($expected, $val)
    {
        $this->assertTrue(safe_string($val));
        $this->assertSame($expected, to_string($val));
    }

    public function disallowedTypes()
    {
        return [
            [null],
            [true],
            [false],
            [fopen("data:text/html,foobar", "r")],
            [[]],
        ];
    }

    /**
     * @dataProvider disallowedTypes
     */
    public function testDisallowedTypes($val)
    {
        $this->assertFalse(safe_string($val));
        $this->expectException(CastException::class);
        to_string($val);
    }

    public function invalidObjects()
    {
        return [
            [new \stdClass()],
            [new NotStringable()],
        ];
    }

    /**
     * @dataProvider invalidObjects
     */
    public function testInvalidObjects($val)
    {
        $this->assertFalse(safe_string($val));
        $this->expectException(CastException::class);
        to_string($val);
    }
}

class NotStringable {}

class Stringable
{
    public function __toString()
    {
        return "foobar";
    }
}
