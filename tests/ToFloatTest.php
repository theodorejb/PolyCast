<?php

class ToFloatTest extends PHPUnit_Framework_TestCase
{
    public function testShouldPass()
    {
        $this->assertSame(0.0, to_float("0"));
        $this->assertSame(0.0, to_float(0));
        $this->assertSame(0.0, to_float(0.0));
        $this->assertSame(10.0, to_float("10"));
        $this->assertSame(10.0, to_float(10));
        $this->assertSame(10.0, to_float(10.0));
        $this->assertSame(1.5, to_float(1.5));
        $this->assertSame(1.5, to_float("1.5"));

        $this->assertSame((float) PHP_INT_MAX, to_float((string) PHP_INT_MAX));
        $this->assertSame((float) PHP_INT_MAX, to_float(PHP_INT_MAX));
        $this->assertSame((float) PHP_INT_MAX, to_float((float) PHP_INT_MAX));
        $this->assertSame((float) PHP_INT_MIN, to_float((string) PHP_INT_MIN));
        $this->assertSame((float) PHP_INT_MIN, to_float(PHP_INT_MIN));
        $this->assertSame((float) PHP_INT_MIN, to_float((float) PHP_INT_MIN));
    }

    public function testDisallowedTypes()
    {
        $this->assertFalse(to_float(null));
        $this->assertFalse(to_float(true));
        $this->assertFalse(to_float(false));
        $this->assertFalse(to_float(new stdClass()));
        $this->assertFalse(to_float(fopen("data:text/html,foobar", "r")));
        $this->assertFalse(to_float([]));
    }

    public function testRejectLeadingTrailingChars()
    {
        $this->assertFalse(to_float("10abc"));
        $this->assertFalse(to_float("abc10"));
    }

    public function testAcceptLeadingTrailingWhitespace()
    {
        $this->assertSame(100.0, to_float("   100    "));
        $this->assertSame(78.0, to_float("\n\t\v\r\f   78 \n \t\v\r\f   \n"));
        $this->assertSame(78.0, to_float("\n\t\v\r\f78"));
        $this->assertSame(78.0, to_float("78\n\t\v\r\f"));
    }

    public function testOverflowNanInf()
    {
        $this->assertSame(INF, to_float(INF));
        $this->assertSame(-INF, to_float(-INF));
        $this->assertTrue(is_nan(to_float(NAN)));
        $this->assertSame(PHP_INT_MAX * 2, to_float(PHP_INT_MAX * 2));
        $this->assertSame(PHP_INT_MIN * 2, to_float(PHP_INT_MIN * 2));
    }

    public function testExponents()
    {
        $this->assertSame(0.00075, to_float("75e-5"));
        $this->assertSame(310000000.0, to_float("31e+7"));
    }
}
