<?php

class ToStringTest extends PHPUnit_Framework_TestCase
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
        $this->assertTrue(string_castable($val));
        $this->assertSame($expected, to_string($val));
    }

    public function disallowedTypes()
    {
        return [
            [null],
            [true],
            [false],
            [[]],
            [fopen("data:text/html,foobar", "r")],
        ];
    }

    /**
     * @dataProvider disallowedTypes
     * @expectedException CastException
     */
    public function testDisallowedTypes($val)
    {
        $this->assertFalse(string_castable($val));
        to_string($val);
    }

    public function invalidObjects()
    {
        return [
            [new stdClass()],
            [new NotStringable()],
        ];
    }

    /**
     * @dataProvider invalidObjects
     * @expectedException CastException
     */
    public function testInvalidObjects($val)
    {
        $this->assertFalse(string_castable($val));
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
