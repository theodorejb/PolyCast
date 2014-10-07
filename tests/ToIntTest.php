<?php

class ToIntTest extends PHPUnit_Framework_TestCase
{
    public function testShouldPass()
    {
        $PHP_INT_MIN = ~PHP_INT_MAX; // const isn't in PHP 5.x

        $this->assertSame(0, to_int("0"));
        $this->assertSame(0, to_int(0));
        $this->assertSame(0, to_int(0.0));
        $this->assertSame(10, to_int("10"));
        $this->assertSame(10, to_int(10));
        $this->assertSame(10, to_int(10.0));

        $this->assertSame(PHP_INT_MAX, to_int((string) PHP_INT_MAX));
        $this->assertSame(PHP_INT_MAX, to_int(PHP_INT_MAX));
        $this->assertSame($PHP_INT_MIN, to_int((string) $PHP_INT_MIN));
        $this->assertSame($PHP_INT_MIN, to_int($PHP_INT_MIN));
    }

    public function testDisallowedTypes()
    {
        $this->assertFalse(to_int(null));
        $this->assertFalse(to_int(true));
        $this->assertFalse(to_int(false));
        $this->assertFalse(to_int(new stdClass()));
        $this->assertFalse(to_int(fopen("data:text/html,foobar", "r")));
        $this->assertFalse(to_int([]));
    }

    public function testTruncation()
    {
        $this->assertSame(1, to_int(1.5));
        $this->assertFalse(to_int("1.5"));
    }

    public function testRejectLeadingTrailingChars()
    {
        $this->assertFalse(to_int("10abc"));
        $this->assertFalse(to_int("abc10"));
    }

    public function testAcceptLeadingTrailingWhitespace()
    {
        $this->assertSame(100, to_int("   100    "));
        $this->assertSame(78, to_int("\n\t\v\r\f   78 \n \t\v\r\f   \n"));
        $this->assertSame(78, to_int("\n\t\v\r\f78"));
        $this->assertSame(78, to_int("78\n\t\v\r\f"));
    }

    public function testOverflowNanInf()
    {
        $this->assertFalse(to_int(INF));
        $this->assertFalse(to_int(-INF));
        $this->assertFalse(to_int(NAN));
        $this->assertFalse(to_int(PHP_INT_MAX * 2));
    }

    public function testExponents()
    {
        $this->assertFalse(to_int("75e-5"));
        $this->assertFalse(to_int("31e+7"));
    }
}
