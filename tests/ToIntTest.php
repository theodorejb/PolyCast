<?php

namespace theodorejb\polycast;

class ToIntTest extends \PHPUnit_Framework_TestCase
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
        $this->assertTrue(safe_int($val));
        $this->assertSame($expected, to_int($val));
    }

    public function disallowedTypes()
    {
        return [
            [null],
            [true],
            [false],
            [new \stdClass()],
            [new NotAnInt()], // FILTER_VALIDATE_INT accepts this
            [fopen("data:text/html,foobar", "r")],
            [[]],
        ];
    }

    /**
     * @dataProvider disallowedTypes
     * @expectedException theodorejb\polycast\CastException
     */
    public function testDisallowedTypes($val)
    {
        $this->assertFalse(safe_int($val));
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
            ["   100    "], // FILTER_VALIDATE_INT accepts this
            ["\n\t\v\r\f   78 \n \t\v\r\f   \n"],
            ["\n\t\v\r\f78"],
            ["78\n\t\v\r\f"],
        ];
    }

    /**
     * @dataProvider invalidFormats
     * @expectedException theodorejb\polycast\CastException
     */
    public function testInvalidFormats($val)
    {
        $this->assertFalse(safe_int($val));
        to_int($val);
    }

    public function unsafeValues()
    {
        return [
            [1.000000000000001], // FILTER_VALIDATE_INT accepts this
            [NAN],
            [1.5],
        ];
    }

    /**
     * @dataProvider unsafeValues
     * @expectedException theodorejb\polycast\CastException
     */
    public function testUnsafeValues($val)
    {
        $this->assertFalse(safe_int($val));
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
     * @expectedException theodorejb\polycast\CastException
     */
    public function testOverflowValues($val)
    {
        $this->assertFalse(safe_int($val));
        to_int($val);
    }
}

class NotAnInt
{
    function __toString()
    {
        return "1";
    }
}
