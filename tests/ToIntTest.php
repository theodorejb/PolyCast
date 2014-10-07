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
        $this->assertNull(to_int(null));
        $this->assertNull(to_int(true));
        $this->assertNull(to_int(false));
        $this->assertNull(to_int(new stdClass()));
        $this->assertNull(to_int(fopen("data:text/html,foobar", "r")));
        $this->assertNull(to_int([]));
    }

    public function testBases()
    {
        $this->assertSame(8, to_int("010", 0));    // base detect octal
        $this->assertSame(10, to_int("010", 10));  // not octal
        $this->assertSame(10, to_int("010"));      // not octal
        $this->assertSame(16, to_int("0x10", 0));  // base detect hex
        $this->assertSame(16, to_int("0x10", 16)); // hex
        $this->assertNull(to_int("0x10", 10));     // not hex
        $this->assertNull(to_int("0x10"));         // not hex
        $this->assertNull(to_int("123AyZ", 35));   // z is only in base 36
        $this->assertSame(63979595, to_int("123ayz", 36));
        $this->assertSame(63979595, to_int("123AyZ", 36));
    }

    public function testTruncation()
    {
        $this->assertSame(1, to_int(1.5));
        $this->assertNull(to_int("1.5"));
    }

    public function testRejectLeadingTrailingChars()
    {
        $this->assertNull(to_int("10abc"));
        $this->assertNull(to_int("123abcxyz", 13));
        $this->assertNull(to_int("abc10"));
        $this->assertNull(to_int("abcxyz123", 13));
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
        $this->assertNull(to_int(INF));
        $this->assertNull(to_int(-INF));
        $this->assertNull(to_int(NAN));
        $this->assertNull(to_int(PHP_INT_MAX * 2));
    }
}
