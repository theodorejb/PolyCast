<?php

class ToFloatTest extends PHPUnit_Framework_TestCase
{
    public function testShouldPass()
    {
        $this->assertSame(0.0, toFloat("0"));
        $this->assertSame(0.0, toFloat(0));
        $this->assertSame(0.0, toFloat(0.0));
        $this->assertSame(10.0, toFloat("10"));
        $this->assertSame(10.0, toFloat(10));
        $this->assertSame(10.0, toFloat(10.0));
        $this->assertSame(1.5, toFloat(1.5));
        $this->assertSame(1.5, toFloat("1.5"));

        $this->assertSame((float) PHP_INT_MAX, toFloat((string) PHP_INT_MAX));
        $this->assertSame((float) PHP_INT_MAX, toFloat(PHP_INT_MAX));
        $this->assertSame((float) PHP_INT_MAX, toFloat((float) PHP_INT_MAX));
    }

    public function testDisallowedTypes()
    {
        $this->assertNull(toFloat(null));
        $this->assertNull(toFloat(true));
        $this->assertNull(toFloat(false));
        $this->assertNull(toFloat(new stdClass()));
        $this->assertNull(toFloat(fopen("data:text/html,foobar", "r")));
        $this->assertNull(toFloat([]));
    }

    public function testRejectLeadingTrailingChars()
    {
        $this->assertNull(toFloat("10abc"));
        $this->assertNull(toFloat("abc10"));
    }

    public function testAcceptLeadingTrailingWhitespace()
    {
        $this->assertSame(100.0, toFloat("   100    "));
        $this->assertSame(78.0, toFloat("\n\t\v\r\f   78 \n \t\v\r\f   \n"));
        $this->assertSame(78.0, toFloat("\n\t\v\r\f78"));
        $this->assertSame(78.0, toFloat("78\n\t\v\r\f"));
    }

    public function testOverflowNanInf()
    {
        $this->assertSame(INF, toFloat(INF));
        $this->assertSame(-INF, toFloat(-INF));
        $this->assertTrue(is_nan(toFloat(NAN)));
        $this->assertSame(PHP_INT_MAX * 2, toFloat(PHP_INT_MAX * 2));
    }

    public function testExponents()
    {
        $this->assertSame(0.00075, toFloat("75e-5"));
        $this->assertSame(310000000.0, toFloat("31e+7"));
    }
}
