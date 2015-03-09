<?php

class ToIntTest extends PHPUnit_Framework_TestCase
{
    public function shouldPass()
    {
        return [
            [0, "0"],
            [0, 0],
            [0, 0.0],
            [10, "10"],
            [10, "+10"],
            [-10, "-10"],
            [10, 10],
            [10, 10.0],
            [PHP_INT_MAX, (string)PHP_INT_MAX],
            [PHP_INT_MAX, PHP_INT_MAX],
            [PHP_INT_MIN, (string)PHP_INT_MIN],
            [PHP_INT_MIN, PHP_INT_MIN],
        ];
    }

    /**
     * @dataProvider shouldPass
     */
    public function testShouldPass($expected, $val)
    {
        $this->assertTrue(int_castable($val));
        $this->assertSame($expected, to_int($val));
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
        $this->assertFalse(int_castable($val));
        to_int($val);
    }

    public function invalidFormats()
    {
        return [
            [""],
            ["10.0"],
            ["75e-5"],
            ["31e+7"],
            ["0x10"],
            ["1.5"],
            ["010"],
            ["10abc"],
            ["abc10"],
            ["10 "],
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
        $this->assertFalse(int_castable($val));
        to_int($val);
    }

    public function unsafeValues()
    {
        return [
            [NAN],
            [1.5],
        ];
    }

    /**
     * @dataProvider unsafeValues
     * @expectedException CastException
     */
    public function testUnsafeValues($val)
    {
        $this->assertFalse(int_castable($val));
        to_int($val);
    }

    public function overflowValues()
    {
        return [
            [INF],
            [-INF],
            [PHP_INT_MAX * 2],
            [PHP_INT_MIN * 2],
            [(string)(PHP_INT_MAX * 2)],
            [(string)(PHP_INT_MIN * 2)],
        ];
    }

    /**
     * @dataProvider overflowValues
     * @expectedException CastException
     */
    public function testOverflowValues($val)
    {
        $this->assertFalse(int_castable($val));
        to_int($val);
    }
}
