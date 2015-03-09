<?php

class ToFloatTest extends PHPUnit_Framework_TestCase
{
    public function shouldPass()
    {
        return [
            [0.0, "0"],
            [0.0, 0],
            [0.0, 0.0],
            [10.0, "10"],
            [10.0, "+10"],
            [-10.0, "-10"],
            [10.0, "10.0"],
            [10.0, 10],
            [10.0, 10.0],
            [1.5, 1.5],
            [1.5, "1.5"],
            [0.00075, "75e-5"],
            [310000000.0, "31e+7"],
            [(float) PHP_INT_MAX, (string) PHP_INT_MAX],
            [(float) PHP_INT_MAX, PHP_INT_MAX],
            [(float) PHP_INT_MAX, (float) PHP_INT_MAX],
            [(float) PHP_INT_MIN, (string) PHP_INT_MIN],
            [(float) PHP_INT_MIN, PHP_INT_MIN],
            [(float) PHP_INT_MIN, (float) PHP_INT_MIN],
        ];
    }

    /**
     * @dataProvider shouldPass
     */
    public function testShouldPass($expected, $val)
    {
        $this->assertSame($expected, try_float($val));
        $this->assertSame($expected, to_float($val));
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
     * @expectedException CastException
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
            ["10abc"],
            ["abc10"],
            ["10 "], // FILTER_VALIDATE_FLOAT accepts this
            ["   100    "],
            ["\n\t\v\r\f   78 \n \t\v\r\f   \n"],
            ["\n\t\v\r\f78"],
            ["78\n\t\v\r\f"],
        ];
    }

    /**
     * @dataProvider invalidFormats
     * @expectedException CastException
     */
    public function testInvalidFormats($val)
    {
        $this->assertNull(try_float($val));
        to_float($val);
    }
}
