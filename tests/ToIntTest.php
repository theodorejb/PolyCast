<?php

class ToIntTest extends PHPUnit_Framework_TestCase
{
    public function testShouldPass()
    {
        $this->assertSame(0, toInt("0"));
        $this->assertSame(0, toInt(0));
        $this->assertSame(0, toInt(0.0));
        $this->assertSame(10, toInt("10"));
        $this->assertSame(10, toInt(10));
        $this->assertSame(10, toInt(10.0));

        $this->assertSame(PHP_INT_MAX, toInt((string) PHP_INT_MAX));
        $this->assertSame(PHP_INT_MAX, toInt(PHP_INT_MAX));
    }

    public function testDisallowedTypes()
    {
        $this->assertNull(toInt(null));
        $this->assertNull(toInt(true));
        $this->assertNull(toInt(false));
        $this->assertNull(toInt(new stdClass()));
        $this->assertNull(toInt(fopen("data:text/html,foobar", "r")));
        $this->assertNull(toInt([]));
    }

    public function testBases()
    {
        $this->assertSame(8, toInt("010", 0));    // base detect octal
        $this->assertSame(10, toInt("010", 10));  // not octal
        $this->assertSame(10, toInt("010"));      // not octal
        $this->assertSame(16, toInt("0x10", 0));  // base detect hex
        $this->assertSame(16, toInt("0x10", 16)); // hex
        $this->assertNull(toInt("0x10", 10));     // not hex
        $this->assertNull(toInt("0x10"));         // not hex
        $this->assertNull(toInt("123AyZ", 35));   // z is only in base 36
        $this->assertSame(63979595, toInt("123ayz", 36));
        $this->assertSame(63979595, toInt("123AyZ", 36));
    }

    public function testTruncation()
    {
        $this->assertSame(1, toInt(1.5));
        $this->assertNull(toInt("1.5"));
    }

    public function testRejectLeadingTrailingChars()
    {
        $this->assertNull(toInt("10abc"));
        $this->assertNull(toInt("123abcxyz", 13));
        $this->assertNull(toInt("abc10"));
        $this->assertNull(toInt("abcxyz123", 13));
    }

    public function testAcceptLeadingTrailingWhitespace()
    {
        $this->assertSame(100, toInt("   100    "));
        $this->assertSame(78, toInt("\n\t\v\r\f   78 \n \t\v\r\f   \n"));
        $this->assertSame(78, toInt("\n\t\v\r\f78"));
        $this->assertSame(78, toInt("78\n\t\v\r\f"));
    }

    public function testOverflowNanInf()
    {
        $this->assertNull(toInt(INF));
        $this->assertNull(toInt(-INF));
        $this->assertNull(toInt(NAN));
        $this->assertNull(toInt(PHP_INT_MAX * 2));
    }
}
