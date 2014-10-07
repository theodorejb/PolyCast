<?php

class ToIntTest extends PHPUnit_Framework_TestCase
{
    public function testShouldPass()
    {
        $this->assertSame(0, to_int("0"));
        $this->assertSame(0, to_int(0));
        $this->assertSame(0, to_int(0.0));
        $this->assertSame(10, to_int("10"));
        $this->assertSame(10, to_int(10));
        $this->assertSame(10, to_int(10.0));

        $this->assertSame(PHP_INT_MAX, to_int((string) PHP_INT_MAX));
        $this->assertSame(PHP_INT_MAX, to_int(PHP_INT_MAX));
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

    public function testBases()
    {
        $this->assertSame(8, to_int("010", 0));    // base detect octal
        $this->assertSame(10, to_int("010", 10));  // not octal
        $this->assertSame(10, to_int("010"));      // not octal
        $this->assertSame(16, to_int("0x10", 0));  // base detect hex
        $this->assertSame(16, to_int("0x10", 16)); // hex
        $this->assertFalse(to_int("0x10", 10));     // not hex
        $this->assertFalse(to_int("0x10"));         // not hex
        $this->assertFalse(to_int("123AyZ", 35));   // z is only in base 36
        $this->assertSame(63979595, to_int("123ayz", 36));
        $this->assertSame(63979595, to_int("123AyZ", 36));
    }

    public function testTruncation()
    {
        $this->assertSame(1, to_int(1.5));
        $this->assertFalse(to_int("1.5"));
    }

    public function testRejectLeadingTrailingChars()
    {
        $this->assertFalse(to_int("10abc"));
        $this->assertFalse(to_int("123abcxyz", 13));
        $this->assertFalse(to_int("abc10"));
        $this->assertFalse(to_int("abcxyz123", 13));
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
}
