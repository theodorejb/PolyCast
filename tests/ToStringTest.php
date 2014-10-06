<?php

class ToStringTest extends PHPUnit_Framework_TestCase
{
    public function testShouldPass()
    {
        $this->assertSame("foobar", toString("foobar"));
        $this->assertSame("123", toString(123));
        $this->assertSame("123.45", toString(123.45));
        $this->assertSame("INF", toString(INF));
        $this->assertSame("-INF", toString(-INF));
        $this->assertSame("NAN", toString(NAN));
    }

    public function testDisallowedTypes()
    {
        $this->assertNull(toString(null));
        $this->assertNull(toString(true));
        $this->assertNull(toString(false));
        $this->assertNull(toString([]));
        $this->assertNull(toString(fopen("data:text/html,foobar", "r")));
    }

    public function testObjects()
    {
        $this->assertNull(toString(new stdClass()));
        $this->assertNull(toString(new NotStringable()));
        $this->assertSame("foobar", toString(new Stringable()));
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
