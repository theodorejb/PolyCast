<?php

class ToIntTest extends PHPUnit_Framework_TestCase
{
    public function testShouldPass()
    {
        $this->assertSame(0, to_int("0"));
        $this->assertSame(0, to_int(0));
        $this->assertSame(0, to_int(0.0));
        $this->assertSame(10, to_int("10"));
        $this->assertSame(10, to_int("010"));
        $this->assertSame(10, to_int("+10"));
        $this->assertSame(10, to_int(10));
        $this->assertSame(10, to_int(10.0));

        $this->assertSame(PHP_INT_MAX, to_int((string) PHP_INT_MAX));
        $this->assertSame(PHP_INT_MAX, to_int(PHP_INT_MAX));
        $this->assertSame(PHP_INT_MIN, to_int((string) PHP_INT_MIN));
        $this->assertSame(PHP_INT_MIN, to_int(PHP_INT_MIN));
    }

    public function testShouldNotPass()
    {
        $this->assertNull(to_int("10.0"));
        $this->assertNull(to_int("75e-5"));
        $this->assertNull(to_int("31e+7"));
        $this->assertNull(to_int("0x10"));
        $this->assertNull(to_int(1.5));
        $this->assertNull(to_int("1.5"));
    }

    public function testDisallowedTypes()
    {
        $this->assertNull(to_int(null));
        $this->assertNull(to_int(true));
        $this->assertNull(to_int(false));
        $this->assertNull(to_int(new stdClass()));
        $this->assertNull(to_int(fopen("data:text/html,foobar", "r")));
        $this->assertNull(to_int([]));
    }

    public function testRejectLeadingTrailingChars()
    {
        $this->assertNull(to_int("10abc"));
        $this->assertNull(to_int("abc10"));
        $this->assertNull(to_int("   100    "));
        $this->assertNull(to_int("\n\t\v\r\f   78 \n \t\v\r\f   \n"));
        $this->assertNull(to_int("\n\t\v\r78"));
        $this->assertNull(to_int("\n\t\v\r\f78"));
        $this->assertNull(to_int("78\n\t\v\r\f"));
    }

    public function testOverflowNanInf()
    {
        $this->assertNull(to_int(INF));
        $this->assertNull(to_int(-INF));
        $this->assertNull(to_int(NAN));
        $this->assertNull(to_int(PHP_INT_MAX * 2));
        $this->assertNull(to_int(PHP_INT_MIN * 2));
        $this->assertNull(to_int((string) PHP_INT_MAX * 2));
        $this->assertNull(to_int((string) PHP_INT_MIN * 2));
    }
}
