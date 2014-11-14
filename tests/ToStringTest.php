<?php

class ToStringTest extends PHPUnit_Framework_TestCase
{
    public function testShouldPass()
    {
        $this->assertSame("foobar", to_string("foobar"));
        $this->assertSame("123", to_string(123));
        $this->assertSame("123.45", to_string(123.45));
        $this->assertSame("INF", to_string(INF));
        $this->assertSame("-INF", to_string(-INF));
        $this->assertSame("NAN", to_string(NAN));
        $this->assertSame("", to_string(""));
        $this->assertSame("foobar", to_string(new Stringable()));
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
     * @expectedException InvalidArgumentException
     */
    public function testDisallowedTypes($val)
    {
        $this->assertNull(try_string($val));
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
     * @expectedException BadMethodCallException
     */
    public function testInvalidObjects($val)
    {
        $this->assertNull(try_string($val));
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
