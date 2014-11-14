<?php

class ToFloatTest extends PHPUnit_Framework_TestCase
{
    public function testShouldPass()
    {
        $this->assertSame(0.0, to_float("0"));
        $this->assertSame(0.0, to_float(0));
        $this->assertSame(0.0, to_float(0.0));
        $this->assertSame(10.0, to_float("10"));
        $this->assertSame(-10.0, to_float("-10"));
        $this->assertSame(10.0, to_float("10.0"));
        $this->assertSame(10.0, to_float(10));
        $this->assertSame(10.0, to_float(10.0));
        $this->assertSame(1.5, to_float(1.5));
        $this->assertSame(1.5, to_float("1.5"));
        $this->assertSame(0.00075, to_float("75e-5"));
        $this->assertSame(310000000.0, to_float("31e+7"));

        $this->assertSame((float) PHP_INT_MAX, to_float((string) PHP_INT_MAX));
        $this->assertSame((float) PHP_INT_MAX, to_float(PHP_INT_MAX));
        $this->assertSame((float) PHP_INT_MAX, to_float((float) PHP_INT_MAX));
        $this->assertSame((float) PHP_INT_MIN, to_float((string) PHP_INT_MIN));
        $this->assertSame((float) PHP_INT_MIN, to_float(PHP_INT_MIN));
        $this->assertSame((float) PHP_INT_MIN, to_float((float) PHP_INT_MIN));
    }

    public function testOverflowNanInf()
    {
        $this->assertSame(INF, to_float(INF));
        $this->assertSame(-INF, to_float(-INF));
        $this->assertTrue(is_nan(to_float(NAN)));
        $this->assertSame((float) (PHP_INT_MAX * 2), to_float(PHP_INT_MAX * 2));
        $this->assertSame((float) (PHP_INT_MIN * 2), to_float(PHP_INT_MIN * 2));
        $this->assertSame((string) (float) (PHP_INT_MAX * 2), (string) to_float((string) (PHP_INT_MAX * 2)));
        $this->assertSame((string) (float) (PHP_INT_MIN * 2), (string) to_float((string) (PHP_INT_MIN * 2)));
    }

    public function disallowedTypes()
    {
        return [
            [null],
            [true],
            [false],
            [new stdClass()],
            [fopen("data:text/html,foobar", "r")],
            [[]],
        ];
    }

    /**
     * @dataProvider disallowedTypes
     * @expectedException InvalidArgumentException
     */
    public function testDisallowedTypes($val)
    {
        $this->assertNull(try_float($val));
        to_float($val);
    }

    public function invalidFormats()
    {
        return [
            [""],
            ["0x10"],
            ["010"],
            ["+10"],
            ["10abc"],
            ["abc10"],
            ["   100    "],
            ["\n\t\v\r\f   78 \n \t\v\r\f   \n"],
            ["\n\t\v\r\f78"],
            ["78\n\t\v\r\f"],
        ];
    }

    /**
     * @dataProvider invalidFormats
     * @expectedException FormatException
     */
    public function testInvalidFormats($val)
    {
        $this->assertNull(try_float($val));
        to_float($val);
    }
}
