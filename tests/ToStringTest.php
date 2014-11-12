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
    }

    public function testDisallowedTypes()
    {
        $this->assertNull(to_string(null));
        $this->assertNull(to_string(true));
        $this->assertNull(to_string(false));
        $this->assertNull(to_string([]));
        $this->assertNull(to_string(fopen("data:text/html,foobar", "r")));
    }

    public function testObjects()
    {
        $this->assertNull(to_string(new stdClass()));
        $this->assertNull(to_string(new NotStringable()));
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
