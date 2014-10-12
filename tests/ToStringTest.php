<?php

class ToStringTest extends PHPUnit_Framework_TestCase
{
    public function testShouldPass()
    {
        $this->assertSame("foobar", to_string("foobar"));
        $this->assertSame("10", to_string(10));
        $this->assertSame("10", to_string(10.0));
        $this->assertSame("-10", to_string(-10));
        $this->assertSame("-10", to_string(-10.0));
        $this->assertSame("1.5", to_string(1.5));
        $this->assertSame("75e-5", to_string("75e-5"));
        $this->assertSame("INF", to_string(INF));
        $this->assertSame("-INF", to_string(-INF));
        $this->assertSame("NAN", to_string(NAN));
    }

    public function testDisallowedTypes()
    {
        $this->assertFalse(to_string(null));
        $this->assertFalse(to_string(true));
        $this->assertFalse(to_string(false));
        $this->assertFalse(to_string([]));
        $this->assertFalse(to_string(fopen("data:text/html,foobar", "r")));
    }

    public function testObjects()
    {
        $this->assertFalse(to_string(new stdClass()));
        $this->assertFalse(to_string(new NotStringable()));
        $this->assertSame("foobar", to_string(new Stringable()));
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
